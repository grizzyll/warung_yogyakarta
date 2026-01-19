<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL; // <--- INI WAJIB ADA BIAR TIDAK ERROR
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Paksa HTTPS agar CSS terbaca di Ngrok (HP)
        // Hapus baris ini nanti kalau balik ke Localhost biasa tanpa Ngrok
        //URL::forceScheme('https');
    }
}