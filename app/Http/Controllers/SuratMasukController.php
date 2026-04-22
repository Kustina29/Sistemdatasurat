<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use Illuminate\Http\Request;

class SuratMasukController extends Controller
{
    public function index(Request $request)
    {
        $query = SuratMasuk::query();
        
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('perihal', 'like', '%' . $request->search . '%')
                  ->orWhere('nomor_surat', 'like', '%' . $request->search . '%')
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
        return view('surat_masuk.create');
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
        ]);

        SuratMasuk::create($request->all());

        return redirect()->route('surat-masuk.index')->with('success', 'Data surat masuk berhasil ditambahkan.');
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
        ]);

        $surat_masuk->update($request->all());

        return redirect()->route('surat-masuk.index')->with('success', 'Data surat masuk berhasil diperbarui.');
    }

    public function destroy(SuratMasuk $surat_masuk)
    {
        $surat_masuk->delete();
        return redirect()->route('surat-masuk.index')->with('success', 'Data surat masuk berhasil dihapus.');
    }

    public function export(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));
        
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\SuratMasukExport($bulan, $tahun), 'Agenda_Surat_Masuk.xlsx');
    }
}
