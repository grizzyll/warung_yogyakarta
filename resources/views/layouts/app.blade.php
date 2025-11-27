<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Sistem Ayam Yogya</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-brand-gray font-sans antialiased text-brand-black">

    <div class="min-h-screen flex" id="app">
        
        <!-- SIDEBAR -->
        <aside class="w-72 bg-brand-red text-white flex flex-col shadow-2xl fixed h-full z-20">
            <!-- Logo -->
            <div class="h-24 flex items-center justify-center bg-white border-b-4 border-brand-yellow px-4">
                <img src="/logo.png" alt="Ayam Yogya" class="h-16 object-contain">
            </div>

            <!-- Menu Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-3 overflow-y-auto">
                
                <!-- Menu Owner -->
                @if(Auth::check() && (Auth::user()->role == 'owner' || Auth::user()->role == 'admin'))
                <div class="text-xs font-bold text-red-200 uppercase tracking-widest mb-2 border-b border-red-400 pb-1">Owner Area</div>
                
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center px-4 py-3 rounded-xl transition font-medium
                          {{ request()->routeIs('dashboard') ? 'bg-red-800 text-white border-l-4 border-brand-yellow shadow-inner' : 'text-red-100 hover:bg-red-700 hover:text-white' }}">
                    <i class="fas fa-chart-pie w-6 opacity-80"></i> Dashboard
                </a>
                @endif

                <div class="text-xs font-bold text-red-200 uppercase tracking-widest mt-6 mb-2 border-b border-red-400 pb-1">Operasional</div>

                <!-- Menu KASIR (Perhatikan class text-red-100) -->
                <a href="{{ route('pos.index') }}" 
                   class="group flex items-center px-4 py-3 rounded-xl transition font-medium
                          {{ request()->routeIs('pos.index') ? 'bg-red-800 text-white border-l-4 border-brand-yellow shadow-inner' : 'text-red-100 hover:bg-red-700 hover:text-white' }}">
                    <i class="fas fa-cash-register w-6 opacity-80"></i> KASIR (POS)
                </a>

                <!-- Menu DAPUR -->
                <a href="{{ route('kitchen.index') }}" 
                   class="group flex items-center px-4 py-3 rounded-xl transition font-medium
                          {{ request()->routeIs('kitchen.index') ? 'bg-red-800 text-white border-l-4 border-brand-yellow shadow-inner' : 'text-red-100 hover:bg-red-700 hover:text-white' }}">
                    <i class="fas fa-fire w-6 opacity-80"></i> Dapur (KDS)
                </a>

                <!-- Logout -->
                <div class="mt-auto mb-4 pt-4 border-t border-red-500">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center px-4 py-3 text-red-100 hover:text-white hover:bg-red-700 rounded-xl w-full text-left transition font-medium">
                            <i class="fas fa-sign-out-alt w-6"></i> Logout
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <!-- KONTEN KANAN -->
        <main class="flex-1 ml-72 flex flex-col min-h-screen bg-brand-gray relative">
            <!-- Header -->
            <header class="h-16 bg-white shadow-sm flex items-center justify-between px-8 sticky top-0 z-10 border-b border-gray-200">
                <div>
                    <h2 class="text-2xl font-bold text-brand-black tracking-tight">@yield('title', 'Dashboard')</h2>
                </div>
                
                <div class="flex items-center gap-4">
                    <div class="text-right hidden md:block">
                        <p class="text-sm font-bold text-gray-800">{{ Auth::user()->name ?? 'Guest' }}</p>
                        <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">{{ Auth::user()->role ?? '-' }}</p>
                    </div>
                    <div class="h-10 w-10 bg-brand-yellow rounded-full flex items-center justify-center text-brand-red font-bold shadow-md border-2 border-brand-red text-lg">
                        {{ substr(Auth::user()->name ?? 'G', 0, 1) }}
                    </div>
                </div>
            </header>

            <!-- Isi Halaman -->
            <div class="p-6 h-full overflow-y-auto">
                @yield('content')
            </div>
        </main>

    </div>
</body>
</html>