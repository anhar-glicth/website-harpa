@extends('layouts.app')

@section('content')
<div class="container max-w-7xl mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-8 items-start">

        {{-- Sidebar --}}
        <aside class="hidden lg:block space-y-5">
            @include('admin.sidebar', ['active' => 'transactions_list'])
        </aside>

        {{-- Main Content --}}
        <div class="space-y-6">

            {{-- Header Card --}}
            <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 shadow-sm flex items-center justify-between gap-4">
                <div class="space-y-1">
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">List Transaksi</h1>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest">Transaction Management → Data Transaksi Donasi</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 shadow-sm">
                    <i class="fa-solid fa-receipt text-lg"></i>
                </div>
            </div>

            {{-- Info Summary Banner --}}
            <div class="bg-white border border-emerald-100 rounded-3xl p-6 shadow-sm flex flex-wrap items-center justify-around gap-4 text-center">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">Total Transaksi Success</p>
                    <p class="text-2xl font-black text-emerald-600">Rp {{ number_format($totalTransactionAmount, 0, ',', '.') }}</p>
                </div>
                <div class="h-8 w-px bg-slate-200 hidden sm:block"></div>
                <div>
                    <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">Jumlah Transaksi Terdaftar</p>
                    <p class="text-2xl font-black text-slate-900">{{ number_format($donations->total()) }} Record</p>
                </div>
            </div>

            {{-- Transactions Table --}}
            <div class="bg-white border border-slate-100 rounded-3xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100 text-[10px] font-black uppercase tracking-wider text-slate-500">
                                <th class="py-4 px-6">ID / Tgl Transaksi</th>
                                <th class="py-4 px-6">Donatur</th>
                                <th class="py-4 px-6">Kampanye / Program</th>
                                <th class="py-4 px-6 text-right">Nominal</th>
                                <th class="py-4 px-6 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs font-semibold text-slate-700 divide-y divide-slate-100">
                            @forelse($donations as $donation)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="py-4 px-6 font-mono">
                                        <p class="font-bold text-slate-900">{{ $donation->transaction_id ?? ('TRX-' . $donation->id) }}</p>
                                        <p class="text-[10px] text-slate-400">{{ $donation->donation_date ? $donation->donation_date->format('Y-m-d H:i') : '' }}</p>
                                    </td>
                                    <td class="py-4 px-6">
                                        <p class="font-bold text-slate-900">{{ $donation->donor_name ?? 'Hamba Allah' }}</p>
                                        <p class="text-[10px] text-slate-400">{{ $donation->donor_email ?? '-' }}</p>
                                    </td>
                                    <td class="py-4 px-6 text-slate-800">
                                        {{ $donation->campaign ? $donation->campaign->title : 'Program Umum' }}
                                    </td>
                                    <td class="py-4 px-6 text-right font-black text-slate-900">Rp {{ number_format($donation->amount, 0, ',', '.') }}</td>
                                    <td class="py-4 px-6 text-center">
                                        @if($donation->payment_status === 'Success')
                                            <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-black uppercase">Success</span>
                                        @else
                                            <span class="px-3 py-1 bg-amber-50 text-amber-600 rounded-full text-[10px] font-black uppercase">{{ $donation->payment_status }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-400 text-xs font-bold">Belum ada data transaksi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($donations->hasPages())
                    <div class="p-6 border-t border-slate-100">
                        {{ $donations->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection
