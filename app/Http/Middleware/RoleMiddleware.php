<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
     public function handle(Request $request, Closure $next, $role): Response
    {
        // 1. Cek apakah user sudah login?
        if (!Auth::check()) {
            return redirect('/login');
        }

        // 2. Cek apakah jabatannya sesuai?
        // Kita bisa pasang banyak role dipisah koma (misal: admin,owner)
        $allowedRoles = explode(',', $role);

        if (in_array(Auth::user()->role, $allowedRoles)) {
            return $next($request); // Silakan masuk
        }

        // 3. Kalau jabatan gak sesuai, tolak!
        abort(403, 'ANDA TIDAK PUNYA AKSES KE HALAMAN INI.');
    }
}
