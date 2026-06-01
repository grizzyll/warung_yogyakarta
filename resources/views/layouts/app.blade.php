<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ayam Yogya POS</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #F3F4F6; }
        .sidebar-transition { transition: transform 0.3s ease-in-out; }
        /* Sembunyikan scrollbar tapi tetap bisa scroll */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>

<!-- PERBAIKAN 1: h-[100dvh] agar pas dengan layar HP Android -->
<body class="text-gray-800 antialiased h-[100dvh] overflow-hidden flex flex-col">

    <!-- 1. MOBILE HEADER -->
    <div class="md:hidden bg-[#B91C1C] text-white p-3 flex justify-between items-center shadow-md z-50 shrink-0">
        <div class="font-bold text-base flex items-center gap-2">
            <img src="/logo.png" alt="Logo" class="h-8 w-8 bg-white rounded p-1 object-contain">
            <span>AYAM YOGYA</span>
        </div>
        <button onclick="toggleSidebar()" class="text-white focus:outline-none p-2 rounded hover:bg-white/20 active:bg-white/30">
            <i class="fas fa-bars text-2xl"></i>
        </button>
    </div>

    <!-- WRAPPER UTAMA -->
    <div class="flex flex-1 overflow-hidden relative" id="app">

        <!-- 2. SIDEBAR -->
        <!-- Perbaikan: z-index tinggi (z-50) biar menutupi konten, dan h-full relatif terhadap wrapper -->
        <aside id="sidebar" 
               class="sidebar-transition absolute md:relative z-50 w-64 h-full bg-[#7F1D1D] text-white flex flex-col shadow-2xl transform -translate-x-full md:translate-x-0">
            
            <!-- Brand Area (Desktop) -->
            <div class="h-16 hidden md:flex items-center justify-center border-b border-red-900/30 bg-[#7F1D1D] shrink-0">
                <div class="flex items-center gap-3">
                    <div class="bg-white p-1 rounded-lg shadow-lg">
                        <img src="/logo.png" alt="Logo" class="h-8 w-8 object-contain">
                    </div>
                    <div>
                        <h1 class="font-bold text-lg tracking-wide leading-none">AYAM YOGYA</h1>
                    </div>
                </div>
            </div>

            <!-- Tombol Close (Mobile) -->
            <div class="md:hidden p-4 flex justify-end bg-[#991b1b] shrink-0">
                <button onclick="toggleSidebar()" class="text-white hover:text-gray-200 flex items-center gap-2 bg-black/20 px-3 py-1 rounded-full">
                    <span class="text-xs font-bold uppercase">Tutup</span> <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Menu Navigation (Scrollable) -->
            <!-- Perbaikan: flex-1 overflow-y-auto agar bagian ini saja yang scroll, tombol logout tetap di bawah -->
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto no-scrollbar">

                @if(Auth::check() && Auth::user()->role == 'owner')
                    <div class="px-3 mb-2 mt-2 text-[10px] font-bold text-red-300 uppercase tracking-wider">Owner Area</div>
                    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs('dashboard') ? 'bg-white text-[#D9232D] shadow-lg font-bold' : 'text-red-100 hover:bg-white/10' }}">
                        <i class="fas fa-chart-pie w-5 text-center"></i> <span class="ml-3 text-sm">Dashboard</span>
                    </a>
                    <a href="{{ route('users.index') }}" class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs('users.index') ? 'bg-white text-[#D9232D] shadow-lg font-bold' : 'text-red-100 hover:bg-white/10' }}">
                        <i class="fas fa-users-gear w-5 text-center"></i> <span class="ml-3 text-sm">Pegawai</span>
                    </a>
                    <a href="{{ route('reports.index') }}" class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs('reports.index') ? 'bg-white text-[#D9232D] shadow-lg font-bold' : 'text-red-100 hover:bg-white/10' }}">
                        <i class="fas fa-folder-open w-5 text-center"></i> <span class="ml-3 text-sm">Laporan</span>
                    </a>
                @endif

                @if(Auth::user()->role == 'admin')
                    <div class="px-3 mb-2 mt-2 text-[10px] font-bold text-red-300 uppercase tracking-wider">Finance</div>
                    <a href="{{ route('restock.create') }}" class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs('restock.create') ? 'bg-white text-[#D9232D] shadow-lg font-bold' : 'text-red-100 hover:bg-white/10' }}">
                        <i class="fas fa-boxes w-5 text-center"></i> <span class="ml-3 text-sm">Belanja Stok</span>
                    </a>
                @endif

                <div class="px-3 mb-2 mt-4 text-[10px] font-bold text-red-300 uppercase tracking-wider">Operasional</div>
                <a href="{{ route('pos.index') }}" class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs('pos.index') ? 'bg-white text-[#D9232D] shadow-lg font-bold' : 'text-red-100 hover:bg-white/10' }}">
                    <i class="fas fa-cash-register w-5 text-center"></i> <span class="ml-3 text-sm">Kasir (POS)</span>
                </a>
                <a href="{{ route('kitchen.index') }}" class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs('kitchen.index') ? 'bg-white text-[#D9232D] shadow-lg font-bold' : 'text-red-100 hover:bg-white/10' }}">
                    <i class="fas fa-fire-burner w-5 text-center"></i> <span class="ml-3 text-sm">Dapur (KDS)</span>
                </a>

                <!-- Jarak aman di HP agar menu paling bawah tidak ketutup tombol logout -->
                <div class="h-20 md:h-0"></div>
            </nav>

            <!-- Logout (Fixed at Bottom) -->
            <!-- Perbaikan: shrink-0 agar tidak tergencet, z-20 agar di atas scroll menu -->
            <div class="p-4 border-t border-red-800/30 bg-[#601212] shrink-0 z-20">
                <div class="flex items-center gap-3 mb-3">
                    <div class="h-9 w-9 rounded-full bg-yellow-500 flex items-center justify-center text-red-900 font-bold text-sm">
                        {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-white truncate">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-red-300 truncate uppercase">{{ Auth::user()->role }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center justify-center w-full px-4 py-2 bg-red-800 hover:bg-red-700 text-white rounded-lg transition text-xs font-bold shadow-sm border border-red-700">
                        <i class="fas fa-sign-out-alt mr-2"></i> LOGOUT
                    </button>
                </form>
            </div>
        </aside>

        <!-- OVERLAY (Latar Gelap Mobile) -->
        <div id="mobile-overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/60 z-40 hidden md:hidden backdrop-blur-sm transition-opacity"></div>

        <!-- MAIN CONTENT AREA -->
        <main class="flex-1 flex flex-col relative overflow-hidden bg-[#F3F4F6]">
            <!-- Header Desktop -->
            <header class="hidden md:flex h-16 bg-white shadow-sm items-center justify-between px-8 z-10 shrink-0">
                <h2 class="text-xl font-bold text-gray-800 tracking-tight">@yield('title', 'Dashboard')</h2>
                <div class="text-sm text-gray-500 font-medium">{{ date('l, d M Y') }}</div>
            </header>

            <!-- Scrollable Content -->
            <!-- Perbaikan: pb-24 di mobile agar konten paling bawah (seperti stok menipis) tidak ketutup -->
            <div class="flex-1 overflow-x-hidden overflow-y-auto p-4 pb-24 md:p-6 md:pb-6">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobile-overlay');
            
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        }
    </script>
</body>
</html>