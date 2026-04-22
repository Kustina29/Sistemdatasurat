@extends('layouts.app')

@section('content')
<div class="sm:flex sm:items-center sm:justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Agenda Surat Masuk</h1>
        <p class="mt-1 text-sm text-slate-500">Daftar rekapan seluruh surat masuk bagian kepegawaian.</p>
    </div>
    <div class="mt-4 sm:flex-none sm:mt-0 flex gap-3">
        <!-- Fitur Export ke Excel -->
        <a href="{{ route('surat-masuk.export', ['bulan' => request('bulan', date('m')), 'tahun' => request('tahun', date('Y'))]) }}" class="inline-flex items-center gap-2 rounded-md bg-emerald-50 px-3 py-2 text-sm font-semibold text-emerald-700 shadow-sm hover:bg-emerald-100 border border-emerald-200">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
            </svg>
            Export Excel
        </a>
        <a href="{{ route('surat-masuk.create') }}" class="inline-flex items-center gap-2 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Tambah Data
        </a>
    </div>
</div>

<!-- Search Bar -->
<div class="mb-6 bg-white p-4 shadow-sm border border-slate-200 rounded-lg">
    <form action="{{ route('surat-masuk.index') }}" method="GET" class="flex gap-4 items-center">
        <label for="search" class="sr-only">Cari</label>
        <div class="relative w-full max-w-sm">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                </svg>
            </div>
            <input type="text" name="search" id="search" value="{{ request('search') }}" class="block w-full rounded-md border-0 py-2 pl-10 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Cari perihal, pengirim, atau nomor...">
        </div>
        
        <select name="bulan" class="rounded-md border-0 py-2 pl-3 pr-8 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            <option value="all" {{ $filterBulan == 'all' ? 'selected' : '' }}>Semua Bulan</option>
            <option value="01" {{ $filterBulan == '01' ? 'selected' : '' }}>Januari</option>
            <option value="02" {{ $filterBulan == '02' ? 'selected' : '' }}>Februari</option>
            <option value="03" {{ $filterBulan == '03' ? 'selected' : '' }}>Maret</option>
            <option value="04" {{ $filterBulan == '04' ? 'selected' : '' }}>April</option>
            <option value="05" {{ $filterBulan == '05' ? 'selected' : '' }}>Mei</option>
            <option value="06" {{ $filterBulan == '06' ? 'selected' : '' }}>Juni</option>
            <option value="07" {{ $filterBulan == '07' ? 'selected' : '' }}>Juli</option>
            <option value="08" {{ $filterBulan == '08' ? 'selected' : '' }}>Agustus</option>
            <option value="09" {{ $filterBulan == '09' ? 'selected' : '' }}>September</option>
            <option value="10" {{ $filterBulan == '10' ? 'selected' : '' }}>Oktober</option>
            <option value="11" {{ $filterBulan == '11' ? 'selected' : '' }}>November</option>
            <option value="12" {{ $filterBulan == '12' ? 'selected' : '' }}>Desember</option>
        </select>

        <select name="tahun" class="rounded-md border-0 py-2 pl-3 pr-8 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            <option value="all" {{ $filterTahun == 'all' ? 'selected' : '' }}>Semua Tahun</option>
            @php $currentYear = date('Y'); @endphp
            @for($i = $currentYear; $i >= 2020; $i--)
                <option value="{{ $i }}" {{ $filterTahun == $i ? 'selected' : '' }}>{{ $i }}</option>
            @endfor
        </select>
        <button type="submit" class="rounded-md bg-white px-3.5 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Cari Data</button>
        @if(request('search'))
            <a href="{{ route('surat-masuk.index') }}" class="text-sm text-red-600 hover:text-red-800">Clear Search</a>
        @endif
    </form>
</div>

<!-- Main Table -->
<div class="mt-8 flow-root">
    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                <table class="min-w-full border-collapse border border-gray-300">
                    <thead class="bg-slate-100">
                        <tr>
                            <th scope="col" class="border border-gray-300 py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6" rowspan="2" style="vertical-align: middle;">Tgl Masuk Surat</th>
                            <th scope="col" class="border border-gray-300 px-3 py-3.5 text-left text-sm font-semibold text-gray-900" rowspan="2" style="vertical-align: middle;">Nomor Urut</th>
                            <th scope="col" class="border border-gray-300 px-3 py-3.5 text-left text-sm font-semibold text-gray-900" rowspan="2" style="vertical-align: middle;">Alamat Pengirim</th>
                            <th scope="col" class="border border-gray-300 px-3 py-3.5 text-center text-sm font-semibold text-gray-900" colspan="3">Dari Surat Masuk</th>
                            <th scope="col" class="border border-gray-300 px-3 py-3.5 text-left text-sm font-semibold text-gray-900" rowspan="2" style="vertical-align: middle;">Tujuan / Disposisi</th>
                            <th scope="col" class="border border-gray-300 px-3 py-3.5 text-center text-sm font-semibold text-gray-900" rowspan="2" style="vertical-align: middle;">Aksi</th>
                        </tr>
                        <tr>
                            <th scope="col" class="border border-gray-300 px-3 py-2 text-left text-sm text-gray-700 font-medium">Tanggal</th>
                            <th scope="col" class="border border-gray-300 px-3 py-2 text-left text-sm text-gray-700 font-medium">Nomor</th>
                            <th scope="col" class="border border-gray-300 px-3 py-2 text-left text-sm text-gray-700 font-medium">Perihal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @forelse($surat_masuk as $surat)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="border border-gray-300 whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-900 sm:pl-6">{{ \Carbon\Carbon::parse($surat->tanggal_masuk_surat)->format('d/m/Y') }}</td>
                            <td class="border border-gray-300 whitespace-nowrap px-3 py-4 text-sm text-gray-500 font-medium">{{ $surat->nomor_urut }}</td>
                            <td class="border border-gray-300 px-3 py-4 text-sm text-gray-600">{{ $surat->alamat_pengirim }}</td>
                            <td class="border border-gray-300 whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ \Carbon\Carbon::parse($surat->tanggal_surat)->format('d/m/Y') }}</td>
                            <td class="border border-gray-300 whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $surat->nomor_surat }}</td>
                            <td class="border border-gray-300 px-3 py-4 text-sm text-gray-900 max-w-xs truncate" title="{{ $surat->perihal }}">{{ $surat->perihal }}</td>
                            <td class="border border-gray-300 px-3 py-4 text-sm text-gray-600">{{ $surat->tujuan_disposisi }}</td>
                            <td class="border border-gray-300 relative whitespace-nowrap py-4 pl-3 pr-4 text-center text-sm font-medium sm:pr-6">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('surat-masuk.edit', $surat->id) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-2 py-1 rounded">Edit</a>
                                    <form action="{{ route('surat-masuk.destroy', $surat->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 px-2 py-1 rounded">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="border border-gray-300 px-3 py-8 text-center text-sm text-gray-500 italic">
                                Tidak ada data surat masuk ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Pagination -->
<div class="mt-4">
    {{ $surat_masuk->links() }}
</div>
@endsection
