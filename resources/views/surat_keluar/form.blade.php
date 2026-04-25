@csrf

<!-- Kolom Kiri: Form Data -->
<div class="order-2 lg:order-1 lg:col-span-2 space-y-6">
    
    <!-- Section: Data Administrasi -->
    <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
        <div class="flex items-center gap-3 mb-6 border-b border-slate-100 pb-4">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" /></svg>
            </div>
            <h2 class="text-lg font-bold text-slate-800 tracking-tight">Data Administrasi</h2>
        </div>
        
        <div class="grid gap-6 sm:grid-cols-2">
            <div>
                <label for="tanggal_keluar_surat" class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal Keluar Surat <span class="text-rose-500">*</span></label>
                <input type="date" name="tanggal_keluar_surat" id="tanggal_keluar_surat" value="{{ old('tanggal_keluar_surat', $surat_keluar->tanggal_keluar_surat ?? date('Y-m-d')) }}" required class="block w-full rounded-xl border-0 bg-slate-50/50 py-2.5 pl-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm font-medium transition-all hover:bg-slate-50">
            </div>
            <div>
                <label for="nomor_urut" class="block text-sm font-semibold text-slate-700 mb-1.5">Nomor Urut</label>
                <input type="text" name="nomor_urut" id="nomor_urut" value="{{ old('nomor_urut', $surat_keluar->nomor_urut ?? $nomorRekomendasi ?? '') }}" placeholder="Contoh: 001" class="block w-full rounded-xl border-0 bg-slate-50/50 py-2.5 pl-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm font-medium transition-all hover:bg-slate-50">
                @isset($nomorRekomendasi)<p class="mt-1.5 text-xs text-emerald-600 font-medium">Rekomendasi otomatis tersedia.</p>@endisset
            </div>
        </div>
    </section>

    <!-- Section: Detail Surat -->
    <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
        <div class="flex items-center gap-3 mb-6 border-b border-slate-100 pb-4">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
            </div>
            <h2 class="text-lg font-bold text-slate-800 tracking-tight">Detail Surat Keluar</h2>
        </div>

        <div class="grid gap-6 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label for="alamat_penerima" class="block text-sm font-semibold text-slate-700 mb-1.5">Instansi / Penerima</label>
                <input type="text" name="alamat_penerima" id="alamat_penerima" value="{{ old('alamat_penerima', $surat_keluar->alamat_penerima ?? '') }}" placeholder="Ketik nama instansi penerima" class="block w-full rounded-xl border-0 bg-slate-50/50 py-2.5 pl-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm font-medium transition-all hover:bg-slate-50">
            </div>
            <div>
                <label for="tanggal_surat" class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal Surat <span class="text-rose-500">*</span></label>
                <input type="date" name="tanggal_surat" id="tanggal_surat" value="{{ old('tanggal_surat', $surat_keluar->tanggal_surat ?? '') }}" required class="block w-full rounded-xl border-0 bg-slate-50/50 py-2.5 pl-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm font-medium transition-all hover:bg-slate-50">
            </div>
            <div>
                <label for="nomor_surat" class="block text-sm font-semibold text-slate-700 mb-1.5">Nomor Surat Asli</label>
                <input type="text" name="nomor_surat" id="nomor_surat" value="{{ old('nomor_surat', $surat_keluar->nomor_surat ?? '') }}" placeholder="Kosongkan jika belum ada" class="block w-full rounded-xl border-0 bg-slate-50/50 py-2.5 pl-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm font-medium transition-all hover:bg-slate-50">
            </div>
            <div class="sm:col-span-2">
                <label for="perihal" class="block text-sm font-semibold text-slate-700 mb-1.5">Perihal / Isi Ringkas <span class="text-rose-500">*</span></label>
                <textarea id="perihal" name="perihal" rows="3" required placeholder="Jelaskan secara singkat perihal surat ini..." class="block w-full rounded-xl border-0 bg-slate-50/50 py-3 pl-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm font-medium transition-all hover:bg-slate-50">{{ old('perihal', $surat_keluar->perihal ?? '') }}</textarea>
            </div>
        </div>
    </section>

    <!-- Section: Asal -->
    <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
        <div class="flex items-center gap-3 mb-6 border-b border-slate-100 pb-4">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-sky-50 text-sky-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" /></svg>
            </div>
            <h2 class="text-lg font-bold text-slate-800 tracking-tight">Pengirim Internal (Asal)</h2>
        </div>
        
        <div>
            <label for="asal" class="block text-sm font-semibold text-slate-700 mb-1.5">Berasal Dari / Oleh</label>
            <input type="text" name="asal" id="asal" value="{{ old('asal', $surat_keluar->asal ?? '') }}" placeholder="Contoh: Kepala Bagian Kepegawaian" class="block w-full rounded-xl border-0 bg-slate-50/50 py-2.5 pl-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm font-medium transition-all hover:bg-slate-50">
        </div>
    </section>
</div>

<!-- Kolom Kanan: Sidebar Lampiran & Action -->
<div class="order-1 lg:order-2 space-y-6">
    <aside class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100 sticky top-24">
        <div class="flex items-center gap-3 mb-6 border-b border-slate-100 pb-4">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-50 text-amber-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.112 2.13" /></svg>
            </div>
            <h2 class="text-lg font-bold text-slate-800 tracking-tight">Lampiran File</h2>
        </div>

        @isset($surat_keluar)
            @if($surat_keluar->lampiran_path)
                <!-- Existing Attachment -->
                <div class="mb-6 rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <div class="flex items-start gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-white shadow-sm ring-1 ring-slate-200">
                            <svg class="h-5 w-5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-bold text-slate-800" title="{{ $surat_keluar->lampiran_nama_asli }}">{{ $surat_keluar->lampiran_nama_asli }}</p>
                            <p class="text-xs text-slate-500">{{ $surat_keluar->lampiran_ukuran_label ?: 'Ukuran tidak diketahui' }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-4 flex flex-col gap-2">
                        <a href="{{ $surat_keluar->lampiran_url }}" target="_blank" class="flex w-full items-center justify-center rounded-lg bg-white px-3 py-2 text-xs font-semibold text-indigo-700 ring-1 ring-inset ring-indigo-200 hover:bg-indigo-50 transition-colors">
                            Lihat Dokumen Saat Ini
                        </a>
                        <label class="group flex cursor-pointer items-start gap-2 rounded-lg border border-rose-200 bg-rose-50/50 p-2.5 hover:bg-rose-50 transition-colors">
                            <div class="flex h-5 items-center">
                                <input type="checkbox" name="hapus_lampiran" value="1" class="h-4 w-4 rounded border-rose-300 text-rose-600 focus:ring-rose-600">
                            </div>
                            <div class="text-xs font-semibold text-rose-700 leading-tight mt-0.5">
                                Hapus lampiran ini secara permanen saat disimpan
                            </div>
                        </label>
                    </div>
                </div>
            @endif
        @endisset

        <!-- Upload New -->
        <div>
            <p class="text-sm font-semibold text-slate-900">1. Pilih sumber dokumen</p>
            <p class="mt-1 text-xs text-slate-500">Gunakan salah satu: upload file yang sudah ada atau ambil foto langsung dari kamera.</p>
            @isset($surat_keluar)
                @if($surat_keluar->lampiran_path)
                    <p class="mt-3 text-xs font-semibold text-slate-500">
                        {{ $surat_keluar->lampiran_path ? 'Pilih file baru jika ingin mengganti lampiran.' : '' }}
                    </p>
                @endif
            @endisset
            <div class="mt-3 grid gap-3">
                <label for="lampiran" class="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 hover:border-indigo-300 hover:bg-indigo-50/60 transition-colors">
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-white text-indigo-600 ring-1 ring-slate-200">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0 3 3m-3-3-3 3M6.75 19.5h10.5A2.25 2.25 0 0019.5 17.25V6.75A2.25 2.25 0 0017.25 4.5H6.75A2.25 2.25 0 004.5 6.75v10.5A2.25 2.25 0 006.75 19.5z" /></svg>
                    </span>
                    <span class="min-w-0">
                        <span class="block text-sm font-bold text-slate-800">
                @isset($surat_keluar)
                    {{ $surat_keluar->lampiran_path ? 'Ganti File' : 'Pilih File' }}
                @else
                    Pilih File
                @endisset
                        </span>
                        <span class="block text-xs text-slate-500">PDF, JPG, PNG, WEBP sampai 10 MB</span>
                    </span>
                    <input id="lampiran" name="lampiran" type="file" class="sr-only" accept=".pdf,image/jpeg,image/png,image/webp">
                </label>
                <button type="button" id="cameraButton" class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 text-left hover:border-indigo-300 hover:bg-slate-50 transition-colors">
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 019.186 4.5h5.628a2.31 2.31 0 012.36 1.675l.183.642A2.25 2.25 0 0019.52 8.5h.23A2.25 2.25 0 0122 10.75v7A2.25 2.25 0 0119.75 20h-15A2.25 2.25 0 012.5 17.75v-7A2.25 2.25 0 014.75 8.5h.23a2.25 2.25 0 002.163-1.683l.184-.642z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 13.25a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" /></svg>
                    </span>
                    <span>
                        <span class="block text-sm font-bold text-slate-800">Ambil Foto</span>
                        <span class="block text-xs text-slate-500">Foto langsung menjadi file scan surat</span>
                    </span>
                </button>
                <div id="selectedFilePanel" class="hidden rounded-xl border border-emerald-100 bg-emerald-50 px-4 py-3">
                    <p class="text-xs font-bold uppercase tracking-wide text-emerald-700">Dokumen siap disimpan</p>
                    <p id="selectedFileName" class="mt-0.5 truncate text-sm font-semibold text-slate-800"></p>
                </div>
            </div>

            <div class="mt-5 rounded-xl border border-indigo-100 bg-indigo-50 p-4">
                <p class="text-sm font-semibold text-slate-900">2. Pindai isi gambar <span class="font-normal text-slate-500">(opsional)</span></p>
                <p class="mt-1 text-xs text-slate-600">Dipakai hanya jika dokumen berupa foto/gambar. PDF tetap bisa langsung disimpan sebagai lampiran.</p>
                <button type="button" id="scanButton" class="mt-3 w-full inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-3 py-2.5 text-xs font-bold text-white hover:bg-indigo-500 transition-colors shadow-sm">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" /><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h.75v.75h-.75v-.75zM13.5 19.5h.75v.75h-.75v-.75zM19.5 13.5h.75v.75h-.75v-.75zM19.5 19.5h.75v.75h-.75v-.75zM16.5 16.5h.75v.75h-.75v-.75z" /></svg>
                        Pindai Isi Gambar
                    </button>
            </div>
            
            <!-- OCR Status -->
            <div id="ocr_status" class="mt-3 hidden rounded-lg border border-indigo-100 bg-indigo-50/50 p-3 text-xs font-medium text-indigo-700"></div>
            <textarea id="ocr_text" class="mt-3 hidden w-full rounded-lg border-0 bg-slate-50 p-3 text-xs text-slate-600 ring-1 ring-inset ring-slate-200" rows="3" readonly></textarea>
        </div>

        <!-- Submit Button -->
        <div class="mt-8 hidden pt-6 border-t border-slate-100 lg:flex lg:flex-col gap-3">
            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-indigo-600 px-4 py-3 text-sm font-bold text-white shadow-md shadow-indigo-500/20 hover:from-indigo-600 hover:to-indigo-700 hover:shadow-lg hover:shadow-indigo-500/30 transition-all duration-300">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" /></svg>
                {{ $buttonLabel ?? 'Simpan Data' }}
            </button>
            <a href="javascript:history.back()" class="w-full inline-flex items-center justify-center rounded-xl bg-white px-4 py-3 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-200 hover:bg-slate-50 transition-colors">
                Batalkan
            </a>
        </div>
    </aside>
</div>

<div class="order-3 lg:hidden rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-100">
    <div class="flex flex-col gap-3">
        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-indigo-600 px-4 py-3 text-sm font-bold text-white shadow-md shadow-indigo-500/20">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" /></svg>
            {{ $buttonLabel ?? 'Simpan Data' }}
        </button>
        <a href="javascript:history.back()" class="w-full inline-flex items-center justify-center rounded-xl bg-white px-4 py-3 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-200">
            Batalkan
        </a>
    </div>
</div>

<div id="cameraModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/70 px-4 backdrop-blur-sm">
    <div class="w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
            <div>
                <h2 class="text-base font-bold text-slate-900">Ambil Foto Surat</h2>
                <p class="text-sm text-slate-500">Foto akan masuk ke File Scan Surat dan bisa langsung disimpan.</p>
            </div>
            <button type="button" id="cameraClose" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <div class="p-5">
            <video id="cameraVideo" class="aspect-video w-full rounded-xl bg-slate-900 object-cover" autoplay playsinline muted></video>
            <p id="cameraStatus" class="mt-3 hidden rounded-lg bg-amber-50 px-3 py-2 text-sm text-amber-800"></p>
            <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:justify-end">
                <button type="button" id="cameraCancel" class="rounded-md bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 ring-1 ring-slate-300 hover:bg-slate-50">Batal</button>
                <button type="button" id="cameraTake" class="rounded-md bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500">Gunakan Foto</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const scanButton = document.getElementById('scanButton');
    const imageInput = document.getElementById('lampiran');
    const statusBox = document.getElementById('ocr_status');
    const textBox = document.getElementById('ocr_text');
    const cameraButton = document.getElementById('cameraButton');
    const cameraModal = document.getElementById('cameraModal');
    const cameraVideo = document.getElementById('cameraVideo');
    const cameraTake = document.getElementById('cameraTake');
    const cameraClose = document.getElementById('cameraClose');
    const cameraCancel = document.getElementById('cameraCancel');
    const cameraStatus = document.getElementById('cameraStatus');
    const selectedFilePanel = document.getElementById('selectedFilePanel');
    const selectedFileName = document.getElementById('selectedFileName');
    let cameraStream = null;

    const monthMap = {
        januari: '01', februari: '02', maret: '03', april: '04', mei: '05', juni: '06',
        juli: '07', agustus: '08', september: '09', oktober: '10', november: '11', desember: '12'
    };

    function setStatus(message) {
        statusBox.textContent = message;
        statusBox.classList.remove('hidden');
    }

    function setCameraStatus(message) {
        cameraStatus.textContent = message;
        cameraStatus.classList.remove('hidden');
    }

    function stopCamera() {
        if (cameraStream) {
            cameraStream.getTracks().forEach(track => track.stop());
            cameraStream = null;
        }
        if (cameraVideo) cameraVideo.srcObject = null;
    }

    async function openCamera() {
        cameraStatus.classList.add('hidden');
        cameraModal.classList.remove('hidden');
        cameraModal.classList.add('flex');

        try {
            cameraStream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: { ideal: 'environment' } },
                audio: false,
            });
            cameraVideo.srcObject = cameraStream;
        } catch (error) {
            setCameraStatus('Kamera tidak bisa dibuka. Berikan izin kamera atau gunakan pilih file biasa.');
        }
    }

    function closeCamera() {
        stopCamera();
        cameraModal.classList.add('hidden');
        cameraModal.classList.remove('flex');
    }

    function setLampiranFromBlob(blob, fileName) {
        const file = new File([blob], fileName, { type: 'image/jpeg' });
        const transfer = new DataTransfer();
        transfer.items.add(file);
        imageInput.files = transfer.files;
        showSelectedFile(fileName);
        setStatus('Foto kamera sudah masuk ke File Scan Surat. Klik Pindai Data Gambar untuk OCR atau langsung simpan data.');
    }

    function showSelectedFile(fileName) {
        if (!selectedFilePanel || !selectedFileName) return;
        selectedFileName.textContent = fileName || '';
        selectedFilePanel.classList.toggle('hidden', !fileName);
    }

    function normalizeDate(value) {
        if (!value) return '';
        const text = value.toLowerCase().replace(/[,]/g, ' ').replace(/\s+/g, ' ').trim();
        let match = text.match(/(\d{1,2})[\/\-.](\d{1,2})[\/\-.](\d{2,4})/);
        if (match) {
            const year = match[3].length === 2 ? '20' + match[3] : match[3];
            return `${year}-${match[2].padStart(2, '0')}-${match[1].padStart(2, '0')}`;
        }
        match = text.match(/(\d{1,2})\s+(januari|februari|maret|april|mei|juni|juli|agustus|september|oktober|november|desember)\s+(\d{4})/);
        if (match) {
            return `${match[3]}-${monthMap[match[2]]}-${match[1].padStart(2, '0')}`;
        }
        return '';
    }

    function valueAfterLabel(lines, labels) {
        for (const line of lines) {
            const lower = line.toLowerCase();
            for (const label of labels) {
                if (lower.includes(label)) {
                    const parts = line.split(/:|-/);
                    if (parts.length > 1) return parts.slice(1).join('-').trim();
                }
            }
        }
        return '';
    }

    function fillIfEmpty(id, value) {
        const input = document.getElementById(id);
        if (input && value && !input.value.trim()) input.value = value;
    }

    function parseLetter(text) {
        const lines = text.split(/\r?\n/).map(line => line.trim()).filter(Boolean);
        const nomor = valueAfterLabel(lines, ['nomor', 'no. surat', 'no surat']);
        const perihal = valueAfterLabel(lines, ['perihal', 'hal ']);
        const penerima = valueAfterLabel(lines, ['kepada', 'penerima', 'yth']);
        const tanggalLine = lines.find(line => normalizeDate(line));

        fillIfEmpty('nomor_surat', nomor);
        fillIfEmpty('perihal', perihal);
        fillIfEmpty('alamat_penerima', penerima);
        fillIfEmpty('tanggal_surat', normalizeDate(tanggalLine || ''));
    }

    cameraButton?.addEventListener('click', () => {
        if (!navigator.mediaDevices?.getUserMedia) {
            setStatus('Browser tidak mendukung akses kamera langsung. Gunakan pilih file biasa.');
            return;
        }

        openCamera();
    });

    cameraClose?.addEventListener('click', closeCamera);
    cameraCancel?.addEventListener('click', closeCamera);

    cameraTake?.addEventListener('click', () => {
        if (!cameraStream) {
            setCameraStatus('Kamera belum siap.');
            return;
        }

        const canvas = document.createElement('canvas');
        canvas.width = cameraVideo.videoWidth;
        canvas.height = cameraVideo.videoHeight;
        canvas.getContext('2d').drawImage(cameraVideo, 0, 0, canvas.width, canvas.height);
        canvas.toBlob((blob) => {
            if (!blob) {
                setCameraStatus('Gagal mengambil foto. Coba ulangi.');
                return;
            }

            setLampiranFromBlob(blob, 'scan-surat-keluar.jpg');
            closeCamera();
        }, 'image/jpeg', 0.92);
    });

    imageInput?.addEventListener('change', () => {
        showSelectedFile(imageInput.files?.[0]?.name || '');
    });

    scanButton?.addEventListener('click', async () => {
        if (!imageInput.files.length) {
            setStatus('Pilih file scan surat terlebih dahulu.');
            return;
        }

        if (!imageInput.files[0].type.startsWith('image/')) {
            setStatus('Pindai otomatis hanya tersedia untuk file gambar (JPG/PNG/WEBP). PDF akan tetap tersimpan sebagai lampiran biasa.');
            return;
        }

        scanButton.disabled = true;
        const originalText = scanButton.innerHTML;
        scanButton.innerHTML = `<svg class="h-4 w-4 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memindai...`;
        
        setStatus('Menjalankan AI Scanner lokal. Harap tunggu beberapa saat...');

        try {
            if (!window.SipasOcr) {
                throw new Error('AI Engine (OCR) belum dimuat.');
            }

            const text = await window.SipasOcr.recognize(imageInput.files[0], (message) => {
                if (message.status === 'recognizing text' && message.progress) {
                    setStatus(`AI sedang membaca teks... ${Math.round(message.progress * 100)}%`);
                }
            });
            textBox.value = text;
            textBox.classList.remove('hidden');
            parseLetter(text);
            setStatus('Pemindaian selesai! Field yang berhasil dideteksi telah diisi otomatis. Mohon periksa kembali.');
        } catch (error) {
            setStatus('Gagal membaca dokumen. Pastikan gambar tidak buram dan teks dapat dibaca jelas.');
        } finally {
            scanButton.disabled = false;
            scanButton.innerHTML = originalText;
        }
    });
</script>
@endpush
