<?php

namespace App\Imports;

use App\Models\SuratKeluar;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class SuratKeluarImport implements WithMultipleSheets
{
    public int $created = 0;

    public array $errors = [];

    public array $previewRows = [];

    public int $validRows = 0;

    public int $duplicateRows = 0;

    public int $invalidRows = 0;

    public function __construct(public bool $previewOnly = false)
    {
    }

    public function sheets(): array
    {
        return [
            'Surat Keluar' => new SuratKeluarSheetImport($this),
        ];
    }
}

class SuratKeluarSheetImport implements ToCollection
{
    public function __construct(private SuratKeluarImport $parent)
    {
    }

    public function collection(Collection $rows)
    {
        $lastTanggalKeluar = null;
        $lastAlamatPenerima = null;
        $lastTanggalSurat = null;
        $lastNomorSurat = null;
        $lastPerihal = null;
        $lastAsal = null;

        foreach ($rows as $index => $row) {
            if ($index < 6) {
                continue;
            }

            $nomorUrut = $this->cell($row, 2);
            $alamatPenerima = $this->cell($row, 3);
            $tanggalSurat = $this->cell($row, 4);
            $nomorSurat = $this->cell($row, 5);
            $perihal = $this->cell($row, 6);
            $asal = $this->cell($row, 7);

            if ($this->dateValue($this->cell($row, 1))) {
                $lastTanggalKeluar = $this->dateValue($this->cell($row, 1));
            }
            if ($alamatPenerima !== null) {
                $lastAlamatPenerima = $alamatPenerima;
            }
            if ($this->dateValue($tanggalSurat)) {
                $lastTanggalSurat = $this->dateValue($tanggalSurat);
            }
            if ($nomorSurat !== null) {
                $lastNomorSurat = $nomorSurat;
            }
            if ($perihal !== null) {
                $lastPerihal = $perihal;
            }
            if ($asal !== null) {
                $lastAsal = $asal;
            }

            if (! $nomorUrut && ! $alamatPenerima && ! $tanggalSurat && ! $nomorSurat && ! $perihal && ! $asal) {
                continue;
            }

            $this->createRow([
                'tanggal_keluar_surat' => $lastTanggalKeluar ?: $this->dateValue($tanggalSurat),
                'nomor_urut' => $nomorUrut,
                'alamat_penerima' => $alamatPenerima ?? $lastAlamatPenerima,
                'tanggal_surat' => $this->legacyTanggalSurat($tanggalSurat, $lastTanggalSurat, $lastTanggalKeluar),
                'nomor_surat' => $nomorSurat ?? $lastNomorSurat,
                'perihal' => $perihal ?? $lastPerihal,
                'asal' => $asal ?? $lastAsal,
            ], $index + 1);
        }
    }

    private function createRow(array $payload, int $rowNumber): void
    {
        $validator = Validator::make($payload, [
            'tanggal_keluar_surat' => ['required', 'date'],
            'nomor_urut' => ['nullable', 'string'],
            'alamat_penerima' => ['nullable', 'string'],
            'tanggal_surat' => ['required', 'date'],
            'nomor_surat' => ['nullable', 'string'],
            'perihal' => ['required', 'string'],
            'asal' => ['nullable', 'string'],
        ]);

        $status = 'valid';
        $message = $this->hasExistingDataInSameMonth($payload)
            ? 'Bulan sudah punya data, tetapi isi berbeda. Akan ditambahkan.'
            : 'Siap diimport';

        if ($validator->fails()) {
            $status = 'error';
            $message = $validator->errors()->first();
            $this->parent->errors[] = 'Baris '.$rowNumber.': '.$validator->errors()->first();
            $this->parent->invalidRows++;
            $this->appendPreviewRow($rowNumber, $payload, $status, $message);

            return;
        }

        if ($this->isDuplicate($payload)) {
            $status = 'duplicate';
            $message = 'Bulan dan isi data sama dengan arsip yang sudah ada. Akan dilewati.';
            $this->parent->duplicateRows++;
            $this->appendPreviewRow($rowNumber, $payload, $status, $message);

            return;
        }

        $this->parent->validRows++;
        $this->appendPreviewRow($rowNumber, $payload, $status, $message);

        if ($this->parent->previewOnly) {
            return;
        }

        SuratKeluar::create($payload);
        $this->parent->created++;
    }

    private function appendPreviewRow(int $rowNumber, array $payload, string $status, string $message): void
    {
        if (! $this->parent->previewOnly) {
            return;
        }

        $this->parent->previewRows[] = [
            'row' => $rowNumber,
            'status' => $status,
            'message' => $message,
            'data' => $payload,
        ];
    }

    private function isDuplicate(array $payload): bool
    {
        return SuratKeluar::where('tanggal_keluar_surat', $payload['tanggal_keluar_surat'])
            ->where('nomor_urut', $payload['nomor_urut'])
            ->where('tanggal_surat', $payload['tanggal_surat'])
            ->where('nomor_surat', $payload['nomor_surat'])
            ->where('perihal', $payload['perihal'])
            ->exists();
    }

    private function hasExistingDataInSameMonth(array $payload): bool
    {
        if (empty($payload['tanggal_keluar_surat'])) {
            return false;
        }

        $date = Carbon::parse($payload['tanggal_keluar_surat']);

        return SuratKeluar::whereYear('tanggal_keluar_surat', $date->year)
            ->whereMonth('tanggal_keluar_surat', $date->month)
            ->exists();
    }

    private function cell(Collection $row, int $index): ?string
    {
        $value = $row->get($index);

        if ($value === null || trim((string) $value) === '') {
            return null;
        }

        return trim((string) $value);
    }

    private function dateValue($value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            return Carbon::instance(ExcelDate::excelToDateTimeObject($value))->format('Y-m-d');
        }

        $value = $this->normalizeDateText((string) $value);

        try {
            return Carbon::parse(str_replace('/', '-', $value))->format('Y-m-d');
        } catch (\Throwable) {
            return null;
        }
    }

    private function normalizeDateText(string $value): string
    {
        $value = trim($value);

        if (preg_match('/^(\d{1,2})[\/\-](0\d{2,})[\/\-](\d{4})$/', $value, $matches)) {
            return $matches[1].'/'.substr($matches[2], 0, 2).'/'.$matches[3];
        }

        return $value;
    }

    private function legacyTanggalSurat(?string $value, ?string $lastTanggalSurat, ?string $lastTanggalKeluar): ?string
    {
        if ($value === null) {
            return $lastTanggalSurat;
        }

        $date = $this->dateValue($value);
        if ($date) {
            return $date;
        }

        return trim($value) === '-' ? $lastTanggalKeluar : null;
    }
}
