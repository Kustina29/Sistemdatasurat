<?php

namespace App\Http\Controllers;

use App\Imports\SuratKeluarImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class SuratKeluarImportController extends Controller
{
    public function create()
    {
        return view('surat_keluar.import');
    }

    public function store(Request $request)
    {
        if ($request->filled('import_token')) {
            return $this->confirmImport($request);
        }

        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:5120'],
        ]);

        $token = (string) Str::uuid();
        $extension = $request->file('file')->getClientOriginalExtension();
        $path = $request->file('file')->storeAs('import-preview/surat-keluar', $token.'.'.$extension, 'local');

        $import = new SuratKeluarImport(previewOnly: true);
        Excel::import($import, Storage::disk('local')->path($path));

        session([
            'surat_keluar_import' => [
                'token' => $token,
                'path' => $path,
                'name' => $request->file('file')->getClientOriginalName(),
            ],
        ]);

        return view('surat_keluar.import', [
            'previewRows' => $import->previewRows,
            'validRows' => $import->validRows,
            'duplicateRows' => $import->duplicateRows,
            'invalidRows' => $import->invalidRows,
            'importToken' => $token,
            'fileName' => $request->file('file')->getClientOriginalName(),
        ]);
    }

    private function confirmImport(Request $request)
    {
        $preview = session('surat_keluar_import');

        if (! $preview || ! hash_equals($preview['token'], $request->input('import_token'))) {
            return redirect()->route('surat-keluar.import')->withErrors(['file' => 'Sesi preview import sudah tidak valid. Upload file kembali.']);
        }

        if (! Storage::disk('local')->exists($preview['path'])) {
            session()->forget('surat_keluar_import');

            return redirect()->route('surat-keluar.import')->withErrors(['file' => 'File preview import tidak ditemukan. Upload file kembali.']);
        }

        $import = new SuratKeluarImport();
        Excel::import($import, Storage::disk('local')->path($preview['path']));
        Storage::disk('local')->delete($preview['path']);
        session()->forget('surat_keluar_import');

        $message = $import->created.' data surat keluar berhasil diimport.';
        if ($import->errors !== []) {
            return back()->with('warning', $message.' Beberapa baris dilewati.')->with('import_errors', $import->errors);
        }

        return redirect()->route('surat-keluar.index')->with('success', $message);
    }
}
