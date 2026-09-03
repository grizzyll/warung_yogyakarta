<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
   public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        // Ambil Role User dari Database
        $role = $request->user()->role;

        // Logika Pengarahan
        // ... (kode atas sama) ...

        // LOGIKA REDIRECT FIX
        if ($role === 'cashier') {
            return redirect()->route('pos.index');
        } 
        elseif ($role === 'kitchen') {
            return redirect()->route('kitchen.index');
        } 
        elseif ($role === 'owner') {
            // Owner ke Dashboard
            return redirect()->route('dashboard');
        } 
        elseif ($role === 'admin') {
            // Admin JANGAN ke Dashboard, tapi ke Menu Belanja (Tugas Utama)
            return redirect()->route('restock.create');
        }

        return redirect('/');
    }
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
