<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ayam Yogya POS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #F3F4F6; }
        /* Transisi halus buat sidebar */
        .sidebar-transition { transition: transform 0.3s ease-in-out; }
    </style>
</head>

<body class="text-gray-800 antialiased h-screen overflow-hidden flex flex-col">

    <!-- 1. MOBILE HEADER (Hanya Muncul di HP) -->
    <!-- Ini Header Merah di atas buat tombol hamburger -->
    <div class="md:hidden bg-[#B91C1C] text-white p-4 flex justify-between items-center shadow-md z-50 shrink-0">
        <div class="font-bold text-lg flex items-center gap-2">
            <img src="/logo.png" alt="Logo" class="h-8 w-8 bg-white rounded p-1 object-contain">
            <span>AYAM YOGYA</span>
        </div>
        <!-- Tombol Buka Menu -->
        <button onclick="toggleSidebar()" class="text-white focus:outline-none p-2 rounded hover:bg-white/20">
            <i class="fas fa-bars text-2xl"></i>
        </button>
    </div>

    <!-- WRAPPER UTAMA -->
    <div class="flex flex-1 overflow-hidden relative" id="app">

        <!-- 2. SIDEBAR (RESPONSIVE) -->
        <!-- Logic: Di HP dia absolute & sembunyi (-translate-x-full). Di Laptop dia relative & muncul. -->
        <aside id="sidebar" 
               class="sidebar-transition absolute md:relative z-40 w-64 h-full bg-[#7F1D1D] text-white flex flex-col shadow-2xl transform -translate-x-full md:translate-x-0">
            
            <!-- Brand Area (Desktop) -->
            <div class="h-20 hidden md:flex items-center justify-center border-b border-red-900/30 bg-[#7F1D1D] relative">
                <div class="absolute left-0 top-0 h-full w-1 bg-yellow-500"></div>
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

            <!-- Tombol Close (Hanya di Mobile) -->
            <div class="md:hidden p-4 flex justify-end bg-[#991b1b]">
                <button onclick="toggleSidebar()" class="text-white hover:text-gray-200 flex items-center gap-2">
                    <span class="text-xs font-bold uppercase">Tutup</span> <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Menu Navigation -->
            <nav class="flex-1 px-3 py-6 space-y-1 overflow-y-auto custom-scrollbar">

                <!-- 1. OWNER AREA -->
                @if(Auth::check() && Auth::user()->role == 'owner')
                    <div class="px-3 mb-2 mt-2 text-[10px] font-bold text-red-300 uppercase tracking-wider">Owner Area</div>

                    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-[#B91C1C] to-[#DC2626] shadow-lg text-white' : 'text-red-100 hover:bg-white/10' }}">
                        <i class="fas fa-chart-pie w-6 {{ request()->routeIs('dashboard') ? 'text-yellow-400' : 'text-red-300 group-hover:text-white' }}"></i>
                        <span class="font-medium">Dashboard</span>
                    </a>
                    <a href="{{ route('users.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('users.index') ? 'bg-gradient-to-r from-[#B91C1C] to-[#DC2626] shadow-lg text-white' : 'text-red-100 hover:bg-white/10' }}">
                        <i class="fas fa-users-gear w-6 {{ request()->routeIs('users.index') ? 'text-yellow-400' : 'text-red-300 group-hover:text-white' }}"></i>
                        <span class="font-medium">Pegawai</span>
                    </a>
                    <a href="{{ route('reports.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('reports.index') ? 'bg-gradient-to-r from-[#B91C1C] to-[#DC2626] shadow-lg text-white' : 'text-red-100 hover:bg-white/10' }}">
                        <i class="fas fa-folder-open w-6 {{ request()->routeIs('reports.index') ? 'text-yellow-400' : 'text-red-300 group-hover:text-white' }}"></i>
                        <span class="font-medium">Laporan & Export</span>
                    </a>
                @endif

                <!-- 2. FINANCE AREA (ADMIN) -->
                @if(Auth::user()->role == 'admin')
                    <div class="px-3 mb-2 mt-2 text-[10px] font-bold text-red-300 uppercase tracking-wider">Finance</div>
                    
                    <!-- Contoh menu finance index kalau ada -->
                    <!-- <a href="..." ...> ... </a> -->

                    <a href="{{ route('restock.create') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('restock.create') ? 'bg-gradient-to-r from-[#B91C1C] to-[#DC2626] shadow-lg text-white' : 'text-red-100 hover:bg-white/10' }}">
                        <i class="fas fa-boxes w-6 {{ request()->routeIs('restock.create') ? 'text-yellow-400' : 'text-red-300 group-hover:text-white' }}"></i>
                        <span class="font-medium">Belanja Stok</span>
                    </a>
                @endif

                <!-- 3. OPERATIONAL AREA -->
                <div class="px-3 mb-2 mt-6 text-[10px] font-bold text-red-300 uppercase tracking-wider">Operasional</div>

                <a href="{{ route('pos.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('pos.index') ? 'bg-gradient-to-r from-[#B91C1C] to-[#DC2626] shadow-lg text-white' : 'text-red-100 hover:bg-white/10' }}">
                    <i class="fas fa-cash-register w-6 {{ request()->routeIs('pos.index') ? 'text-yellow-400' : 'text-red-300 group-hover:text-white' }}"></i>
                    <span class="font-medium">Kasir (POS)</span>
                </a>

                <a href="{{ route('kitchen.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('kitchen.index') ? 'bg-gradient-to-r from-[#B91C1C] to-[#DC2626] shadow-lg text-white' : 'text-red-100 hover:bg-white/10' }}">
                    <i class="fas fa-fire-burner w-6 {{ request()->routeIs('kitchen.index') ? 'text-yellow-400' : 'text-red-300 group-hover:text-white' }}"></i>
                    <span class="font-medium">Dapur (KDS)</span>
                </a>

            </nav>

            <!-- User Footer -->
            <div class="p-4 border-t border-red-900/30 bg-black/20">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center text-red-900 font-bold shadow-md">
                        {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-red-300 truncate capitalize">{{ Auth::user()->role }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-red-300 hover:text-white transition"><i class="fas fa-sign-out-alt"></i></button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- OVERLAY (Latar Gelap saat menu mobile buka) -->
        <div id="mobile-overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/50 z-30 hidden md:hidden transition-opacity"></div>

        <!-- MAIN CONTENT AREA -->
        <main class="flex-1 flex flex-col relative overflow-hidden bg-[#F3F4F6]">
            <!-- Header Desktop Only -->
            <header class="hidden md:flex h-16 bg-white shadow-sm items-center justify-between px-8 z-10 shrink-0">
                <h2 class="text-xl font-bold text-gray-800 tracking-tight">@yield('title', 'Dashboard')</h2>
                <div class="text-sm text-gray-500 font-medium">
                    {{ date('l, d M Y') }}
                </div>
            </header>

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-x-hidden overflow-y-auto p-4 md:p-6">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- SCRIPT HAMBURGER -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobile-overlay');
            
            // Logic Toggle Class
            if (sidebar.classList.contains('-translate-x-full')) {
                // Munculkan
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            } else {
                // Sembunyikan
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        }
    </script>
</body>
</html>