@extends('layouts.app')

@section('content')
<section class="max-w-7xl mx-auto px-3 sm:px-6 py-20">
    <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-12 gap-6">
        <div>
            <h1 class="text-3xl sm:text-5xl font-montserrat font-black text-slate-900 uppercase tracking-tight italic">
                Daftar <span class="text-primary-600">Kampanye Kebaikan</span>
            </h1>
            <p class="text-slate-600 font-bold text-xs tracking-widest uppercase mt-2">Dukung program kemanusiaan pilihan Anda secara langsung</p>
        </div>

        @auth
            <a href="{{ route('campaigns.create') }}" class="inline-flex items-center justify-center min-h-[44px] bg-primary-600 hover:bg-primary-700 text-white text-xs font-black uppercase tracking-widest px-6 py-3.5 rounded-2xl shadow-lg shadow-primary-600/10 hover:shadow-primary-600/20 transition duration-300">
                Buat Kampanye Baru <i class="fa-solid fa-plus ml-1.5" aria-hidden="true"></i>
            </a>
        @endauth
    </div>

    <!-- Category Filters -->
    <div class="flex overflow-x-auto no-scrollbar gap-2.5 mb-10 -mx-3 px-3 md:mx-0 md:px-0">
        <a 
            href="{{ route('campaigns.index') }}"
            class="inline-flex items-center min-h-[44px] px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition-all whitespace-nowrap shadow-sm border {{ !$category ? 'bg-primary-600 text-white border-primary-600' : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300 hover:text-primary-700' }}"
        >
            Semua Program
        </a>
        @foreach($categories as $cat)
            <a 
                href="{{ route('campaigns.index', ['category' => $cat]) }}"
                class="inline-flex items-center min-h-[44px] px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition-all whitespace-nowrap shadow-sm border {{ $category === $cat ? 'bg-primary-600 text-white border-primary-600' : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300 hover:text-primary-700' }}"
            >
                {{ $cat }}
            </a>
        @endforeach
    </div>

    <!-- Campaigns Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-8 mb-12">
        @forelse($campaigns as $c)
            @php
                $percent = $c->target_amount > 0 ? min(100, ($c->current_amount / $c->target_amount) * 100) : 0;
            @endphp
            <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200 hover:border-slate-300 hover:shadow-xl shadow-sm transition-all duration-300 flex flex-col group overflow-hidden">
                <!-- Cover Image -->
                <a href="{{ route('campaigns.show', $c->id) }}" class="relative h-48 sm:h-56 overflow-hidden block">
                    <img 
                        src="{{ $c->foto ?? 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=800&fit=crop' }}" 
                        width="800" height="450" loading="lazy" decoding="async"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                        alt="{{ $c->title }}"
                    >
                    <span class="absolute top-3 left-3 sm:top-4 sm:left-4 bg-slate-900/90 text-white text-[10px] sm:text-xs font-black uppercase tracking-widest px-2.5 py-1 sm:px-3 sm:py-1.5 rounded-lg border border-white/15">
                        {{ $c->category }}
                    </span>
                </a>

                <!-- Card Body -->
                <div class="p-5 sm:p-8 flex-grow flex flex-col justify-between space-y-4 sm:space-y-6">
                    <div class="space-y-2 sm:space-y-3">
                        <h2 class="font-montserrat font-black uppercase text-sm sm:text-base text-slate-800 leading-snug sm:leading-tight group-hover:text-primary-600 transition-colors duration-200 line-clamp-2">
                            <a href="{{ route('campaigns.show', $c->id) }}" class="hover:underline">
                                {{ $c->title }}
                            </a>
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-600 font-medium line-clamp-3 leading-relaxed">
                            {{ $c->description }}
                        </p>
                    </div>

                    <div class="space-y-3 sm:space-y-4">
                        <!-- Progress Bar -->
                        <div class="space-y-1.5">
                            <div class="w-full bg-slate-200 h-2 sm:h-2.5 rounded-full overflow-hidden">
                                <div class="bg-primary-600 h-full rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
                            </div>
                            <div class="flex flex-wrap justify-between items-center text-xs font-bold text-slate-600 uppercase tracking-wider gap-1">
                                <span>{{ number_format($percent, 1) }}% Terkumpul</span>
                                <span class="truncate">Target: Rp {{ number_format($c->target_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pt-2 gap-3">
                            <div>
                                <span class="text-xs font-bold text-slate-600 uppercase block tracking-wider leading-none">Terkumpul</span>
                                <span class="text-sm sm:text-base font-montserrat font-black text-slate-900 leading-tight block mt-1">
                                    Rp {{ number_format($c->current_amount, 0, ',', '.') }}
                                </span>
                            </div>
                            <a href="{{ route('campaigns.show', $c->id) }}" class="w-full sm:w-auto inline-flex items-center justify-center min-h-[44px] bg-primary-50 hover:bg-primary-600 border border-primary-200 hover:border-primary-600 hover:text-white text-primary-700 text-xs font-black uppercase tracking-wider px-5 py-2.5 rounded-xl transition-all duration-300">
                                Detail <i class="fa-solid fa-arrow-right ml-1.5 text-xs" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-20 bg-white rounded-3xl border border-slate-200">
                <i class="fa-solid fa-circle-info text-slate-400 text-4xl mb-4" aria-hidden="true"></i>
                <p class="text-sm font-bold text-slate-600 uppercase tracking-wider">Belum ada kampanye aktif di kategori ini.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div>
        {{ $campaigns->links() }}
    </div>
</section>
@endsection
