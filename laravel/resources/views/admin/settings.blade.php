@extends('layouts.app')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-20">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar Menu -->
        <div class="hidden lg:block lg:col-span-1 space-y-6">
            @include('admin.sidebar', ['active' => 'settings'])
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
            <div class="bg-white border border-slate-200 rounded-3xl p-8 sm:p-10 space-y-6 shadow-sm">
                <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <!-- Hero settings -->
                    <div class="space-y-4">
                        <h2 class="font-montserrat font-black uppercase text-xs text-slate-800 tracking-wider">Bagian Hero Utama</h2>
                        
                        <div>
                            <label for="hero_title" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Judul Hero (Title)</label>
                            <input id="hero_title" type="text" name="hero_title" required value="{{ $settings->hero_title ?? '' }}" class="w-full min-h-[44px] bg-slate-50 border border-slate-300 focus:border-primary-600 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-800 transition-colors">
                        </div>

                        <div>
                            <label for="hero_subtitle" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Sub-judul Hero (Subtitle)</label>
                            <textarea id="hero_subtitle" name="hero_subtitle" required rows="3" class="w-full bg-slate-50 border border-slate-300 focus:border-primary-600 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-800 transition-colors">{{ $settings->hero_subtitle ?? '' }}</textarea>
                        </div>

                        <div>
                            <label for="hero_image" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Gambar Latar Hero</label>
                            <input id="hero_image" type="file" name="hero_image" class="w-full min-h-[44px] bg-slate-50 border border-slate-300 focus:border-primary-600 rounded-2xl px-5 py-3 text-xs font-bold text-slate-800 transition-colors">
                            @if($settings && $settings->hero_image)
                                <a href="{{ asset($settings->hero_image) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center min-h-[44px] text-xs font-bold text-primary-700 hover:underline mt-1">Lihat Foto Latar Saat Ini</a>
                            @endif
                        </div>
                    </div>

                    <hr class="border-slate-200">

                    <!-- About mission settings -->
                    <div class="space-y-4">
                        <h2 class="font-montserrat font-black uppercase text-xs text-slate-800 tracking-wider">Bagian Visi &amp; Misi</h2>
                        
                        <div>
                            <label for="about_mission" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Deskripsi Visi/Misi (Mission)</label>
                            <textarea id="about_mission" name="about_mission" required rows="4" class="w-full bg-slate-50 border border-slate-300 focus:border-primary-600 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-800 transition-colors">{{ $settings->about_mission ?? '' }}</textarea>
                        </div>

                        <div>
                            <label for="about_image" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Gambar Visi/Misi</label>
                            <input id="about_image" type="file" name="about_image" class="w-full min-h-[44px] bg-slate-50 border border-slate-300 focus:border-primary-600 rounded-2xl px-5 py-3 text-xs font-bold text-slate-800 transition-colors">
                            @if($settings && $settings->about_image)
                                <a href="{{ asset($settings->about_image) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center min-h-[44px] text-xs font-bold text-primary-700 hover:underline mt-1">Lihat Foto Saat Ini</a>
                            @endif
                        </div>
                    </div>

                    <hr class="border-slate-200">

                    <!-- Impact settings -->
                    <div class="space-y-4">
                        <h2 class="font-montserrat font-black uppercase text-xs text-slate-800 tracking-wider">Bagian Dampak Nyata</h2>
                        
                        <div>
                            <label for="impact_title" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Judul Dampak (Impact Title)</label>
                            <input id="impact_title" type="text" name="impact_title" required value="{{ $settings->impact_title ?? '' }}" class="w-full min-h-[44px] bg-slate-50 border border-slate-300 focus:border-primary-600 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-800 transition-colors">
                        </div>

                        <div>
                            <label for="impact_image" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Gambar Dampak</label>
                            <input id="impact_image" type="file" name="impact_image" class="w-full min-h-[44px] bg-slate-50 border border-slate-300 focus:border-primary-600 rounded-2xl px-5 py-3 text-xs font-bold text-slate-800 transition-colors">
                            @if($settings && $settings->impact_image)
                                <a href="{{ asset($settings->impact_image) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center min-h-[44px] text-xs font-bold text-primary-700 hover:underline mt-1">Lihat Foto Dampak Saat Ini</a>
                            @endif
                        </div>
                    </div>

                    <hr class="border-slate-200">

                    <!-- Counter Statistics Settings -->
                    <div class="space-y-4">
                        <h2 class="font-montserrat font-black uppercase text-xs text-slate-800 tracking-wider">Bagian Counter Statistik</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="stats_beneficiaries" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Penerima Manfaat</label>
                                <input id="stats_beneficiaries" type="text" name="stats_beneficiaries" value="{{ $settings->stats_beneficiaries ?? '2.500 +' }}" class="w-full min-h-[44px] bg-slate-50 border border-slate-300 focus:border-primary-600 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-800 transition-colors">
                            </div>
                            <div>
                                <label for="stats_projects" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Project Kebaikan</label>
                                <input id="stats_projects" type="text" name="stats_projects" value="{{ $settings->stats_projects ?? '95 +' }}" class="w-full min-h-[44px] bg-slate-50 border border-slate-300 focus:border-primary-600 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-800 transition-colors">
                            </div>
                            <div>
                                <label for="stats_volunteers" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Perwakilan Relawan</label>
                                <input id="stats_volunteers" type="text" name="stats_volunteers" value="{{ $settings->stats_volunteers ?? '20 +' }}" class="w-full min-h-[44px] bg-slate-50 border border-slate-300 focus:border-primary-600 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-800 transition-colors">
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-200">
                        <button type="submit" class="w-full min-h-[48px] bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-widest py-4 rounded-2xl shadow-lg shadow-primary-600/10 transition duration-300">
                            Perbarui Pengaturan <i class="fa-solid fa-cloud-arrow-up ml-2" aria-hidden="true"></i>
                        </button>
                    </div>
                </form>
            </div>

        </div>

    </div>
</section>
@endsection
