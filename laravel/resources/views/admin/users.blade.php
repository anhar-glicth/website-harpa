@extends('layouts.app')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-20">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar Menu -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white border border-slate-100 p-8 rounded-3xl space-y-6 shadow-sm">
                <h3 class="font-montserrat font-black uppercase text-xs sm:text-sm text-slate-800 tracking-wider">Navigasi Admin</h3>
                <hr class="border-slate-100">
                <div class="flex flex-col space-y-2 font-bold text-[10px] uppercase tracking-wider text-slate-500">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 py-3 px-4 rounded-xl hover:bg-slate-50 hover:text-slate-800 transition-colors">
                        <i class="fa-solid fa-chart-pie"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('admin.campaigns') }}" class="flex items-center space-x-3 py-3 px-4 rounded-xl hover:bg-slate-50 hover:text-slate-800 transition-colors">
                        <i class="fa-solid fa-gift"></i>
                        <span>Kelola Kampanye</span>
                    </a>
                    <a href="{{ route('admin.donations') }}" class="flex items-center space-x-3 py-3 px-4 rounded-xl hover:bg-slate-50 hover:text-slate-800 transition-colors">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                        <span>Verifikasi Donasi</span>
                    </a>
                    <a href="{{ route('admin.distributions') }}" class="flex items-center space-x-3 py-3 px-4 rounded-xl hover:bg-slate-50 hover:text-slate-800 transition-colors">
                        <i class="fa-solid fa-truck-ramp-box"></i>
                        <span>Distribusi</span>
                    </a>
                    <a href="{{ route('admin.users') }}" class="flex items-center space-x-3 py-3 px-4 rounded-xl bg-primary-50 text-primary-700">
                        <i class="fa-solid fa-users"></i>
                        <span>Kelola Pengguna</span>
                    </a>
                    <a href="{{ route('admin.settings') }}" class="flex items-center space-x-3 py-3 px-4 rounded-xl hover:bg-slate-50 hover:text-slate-800 transition-colors">
                        <i class="fa-solid fa-gears"></i>
                        <span>Pengaturan Web</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Dashboard Content -->
        <div class="lg:col-span-3 space-y-8">
            <div class="space-y-2">
                <h1 class="text-2xl sm:text-3xl font-montserrat font-black text-slate-900 uppercase tracking-tight italic">
                    Kelola <span class="text-primary-600">Pengguna & Hak Akses</span>
                </h1>
                <div class="w-12 h-1 bg-primary-500 rounded-full"></div>
            </div>

            <!-- Users table -->
            <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 space-y-6 shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-[10px] font-black uppercase tracking-wider text-slate-400">
                                <th class="pb-4">Nama</th>
                                <th class="pb-4">Email</th>
                                <th class="pb-4">Peran / Hak Akses</th>
                                <th class="pb-4">No. Telp</th>
                                <th class="pb-4">Terdaftar</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                            @forelse($users as $user)
                                <tr class="border-b border-slate-50 last:border-0">
                                    <td class="py-4">{{ $user->name }}</td>
                                    <td class="py-4">{{ $user->email }}</td>
                                    <td class="py-4">
                                        @if($user->role === 'admin')
                                            <span class="bg-red-50 text-red-700 text-[8px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md">ADMIN</span>
                                        @elseif($user->role === 'field_worker')
                                            <span class="bg-indigo-50 text-indigo-700 text-[8px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md">PETUGAS LAPANGAN</span>
                                        @else
                                            <span class="bg-slate-100 text-slate-700 text-[8px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md">DONATUR</span>
                                        @endif
                                    </td>
                                    <td class="py-4">{{ $user->phone ?? '-' }}</td>
                                    <td class="py-4">{{ $user->created_at->format('d M Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-400 font-bold uppercase tracking-wider">Belum ada pengguna terdaftar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div>
                    {{ $users->links() }}
                </div>
            </div>

        </div>

    </div>
</section>
@endsection
