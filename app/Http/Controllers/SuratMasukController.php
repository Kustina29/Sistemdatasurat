<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SuratMasukController extends Controller
{
    public function index(Request $request)
    {
        $query = SuratMasuk::query();
        
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('perihal', 'like', '%' . $request->search . '%')
                  ->orWhere('nomor_surat', 'like', '%' . $request->search . '%')
                  ->orWhere('nomor_urut', 'like', '%' . $request->search . '%')
                  ->orWhere('tujuan_disposisi', 'like', '%' . $request->search . '%')
                  ->orWhere('alamat_pengirim', 'like', '%' . $request->search . '%');
            });
        }

        $filterBulan = $request->input('bulan', date('m'));
        $filterTahun = $request->input('tahun', date('Y'));
        
        if ($filterBulan != 'all') {
            $query->whereMonth('tanggal_masuk_surat', $filterBulan);
        }
        if ($filterTahun != 'all') {
            $query->whereYear('tanggal_masuk_surat', $filterTahun);
        }

        $surat_masuk = $query->orderBy('tanggal_masuk_surat', 'desc')->paginate(10)->withQueryString();
        
        return view('surat_masuk.index', compact('surat_masuk', 'filterBulan', 'filterTahun'));
    }

    public function create()
    {
        $nomorRekomendasi = $this->rekomendasiNomorAgenda();

        return view('surat_masuk.create', compact('nomorRekomendasi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_masuk_surat' => 'required|date',
            'nomor_urut' => 'nullable|string',
            'alamat_pengirim' => 'nullable|string',
            'tanggal_surat' => 'required|date',
            'nomor_surat' => 'nullable|string',
            'perihal' => 'required|string',
            'tujuan_disposisi' => 'nullable|string',
            'lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:10240',
        ]);

        SuratMasuk::create(array_merge(
            $request->only($this->fields()),
            $this->lampiranPayload($request)
        ));

        return redirect()->route('surat-masuk.index')->with('success', 'Data surat masuk berhasil ditambahkan.');
    }

    public function show(SuratMasuk $surat_masuk)
    {
        return view('surat_masuk.show', compact('surat_masuk'));
    }

    public function edit(SuratMasuk $surat_masuk)
    {
        return view('surat_masuk.edit', compact('surat_masuk'));
    }

    public function update(Request $request, SuratMasuk $surat_masuk)
    {
        $request->validate([
            'tanggal_masuk_surat' => 'required|date',
            'nomor_urut' => 'nullable|string',
            'alamat_pengirim' => 'nullable|string',
            'tanggal_surat' => 'required|date',
            'nomor_surat' => 'nullable|string',
            'perihal' => 'required|string',
            'tujuan_disposisi' => 'nullable|string',
            'lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:10240',
            'hapus_lampiran' => 'nullable|boolean',
        ]);

        $payload = $request->only($this->fields());

        if ($request->boolean('hapus_lampiran')) {
            $this->deleteLampiran($surat_masuk);
            $payload = array_merge($payload, $this->emptyLampiranPayload());
        }

        if ($request->hasFile('lampiran')) {
            $this->deleteLampiran($surat_masuk);
            $payload = array_merge($payload, $this->lampiranPayload($request));
        }

        $surat_masuk->update($payload);

        return redirect()->route('surat-masuk.index')->with('success', 'Data surat masuk berhasil diperbarui.');
    }

    public function destroy(SuratMasuk $surat_masuk)
    {
        $this->deleteLampiran($surat_masuk);
        $surat_masuk->delete();
        return redirect()->route('surat-masuk.index')->with('success', 'Data surat masuk berhasil dihapus.');
    }

    public function reset()
    {
        DB::transaction(function () {
            SuratMasuk::whereNotNull('lampiran_path')
                ->pluck('lampiran_path')
                ->each(fn ($path) => Storage::disk('public')->delete($path));

            SuratMasuk::query()->delete();

            // Reset auto-increment: kompatibel dengan SQLite & MySQL
            match (DB::getDriverName()) {
                'sqlite' => DB::statement("DELETE FROM sqlite_sequence WHERE name = 'surat_masuk'"),
                'mysql'  => DB::statement('ALTER TABLE surat_masuk AUTO_INCREMENT = 1'),
                default  => null,
            };
        });

        return redirect()->route('surat-masuk.index')->with('success', 'Database surat masuk sudah dikosongkan. Silakan import ulang data.');
    }

    public function export(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));
        $search = $request->input('search');
        $namaFile = 'Agenda_Surat_Masuk_'.$this->labelPeriode($bulan, $tahun).'.xlsx';
        
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\SuratMasukExport($bulan, $tahun, $search), $namaFile);
    }

    private function fields(): array
    {
        return [
            'tanggal_masuk_surat',
            'nomor_urut',
            'alamat_pengirim',
            'tanggal_surat',
            'nomor_surat',
            'perihal',
            'tujuan_disposisi',
        ];
    }

    private function lampiranPayload(Request $request): array
    {
        if (! $request->hasFile('lampiran')) {
            return [];
        }

        $file = $request->file('lampiran');

        return [
            'lampiran_path' => $file->store('surat-masuk', 'public'),
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

    private function deleteLampiran(SuratMasuk $suratMasuk): void
    {
        if ($suratMasuk->lampiran_path) {
            Storage::disk('public')->delete($suratMasuk->lampiran_path);
        }
    }

    private function rekomendasiNomorAgenda(): string
    {
        $bulan = date('m');
        $tahun = date('Y');

        $last = SuratMasuk::whereYear('tanggal_masuk_surat', $tahun)
            ->whereMonth('tanggal_masuk_surat', $bulan)
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
        $parts = [];
        $parts[] = $bulan === 'all' ? 'Semua_Bulan' : str_pad((string) $bulan, 2, '0', STR_PAD_LEFT);
        $parts[] = $tahun === 'all' ? 'Semua_Tahun' : (string) $tahun;

        return Str::slug(implode('_', $parts), '_');
    }
}
