<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuratMasukController;

Route::get('/', function () {
    return redirect()->route('surat-masuk.index');
});

Route::get('/surat-masuk/export', [SuratMasukController::class, 'export'])->name('surat-masuk.export');
Route::resource('surat-masuk', SuratMasukController::class)->parameters([
    'surat-masuk' => 'surat_masuk'
]);
