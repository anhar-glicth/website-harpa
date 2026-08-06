@extends('layouts.app')

@section('content')
<div class="container max-w-7xl mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-8 items-start">

        {{-- Sidebar --}}
        <aside class="hidden lg:block space-y-5">
            @include('admin.sidebar', ['active' => 'transactions_dashboard'])
        </aside>

        {{-- Main Content --}}
        <div class="space-y-6">

            {{-- Header Card --}}
            <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 shadow-sm flex items-center justify-between gap-4">
                <div class="space-y-1">
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Dashboard Transaksi</h1>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest">Transaction Management → Analytics & Grafik Transaksi</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 shadow-sm">
                    <i class="fa-solid fa-chart-pie text-lg"></i>
                </div>
            </div>

            {{-- Top KPI Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-2">
                    <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">Total Nominal Berhasil</p>
                    <p class="text-2xl font-black text-emerald-600">Rp {{ number_format($totalAmount, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-2">
                    <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">Transaksi Berhasil</p>
                    <p class="text-2xl font-black text-slate-900">{{ number_format($successCount) }}</p>
                </div>
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-2">
                    <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">Transaksi Pending</p>
                    <p class="text-2xl font-black text-amber-500">{{ number_format($pendingCount) }}</p>
                </div>
            </div>

            {{-- Recent Transactions List --}}
            <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 shadow-sm space-y-4">
                <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider">Transaksi Terbaru</h3>
                <div class="divide-y divide-slate-100">
                    @foreach($recentTransactions as $trx)
                        <div class="py-3 flex items-center justify-between text-xs font-semibold text-slate-700">
                            <div>
                                <p class="font-bold text-slate-900">{{ $trx->donor_name ?? 'Hamba Allah' }}</p>
                                <p class="text-slate-400 text-[10px]">{{ $trx->campaign ? $trx->campaign->title : 'Program Umum' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-black text-emerald-600">Rp {{ number_format($trx->amount, 0, ',', '.') }}</p>
                                <p class="text-[10px] text-slate-400">{{ $trx->payment_status }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
