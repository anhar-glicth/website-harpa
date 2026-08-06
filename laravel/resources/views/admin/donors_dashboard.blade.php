@extends('layouts.app')

@section('content')
<div class="container max-w-7xl mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-8 items-start">

        {{-- Sidebar --}}
        <aside class="hidden lg:block space-y-5">
            @include('admin.sidebar', ['active' => 'donors_dashboard'])
        </aside>

        {{-- Main Content --}}
        <div class="space-y-6">

            {{-- Header Card --}}
            <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 shadow-sm flex items-center justify-between gap-4">
                <div class="space-y-1">
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Dashboard Donatur</h1>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest">Transaction Management → Analytics & Top Donatur</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 shadow-sm">
                    <i class="fa-solid fa-chart-column text-lg"></i>
                </div>
            </div>

            {{-- Top KPI Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-2">
                    <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">Total Donatur Terdaftar</p>
                    <p class="text-2xl font-black text-emerald-600">{{ number_format($totalDonors) }}</p>
                </div>
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-2">
                    <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">Donatur Laki-Laki</p>
                    <p class="text-2xl font-black text-sky-600">{{ number_format($maleDonors) }}</p>
                </div>
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-2">
                    <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">Donatur Perempuan</p>
                    <p class="text-2xl font-black text-rose-600">{{ number_format($femaleDonors) }}</p>
                </div>
            </div>

            {{-- Top 10 Donors --}}
            <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 shadow-sm space-y-4">
                <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider">Top 10 Donatur Terbesar</h3>
                <div class="divide-y divide-slate-100">
                    @foreach($topDonors as $index => $donor)
                        <div class="py-3 flex items-center justify-between text-xs font-semibold text-slate-700">
                            <div class="flex items-center gap-3">
                                <span class="w-6 h-6 rounded-full bg-slate-100 text-slate-500 font-mono flex items-center justify-center text-[10px] font-bold">{{ $index + 1 }}</span>
                                <div>
                                    <p class="font-bold text-slate-900">{{ $donor->name }}</p>
                                    <p class="text-[10px] text-slate-400">{{ $donor->email ?? '-' }}</p>
                                </div>
                            </div>
                            <p class="font-black text-emerald-600">Rp {{ number_format($donor->donations_sum_amount ?? 0, 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
