@extends('layouts.app')

@section('content')
<div class="container max-w-7xl mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-8 items-start">

        {{-- Sidebar --}}
        <aside class="hidden lg:block space-y-5">
            @include('admin.sidebar', ['active' => 'prospects_dashboard'])
        </aside>

        {{-- Main Content --}}
        <div class="space-y-6">

            {{-- Header Card --}}
            <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 shadow-sm flex items-center justify-between gap-4">
                <div class="space-y-1">
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Dashboard Prospek</h1>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest">Transaction Management → Analytics Status Prospek</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 shadow-sm">
                    <i class="fa-solid fa-chart-line text-lg"></i>
                </div>
            </div>

            {{-- Top KPI Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-2">
                    <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">Total Prospek Terdaftar</p>
                    <p class="text-2xl font-black text-slate-900">{{ number_format($totalProspects) }}</p>
                </div>
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-2">
                    <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">Konversi Tertarik</p>
                    <p class="text-2xl font-black text-emerald-600">{{ number_format($convertedProspects) }}</p>
                </div>
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-2">
                    <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">Status Follow Up</p>
                    <p class="text-2xl font-black text-amber-500">{{ number_format($followUpProspects) }}</p>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
