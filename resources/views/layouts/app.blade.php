<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Agenda Surat Masuk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex overflow-hidden">

    <!-- Sidebar Navigation -->
    <aside class="w-64 bg-indigo-700 shadow-xl flex-shrink-0 flex flex-col transition-all duration-300 rounded-r-2xl">
        <!-- Logo Area -->
        <div class="h-20 flex items-center px-8 border-b border-indigo-600/50">
            <span class="text-white text-2xl font-bold tracking-wider">SIPAS</span>
        </div>
        
        <!-- User/Department Info -->
        <div class="px-8 py-5 border-b border-indigo-600/50 bg-indigo-800/30">
            <p class="text-xs text-indigo-300 uppercase tracking-wider font-semibold mb-1">Unit Kerja</p>
            <p class="text-sm font-medium text-white">Bagian Kepegawaian</p>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-4 py-8 space-y-3 overflow-y-auto">
            <a href="{{ route('surat-masuk.index') }}" class="{{ request()->routeIs('surat-masuk.*') ? 'bg-indigo-800 text-white shadow-inner' : 'text-indigo-100 hover:bg-indigo-600/50 hover:text-white' }} group flex items-center px-4 py-3.5 text-sm font-medium rounded-xl transition-all duration-200">
                <span class="text-xl mr-4 {{ request()->routeIs('surat-masuk.*') ? 'opacity-100' : 'opacity-80 group-hover:opacity-100' }}">✉️</span>
                Surat Masuk
            </a>

            <a href="/surat-keluar" class="{{ request()->is('surat-keluar*') ? 'bg-indigo-800 text-white shadow-inner' : 'text-indigo-100 hover:bg-indigo-600/50 hover:text-white' }} group flex items-center px-4 py-3.5 text-sm font-medium rounded-xl transition-all duration-200">
                <span class="text-xl mr-4 {{ request()->is('surat-keluar*') ? 'opacity-100' : 'opacity-80 group-hover:opacity-100' }}">📤</span>
                Surat Keluar
            </a>
            
            <div class="pt-8 mt-8 border-t border-indigo-600/50">
                <p class="px-4 text-xs font-semibold text-indigo-300 uppercase tracking-wider">Laporan Lainnya</p>
                <a href="#" class="mt-4 text-indigo-200 hover:bg-indigo-600/50 hover:text-white group flex items-center px-4 py-3.5 text-sm font-medium rounded-xl transition-all duration-200">
                    <span class="text-xl mr-4 opacity-80 group-hover:opacity-100">📊</span>
                    Grafik Bulanan
                </a>
            </div>
        </nav>

        <!-- Footer Sidebar -->
        <div class="p-4 border-t border-indigo-600/50">
            <p class="text-center text-xs text-indigo-300">&copy; {{ date('Y') }} Sistem Agenda</p>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-slate-50/50">
        <!-- Top Header optional minimalis -->
        <header class="bg-white shadow-sm border-b border-slate-200 h-20 flex items-center px-8 justify-between">
            <h1 class="text-lg font-semibold text-slate-800">Manajemen Dokumen & Arsip</h1>
        </header>

        <!-- Content Scrolling -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto px-8 py-8 w-full">
            @if(session('success'))
                <div class="mb-8 p-4 rounded-xl bg-emerald-50 border border-emerald-200 shadow-sm animate-fade-in-down">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-emerald-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-emerald-800">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <div class="animate-fade-in">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Small keyframes added for extra juiciness -->
    <style>
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .animate-fade-in-down { animation: fadeInDown 0.4s ease-out; }
        .animate-fade-in { animation: fadeIn 0.5s ease-out; }
    </style>
</body>
</html>
