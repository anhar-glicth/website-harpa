@extends('layouts.app')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-20">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Campaign Details -->
        <div class="lg:col-span-2 space-y-8">
            <div class="space-y-4">
                <span class="bg-primary-50 text-primary-700 text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-lg border border-primary-100">
                    {{ $campaign->category }}
                </span>
                <h1 class="text-2xl sm:text-4xl font-montserrat font-black uppercase tracking-tight text-slate-900 leading-tight">
                    {{ $campaign->title }}
                </h1>
                <div class="flex items-center space-x-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                    <span>Dibuat Oleh: {{ $campaign->author->name }}</span>
                    <span>&bull;</span>
                    <span>Status: {{ $campaign->status }}</span>
                </div>
            </div>

            <!-- Cover Image -->
            <div class="rounded-3xl overflow-hidden shadow-lg h-[350px] sm:h-[450px]">
                <img 
                    src="{{ $campaign->foto ?? 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=1200&fit=crop' }}" 
                    class="w-full h-full object-cover" 
                    alt="{{ $campaign->title }}"
                >
            </div>

            <!-- Campaign Description -->
            <div class="space-y-4">
                <h3 class="font-montserrat font-black uppercase text-base text-slate-800 tracking-wider">Deskripsi Lengkap</h3>
                <p class="text-slate-600 font-semibold text-xs sm:text-sm leading-relaxed whitespace-pre-line">
                    {{ $campaign->description }}
                </p>
            </div>

            <!-- Updates (Impact Stories) -->
            <div class="space-y-6 pt-6 border-t border-slate-100">
                <h3 class="font-montserrat font-black uppercase text-base text-slate-800 tracking-wider">Update Penyaluran & Cerita Dampak</h3>
                @forelse($campaign->updates as $up)
                    <div class="bg-white border border-slate-100 p-6 sm:p-8 rounded-3xl space-y-4 shadow-sm">
                        <div class="flex justify-between items-start">
                            <h4 class="font-montserrat font-black uppercase text-xs sm:text-sm text-slate-800">{{ $up->title }}</h4>
                            <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">{{ $up->created_at->format('d M Y') }}</span>
                        </div>
                        @if($up->image)
                            <div class="rounded-2xl overflow-hidden max-h-72">
                                <img src="{{ $up->image }}" class="w-full h-full object-cover" alt="">
                            </div>
                        @endif
                        <p class="text-[11px] sm:text-xs text-slate-500 font-semibold leading-relaxed">
                            {{ $up->content }}
                        </p>
                    </div>
                @empty
                    <div class="bg-slate-50 p-8 rounded-3xl border border-slate-100 text-center text-xs font-bold text-slate-400 uppercase tracking-wider">
                        Belum ada laporan cerita dampak dari petugas lapangan.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Sidebar / Donation Panel -->
        <div class="space-y-8">
            <!-- Donation Progress Box -->
            <div class="bg-white border border-slate-100 p-8 rounded-3xl space-y-6 shadow-xl sticky top-28">
                <div class="space-y-2">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block leading-none">Terkumpul</span>
                    <h2 class="text-2xl sm:text-3xl font-montserrat font-black text-slate-900 leading-none">
                        Rp {{ number_format($campaign->current_amount, 0, ',', '.') }}
                    </h2>
                    <div class="flex justify-between items-center text-[10px] font-bold text-slate-500 uppercase tracking-wider pt-2">
                        <span>Target: Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}</span>
                        <span>{{ number_format($percent, 1) }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden">
                        <div class="bg-primary-600 h-full rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
                    </div>
                </div>

                <div class="space-y-3">
                    <a href="{{ route('donations.checkout', $campaign->id) }}" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-widest py-4.5 rounded-2xl shadow-lg shadow-primary-600/10 hover:shadow-primary-600/20 text-center block transition-all duration-300 transform hover:-translate-y-0.5">
                        Berdonasi Sekarang <i class="fa-solid fa-heart ml-2"></i>
                    </a>
                </div>

                <!-- Verified Donor list -->
                <div class="pt-6 border-t border-slate-100 space-y-4">
                    <h4 class="font-montserrat font-black uppercase text-xs text-slate-800 tracking-wider">Donatur Terkini</h4>
                    <div class="space-y-4 max-h-[250px] overflow-y-auto no-scrollbar">
                        @forelse($campaign->donations as $don)
                            <div class="flex items-start space-x-3 text-left">
                                <div class="w-8 h-8 bg-slate-50 border border-slate-100 text-slate-500 rounded-xl flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-user text-xs"></i>
                                </div>
                                <div class="space-y-0.5">
                                    <span class="text-[10px] font-black uppercase text-slate-800 tracking-wide block">{{ $don->donor_name }}</span>
                                    <span class="text-[10px] font-bold text-primary-600 block">Rp {{ number_format($don->amount, 0, ',', '.') }}</span>
                                    @if($don->message)
                                        <p class="text-[10px] text-slate-400 font-semibold italic mt-1 leading-normal">"{{ $don->message }}"</p>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider text-center py-4">Jadilah donatur pertama untuk program ini!</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
