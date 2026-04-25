<?php

namespace App\Imports;

use App\Models\SuratMasuk;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class SuratMasukImport implements ToCollection
{
    public int $created = 0;

    public array $errors = [];

    public array $previewRows = [];

    public int $validRows = 0;

    public int $duplicateRows = 0;

    public int $invalidRows = 0;

    public function __construct(private bool $previewOnly = false)
    {
    }

    public function collection(Collection $rows)
    {
        if ($this->looksLikeLegacyAgenda($rows)) {
            $this->importLegacyAgenda($rows);

            return;
        }

        $this->importFlatTemplate($rows);
    }

    private function importLegacyAgenda(Collection $rows): void
    {
        $lastTanggalMasuk = null;
        $lastAlamatPengirim = null;
        $lastTanggalSurat = null;
        $lastNomorSurat = null;
        $lastPerihal = null;
        $lastTujuanDisposisi = null;

        foreach ($rows as $index => $row) {
            if ($index < 6) {
                continue;
            }

            $nomorUrut = $this->cell($row, 2);
            $alamatPengirim = $this->cell($row, 3);
            $tanggalSurat = $this->cell($row, 4);
            $nomorSurat = $this->cell($row, 5);
            $perihal = $this->cell($row, 6);
            $tujuanDisposisi = $this->cell($row, 7);

            if ($this->dateValue($this->cell($row, 1))) {
                $lastTanggalMasuk = $this->dateValue($this->cell($row, 1));
            }
            if ($alamatPengirim !== null) {
                $lastAlamatPengirim = $alamatPengirim;
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
            if ($tujuanDisposisi !== null) {
                $lastTujuanDisposisi = $tujuanDisposisi;
            }

            if (! $nomorUrut && ! $alamatPengirim && ! $tanggalSurat && ! $nomorSurat && ! $perihal && ! $tujuanDisposisi) {
                continue;
            }

            $payload = [
                'tanggal_masuk_surat' => $lastTanggalMasuk,
                'nomor_urut' => $nomorUrut,
                'alamat_pengirim' => $alamatPengirim ?? $lastAlamatPengirim,
                'tanggal_surat' => $this->legacyTanggalSurat($tanggalSurat, $lastTanggalSurat, $lastTanggalMasuk),
                'nomor_surat' => $nomorSurat ?? $lastNomorSurat,
                'perihal' => $perihal ?? $lastPerihal,
                'tujuan_disposisi' => $tujuanDisposisi ?? $lastTujuanDisposisi,
            ];

            $this->createRow($payload, $index + 1);
        }
    }

    private function importFlatTemplate(Collection $rows): void
    {
        $headers = $rows->first()?->map(fn ($value) => $this->normalizeHeader($value))->all() ?? [];

        foreach ($rows->skip(1) as $index => $row) {
            $payload = [
                'tanggal_masuk_surat' => $this->dateValue($this->valueByHeader($row, $headers, ['tanggal_masuk_surat', 'tgl_masuk_surat', 'tanggal_masuk', 'tgl_masuk'])),
                'nomor_urut' => $this->valueByHeader($row, $headers, ['nomor_urut', 'no_urut', 'nomor_agenda', 'no_agenda']),
                'alamat_pengirim' => $this->valueByHeader($row, $headers, ['alamat_pengirim', 'pengirim', 'asal_surat', 'asal']),
                'tanggal_surat' => $this->dateValue($this->valueByHeader($row, $headers, ['tanggal_surat', 'tgl_surat'])),
                'nomor_surat' => $this->valueByHeader($row, $headers, ['nomor_surat', 'no_surat']),
                'perihal' => $this->valueByHeader($row, $headers, ['perihal', 'hal']),
                'tujuan_disposisi' => $this->valueByHeader($row, $headers, ['tujuan_disposisi', 'disposisi', 'tujuan']),
            ];

            if (collect($payload)->filter()->isEmpty()) {
                continue;
            }

            $this->createRow($payload, $index + 1);
        }
    }

    private function createRow(array $payload, int $rowNumber): void
    {
        $validator = Validator::make($payload, [
            'tanggal_masuk_surat' => ['required', 'date'],
            'nomor_urut' => ['nullable', 'string'],
            'alamat_pengirim' => ['nullable', 'string'],
            'tanggal_surat' => ['required', 'date'],
            'nomor_surat' => ['nullable', 'string'],
            'perihal' => ['required', 'string'],
            'tujuan_disposisi' => ['nullable', 'string'],
        ]);

        $status = 'valid';
        $message = $this->hasExistingDataInSameMonth($payload)
            ? 'Bulan sudah punya data, tetapi isi berbeda. Akan ditambahkan.'
            : 'Siap diimport';

        if ($validator->fails()) {
            $status = 'error';
            $message = $validator->errors()->first();
            $this->errors[] = 'Baris '.$rowNumber.': '.$message;
            $this->invalidRows++;
            $this->appendPreviewRow($rowNumber, $payload, $status, $message);

            return;
        }

        if ($this->isDuplicate($payload)) {
            $status = 'duplicate';
            $message = 'Bulan dan isi data sama dengan arsip yang sudah ada. Akan dilewati.';
            $this->duplicateRows++;
            $this->appendPreviewRow($rowNumber, $payload, $status, $message);

            return;
        }

        $this->validRows++;
        $this->appendPreviewRow($rowNumber, $payload, $status, $message);

        if ($this->previewOnly) {
            return;
        }

        SuratMasuk::create($payload);
        $this->created++;
    }

    private function appendPreviewRow(int $rowNumber, array $payload, string $status, string $message): void
    {
        if (! $this->previewOnly) {
            return;
        }

        $this->previewRows[] = [
            'row' => $rowNumber,
            'status' => $status,
            'message' => $message,
            'data' => $payload,
        ];
    }

    private function isDuplicate(array $payload): bool
    {
        return SuratMasuk::where('tanggal_masuk_surat', $payload['tanggal_masuk_surat'])
            ->where('nomor_urut', $payload['nomor_urut'])
            ->where('tanggal_surat', $payload['tanggal_surat'])
            ->where('nomor_surat', $payload['nomor_surat'])
            ->where('perihal', $payload['perihal'])
            ->exists();
    }

    private function hasExistingDataInSameMonth(array $payload): bool
    {
        if (empty($payload['tanggal_masuk_surat'])) {
            return false;
        }

        $date = Carbon::parse($payload['tanggal_masuk_surat']);

        return SuratMasuk::whereYear('tanggal_masuk_surat', $date->year)
            ->whereMonth('tanggal_masuk_surat', $date->month)
            ->exists();
    }

    private function looksLikeLegacyAgenda(Collection $rows): bool
    {
        $headerText = $rows->take(8)
            ->flatten()
            ->filter()
            ->map(fn ($value) => strtolower((string) $value))
            ->implode(' ');

        return str_contains($headerText, 'surat masuk')
            && str_contains($headerText, 'nomor urut')
            && str_contains($headerText, 'alamat pengirim');
    }

    private function valueByHeader(Collection $row, array $headers, array $keys): ?string
    {
        foreach ($keys as $key) {
            $index = array_search($this->normalizeHeader($key), $headers, true);
            if ($index === false) {
                continue;
            }

            $value = $this->cell($row, $index);
            if ($value !== null && trim((string) $value) !== '') {
                return trim((string) $value);
            }
        }

        return null;
    }

    private function cell(Collection $row, int $index): ?string
    {
        $value = $row->get($index);

        if ($value === null || trim((string) $value) === '') {
            return null;
        }

        return trim((string) $value);
    }

    private function normalizeHeader($value): string
    {
        return str($value)
            ->lower()
            ->replace(['/', '-', '.', ' '], '_')
            ->replaceMatches('/_+/', '_')
            ->trim('_')
            ->toString();
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

    private function legacyTanggalSurat(?string $value, ?string $lastTanggalSurat, ?string $lastTanggalMasuk): ?string
    {
        if ($value === null) {
            return $lastTanggalSurat;
        }

        $date = $this->dateValue($value);
        if ($date) {
            return $date;
        }

        return trim($value) === '-' ? $lastTanggalMasuk : null;
    }
}
