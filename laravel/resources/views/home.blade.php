@extends('layouts.app')

@section('content')
@php
    $primaryHeroImage = !empty($settings->hero_image)
        ? (str_starts_with($settings->hero_image, 'http') ? $settings->hero_image : asset($settings->hero_image))
        : 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=2070&auto=format&fit=crop';

    $heroSlides = [
        ['url' => $primaryHeroImage, 'alt' => 'Penyaluran bantuan kemanusiaan Yayasan Harpa Indonesia Madani'],
        ['url' => 'https://images.unsplash.com/photo-1593113598332-cd288d649433?q=80&w=2070&auto=format&fit=crop', 'alt' => 'Aksi kepedulian dan distribusi logistik kemanusiaan'],
        ['url' => 'https://images.unsplash.com/photo-1509099836639-18ba1795216d?q=80&w=2070&auto=format&fit=crop', 'alt' => 'Program pendidikan anak dan masyarakat penerima manfaat'],
        ['url' => 'https://images.unsplash.com/photo-1469571486292-0ba58a3f068b?q=80&w=2070&auto=format&fit=crop', 'alt' => 'Kolaborasi relawan dan donatur untuk sesama'],
    ];

    $statFirstValue = !empty($settings?->stats_beneficiaries) ? $settings->stats_beneficiaries : number_format($realStats['distributions'] ?? 0, 0, ',', '.');
    $statFirstLabel = !empty($settings?->stats_beneficiaries) ? 'Penerima Manfaat' : 'Titik Penyaluran';
    $statProjectsValue = !empty($settings?->stats_projects) ? $settings->stats_projects : number_format($realStats['campaigns'] ?? 0, 0, ',', '.');
    $statVolunteersValue = !empty($settings?->stats_volunteers) ? $settings->stats_volunteers : number_format($realStats['volunteers'] ?? 0, 0, ',', '.');
@endphp

<!-- Hero section with pausable slide carousel -->
<section 
    x-data="{
        activeSlide: 0,
        totalSlides: {{ count($heroSlides) }},
        paused: false,
        userPaused: false,
        timer: null,
        init() {
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                this.userPaused = true;
                this.paused = true;
                return;
            }
            this.timer = setInterval(() => {
                if (!this.paused && !this.userPaused) {
                    this.next();
                }
            }, 4500);
        },
        togglePause() {
            this.userPaused = !this.userPaused;
            this.paused = this.userPaused;
        },
        next() {
            this.activeSlide = (this.activeSlide + 1) % this.totalSlides;
        },
        prev() {
            this.activeSlide = (this.activeSlide - 1 + this.totalSlides) % this.totalSlides;
        }
    }"
    @mouseenter="paused = true"
    @mouseleave="if (!userPaused) paused = false"
    class="relative min-h-[70vh] flex items-center justify-center text-center text-white overflow-hidden py-24 sm:py-32 px-6"
>
    <!-- Hero images -->
    <div class="absolute inset-0 z-0 overflow-hidden bg-slate-950">
        @foreach($heroSlides as $index => $slide)
            <div 
                x-show="activeSlide === {{ $index }}"
                @if($index > 0) x-cloak @endif
                x-transition:enter="transition-opacity ease-out duration-700"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-in duration-700"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="absolute inset-0 w-full h-full"
            >
                <img 
                    src="{{ $slide['url'] }}" 
                    width="1920"
                    height="1080"
                    @if($index === 0) fetchpriority="high" decoding="sync" @else loading="lazy" decoding="async" @endif
                    class="w-full h-full object-cover"
                    alt="{{ $slide['alt'] }}"
                >
            </div>
        @endforeach
        <div class="absolute inset-0 bg-gradient-to-b from-black/75 via-black/60 to-black/80"></div>
    </div>

    <!-- Navigation arrows -->
    <button 
        @click="prev()" 
        type="button" 
        aria-label="Slide sebelumnya"
        class="hidden md:flex absolute left-4 lg:left-8 top-1/2 -translate-y-1/2 z-20 w-11 h-11 rounded-xl bg-slate-950/70 hover:bg-[#f7ad1a] hover:text-[#063f5c] text-white items-center justify-center border border-white/25 transition-colors duration-200 shadow-lg"
    >
        <i class="fa-solid fa-chevron-left text-sm" aria-hidden="true"></i>
    </button>
    <button 
        @click="next()" 
        type="button" 
        aria-label="Slide berikutnya"
        class="hidden md:flex absolute right-4 lg:right-8 top-1/2 -translate-y-1/2 z-20 w-11 h-11 rounded-xl bg-slate-950/70 hover:bg-[#f7ad1a] hover:text-[#063f5c] text-white items-center justify-center border border-white/25 transition-colors duration-200 shadow-lg"
    >
        <i class="fa-solid fa-chevron-right text-sm" aria-hidden="true"></i>
    </button>

    <!-- Hero content -->
    <div class="relative z-10 w-full max-w-4xl mx-auto space-y-8">
        <h1 class="text-3xl sm:text-5xl md:text-6xl font-montserrat font-black leading-tight tracking-tight uppercase break-words px-4">
            {{ $settings->hero_title ?? 'Uluran Tangan Anda, Harapan Mereka' }}
        </h1>
        
        <p class="text-sm md:text-lg text-white/95 max-w-2xl mx-auto font-semibold leading-relaxed break-words px-4">
            {{ $settings->hero_subtitle ?? 'Kami menghubungkan kebaikan hati Anda dengan mereka yang benar-benar membutuhkan bantuan di seluruh pelosok negeri.' }}
        </p>

        <div class="pt-4 flex flex-col sm:flex-row justify-center items-center gap-4">
            <a href="#campaigns" class="w-full sm:w-auto min-h-[44px] inline-flex items-center justify-center bg-[#f7ad1a] hover:bg-[#df9408] text-[#063f5c] font-black text-xs uppercase tracking-wider px-8 py-4 rounded-xl shadow-lg transition-colors duration-200">
                <span>Mulai Berdonasi</span>
                <i class="fa-solid fa-arrow-down ml-2" aria-hidden="true"></i>
            </a>
            <a href="{{ route('volunteer') }}" class="w-full sm:w-auto min-h-[44px] inline-flex items-center justify-center bg-[#063f5c]/90 border border-white/30 hover:bg-[#04324a] text-white font-black text-xs uppercase tracking-wider px-8 py-4 rounded-xl transition-colors duration-200">
                <span>Gabung Relawan</span>
                <i class="fa-solid fa-users ml-2" aria-hidden="true"></i>
            </a>
        </div>
    </div>

    <!-- Slider dots and pause control -->
    <div class="absolute bottom-16 sm:bottom-28 left-0 right-0 z-20 flex justify-center items-center gap-3 pointer-events-auto">
        <button 
            type="button" 
            @click="togglePause()" 
            :aria-pressed="userPaused"
            :aria-label="userPaused ? 'Putar slide otomatis' : 'Jeda slide otomatis'"
            class="min-w-[44px] min-h-[44px] px-3 py-1.5 rounded-xl bg-slate-950/75 hover:bg-slate-900 text-white border border-white/25 text-[11px] font-bold inline-flex items-center justify-center gap-1.5 transition-colors"
        >
            <i class="fa-solid text-xs" :class="userPaused ? 'fa-play' : 'fa-pause'" aria-hidden="true"></i>
            <span x-text="userPaused ? 'Putar' : 'Jeda'"></span>
        </button>

        <div class="flex items-center gap-1">
            @foreach($heroSlides as $index => $slide)
                <button 
                    @click="activeSlide = {{ $index }}" 
                    type="button" 
                    aria-label="Tampilkan slide {{ $index + 1 }}"
                    class="min-w-[44px] min-h-[44px] flex items-center justify-center rounded-xl transition-colors"
                >
                    <span 
                        class="h-2 rounded-full transition-all duration-300"
                        :class="activeSlide === {{ $index }} ? 'w-8 bg-[#f7ad1a]' : 'w-2 bg-white/60 hover:bg-white'"
                    ></span>
                </button>
            @endforeach
        </div>
    </div>
</section>

<!-- Real impact counters from site settings or database records -->
<section class="max-w-7xl mx-auto px-3 sm:px-4 -mt-10 sm:-mt-20 relative z-20">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-6">
        <div class="bg-white p-4 sm:p-8 rounded-2xl shadow-lg flex items-center gap-4 border border-slate-200/80">
            <div class="bg-emerald-50 w-12 h-12 sm:w-14 sm:h-14 rounded-xl flex items-center justify-center text-emerald-700 text-lg sm:text-xl shrink-0 border border-emerald-200">
                <i class="fa-solid fa-users" aria-hidden="true"></i>
            </div>
            <div>
                <h2 class="text-lg sm:text-2xl font-montserrat font-black tracking-tight text-slate-900 leading-none">
                    {{ $statFirstValue }}
                </h2>
                <p class="text-slate-600 font-bold text-[11px] uppercase tracking-wider mt-1.5 leading-tight">{{ $statFirstLabel }}</p>
            </div>
        </div>

        <div class="bg-white p-4 sm:p-8 rounded-2xl shadow-lg flex items-center gap-4 border border-slate-200/80">
            <div class="bg-primary-50 w-12 h-12 sm:w-14 sm:h-14 rounded-xl flex items-center justify-center text-primary-700 text-lg sm:text-xl shrink-0 border border-primary-100">
                <i class="fa-solid fa-gift" aria-hidden="true"></i>
            </div>
            <div>
                <h2 class="text-lg sm:text-2xl font-montserrat font-black tracking-tight text-slate-900 leading-none">
                    {{ $statProjectsValue }}
                </h2>
                <p class="text-slate-600 font-bold text-[11px] uppercase tracking-wider mt-1.5 leading-tight">Project Kebaikan</p>
            </div>
        </div>

        <div class="bg-white p-4 sm:p-8 rounded-2xl shadow-lg flex items-center gap-4 border border-slate-200/80">
            <div class="bg-amber-50 w-12 h-12 sm:w-14 sm:h-14 rounded-xl flex items-center justify-center text-amber-800 text-lg sm:text-xl shrink-0 border border-amber-200">
                <i class="fa-solid fa-hands-holding-child" aria-hidden="true"></i>
            </div>
            <div>
                <h2 class="text-lg sm:text-2xl font-montserrat font-black tracking-tight text-slate-900 leading-none">
                    {{ $statVolunteersValue }}
                </h2>
                <p class="text-slate-600 font-bold text-[11px] uppercase tracking-wider mt-1.5 leading-tight">Perwakilan Relawan</p>
            </div>
        </div>
    </div>
</section>

<!-- Active Campaigns -->
<section id="campaigns" class="max-w-7xl mx-auto px-3 sm:px-4 py-16 sm:py-24">
    <div class="text-center max-w-2xl mx-auto mb-12 space-y-2">
        <h2 class="text-2xl sm:text-4xl font-montserrat font-black text-slate-900 uppercase tracking-tight">
            Daftar <span class="text-primary-600">Kampanye Aktif</span>
        </h2>
        <div class="w-12 h-1.5 bg-[#f7ad1a] mx-auto rounded-full"></div>
        <p class="text-slate-600 font-semibold text-xs sm:text-sm mt-3">Salurkan kebaikan Anda ke program pilihan berikut</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-8">
        @forelse($campaigns as $c)
            @php
                $percent = $c->target_amount > 0 ? min(100, ($c->current_amount / $c->target_amount) * 100) : 0;
            @endphp
            <div class="bg-white rounded-2xl border border-slate-200/80 hover:border-primary-200 hover:shadow-xl shadow-sm transition-all duration-300 flex flex-col group overflow-hidden">
                <a href="{{ route('campaigns.show', $c->id) }}" class="relative aspect-video overflow-hidden block bg-slate-100">
                    <img 
                        src="{{ $c->foto ?? 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=800&fit=crop' }}" 
                        width="800"
                        height="450"
                        loading="lazy"
                        decoding="async"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" 
                        alt="{{ $c->title }}"
                    >
                    <span class="absolute top-3 left-3 bg-slate-900 text-white text-[10px] font-black uppercase tracking-wider px-2.5 py-1 rounded-lg">
                        {{ $c->category }}
                    </span>
                </a>

                <div class="p-5 sm:p-7 flex-grow flex flex-col justify-between space-y-4">
                    <div class="space-y-2">
                        <h3 class="font-montserrat font-black uppercase text-sm sm:text-base text-slate-900 leading-snug group-hover:text-primary-600 transition-colors duration-200 line-clamp-2">
                            <a href="{{ route('campaigns.show', $c->id) }}" class="hover:underline">
                                {{ $c->title }}
                            </a>
                        </h3>
                        <p class="text-xs text-slate-600 font-medium line-clamp-3 leading-relaxed">
                            {{ $c->description }}
                        </p>
                    </div>

                    <div class="space-y-3">
                        <div class="space-y-1.5">
                            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                <div class="bg-[#063f5c] h-full rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
                            </div>
                            <div class="flex justify-between items-center text-[11px] font-bold text-slate-600 gap-2">
                                <span>{{ number_format($percent, 1) }}% Terkumpul</span>
                                <span class="truncate">Target: Rp {{ number_format($c->target_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pt-2 gap-3 border-t border-slate-100">
                            <div>
                                <span class="text-[10px] font-bold text-slate-600 uppercase block tracking-wider leading-none">Terkumpul</span>
                                <span class="text-sm sm:text-base font-montserrat font-black text-slate-900 leading-tight block mt-1">
                                    Rp {{ number_format($c->current_amount, 0, ',', '.') }}
                                </span>
                            </div>
                            <a href="{{ route('campaigns.show', $c->id) }}" class="w-full sm:w-auto min-h-[44px] inline-flex items-center justify-center bg-primary-50 hover:bg-primary-600 hover:text-white text-primary-700 text-xs font-black uppercase tracking-wider px-5 py-2.5 rounded-xl transition-colors duration-200">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="sm:col-span-2 lg:col-span-3 bg-white border border-slate-200 rounded-2xl p-10 text-center space-y-2">
                <i class="fa-solid fa-hand-holding-heart text-slate-500 text-3xl" aria-hidden="true"></i>
                <h3 class="text-sm font-bold text-slate-800">Belum ada kampanye aktif saat ini</h3>
                <p class="text-xs text-slate-600">Program penggalangan dana terbaru akan tampil di sini setelah dipublikasikan admin.</p>
            </div>
        @endforelse
    </div>
</section>

<!-- Distribution Map Section -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script defer src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<section class="py-20 sm:py-28 bg-white relative border-t border-slate-200/80">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-16">
            <div class="w-full lg:w-1/3 space-y-6">
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-0.5 bg-primary-600"></span>
                        <span class="text-primary-700 font-black uppercase text-xs tracking-wider">Jejak Kebaikan</span>
                    </div>
                    <h2 class="text-3xl sm:text-4xl font-montserrat font-black text-slate-900 leading-tight uppercase">
                        Peta <span class="text-primary-600">Penyaluran</span>
                    </h2>
                    <p class="text-slate-600 text-sm leading-relaxed font-medium">
                        Setiap donasi Anda adalah amanah. Pantau titik penyaluran yang dilaporkan langsung oleh petugas lapangan kami beserta dokumentasi di lokasi.
                    </p>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center gap-4 p-4 bg-slate-50 border border-slate-200/80 rounded-2xl">
                        <div class="w-11 h-11 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-primary-700 text-base shrink-0">
                            <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                        </div>
                        <div>
                            <h3 class="font-montserrat font-black text-slate-900 text-xs uppercase tracking-wide">Titik Presisi GPS</h3>
                            <p class="text-xs text-slate-600 font-medium mt-0.5">Lokasi akurat sesuai koordinat petugas.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4 p-4 bg-slate-50 border border-slate-200/80 rounded-2xl">
                        <div class="w-11 h-11 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-primary-700 text-base shrink-0">
                            <i class="fa-solid fa-camera" aria-hidden="true"></i>
                        </div>
                        <div>
                            <h3 class="font-montserrat font-black text-slate-900 text-xs uppercase tracking-wide">Dokumentasi Nyata</h3>
                            <p class="text-xs text-slate-600 font-medium mt-0.5">Bukti foto penyaluran di setiap titik.</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2.5 pt-1">
                    <span class="inline-block rounded-full h-2.5 w-2.5 bg-emerald-700" aria-hidden="true"></span>
                    <span class="text-xs font-bold text-slate-600">Tercatat {{ number_format($distributions->count(), 0, ',', '.') }} titik lokasi penyaluran</span>
                </div>
            </div>

            <div class="w-full lg:w-2/3">
                <div class="relative bg-white p-3 rounded-3xl shadow-lg border border-slate-200 overflow-hidden">
                    <div id="map" class="w-full h-[380px] sm:h-[480px] rounded-2xl z-10 border border-slate-200"></div>
                    <div class="mt-2.5 px-2 flex items-center justify-between text-xs text-slate-600 font-medium">
                        <span><i class="fa-solid fa-map-pin text-primary-700 mr-1.5" aria-hidden="true"></i>Klik penanda biru pada peta untuk melihat rincian penyaluran.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof L === 'undefined') return;

        const map = L.map('map', { scrollWheelZoom: false }).setView([-2.5489, 118.0149], 5);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        @foreach($distributions as $d)
            @if($d->latitude && $d->longitude)
                (function() {
                    const marker = L.marker([{{ $d->latitude }}, {{ $d->longitude }}]).addTo(map);
                    const popupContent = `
                        <div class="w-56 p-1 font-sans text-slate-800">
                            ${ '{{ $d->photo_url }}' ? `<img src="{{ $d->photo_url }}" width="224" height="96" alt="{{ e($d->title) }}" class="w-full h-24 object-cover rounded-lg mb-2" />` : '' }
                            <h4 class="font-bold text-slate-900 text-xs mb-1">{{ e($d->title) }}</h4>
                            <p class="text-[11px] text-slate-600 leading-relaxed mb-2">{{ e($d->description) }}</p>
                            <div class="flex justify-between items-center text-[10px] font-bold text-slate-600 pt-1.5 border-t border-slate-200">
                                <span>Petugas: {{ e($d->worker->name ?? 'Petugas') }}</span>
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
<section class="bg-[#063f5c] text-white py-20 sm:py-24 relative overflow-hidden">
    <div class="absolute inset-0 opacity-15">
        <img 
            src="{{ !empty($settings->about_image) ? (str_starts_with($settings->about_image, 'http') ? $settings->about_image : asset($settings->about_image)) : 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=1200&h=600&fit=crop' }}" 
            width="1200"
            height="600"
            loading="lazy"
            decoding="async"
            class="w-full h-full object-cover" 
            alt=""
        >
    </div>
    <div class="relative z-10 max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
        <div class="space-y-6">
            <span class="text-xs font-black uppercase tracking-wider text-[#f7ad1a]">Visi dan Misi Lembaga</span>
            <h2 class="text-2xl sm:text-4xl font-montserrat font-black uppercase tracking-tight">
                Penyaluran yang <span class="text-[#f7ad1a]">Transparan</span> dan Bertanggung Jawab
            </h2>
            <div class="w-12 h-1.5 bg-[#f7ad1a] rounded-full"></div>
            <p class="text-sm text-slate-200 leading-relaxed font-medium">
                {{ $settings->about_mission ?? 'Kami memastikan setiap rupiah yang Anda donasikan tersalurkan langsung kepada penerima manfaat yang berhak dengan dokumentasi dan pelaporan terbuka.' }}
            </p>
            <div class="pt-2">
                <a href="{{ route('about') }}" class="inline-flex items-center justify-center min-h-[44px] bg-[#f7ad1a] hover:bg-[#df9408] text-[#063f5c] text-xs font-black uppercase tracking-wider px-8 py-3.5 rounded-xl transition-colors duration-200 shadow-lg">
                    Tentang Lembaga Kami
                </a>
            </div>
        </div>
        <div class="bg-[#04324a]/95 border border-[#0e567d] p-8 rounded-3xl space-y-6">
            <h3 class="text-lg font-montserrat font-black uppercase tracking-wide text-white">Fokus Penyaluran</h3>
            <div class="space-y-5">
                <div class="flex items-start space-x-4">
                    <div class="bg-[#063f5c] border border-[#0e567d] text-[#f7ad1a] w-11 h-11 rounded-xl flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-book-quran" aria-hidden="true"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-black uppercase tracking-wider text-white">Mushaf Al-Quran Pelosok</h4>
                        <p class="text-xs text-slate-200 leading-relaxed mt-1">Mendistribusikan mushaf Al-Quran untuk santri di pesantren wilayah pelosok.</p>
                    </div>
                </div>
                <div class="flex items-start space-x-4">
                    <div class="bg-[#063f5c] border border-[#0e567d] text-[#f7ad1a] w-11 h-11 rounded-xl flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-graduation-cap" aria-hidden="true"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-black uppercase tracking-wider text-white">Pendidikan Anak Dhuafa</h4>
                        <p class="text-xs text-slate-200 leading-relaxed mt-1">Menyalurkan perlengkapan belajar dan sarana sekolah di wilayah terdampak bencana.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Prayer Board -->
<section class="max-w-7xl mx-auto px-4 py-16 sm:py-24">
    <div class="text-center max-w-2xl mx-auto mb-12 space-y-2">
        <h2 class="text-2xl sm:text-4xl font-montserrat font-black text-slate-900 uppercase tracking-tight">
            Papan <span class="text-primary-600">Doa Donatur</span>
        </h2>
        <div class="w-12 h-1.5 bg-[#f7ad1a] mx-auto rounded-full"></div>
        <p class="text-slate-600 font-semibold text-xs sm:text-sm mt-3">Mari saling mendoakan dan mengaminkan kebaikan bersama</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($prayers as $p)
            <div class="bg-white border border-slate-200/80 p-6 sm:p-7 rounded-2xl shadow-sm flex flex-col justify-between min-h-[160px]">
                <p class="text-xs sm:text-sm text-slate-700 font-medium leading-relaxed">
                    "{{ $p->message }}"
                </p>
                <div class="flex items-center justify-between pt-4 border-t border-slate-100 mt-4 gap-3">
                    <div>
                        <span class="text-xs font-bold text-slate-900 block">
                            {{ $p->is_anonymous ? 'Donatur Anonim' : $p->name }}
                        </span>
                        <span class="text-[11px] text-slate-600 font-medium">{{ $p->created_at->diffForHumans() }}</span>
                    </div>
                    <form action="{{ route('prayers.upvote', $p->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="min-h-[44px] bg-primary-50 hover:bg-primary-600 text-primary-700 hover:text-white px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-colors flex items-center gap-1.5 border border-primary-100">
                            <i class="fa-solid fa-hands-praying" aria-hidden="true"></i>
                            <span>Aamiin ({{ $p->aamiin_count }})</span>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="sm:col-span-2 lg:col-span-3 bg-white border border-slate-200 rounded-2xl p-8 text-center">
                <p class="text-xs font-bold text-slate-600">Belum ada doa yang dikirimkan. Jadilah yang pertama mengirimkan doa kebaikan.</p>
            </div>
        @endforelse
    </div>

    <div class="text-center pt-10">
        <a href="{{ route('prayers.index') }}" class="inline-flex items-center justify-center min-h-[44px] px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider text-primary-700 hover:bg-primary-50 transition-colors">
            Kirim Doa atau Lihat Semua Doa
        </a>
    </div>
</section>

<!-- News / Articles Section -->
<section class="bg-slate-50 border-y border-slate-200/80 py-16 sm:py-24">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-col sm:flex-row justify-between sm:items-end mb-12 gap-4">
            <div>
                <h2 class="text-2xl sm:text-4xl font-montserrat font-black text-slate-900 uppercase tracking-tight">
                    Kabar <span class="text-primary-600">Terbaru dan Dampak</span>
                </h2>
                <p class="text-slate-600 font-semibold text-xs sm:text-sm mt-2">Update penyaluran donasi dan cerita dari penerima manfaat</p>
            </div>
            <a href="{{ route('news') }}" class="min-h-[44px] inline-flex items-center justify-center bg-white hover:bg-slate-100 border border-slate-300 text-slate-800 text-xs font-black uppercase tracking-wider px-6 py-3 rounded-xl transition-colors">
                Lihat Semua Artikel
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 sm:gap-8">
            @forelse($news as $n)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden flex flex-col group hover:shadow-lg transition-all duration-300">
                    <div class="aspect-video overflow-hidden bg-slate-100">
                        <img 
                            src="{{ $n->image ?? 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=800&fit=crop' }}" 
                            width="800"
                            height="450"
                            loading="lazy"
                            decoding="async"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" 
                            alt="{{ $n->title }}"
                        >
                    </div>
                    <div class="p-6 flex-grow flex flex-col justify-between space-y-4">
                        <div class="space-y-2">
                            <span class="inline-block bg-primary-50 text-primary-700 text-[10px] font-black uppercase tracking-wider px-2.5 py-1 rounded-md border border-primary-100">
                                {{ $n->tag }}
                            </span>
                            <h3 class="font-montserrat font-black uppercase text-sm sm:text-base text-slate-900 line-clamp-2 leading-tight group-hover:text-primary-600 transition-colors">
                                <a href="{{ route('news.detail', $n->id) }}" class="hover:underline">{{ $n->title }}</a>
                            </h3>
                        </div>
                        <div class="flex justify-between items-center text-xs font-bold text-slate-600 pt-3 border-t border-slate-100">
                            <span>{{ $n->created_at->format('d M Y') }}</span>
                            <a href="{{ route('news.detail', $n->id) }}" class="text-primary-700 font-black hover:underline">Baca Artikel</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="sm:col-span-3 bg-white border border-slate-200 rounded-2xl p-8 text-center">
                    <p class="text-xs font-bold text-slate-600">Belum ada artikel penyaluran yang diterbitkan.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
