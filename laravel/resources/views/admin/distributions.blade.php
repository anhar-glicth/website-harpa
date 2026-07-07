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
                    <a href="{{ route('admin.distributions') }}" class="flex items-center space-x-3 py-3 px-4 rounded-xl bg-primary-50 text-primary-700">
                        <i class="fa-solid fa-truck-ramp-box"></i>
                        <span>Distribusi</span>
                    </a>
                    <a href="{{ route('admin.users') }}" class="flex items-center space-x-3 py-3 px-4 rounded-xl hover:bg-slate-50 hover:text-slate-800 transition-colors">
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
                    Catat & Kelola <span class="text-primary-600">Penyaluran Bantuan</span>
                </h1>
                <div class="w-12 h-1 bg-primary-500 rounded-full"></div>
            </div>

            <!-- Distribution Form -->
            <div class="bg-white border border-slate-100 rounded-3xl p-8 sm:p-10 space-y-6 shadow-sm">
                <h3 class="font-montserrat font-black uppercase text-base text-slate-900 leading-tight">Input Distribusi Baru</h3>
                <form action="{{ route('admin.distributions.store') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Program Kampanye</label>
                            <select name="program_id" required class="w-full bg-slate-50 border border-slate-100 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all">
                                @foreach($campaigns as $camp)
                                    <option value="{{ $camp->id }}">{{ $camp->title }} (Saldo: Rp {{ number_format($camp->current_amount, 0, ',', '.') }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Nama Penerima Manfaat</label>
                            <input type="text" name="recipient_name" required placeholder="Contoh: Warga Desa Pelosok / Nama Yayasan" class="w-full bg-slate-50 border border-slate-100 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Jumlah Distribusi (Rupiah)</label>
                            <input type="number" name="amount" required min="1000" class="w-full bg-slate-50 border border-slate-100 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Tanggal Penyaluran</label>
                            <input type="date" name="distribution_date" required class="w-full bg-slate-50 border border-slate-100 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Asnaf</label>
                            <input type="text" name="asnaf" placeholder="Contoh: Fakir / Miskin / Fi Sabilillah" class="w-full bg-slate-50 border border-slate-100 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Kota / Kabupaten</label>
                            <input type="text" name="city" placeholder="Contoh: Ende" class="w-full bg-slate-50 border border-slate-100 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Keterangan Penyaluran</label>
                        <textarea name="notes" rows="3" placeholder="Rincian barang/bantuan yang diserahkan..." class="w-full bg-slate-50 border border-slate-100 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all"></textarea>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-widest py-4.5 rounded-2xl shadow-lg shadow-primary-600/10 transition duration-300">
                            Simpan Laporan Penyaluran <i class="fa-solid fa-file-circle-plus ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Distribution records table -->
            <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 space-y-6 shadow-sm">
                <h3 class="font-montserrat font-black uppercase text-sm text-slate-800 tracking-wider">Daftar Distribusi Bantuan</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-[10px] font-black uppercase tracking-wider text-slate-400">
                                <th class="pb-4">Program</th>
                                <th class="pb-4">Penerima</th>
                                <th class="pb-4">Jumlah</th>
                                <th class="pb-4">Asnaf</th>
                                <th class="pb-4">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                            @forelse($distributions as $dis)
                                <tr class="border-b border-slate-50 last:border-0">
                                    <td class="py-4 pr-4 max-w-[200px] line-clamp-1 mt-3">{{ $dis->campaign->title }}</td>
                                    <td class="py-4">{{ $dis->recipient_name }}</td>
                                    <td class="py-4 text-primary-600">Rp {{ number_format($dis->amount, 0, ',', '.') }}</td>
                                    <td class="py-4">{{ $dis->asnaf ?? '-' }}</td>
                                    <td class="py-4">{{ $dis->distribution_date->format('d M Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-400 font-bold uppercase tracking-wider">Belum ada distribusi bantuan dicatat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div>
                    {{ $distributions->links() }}
                </div>
            </div>

        </div>

    </div>
</section>
@endsection
