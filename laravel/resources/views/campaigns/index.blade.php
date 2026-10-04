@extends('layouts.app')

@section('content')
<section class="max-w-7xl mx-auto px-3 sm:px-6 py-20">
    <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-12 gap-6">
        <div>
            <h1 class="text-3xl sm:text-5xl font-montserrat font-black text-slate-900 uppercase tracking-tight italic">
                Daftar <span class="text-primary-600">Kampanye Kebaikan</span>
            </h1>
            <p class="text-slate-400 font-bold text-xs tracking-widest uppercase mt-2">Dukung program kemanusiaan pilihan Anda secara langsung</p>
        </div>

        @auth
            <a href="{{ route('campaigns.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white text-xs font-black uppercase tracking-widest px-6 py-4 rounded-2xl shadow-lg shadow-primary-600/10 hover:shadow-primary-600/20 transition duration-300">
                Buat Kampanye Baru <i class="fa-solid fa-plus ml-1"></i>
            </a>
        @endauth
    </div>

    <!-- Category Filters -->
    <div class="flex overflow-x-auto no-scrollbar gap-2 mb-10 -mx-6 px-6 md:mx-0 md:px-0">
        <a 
            href="{{ route('campaigns.index') }}"
            class="px-5 py-3 rounded-xl text-[9px] font-black uppercase tracking-wider transition-all whitespace-nowrap shadow-sm border {{ !$category ? 'bg-primary-600 text-white border-primary-600' : 'bg-white text-slate-400 border-slate-100 hover:border-slate-200' }}"
        >
            Semua Program
        </a>
        @foreach($categories as $cat)
            <a 
                href="{{ route('campaigns.index', ['category' => $cat]) }}"
                class="px-5 py-3 rounded-xl text-[9px] font-black uppercase tracking-wider transition-all whitespace-nowrap shadow-sm border {{ $category === $cat ? 'bg-primary-600 text-white border-primary-600' : 'bg-white text-slate-400 border-slate-100 hover:border-slate-200' }}"
            >
                {{ $cat }}
            </a>
        @endforeach
    </div>

    <!-- Campaigns Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-8 mb-12">
        @forelse($campaigns as $c)
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
                        <p class="hidden sm:block text-xs text-slate-500 font-medium line-clamp-3 leading-relaxed">
                            {{ $c->description }}
                        </p>
                    </div>

                    <div class="space-y-2.5 sm:space-y-4">
                        <!-- Progress Bar -->
                        <div class="space-y-1 sm:space-y-1.5">
                            <div class="w-full bg-slate-100 h-1.5 sm:h-2.5 rounded-full overflow-hidden">
                                <div class="bg-primary-600 h-full rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
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
                            <a href="{{ route('campaigns.show', $c->id) }}" class="w-full sm:w-auto text-center bg-primary-50 hover:bg-primary-600 hover:text-white text-primary-700 text-[10px] sm:text-xs font-black uppercase tracking-wider px-3 py-2 sm:px-5 sm:py-3 rounded-lg sm:rounded-xl transition-all duration-300">
                                Detail <i class="fa-solid fa-arrow-right ml-0.5 sm:ml-1 text-[9px] sm:text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-20 bg-white rounded-3xl border border-slate-100">
                <i class="fa-solid fa-circle-info text-slate-300 text-4xl mb-4"></i>
                <p class="text-sm font-bold text-slate-500 uppercase tracking-wider">Belum ada kampanye aktif di kategori ini.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div>
        {{ $campaigns->links() }}
    </div>
</section>
@endsection
