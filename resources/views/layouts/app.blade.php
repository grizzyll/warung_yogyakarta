<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ayam Yogya POS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #F3F4F6;
        }
    </style>
</head>

<body class="text-gray-800 antialiased">
    <div class="flex h-screen overflow-hidden" id="app">

        <!-- SIDEBAR MODERN -->
        <aside class="w-64 bg-primary-dark text-white flex flex-col shadow-2xl z-20">
            <!-- Brand -->
            <div class="h-20 flex items-center justify-center border-b border-red-900/30 bg-primary-dark relative">
                <div class="absolute left-0 top-0 h-full w-1 bg-accent"></div>
                <div class="flex items-center gap-3">
                    <div class="bg-white p-1.5 rounded-lg shadow-lg">
                        <img src="/logo.png" alt="Logo" class="h-8 w-8 object-contain">
                    </div>
                    <div>
                        <h1 class="font-bold text-lg tracking-wide leading-none">AYAM YOGYA</h1>
                        <span class="text-[10px] text-red-200 uppercase tracking-widest">Enterprise System</span>
                    </div>
                </div>
            </div>

            <!-- Menu -->
            <nav class="flex-1 px-3 py-6 space-y-1 overflow-y-auto custom-scrollbar">

                <!-- Group: Management -->
                <!-- Cuma Owner yang punya Dashboard -->
                @if(Auth::check() && Auth::user()->role == 'owner')
                    <div class="px-3 mb-2 mt-2 text-[10px] font-bold text-red-300 uppercase tracking-wider">Owner Area</div>

                    <a href="{{ route('dashboard') }}"
                        class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-primary to-primary-light shadow-lg text-white' : 'text-red-100 hover:bg-white/10' }}">
                        <i
                            class="fas fa-chart-pie w-6 {{ request()->routeIs('dashboard') ? 'text-accent' : 'text-red-300 group-hover:text-white' }}"></i>
                        <span class="font-medium">Dashboard</span>
                    </a>
                    <a href="{{ route('users.index') }}"
                        class="flex items-center px-4 py-3 rounded-xl transition font-medium text-red-100 hover:bg-red-700 hover:text-white">
                        <i class="fas fa-users-gear w-6 opacity-80"></i> Pegawai
                    </a>
                    <a href="{{ route('reports.index') }}"
                        class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('reports.index') ? 'bg-gradient-to-r from-primary to-primary-light shadow-lg text-white' : 'text-red-100 hover:bg-white/10' }}">
                        <i
                            class="fas fa-folder-open w-6 {{ request()->routeIs('reports.index') ? 'text-accent' : 'text-red-300 group-hover:text-white' }}"></i>
                        <span class="font-medium">Laporan & Export</span>
                    </a>
                @endif
                <!-- 2. FINANCE AREA (KHUSUS ADMIN) -->
                @if(Auth::user()->role == 'admin')
                    <div class="px-3 mb-2 mt-2 text-[10px] font-bold text-red-300 uppercase tracking-wider">Finance</div>

                    <a href="{{ route('finance.index') }}"
                        class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('finance.index') ? 'bg-gradient-to-r from-primary to-primary-light shadow-lg text-white' : 'text-red-100 hover:bg-white/10' }}">
                        <i
                            class="fas fa-wallet w-6 {{ request()->routeIs('finance.index') ? 'text-accent' : 'text-red-300 group-hover:text-white' }}"></i>
                        <span class="font-medium">Keuangan Detail</span>
                    </a>

                    <a href="{{ route('restock.create') }}"
                        class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('restock.create') ? 'bg-gradient-to-r from-primary to-primary-light shadow-lg text-white' : 'text-red-100 hover:bg-white/10' }}">
                        <i
                            class="fas fa-boxes w-6 {{ request()->routeIs('restock.create') ? 'text-accent' : 'text-red-300 group-hover:text-white' }}"></i>
                        <span class="font-medium">Belanja Stok</span>
                    </a>
                @endif

                <!-- Group: Operasional -->
                <div class="px-3 mb-2 mt-6 text-[10px] font-bold text-red-300 uppercase tracking-wider">Operasional
                </div>

                <a href="{{ route('pos.index') }}"
                    class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('pos.index') ? 'bg-gradient-to-r from-primary to-primary-light shadow-lg text-white' : 'text-red-100 hover:bg-white/10' }}">
                    <i
                        class="fas fa-cash-register w-6 {{ request()->routeIs('pos.index') ? 'text-accent' : 'text-red-300 group-hover:text-white' }}"></i>
                    <span class="font-medium">Kasir (POS)</span>
                </a>

                <a href="{{ route('kitchen.index') }}"
                    class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('kitchen.index') ? 'bg-gradient-to-r from-primary to-primary-light shadow-lg text-white' : 'text-red-100 hover:bg-white/10' }}">
                    <i
                        class="fas fa-fire-burner w-6 {{ request()->routeIs('kitchen.index') ? 'text-accent' : 'text-red-300 group-hover:text-white' }}"></i>
                    <span class="font-medium">Dapur (KDS)</span>
                </a>
            </nav>

            <!-- User Footer -->
            <div class="p-4 border-t border-red-900/30 bg-red-900/20">
                <div class="flex items-center gap-3">
                    <div
                        class="h-10 w-10 rounded-full bg-gradient-to-br from-accent to-yellow-600 flex items-center justify-center text-white font-bold shadow-md">
                        {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-red-300 truncate capitalize">{{ Auth::user()->role }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-red-300 hover:text-white transition"><i
                                class="fas fa-sign-out-alt"></i></button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <main class="flex-1 flex flex-col relative overflow-hidden bg-surface">
            <!-- Header (Optional, mostly for title/breadcrumbs) -->
            <header class="h-16 bg-paper shadow-sm flex items-center justify-between px-8 z-10">
                <h2 class="text-xl font-bold text-gray-800 tracking-tight">@yield('title', 'Dashboard')</h2>
                <div class="text-sm text-gray-500 font-medium">
                    {{ date('l, d M Y') }}
                </div>
            </header>

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-x-hidden overflow-y-auto bg-surface p-6">
                @yield('content')
            </div>
        </main>
    </div>
</body>

</html>