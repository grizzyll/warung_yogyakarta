<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Perhatikan: ...$roles (Titik tiga) artinya menerima BANYAK argumen
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Cek Login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. DISINI BEDANYA:
        // Karena pakai '...$roles', variabel $roles otomatis sudah berbentuk Array.
        // Isinya: ['cashier', 'owner', 'admin']
        // Jadi kita TIDAK PERLU explode lagi.

        // 3. Cek Role User
        // Apakah role user saya (owner) ada di dalam daftar itu?
        if (in_array($request->user()->role, $roles)) {
            return $next($request);
        }

        // 4. Kalau gagal
        // Kita gabungkan array jadi string biar pesan errornya jelas
        $roleString = implode(', ', $roles);
        
        abort(403, 'AKSES DITOLAK. ROLE ANDA: ' . strtoupper(Auth::user()->role) . '. HALAMAN INI KHUSUS: ' . strtoupper($roleString));
    }
}