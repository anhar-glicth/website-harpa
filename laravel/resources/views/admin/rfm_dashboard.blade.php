@extends('layouts.app')

@section('content')
<div class="container max-w-7xl mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-8 items-start">

        {{-- Sidebar --}}
        <aside class="hidden lg:block space-y-5">
            @include('admin.sidebar', ['active' => 'rfm_dashboard'])
        </aside>

        {{-- Main Content --}}
        <div class="space-y-6">

            {{-- Header Card --}}
            <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 shadow-sm flex items-center justify-between gap-4">
                <div class="space-y-1">
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Dashboard RFM</h1>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest">Transaction Management → Recency, Frequency, Monetary Analytics</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 shadow-sm">
                    <i class="fa-solid fa-layer-group text-lg"></i>
                </div>
            </div>

            {{-- RFM Segments Summary Card --}}
            <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 shadow-sm space-y-4">
                <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider">Segmentasi Donatur Berdasarkan Model RFM</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($rfmSegmentCounts as $segment)
                        <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-700">{{ $segment->rfm_alltime ?? 'Unsegmented' }}</span>
                            <span class="text-sm font-black text-emerald-600 font-mono">{{ number_format($segment->count) }} Donatur</span>
                        </div>
                    @empty
                        <div class="col-span-2 py-4 text-center text-slate-400 text-xs font-bold">
                            Segmentasi RFM sedang diproses untuk data donatur.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
