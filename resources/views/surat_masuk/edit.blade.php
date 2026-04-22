@extends('layouts.app')

@section('content')
<div class="mb-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('surat-masuk.index') }}" class="text-slate-400 hover:text-slate-600">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-slate-900">Edit Data Surat Masuk</h1>
    </div>
    <p class="mt-2 text-sm text-slate-500 ml-10">Perbarui formulir di bawah ini untuk mengedit data registrasi surat.</p>
</div>

<div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-2">
    <form action="{{ route('surat-masuk.update', $surat_masuk->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="px-4 py-6 sm:p-8">
            <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                <!-- Data Penerimaan -->
                <div class="sm:col-span-6 border-b border-gray-200 pb-2">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Data Administrasi</h2>
                </div>

                <div class="sm:col-span-3">
                    <label for="tanggal_masuk_surat" class="block text-sm font-medium leading-6 text-gray-900">Tanggal Masuk Surat <span class="text-red-500">*</span></label>
                    <div class="mt-2">
                        <input type="date" name="tanggal_masuk_surat" id="tanggal_masuk_surat" value="{{ old('tanggal_masuk_surat', $surat_masuk->tanggal_masuk_surat) }}" required class="block w-full rounded-lg border-0 bg-slate-50 py-2.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-slate-100 hover:ring-gray-400 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-indigo-600 focus:outline-none transition-all duration-200 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label for="nomor_urut" class="block text-sm font-medium leading-6 text-gray-900">Nomor Urut</label>
                    <div class="mt-2">
                        <input type="text" name="nomor_urut" id="nomor_urut" value="{{ old('nomor_urut', $surat_masuk->nomor_urut) }}" class="block w-full rounded-lg border-0 bg-slate-50 py-2.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 hover:bg-slate-100 hover:ring-gray-400 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-indigo-600 focus:outline-none transition-all duration-200 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <!-- Dari Surat Masuk (Detail Surat Asli) -->
                <div class="sm:col-span-6 border-b border-gray-200 pb-2 mt-4">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Dari Surat Masuk (Detail)</h2>
                </div>

                <div class="sm:col-span-6">
                    <label for="alamat_pengirim" class="block text-sm font-medium leading-6 text-gray-900">Alamat Pengirim</label>
                    <div class="mt-2">
                        <input type="text" name="alamat_pengirim" id="alamat_pengirim" value="{{ old('alamat_pengirim', $surat_masuk->alamat_pengirim) }}" class="block w-full rounded-lg border-0 bg-slate-50 py-2.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-slate-100 hover:ring-gray-400 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-indigo-600 focus:outline-none transition-all duration-200 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label for="tanggal_surat" class="block text-sm font-medium leading-6 text-gray-900">Tanggal Surat <span class="text-red-500">*</span></label>
                    <div class="mt-2">
                        <input type="date" name="tanggal_surat" id="tanggal_surat" value="{{ old('tanggal_surat', $surat_masuk->tanggal_surat) }}" required class="block w-full rounded-lg border-0 bg-slate-50 py-2.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-slate-100 hover:ring-gray-400 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-indigo-600 focus:outline-none transition-all duration-200 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label for="nomor_surat" class="block text-sm font-medium leading-6 text-gray-900">Nomor Surat</label>
                    <div class="mt-2">
                        <input type="text" name="nomor_surat" id="nomor_surat" value="{{ old('nomor_surat', $surat_masuk->nomor_surat) }}" class="block w-full rounded-lg border-0 bg-slate-50 py-2.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-slate-100 hover:ring-gray-400 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-indigo-600 focus:outline-none transition-all duration-200 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div class="sm:col-span-6">
                    <label for="perihal" class="block text-sm font-medium leading-6 text-gray-900">Perihal <span class="text-red-500">*</span></label>
                    <div class="mt-2">
                        <textarea id="perihal" name="perihal" rows="3" required class="block w-full rounded-lg border-0 bg-slate-50 py-2.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 hover:bg-slate-100 hover:ring-gray-400 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-indigo-600 focus:outline-none transition-all duration-200 sm:text-sm sm:leading-6">{{ old('perihal', $surat_masuk->perihal) }}</textarea>
                    </div>
                </div>

                <!-- Disposisi -->
                <div class="sm:col-span-6 border-b border-gray-200 pb-2 mt-4">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Disposisi</h2>
                </div>

                <div class="sm:col-span-6">
                    <label for="tujuan_disposisi" class="block text-sm font-medium leading-6 text-gray-900">Tujuan / Disposisi</label>
                    <div class="mt-2">
                        <input type="text" name="tujuan_disposisi" id="tujuan_disposisi" value="{{ old('tujuan_disposisi', $surat_masuk->tujuan_disposisi) }}" class="block w-full rounded-lg border-0 bg-slate-50 py-2.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-slate-100 hover:ring-gray-400 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-indigo-600 focus:outline-none transition-all duration-200 sm:text-sm sm:leading-6">
                    </div>
                </div>

            </div>
        </div>
        <div class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8 bg-gray-50 rounded-b-xl">
            <a href="{{ route('surat-masuk.index') }}" class="text-sm font-semibold leading-6 text-gray-900">Batal</a>
            <button type="submit" class="rounded-md bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">Perbarui Data</button>
        </div>
    </form>
</div>
@endsection
