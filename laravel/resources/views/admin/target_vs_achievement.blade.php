@extends('layouts.app')

@section('content')
<div class="container max-w-7xl mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-8 items-start">

        {{-- Sidebar --}}
        <aside class="hidden lg:block space-y-5">
            @include('admin.sidebar', ['active' => 'target_vs_achievement'])
        </aside>

        {{-- Main Content --}}
        <div class="space-y-6">

            {{-- Header Card --}}
            <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 shadow-sm flex items-center justify-between gap-4">
                <div class="space-y-1">
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Dashboard Target Vs Capaian</h1>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest">Transaction Management → Perbandingan Target & Realisasi</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 shadow-sm">
                    <i class="fa-solid fa-bullseye text-lg"></i>
                </div>
            </div>

            {{-- Top KPI Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-2">
                    <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">Total Nominal Target</p>
                    <p class="text-2xl font-black text-slate-900">Rp {{ number_format($totalTarget, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-2">
                    <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">Total Capaian Donasi</p>
                    <p class="text-2xl font-black text-emerald-600">Rp {{ number_format($totalAchievement, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-2">
                    <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">Persentase Capaian Target</p>
                    <p class="text-2xl font-black text-sky-600">{{ $achievementPct }}%</p>
                </div>
            </div>

            {{-- Progress Bar Card --}}
            <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 shadow-sm space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-black uppercase tracking-wider text-slate-700">Progres Capaian Terhadap Target</span>
                    <span class="text-xs font-mono font-bold text-emerald-600">{{ $achievementPct }}%</span>
                </div>
                <div class="w-full bg-slate-100 h-4 rounded-full overflow-hidden">
                    <div class="bg-emerald-600 h-full transition-all duration-500 rounded-full" style="width: {{ min(100, $achievementPct) }}%"></div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
