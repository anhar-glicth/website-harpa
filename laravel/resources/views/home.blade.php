@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="relative min-h-[75vh] flex items-center justify-center text-center text-white overflow-hidden py-32 px-6">
    <!-- Background Image -->
    <div class="absolute inset-0 z-0">
        <img 
            src="{{ !empty($settings->hero_image) ? (str_starts_with($settings->hero_image, 'http') ? $settings->hero_image : asset($settings->hero_image)) : 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=2070&auto=format&fit=crop' }}" 
            class="w-full h-full object-cover scale-105"
            alt="Hero Charity"
        />
        <div class="absolute inset-0 bg-black/60 backdrop-blur-[2px]"></div>
    </div>

    <!-- Hero Content -->
    <div class="relative z-10 w-full max-w-4xl mx-auto space-y-8">
        
        <h1 class="text-3xl sm:text-5xl md:text-7xl font-montserrat font-black leading-tight tracking-tight uppercase break-words px-4">
            {{ $settings->hero_title ?? 'Uluran Tangan Anda, Harapan Mereka' }}
        </h1>
        
        <p class="text-xs sm:text-sm md:text-lg text-white/95 max-w-2xl mx-auto font-bold leading-relaxed break-words px-4">
            {{ $settings->hero_subtitle ?? 'Kami menghubungkan kebaikan hati Anda dengan mereka yang benar-benar membutuhkan bantuan di seluruh pelosok negeri.' }}
        </p>

        <div class="pt-4 flex flex-col sm:flex-row justify-center items-center gap-4">
            <a href="#campaigns" class="w-full sm:w-auto bg-[#f7ad1a] hover:bg-[#df9408] text-[#063f5c] font-black text-xs uppercase tracking-widest px-10 py-5 rounded-2xl shadow-xl shadow-[#f7ad1a]/30 hover:shadow-[#f7ad1a]/40 transition-all duration-300 transform hover:-translate-y-1">
                Mulai Berdonasi <i class="fa-solid fa-arrow-down ml-2"></i>
            </a>
            <a href="{{ route('volunteer') }}" class="w-full sm:w-auto bg-white/10 backdrop-blur-md border border-white/20 hover:bg-white/20 text-white font-black text-xs uppercase tracking-widest px-10 py-5 rounded-2xl hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                Gabung Relawan <i class="fa-solid fa-users ml-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Floating Stats Section -->
<section class="max-w-7xl mx-auto px-3 sm:px-4 -mt-14 sm:-mt-24 relative z-20">
    <div class="grid grid-cols-3 gap-2 sm:gap-6">
        
        <!-- Penerima Manfaat -->
        <div class="bg-white p-3 sm:p-8 rounded-2xl sm:rounded-3xl shadow-xl flex flex-col sm:flex-row items-center text-center sm:text-left sm:space-x-6 border border-slate-100/50 hover:shadow-2xl hover:-translate-y-1 transition duration-300">
            <div class="bg-emerald-50 w-10 h-10 sm:w-16 sm:h-16 rounded-xl sm:rounded-2xl flex items-center justify-center text-emerald-600 text-lg sm:text-2xl shrink-0 mb-2 sm:mb-0">
                <i class="fa-solid fa-users"></i>
            </div>
            <div>
                <h4 class="text-sm sm:text-2xl font-montserrat font-black tracking-tight text-slate-900 leading-none">
                    {{ $settings->stats_beneficiaries ?? '2.500 +' }}
                </h4>
                <p class="text-slate-500 font-bold text-[9px] sm:text-[10px] uppercase tracking-wider sm:tracking-widest mt-1 sm:mt-1.5 leading-tight">Penerima Manfaat</p>
            </div>
        </div>

        <!-- Project Kebaikan -->
        <div class="bg-white p-3 sm:p-8 rounded-2xl sm:rounded-3xl shadow-xl flex flex-col sm:flex-row items-center text-center sm:text-left sm:space-x-6 border border-slate-100/50 hover:shadow-2xl hover:-translate-y-1 transition duration-300">
            <div class="bg-primary-50 w-10 h-10 sm:w-16 sm:h-16 rounded-xl sm:rounded-2xl flex items-center justify-center text-primary-600 text-lg sm:text-2xl shrink-0 mb-2 sm:mb-0">
                <i class="fa-solid fa-gift"></i>
            </div>
            <div>
                <h4 class="text-sm sm:text-2xl font-montserrat font-black tracking-tight text-slate-900 leading-none">
                    {{ $settings->stats_projects ?? '95 +' }}
                </h4>
                <p class="text-slate-500 font-bold text-[9px] sm:text-[10px] uppercase tracking-wider sm:tracking-widest mt-1 sm:mt-1.5 leading-tight">Project Kebaikan</p>
            </div>
        </div>

        <!-- Perwakilan Relawan -->
        <div class="bg-white p-3 sm:p-8 rounded-2xl sm:rounded-3xl shadow-xl flex flex-col sm:flex-row items-center text-center sm:text-left sm:space-x-6 border border-slate-100/50 hover:shadow-2xl hover:-translate-y-1 transition duration-300">
            <div class="bg-amber-50 w-10 h-10 sm:w-16 sm:h-16 rounded-xl sm:rounded-2xl flex items-center justify-center text-amber-600 text-lg sm:text-2xl shrink-0 mb-2 sm:mb-0">
                <i class="fa-solid fa-hands-holding-child"></i>
            </div>
            <div>
                <h4 class="text-sm sm:text-2xl font-montserrat font-black tracking-tight text-slate-900 leading-none">
                    {{ $settings->stats_volunteers ?? '20 +' }}
                </h4>
                <p class="text-slate-500 font-bold text-[9px] sm:text-[10px] uppercase tracking-wider sm:tracking-widest mt-1 sm:mt-1.5 leading-tight">Perwakilan Relawan</p>
            </div>
        </div>

    </div>
</section>

<!-- Campaigns Section -->
<section id="campaigns" class="max-w-7xl mx-auto px-3 sm:px-4 py-20 sm:py-28">
    <div class="text-center max-w-2xl mx-auto mb-16 space-y-2">
        <h2 class="text-2xl sm:text-4xl font-montserrat font-black text-slate-900 uppercase tracking-tight italic">
            Daftar <span class="text-primary-600">Kampanye Aktif</span>
        </h2>
        <div class="w-12 h-1.5 bg-[#f7ad1a] mx-auto rounded-full"></div>
        <p class="text-slate-400 font-bold text-[10px] sm:text-xs tracking-widest uppercase mt-3">Salurkan kebaikan Anda ke program pilihan berikut</p>
    </div>

    <!-- Campaigns Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-8">
        @foreach($campaigns as $c)
            @php
                $percent = $c->target_amount > 0 ? min(100, ($c->current_amount / $c->target_amount) * 100) : 0;
            @endphp
            <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-100 hover:border-slate-200/80 hover:shadow-2xl shadow-sm transition-all duration-300 flex flex-col group overflow-hidden">
                <!-- Cover Image -->
                <a href="{{ route('campaigns.show', $c->id) }}" class="relative h-32 sm:h-56 overflow-hidden block">
                    <img 
                        src="{{ $c->foto ?? 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=800&fit=crop' }}" 
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                        alt="{{ $c->title }}"
                    >
                    <span class="absolute top-2 left-2 sm:top-4 sm:left-4 bg-slate-900/80 backdrop-blur-md text-white text-[8px] sm:text-[9px] font-black uppercase tracking-widest px-2 py-1 sm:px-3 sm:py-1.5 rounded-md sm:rounded-lg border border-white/10">
                        {{ $c->category }}
                    </span>
                </a>

                <!-- Card Body -->
                <div class="p-3.5 sm:p-8 flex-grow flex flex-col justify-between space-y-3 sm:space-y-6">
                    <div class="space-y-1.5 sm:space-y-3">
                        <h3 class="font-montserrat font-black uppercase text-xs sm:text-base text-slate-800 leading-snug sm:leading-tight group-hover:text-primary-600 transition-colors duration-200 line-clamp-2">
                            <a href="{{ route('campaigns.show', $c->id) }}" class="hover:underline">
                                {{ $c->title }}
                            </a>
                        </h3>
                        <p class="hidden sm:block text-xs text-slate-500 font-medium line-clamp-3">
                            {{ $c->description }}
                        </p>
                    </div>

                    <div class="space-y-2.5 sm:space-y-4">
                        <!-- Progress Bar -->
                        <div class="space-y-1 sm:space-y-1.5">
                            <div class="w-full bg-slate-100 h-1.5 sm:h-2.5 rounded-full overflow-hidden">
                                <div class="bg-gradient-to-r from-[#063f5c] to-[#f7ad1a] h-full rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center text-[8px] sm:text-[10px] font-bold text-slate-500 uppercase tracking-wider gap-0.5">
                                <span>{{ number_format($percent, 1) }}% Terkumpul</span>
                                <span class="truncate">Target: Rp {{ number_format($c->target_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pt-1 sm:pt-2 gap-2">
                            <div>
                                <span class="text-[8px] sm:text-[9px] font-bold text-slate-400 uppercase block tracking-wider leading-none">Terkumpul</span>
                                <span class="text-xs sm:text-base font-montserrat font-black text-slate-800 leading-tight block mt-0.5 sm:mt-0">
                                    Rp {{ number_format($c->current_amount, 0, ',', '.') }}
                                </span>
                            </div>
                            <a href="{{ route('campaigns.show', $c->id) }}" class="w-full sm:w-auto text-center bg-primary-50 hover:bg-primary-600 hover:text-white text-primary-700 text-[10px] sm:text-xs font-black uppercase tracking-wider px-3 py-2 sm:px-5 sm:py-3 rounded-lg sm:rounded-xl transition-all duration-300 active:scale-95">
                                Detail <i class="fa-solid fa-arrow-right ml-0.5 sm:ml-1 text-[9px] sm:text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>

<!-- ===== MAP OF KINDNESS: DISTRIBUTIONS ===== -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<section class="py-24 sm:py-32 bg-white relative overflow-hidden border-t border-slate-100">
    <!-- Background Accents -->
    <div class="absolute top-0 right-0 w-1/3 h-full bg-slate-50/50 -skew-x-12 transform origin-top translate-x-20"></div>

    <div class="max-w-7xl mx-auto px-6 relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-16">
            
            <!-- Left Side: Description -->
            <div class="w-full lg:w-1/3 space-y-8">
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <span class="w-12 h-[2px] bg-primary-600"></span>
                        <span class="text-primary-600 font-black uppercase text-[10px] tracking-[0.4em]">Jejak Kebaikan</span>
                    </div>
                    <h2 class="text-3xl sm:text-5xl font-montserrat font-black text-slate-900 leading-tight tracking-tight uppercase italic">
                        Peta <br/>
                        <span class="text-primary-600">Penyaluran</span>
                    </h2>
                    <p class="text-slate-500 text-xs sm:text-sm leading-relaxed font-semibold">
                        Setiap donasi Anda adalah amanah. Lihat bagaimana bantuan tersalurkan secara langsung ke berbagai titik lokasi di seluruh penjuru negeri yang dilaporkan langsung oleh petugas lapangan kami.
                    </p>
                </div>

                <!-- Mini Stats/Info Cards -->
                <div class="space-y-4">
                    <div class="flex items-center gap-4 p-5 bg-slate-50 border border-slate-100 rounded-3xl transition duration-300 hover:shadow-lg hover:bg-white group">
                        <div class="w-12 h-12 rounded-xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-xl group-hover:bg-primary-600 group-hover:text-white transition-colors duration-350">
                            📍
                        </div>
                        <div>
                            <h4 class="font-montserrat font-black text-slate-800 text-[11px] uppercase tracking-wide">Titik Presisi GPS</h4>
                            <p class="text-[10px] text-slate-400 font-semibold mt-0.5">Lokasi akurat sesuai koordinat petugas.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4 p-5 bg-slate-50 border border-slate-100 rounded-3xl transition duration-300 hover:shadow-lg hover:bg-white group">
                        <div class="w-12 h-12 rounded-xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-xl group-hover:bg-primary-600 group-hover:text-white transition-colors duration-350">
                            📸
                        </div>
                        <div>
                            <h4 class="font-montserrat font-black text-slate-800 text-[11px] uppercase tracking-wide">Dokumentasi Nyata</h4>
                            <p class="text-[10px] text-slate-400 font-semibold mt-0.5">Bukti foto penyaluran di tiap titik.</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <span class="relative flex h-3.5 w-3.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-emerald-500"></span>
                    </span>
                    <span class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">Pembaruan Lapangan Aktif</span>
                </div>
            </div>

            <!-- Right Side: Map Card -->
            <div class="w-full lg:w-2/3">
                <div class="relative">
                    <!-- Blur backgrounds -->
                    <div class="absolute -top-6 -right-6 w-32 h-32 bg-primary-100 rounded-full blur-3xl opacity-40"></div>
                    <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-emerald-100 rounded-full blur-3xl opacity-40"></div>

                    <!-- Map wrapper container -->
                    <div class="relative bg-white p-3 rounded-[2.5rem] sm:rounded-[3rem] shadow-[0_32px_64px_-16px_rgba(0,0,0,0.08)] border border-slate-100 overflow-hidden">
                        <div id="map" class="w-full h-[400px] sm:h-[500px] rounded-[2rem] sm:rounded-[2.5rem] z-10 border border-slate-50"></div>
                        
                        <!-- Floating Map Tag -->
                        <div class="absolute top-6 right-6 z-20 pointer-events-none bg-slate-900/90 backdrop-blur-md text-white py-2 px-4 rounded-full text-[8px] font-black tracking-widest uppercase flex items-center gap-1.5 shadow-md">
                            <i class="fa-solid fa-map-pin text-primary-500"></i>
                            Klik Marker Untuk Rincian
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Script to populate map markers -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof L === 'undefined') return;

        // Set center view of map to Indonesia coordinates
        const map = L.map('map', { scrollWheelZoom: false }).setView([-2.5489, 118.0149], 5);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href=&quot;https://www.openstreetmap.org/copyright&quot;>OpenStreetMap</a> contributors'
        }).addTo(map);

        // Add distribution markers dynamically from db records passed by controller
        @foreach($distributions as $d)
            @if($d->latitude && $d->longitude)
                (function() {
                    const marker = L.marker([{{ $d->latitude }}, {{ $d->longitude }}]).addTo(map);
                    
                    const popupContent = `
                        <div class="w-56 p-1 font-sans text-slate-800">
                            ${ '{{ $d->photo_url }}' ? `<img src="{{ $d->photo_url }}" class="w-full h-24 object-cover rounded-lg mb-2 shadow-sm" />` : '' }
                            <h4 class="font-bold text-slate-900 text-xs mb-1">{{ $d->title }}</h4>
                            <p class="text-[10px] text-slate-550 leading-relaxed mb-2">{{ $d->description }}</p>
                            <div class="flex justify-between items-center text-[8px] font-bold text-slate-400 uppercase pt-1.5 border-t border-slate-100">
                                <span>Petugas: {{ $d->worker->name }}</span>
                                <span>{{ $d->distribution_date->format('d M Y') }}</span>
                            </div>
                        </div>
                    `;
                    marker.bindPopup(popupContent);
                })();
            @endif
        @endforeach
    });
</script>

<!-- About & Impact Mission Section -->
<section class="bg-[#063f5c] text-white py-20 sm:py-28 relative overflow-hidden">
    <div class="absolute inset-0 opacity-20">
        <img 
            src="{{ !empty($settings->about_image) ? (str_starts_with($settings->about_image, 'http') ? $settings->about_image : asset($settings->about_image)) : 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=1200&h=600&fit=crop' }}" 
            class="w-full h-full object-cover" 
            alt="Impact image"
        >
    </div>
    <div class="relative z-10 max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
        <div class="space-y-6">
            <span class="text-[10px] font-black uppercase tracking-[0.22em] text-[#f7ad1a]">VISI & MISI KITA</span>
            <h2 class="text-2xl sm:text-4xl font-montserrat font-black uppercase tracking-tight italic">
                Penyaluran yang <span class="text-[#f7ad1a]">Transparan</span> dan Bertanggung Jawab
            </h2>
            <div class="w-12 h-1.5 bg-[#f7ad1a] rounded-full"></div>
            <p class="text-xs sm:text-sm text-slate-300 leading-relaxed font-semibold">
                {{ $settings->about_mission ?? 'Kami memastikan setiap rupiah yang Anda donasikan akan tersalurkan langsung kepada penerima manfaat yang berhak dengan dokumentasi dan pelaporan yang terperinci di website kami.' }}
            </p>
            <div class="pt-4">
                <a href="{{ route('about') }}" class="bg-[#f7ad1a] hover:bg-[#df9408] text-[#063f5c] text-xs font-black uppercase tracking-widest px-8 py-4 rounded-xl transition duration-300 shadow-lg shadow-black/20">
                    Pelajari Selengkapnya
                </a>
            </div>
        </div>
        <div class="bg-white/5 border border-white/10 backdrop-blur-md p-8 rounded-3xl space-y-6">
            <h3 class="text-lg font-montserrat font-black uppercase tracking-wide">Dampak yang Diciptakan</h3>
            <div class="space-y-4">
                <div class="flex items-start space-x-4">
                    <div class="bg-white/10 text-[#f7ad1a] w-10 h-10 rounded-xl flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-book-quran"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-black uppercase tracking-wider">Mushaf Al-Quran Pelosok</h4>
                        <p class="text-[11px] text-slate-300 leading-normal">Membantu ribuan santri di pesantren pelosok NTT mendapatkan Al-Quran yang layak.</p>
                    </div>
                </div>
                <div class="flex items-start space-x-4">
                    <div class="bg-white/10 text-[#f7ad1a] w-10 h-10 rounded-xl flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-black uppercase tracking-wider">Pendidikan Anak Dhuafa</h4>
                        <p class="text-[11px] text-slate-300 leading-normal">Bantuan perlengkapan dan sarana sekolah di wilayah bencana.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Prayer Board / Doa Bersama Section -->
<section class="max-w-7xl mx-auto px-4 py-20 sm:py-28">
    <div class="text-center max-w-2xl mx-auto mb-16 space-y-2">
        <h2 class="text-2xl sm:text-4xl font-montserrat font-black text-slate-900 uppercase tracking-tight italic">
            Papan <span class="text-primary-600">Doa Donatur</span>
        </h2>
        <div class="w-12 h-1.5 bg-[#f7ad1a] mx-auto rounded-full"></div>
        <p class="text-slate-400 font-bold text-[10px] sm:text-xs tracking-widest uppercase mt-3">Mari saling mendoakan dan mengaminkan kebaikan bersama</p>
    </div>

    <!-- Prayer Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($prayers as $p)
            <div class="bg-white border border-slate-100 p-6 sm:p-8 rounded-3xl shadow-sm hover:shadow-xl hover:border-slate-200/60 transition duration-300 flex flex-col justify-between min-h-[160px]">
                <div class="space-y-3">
                    <p class="text-xs text-slate-600 font-semibold italic">
                        "{{ $p->message }}"
                    </p>
                </div>
                <div class="flex items-center justify-between pt-4 border-t border-slate-50 mt-4">
                    <div>
                        <span class="text-[10px] font-bold text-slate-800 uppercase block tracking-wider">
                            {{ $p->is_anonymous ? 'Donatur Anonim' : $p->name }}
                        </span>
                        <span class="text-[8px] text-slate-400 font-semibold uppercase tracking-wider">{{ $p->created_at->diffForHumans() }}</span>
                    </div>
                    <form action="{{ route('prayers.upvote', $p->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-primary-50 hover:bg-primary-600 text-primary-700 hover:text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-wider transition duration-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-hands-praying"></i> Aamiin ({{ $p->aamiin_count }})
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <div class="text-center pt-10">
        <a href="{{ route('prayers.index') }}" class="text-xs font-black uppercase tracking-widest text-primary-600 hover:text-primary-700">
            Kirim Doa atau Lihat Semua Doa <i class="fa-solid fa-arrow-right ml-1"></i>
        </a>
    </div>
</section>

<!-- News/Articles Section -->
<section class="bg-slate-50/50 border-y border-slate-100 py-20 sm:py-28">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-col sm:flex-row justify-between items-end mb-16 gap-4">
            <div>
                <h2 class="text-2xl sm:text-4xl font-montserrat font-black text-slate-900 uppercase tracking-tight italic">
                    Kabar <span class="text-primary-600">Terbaru & Dampak</span>
                </h2>
                <p class="text-slate-400 font-bold text-[10px] sm:text-xs tracking-widest uppercase mt-2">Update penyaluran donasi dan cerita dari penerima manfaat</p>
            </div>
            <a href="{{ route('news') }}" class="bg-white hover:bg-slate-100 border border-slate-200/60 text-slate-700 text-xs font-black uppercase tracking-widest px-6 py-3.5 rounded-2xl transition duration-300">
                Lihat Semua Artikel
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
            @foreach($news as $n)
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden flex flex-col group hover:shadow-xl transition duration-300">
                    <div class="h-48 overflow-hidden bg-slate-100">
                        <img 
                            src="{{ $n->image ?? 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=800&fit=crop' }}" 
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-500" 
                            alt="{{ $n->title }}"
                        >
                    </div>
                    <div class="p-6 flex-grow flex flex-col justify-between space-y-4">
                        <div class="space-y-2">
                            <span class="bg-primary-50 text-primary-700 text-[8px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md">
                                {{ $n->tag }}
                            </span>
                            <h3 class="font-montserrat font-black uppercase text-sm sm:text-base text-slate-800 line-clamp-2 leading-tight group-hover:text-primary-600 transition-colors">
                                <a href="{{ route('news.detail', $n->id) }}">{{ $n->title }}</a>
                            </h3>
                        </div>
                        <div class="flex justify-between items-center text-[9px] font-bold text-slate-400 uppercase tracking-wider pt-2 border-t border-slate-50">
                            <span>{{ $n->created_at->format('d M Y') }}</span>
                            <a href="{{ route('news.detail', $n->id) }}" class="text-primary-600 font-black">Baca <i class="fa-solid fa-arrow-right ml-1"></i></a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
