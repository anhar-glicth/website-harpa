@extends('layouts.app')

@section('content')
<div class="container max-w-7xl mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-8 items-start">

        {{-- Sidebar --}}
        <aside class="hidden lg:block space-y-5">
            @include('admin.sidebar', ['active' => 'donors_list'])
        </aside>

        {{-- Main Content --}}
        <div class="space-y-6">

            {{-- Header Card --}}
            <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 shadow-sm flex items-center justify-between gap-4">
                <div class="space-y-1">
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">List Donatur</h1>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest">Transaction Management → Data Seluruh Donatur</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 shadow-sm">
                    <i class="fa-solid fa-users text-lg"></i>
                </div>
            </div>

            {{-- Search Bar & Actions --}}
            <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
                <form action="{{ route('admin.donors_list') }}" method="GET" class="w-full md:w-auto flex-1 flex gap-3">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, atau no handphone donatur..." 
                           class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-3 text-xs font-semibold text-slate-700 focus:outline-none focus:border-emerald-500">
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-6 py-3 rounded-2xl text-xs flex items-center gap-2 transition-all">
                        <i class="fa-solid fa-magnifying-glass"></i> Cari
                    </button>
                </form>
            </div>

            {{-- Donors Table --}}
            <div class="bg-white border border-slate-100 rounded-3xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100 text-[10px] font-black uppercase tracking-wider text-slate-500">
                                <th class="py-4 px-6">No</th>
                                <th class="py-4 px-6">Nama Donatur</th>
                                <th class="py-4 px-6">Email / Kontak</th>
                                <th class="py-4 px-6 text-center">Jumlah Donasi</th>
                                <th class="py-4 px-6 text-right">Total Akumulasi Nominal</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs font-semibold text-slate-700 divide-y divide-slate-100">
                            @forelse($donors as $index => $donor)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="py-4 px-6 font-mono text-slate-400">{{ $donors->firstItem() + $index }}</td>
                                    <td class="py-4 px-6 font-bold text-slate-900">{{ $donor->name }}</td>
                                    <td class="py-4 px-6 text-slate-500">
                                        <p>{{ $donor->email ?? '-' }}</p>
                                        <p class="text-[10px] text-slate-400 font-mono">{{ $donor->phone ?? '-' }}</p>
                                    </td>
                                    <td class="py-4 px-6 text-center font-mono font-bold text-slate-900">{{ number_format($donor->donations_count) }}x</td>
                                    <td class="py-4 px-6 text-right font-black text-emerald-600">Rp {{ number_format($donor->donations_sum_amount ?? 0, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-400 text-xs font-bold">Belum ada data donatur.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($donors->hasPages())
                    <div class="p-6 border-t border-slate-100">
                        {{ $donors->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection
