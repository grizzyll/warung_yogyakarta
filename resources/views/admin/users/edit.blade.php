@extends('layouts.app')

@section('title', 'Edit Pegawai')

@section('content')
<div class="max-w-2xl mx-auto">
    
    <div class="bg-white rounded-2xl shadow-card border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="font-bold text-gray-800 text-lg">Edit Data: {{ $user->name }}</h3>
            <a href="{{ route('users.index') }}" class="text-sm text-gray-500 hover:text-primary font-bold">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>

        <form action="{{ route('users.update', $user->id) }}" method="POST" class="p-8 space-y-6">
            @csrf
            @method('PUT')

            <!-- Nama -->
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Lengkap</label>
                <input type="text" name="name" value="{{ $user->name }}" required
                       class="w-full border-gray-200 rounded-xl focus:ring-primary focus:border-primary py-3 px-4 bg-gray-50 focus:bg-white transition">
            </div>

            <!-- Email -->
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Email Login</label>
                <input type="email" name="email" value="{{ $user->email }}" required
                       class="w-full border-gray-200 rounded-xl focus:ring-primary focus:border-primary py-3 px-4 bg-gray-50 focus:bg-white transition">
            </div>

            <!-- Role -->
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Jabatan</label>
                <select name="role" class="w-full border-gray-200 rounded-xl focus:ring-primary focus:border-primary py-3 px-4 bg-gray-50 focus:bg-white transition">
                    <option value="cashier" {{ $user->role == 'cashier' ? 'selected' : '' }}>Kasir (POS)</option>
                    <option value="kitchen" {{ $user->role == 'kitchen' ? 'selected' : '' }}>Dapur (KDS)</option>
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin Finance</option>
                </select>
            </div>

            <div class="border-t border-gray-100 pt-6 mt-2">
                <h4 class="font-bold text-gray-800 mb-4 text-sm">Ganti Password (Opsional)</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Password Baru</label>
                        <input type="password" name="password" placeholder="Kosongkan jika tetap"
                               class="w-full border-gray-200 rounded-xl focus:ring-primary focus:border-primary text-sm py-2.5">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Konfirmasi</label>
                        <input type="password" name="password_confirmation" placeholder="Ulangi password"
                               class="w-full border-gray-200 rounded-xl focus:ring-primary focus:border-primary text-sm py-2.5">
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="bg-gradient-to-r from-primary to-red-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</div>
@endsection