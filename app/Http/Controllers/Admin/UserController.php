<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    // 1. Tampilkan Daftar Pegawai
    public function index()
    {
        // Owner bisa lihat semua, Admin cuma bisa lihat bawahan (bukan sesama admin/owner)
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.users.index', compact('users'));
    }

    // 2. Simpan Pegawai Baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'role' => ['required', 'string', 'in:admin,cashier,kitchen'], // Owner ga bisa dibuat lewat sini
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Pegawai berhasil ditambahkan!');
    }

    // 3. Hapus Pegawai
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Proteksi: Jangan biarkan menghapus Owner atau Diri Sendiri
        if ($user->role === 'owner') {
            return back()->with('error', 'Akun Owner tidak boleh dihapus!');
        }

        $user->delete();
        return back()->with('success', 'Akun pegawai telah dihapus.');
    }
    // 4. Tampilkan Halaman Edit
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    // 5. Proses Update Data
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$id], // Ignore email sendiri
            'role' => ['required', 'string', 'in:admin,cashier,kitchen'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()], // Password boleh kosong kalau ga mau diganti
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        // Hanya update password kalau diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'Data pegawai berhasil diperbarui!');
    }
}