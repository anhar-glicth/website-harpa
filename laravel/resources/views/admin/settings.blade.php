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
                    <a href="{{ route('admin.users') }}" class="flex items-center space-x-3 py-3 px-4 rounded-xl hover:bg-slate-50 hover:text-slate-800 transition-colors">
                        <i class="fa-solid fa-users"></i>
                        <span>Kelola Pengguna</span>
                    </a>
                    <a href="{{ route('admin.settings') }}" class="flex items-center space-x-3 py-3 px-4 rounded-xl bg-primary-50 text-primary-700">
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
                    Pengaturan <span class="text-primary-600">Tampilan Landing Page</span>
                </h1>
                <div class="w-12 h-1 bg-primary-500 rounded-full"></div>
            </div>

            <!-- Settings Form Card -->
            <div class="bg-white border border-slate-100 rounded-3xl p-8 sm:p-10 space-y-6 shadow-sm">
                <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <!-- Hero settings -->
                    <div class="space-y-4">
                        <h4 class="font-montserrat font-black uppercase text-xs text-slate-800 tracking-wider">Bagian Hero Utama</h4>
                        
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Judul Hero (Title)</label>
                            <input type="text" name="hero_title" required value="{{ $settings->hero_title ?? '' }}" class="w-full bg-slate-50 border border-slate-100 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all">
                        </div>

                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Sub-judul Hero (Subtitle)</label>
                            <textarea name="hero_subtitle" required rows="3" class="w-full bg-slate-50 border border-slate-100 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all">{{ $settings->hero_subtitle ?? '' }}</textarea>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Gambar Latar Hero</label>
                            <input type="file" name="hero_image" class="w-full bg-slate-50 border border-slate-100 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all">
                            @if($settings && $settings->hero_image)
                                <a href="{{ $settings->hero_image }}" target="_blank" class="text-[9px] font-bold text-primary-600 block mt-1.5">Lihat Foto Latar Saat Ini</a>
                            @endif
                        </div>
                    </div>

                    <hr class="border-slate-100">

                    <!-- About mission settings -->
                    <div class="space-y-4">
                        <h4 class="font-montserrat font-black uppercase text-xs text-slate-800 tracking-wider">Bagian Visi & Misi</h4>
                        
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Deskripsi Visi/Misi (Mission)</label>
                            <textarea name="about_mission" required rows="4" class="w-full bg-slate-50 border border-slate-100 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all">{{ $settings->about_mission ?? '' }}</textarea>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Gambar Visi/Misi</label>
                            <input type="file" name="about_image" class="w-full bg-slate-50 border border-slate-100 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all">
                            @if($settings && $settings->about_image)
                                <a href="{{ $settings->about_image }}" target="_blank" class="text-[9px] font-bold text-primary-600 block mt-1.5">Lihat Foto Saat Ini</a>
                            @endif
                        </div>
                    </div>

                    <hr class="border-slate-100">

                    <!-- Impact settings -->
                    <div class="space-y-4">
                        <h4 class="font-montserrat font-black uppercase text-xs text-slate-800 tracking-wider">Bagian Dampak Nyata</h4>
                        
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Judul Dampak (Impact Title)</label>
                            <input type="text" name="impact_title" required value="{{ $settings->impact_title ?? '' }}" class="w-full bg-slate-50 border border-slate-100 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all">
                        </div>

                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Gambar Dampak</label>
                            <input type="file" name="impact_image" class="w-full bg-slate-50 border border-slate-100 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all">
                            @if($settings && $settings->impact_image)
                                <a href="{{ $settings->impact_image }}" target="_blank" class="text-[9px] font-bold text-primary-600 block mt-1.5">Lihat Foto Dampak Saat Ini</a>
                            @endif
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-150">
                        <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-widest py-4.5 rounded-2xl shadow-lg shadow-primary-600/10 transition duration-300">
                            Perbarui Pengaturan <i class="fa-solid fa-cloud-arrow-up ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>

        </div>

    </div>
</section>
@endsection
