<?php

namespace App\Http\Controllers;

use App\Exports\SuratKeluarExport;
use App\Models\SuratKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class SuratKeluarController extends Controller
{
    public function index(Request $request)
    {
        $query = SuratKeluar::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('perihal', 'like', '%' . $request->search . '%')
                    ->orWhere('nomor_surat', 'like', '%' . $request->search . '%')
                    ->orWhere('nomor_urut', 'like', '%' . $request->search . '%')
                    ->orWhere('asal', 'like', '%' . $request->search . '%')
                    ->orWhere('alamat_penerima', 'like', '%' . $request->search . '%');
            });
        }

        $filterBulan = $request->input('bulan', date('m'));
        $filterTahun = $request->input('tahun', date('Y'));

        if ($filterBulan !== 'all') {
            $query->whereMonth('tanggal_keluar_surat', $filterBulan);
        }
        if ($filterTahun !== 'all') {
            $query->whereYear('tanggal_keluar_surat', $filterTahun);
        }

        $surat_keluar = $query->orderBy('tanggal_keluar_surat', 'desc')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('surat_keluar.index', compact('surat_keluar', 'filterBulan', 'filterTahun'));
    }

    public function create()
    {
        $nomorRekomendasi = $this->rekomendasiNomorAgenda();

        return view('surat_keluar.create', compact('nomorRekomendasi'));
    }

    public function store(Request $request)
    {
        $request->validate($this->rules());

        SuratKeluar::create(array_merge(
            $request->only($this->fields()),
            $this->lampiranPayload($request)
        ));

        return redirect()->route('surat-keluar.index')->with('success', 'Data surat keluar berhasil ditambahkan.');
    }

    public function show(SuratKeluar $surat_keluar)
    {
        return view('surat_keluar.show', compact('surat_keluar'));
    }

    public function edit(SuratKeluar $surat_keluar)
    {
        return view('surat_keluar.edit', compact('surat_keluar'));
    }

    public function update(Request $request, SuratKeluar $surat_keluar)
    {
        $request->validate(array_merge($this->rules(), [
            'hapus_lampiran' => ['nullable', 'boolean'],
        ]));

        $payload = $request->only($this->fields());

        if ($request->boolean('hapus_lampiran')) {
            $this->deleteLampiran($surat_keluar);
            $payload = array_merge($payload, $this->emptyLampiranPayload());
        }

        if ($request->hasFile('lampiran')) {
            $this->deleteLampiran($surat_keluar);
            $payload = array_merge($payload, $this->lampiranPayload($request));
        }

        $surat_keluar->update($payload);

        return redirect()->route('surat-keluar.index')->with('success', 'Data surat keluar berhasil diperbarui.');
    }

    public function destroy(SuratKeluar $surat_keluar)
    {
        $this->deleteLampiran($surat_keluar);
        $surat_keluar->delete();

        return redirect()->route('surat-keluar.index')->with('success', 'Data surat keluar berhasil dihapus.');
    }

    public function reset()
    {
        DB::transaction(function () {
            SuratKeluar::whereNotNull('lampiran_path')
                ->pluck('lampiran_path')
                ->each(fn ($path) => Storage::disk('public')->delete($path));

            SuratKeluar::query()->delete();

            // Reset auto-increment: kompatibel dengan SQLite & MySQL
            match (DB::getDriverName()) {
                'sqlite' => DB::statement("DELETE FROM sqlite_sequence WHERE name = 'surat_keluar'"),
                'mysql'  => DB::statement('ALTER TABLE surat_keluar AUTO_INCREMENT = 1'),
                default  => null,
            };
        });

        return redirect()->route('surat-keluar.index')->with('success', 'Database surat keluar sudah dikosongkan. Silakan import ulang data.');
    }

    public function export(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));
        $search = $request->input('search');
        $namaFile = 'Agenda_Surat_Keluar_'.$this->labelPeriode($bulan, $tahun).'.xlsx';

        return Excel::download(new SuratKeluarExport($bulan, $tahun, $search), $namaFile);
    }

    private function rules(): array
    {
        return [
            'tanggal_keluar_surat' => ['required', 'date'],
            'nomor_urut' => ['nullable', 'string'],
            'alamat_penerima' => ['nullable', 'string'],
            'tanggal_surat' => ['required', 'date'],
            'nomor_surat' => ['nullable', 'string'],
            'perihal' => ['required', 'string'],
            'asal' => ['nullable', 'string'],
            'lampiran' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240'],
        ];
    }

    private function fields(): array
    {
        return [
            'tanggal_keluar_surat',
            'nomor_urut',
            'alamat_penerima',
            'tanggal_surat',
            'nomor_surat',
            'perihal',
            'asal',
        ];
    }

    private function lampiranPayload(Request $request): array
    {
        if (! $request->hasFile('lampiran')) {
            return [];
        }

        $file = $request->file('lampiran');

        return [
            'lampiran_path' => $file->store('surat-keluar', 'public'),
            'lampiran_nama_asli' => $file->getClientOriginalName(),
            'lampiran_mime' => $file->getClientMimeType(),
            'lampiran_ukuran' => $file->getSize(),
        ];
    }

    private function emptyLampiranPayload(): array
    {
        return [
            'lampiran_path' => null,
            'lampiran_nama_asli' => null,
            'lampiran_mime' => null,
            'lampiran_ukuran' => null,
        ];
    }

    private function deleteLampiran(SuratKeluar $suratKeluar): void
    {
        if ($suratKeluar->lampiran_path) {
            Storage::disk('public')->delete($suratKeluar->lampiran_path);
        }
    }

    private function rekomendasiNomorAgenda(): string
    {
        $bulan = date('m');
        $tahun = date('Y');

        $last = SuratKeluar::whereYear('tanggal_keluar_surat', $tahun)
            ->whereMonth('tanggal_keluar_surat', $bulan)
            ->orderByDesc('id')
            ->pluck('nomor_urut')
            ->first(fn ($nomor) => preg_match('/^\d+/', (string) $nomor));

        $next = 1;
        if ($last && preg_match('/^(\d+)/', $last, $matches)) {
            $next = ((int) $matches[1]) + 1;
        }

        return str_pad((string) $next, 3, '0', STR_PAD_LEFT).'/'.$this->romawi((int) $bulan).'/'.$tahun;
    }

    private function romawi(int $bulan): string
    {
        return [1 => 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'][$bulan] ?? (string) $bulan;
    }

    private function labelPeriode($bulan, $tahun): string
    {
        return Str::slug(($bulan === 'all' ? 'Semua_Bulan' : str_pad((string) $bulan, 2, '0', STR_PAD_LEFT)).'_'.($tahun === 'all' ? 'Semua_Tahun' : $tahun), '_');
    }
}
