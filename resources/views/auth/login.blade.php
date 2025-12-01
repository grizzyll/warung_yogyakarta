<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Ayam Yogya System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Animasi masuk */
        .fade-in-up { animation: fadeInUp 0.8s ease-out; }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-gray-50">

    <div class="min-h-screen flex">
        
        <!-- BAGIAN KIRI: VISUAL (Gambar & Branding) -->
        <!-- Hidden di HP, Muncul di Tablet/PC -->
        <div class="hidden lg:flex w-1/2 bg-red-900 relative overflow-hidden">
            <!-- Background Image -->
            <img src="https://images.unsplash.com/photo-1598515214211-89d3c73ae83b?q=80&w=2070&auto=format&fit=crop" 
                 class="absolute inset-0 w-full h-full object-cover opacity-60 mix-blend-overlay">
            
            <!-- Overlay Gradient -->
            <div class="absolute inset-0 bg-gradient-to-t from-red-900 via-red-900/40 to-transparent"></div>

            <!-- Teks Branding -->
            <div class="relative z-10 p-16 flex flex-col justify-end h-full text-white">
                <div class="mb-6">
                    <img src="/logo.png" alt="Logo" class="h-16 w-auto bg-white p-2 rounded-lg shadow-lg mb-6">
                </div>
                <h1 class="text-5xl font-bold mb-4 leading-tight">Sistem Manajemen <br>Restoran Terintegrasi</h1>
                <p class="text-red-100 text-lg opacity-90 max-w-md">
                    Kelola pesanan, dapur, stok, dan laporan keuangan dalam satu platform yang efisien.
                </p>
                <div class="mt-8 flex gap-2">
                    <div class="h-1 w-12 bg-yellow-400 rounded-full"></div>
                    <div class="h-1 w-4 bg-white/30 rounded-full"></div>
                    <div class="h-1 w-4 bg-white/30 rounded-full"></div>
                </div>
            </div>
        </div>

        <!-- BAGIAN KANAN: FORM LOGIN -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center items-center p-8 bg-white relative">
            
            <!-- Hiasan Bulat (Background) -->
            <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-red-50 rounded-full opacity-50 blur-3xl pointer-events-none"></div>
            
            <div class="w-full max-w-md fade-in-up z-10">
                
                <!-- Header Form -->
                <div class="text-center mb-10">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Selamat Datang Kembali!</h2>
                    <p class="text-gray-500">Silakan masuk untuk memulai operasional.</p>
                </div>

                <!-- Session Status (Error Message) -->
                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-green-600 bg-green-100 p-3 rounded-lg">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Karyawan</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                                class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:border-red-500 focus:ring focus:ring-red-200 transition-all outline-none bg-gray-50 focus:bg-white text-gray-800 placeholder-gray-400"
                                placeholder="nama@ayam-yogya.com">
                        </div>
                        @error('email')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input id="password" type="password" name="password" required autocomplete="current-password"
                                class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:border-red-500 focus:ring focus:ring-red-200 transition-all outline-none bg-gray-50 focus:bg-white text-gray-800 placeholder-••••••••">
                        </div>
                        @error('password')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500" name="remember">
                            <span class="ms-2 text-sm text-gray-600">Ingat Saya</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-sm text-red-600 hover:text-red-800 font-medium hover:underline" href="{{ route('password.request') }}">
                                Lupa Password?
                            </a>
                        @endif
                    </div>

                    <!-- Tombol Login -->
                    <button type="submit" class="w-full bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-bold py-3.5 rounded-xl shadow-lg hover:shadow-red-500/30 transform hover:-translate-y-0.5 transition-all duration-200 flex justify-center items-center gap-2">
                        <span>MASUK SISTEM</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </form>

                <!-- Footer -->
                <p class="mt-8 text-center text-xs text-gray-400">
                    &copy; {{ date('Y') }} Warung lesehan yogyakarta Enterprise System. <br>All rights reserved.
                </p>
            </div>
        </div>
    </div>

</body>
</html>