@extends('layouts.app')

@section('title', 'Manajemen SDM')

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
    
    <!-- LEFT: FORM TAMBAH (DESAIN BARU) -->
    <div class="bg-white rounded-2xl shadow-card border border-gray-100 overflow-hidden h-fit relative">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-primary to-orange-500"></div>
        
        <div class="p-6">
            <h3 class="font-bold text-gray-800 text-lg mb-1 flex items-center gap-2">
                <div class="bg-red-100 p-2 rounded-lg text-primary"><i class="fas fa-user-plus"></i></div>
                Input Pegawai Baru
            </h3>
            <p class="text-xs text-gray-400 mb-6">Pastikan email aktif untuk login.</p>
            
            <form action="{{ route('users.store') }}" method="POST" class="space-y-5">
                @csrf
                
                <!-- Nama -->
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 block">Nama Lengkap</label>
                    <div class="relative">
                        <i class="fas fa-id-card absolute left-3 top-3 text-gray-400"></i>
                        <input type="text" name="name" required placeholder="Contoh: Budi Santoso"
                               class="w-full pl-10 border-gray-200 rounded-xl focus:ring-primary focus:border-primary text-sm py-2.5 transition bg-gray-50 focus:bg-white">
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 block">Email Login</label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-3 top-3 text-gray-400"></i>
                        <input type="email" name="email" required placeholder="email@ayam.com"
                               class="w-full pl-10 border-gray-200 rounded-xl focus:ring-primary focus:border-primary text-sm py-2.5 transition bg-gray-50 focus:bg-white">
                    </div>
                </div>

                <!-- Role -->
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 block">Jabatan</label>
                    <div class="relative">
                        <i class="fas fa-briefcase absolute left-3 top-3 text-gray-400"></i>
                        <select name="role" class="w-full pl-10 border-gray-200 rounded-xl focus:ring-primary focus:border-primary text-sm py-2.5 transition bg-gray-50 focus:bg-white appearance-none">
                            <option value="cashier">Kasir (POS)</option>
                            <option value="kitchen">Dapur (KDS)</option>
                            <option value="admin">Admin Finance</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-3 top-3.5 text-xs text-gray-400 pointer-events-none"></i>
                    </div>
                </div>

                <!-- Password Group -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1 block">Password</label>
                        <input type="password" name="password" required placeholder="******"
                               class="w-full border-gray-200 rounded-xl focus:ring-primary focus:border-primary text-sm py-2.5 bg-gray-50">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1 block">Konfirmasi</label>
                        <input type="password" name="password_confirmation" required placeholder="******"
                               class="w-full border-gray-200 rounded-xl focus:ring-primary focus:border-primary text-sm py-2.5 bg-gray-50">
                    </div>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-primary to-red-700 hover:from-red-700 hover:to-primary text-white font-bold py-3 rounded-xl transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                    <i class="fas fa-save"></i> Simpan Data
                </button>
            </form>
        </div>
    </div>

    <!-- RIGHT: TABEL DATA -->
    <div class="xl:col-span-2 bg-white rounded-2xl shadow-card border border-gray-100 overflow-hidden flex flex-col">
        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <div>
                <h3 class="font-bold text-gray-800 text-lg">Database Pegawai</h3>
                <p class="text-xs text-gray-500">Kelola akses akun karyawan cabang.</p>
            </div>
            <span class="bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1 rounded-full">{{ count($users) }} Akun</span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-white border-b border-gray-100 text-gray-400 text-[10px] uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-bold">Profil Pegawai</th>
                        <th class="px-6 py-4 font-bold">Jabatan</th>
                        <th class="px-6 py-4 font-bold">Status</th>
                        <th class="px-6 py-4 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center text-gray-600 font-bold shadow-inner">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($user->role == 'owner')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-purple-100 text-purple-700 border border-purple-200">
                                    <i class="fas fa-crown text-[10px]"></i> Owner
                                </span>
                            @elseif($user->role == 'admin')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-100 text-blue-700 border border-blue-200">
                                    <i class="fas fa-briefcase text-[10px]"></i> Admin
                                </span>
                            @elseif($user->role == 'cashier')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                    <i class="fas fa-cash-register text-[10px]"></i> Kasir
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-orange-100 text-orange-700 border border-orange-200">
                                    <i class="fas fa-fire-burner text-[10px]"></i> Dapur
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-bold text-green-600 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Aktif
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($user->role !== 'owner')
                            <!-- DIV INI SUDAH DIPERBAIKI (Selalu Muncul) -->
                            <div class="flex items-center justify-end gap-2">
                                <!-- TOMBOL EDIT -->
                                <a href="{{ route('users.edit', $user->id) }}" class="p-2 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-100 hover:text-yellow-700 transition shadow-sm border border-yellow-100" title="Edit">
                                    <i class="fas fa-pen"></i>
                                </a>

                                <!-- TOMBOL DELETE -->
                                @if($user->id !== Auth::id())
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus permanen?');">
                                    @csrf @method('DELETE')
                                    <button class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 hover:text-red-700 transition shadow-sm border border-red-100" title="Hapus">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection