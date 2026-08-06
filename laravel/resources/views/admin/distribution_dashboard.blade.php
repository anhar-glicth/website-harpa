@extends('layouts.app')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-20">

    <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-8 items-start">

        {{-- Sidebar --}}
        <aside class="hidden lg:block space-y-5">
            @include('admin.sidebar', ['active' => 'distribution_dashboard'])
        </aside>

        {{-- Main Content --}}
        <div class="space-y-8">

            {{-- Header --}}
            <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 shadow-sm flex items-center justify-between gap-4">
                <div class="space-y-1">
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Dashboard Penyaluran</h1>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest">Distribusi Management → Dashboard Penyaluran</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 shadow-sm">
                    <i class="fa-solid fa-truck-ramp-box text-lg"></i>
                </div>
            </div>

            {{-- Stat Cards Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                
                {{-- Total Nominal --}}
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-3 relative overflow-hidden group">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-base font-bold">
                        <i class="fa-solid fa-hand-holding-dollar"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Penyaluran</p>
                        <p class="text-xl font-black text-slate-900 tracking-tight mt-0.5">
                            Rp {{ number_format($totalNominal, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="text-[11px] font-bold text-slate-400 flex items-center gap-1">
                        <i class="fa-solid fa-layer-group text-slate-300"></i> Akumulasi Seluruh Distribusi
                    </div>
                </div>

                {{-- Penyaluran Bulan Ini --}}
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-3 relative overflow-hidden group">
                    <div class="w-10 h-10 rounded-2xl bg-primary-50 text-primary-600 flex items-center justify-center text-base font-bold">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Penyaluran Bulan Ini</p>
                        <p class="text-xl font-black text-slate-900 tracking-tight mt-0.5">
                            Rp {{ number_format($thisMonthNominal, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="text-[11px] font-bold text-emerald-600 flex items-center gap-1">
                        <i class="fa-solid fa-arrow-trend-up"></i> {{ now()->translatedFormat('F Y') }}
                    </div>
                </div>

                {{-- Total Penerima Manfaat --}}
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-3 relative overflow-hidden group">
                    <div class="w-10 h-10 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center text-base font-bold">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Penerima Manfaat</p>
                        <p class="text-xl font-black text-slate-900 tracking-tight mt-0.5">
                            {{ number_format($totalBeneficiaries, 0, ',', '.') }} <span class="text-xs font-semibold text-slate-500">PM</span>
                        </p>
                    </div>
                    <div class="text-[11px] font-bold text-slate-400 flex items-center gap-1">
                        <i class="fa-solid fa-heart text-rose-400"></i> Jiwa / Lembaga Penerima
                    </div>
                </div>

                {{-- Total Transaksi --}}
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-3 relative overflow-hidden group">
                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-base font-bold">
                        <i class="fa-solid fa-box-archive"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Laporan Penyaluran</p>
                        <p class="text-xl font-black text-slate-900 tracking-tight mt-0.5">
                            {{ number_format($totalRecords, 0, ',', '.') }} <span class="text-xs font-semibold text-slate-500">Laporan</span>
                        </p>
                    </div>
                    <div class="text-[11px] font-bold text-slate-400 flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> {{ $statusTersalur }} Tersalur / {{ $statusProses }} Proses
                    </div>
                </div>

            </div>

            {{-- Asnaf & Status Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Distribusi per Asnaf --}}
                <div class="lg:col-span-2 bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 shadow-sm space-y-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-base font-black text-slate-900">Distribusi per Asnaf</h3>
                            <p class="text-xs font-semibold text-slate-400">Ringkasan penyaluran berdasarkan kategori asnaf zakat/infaq</p>
                        </div>
                        <div class="w-8 h-8 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-filter"></i>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-100 text-[10px] font-black uppercase tracking-wider text-slate-400">
                                    <th class="pb-3">Kategori Asnaf</th>
                                    <th class="pb-3 text-center">Jumlah Transaksi</th>
                                    <th class="pb-3 text-right">Total Penyaluran</th>
                                </tr>
                            </thead>
                            <tbody class="text-xs font-semibold text-slate-700">
                                @forelse($topAsnaf as $asnaf)
                                    <tr class="border-b border-slate-50 hover:bg-slate-50/60 transition-colors">
                                        <td class="py-3 font-bold text-slate-900">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-[11px]">
                                                <i class="fa-solid fa-tag text-[9px]"></i>
                                                {{ $asnaf->asnaf ?: 'Umum / Tidak Didefinisikan' }}
                                            </span>
                                        </td>
                                        <td class="py-3 text-center font-mono">
                                            {{ number_format($asnaf->total_count, 0, ',', '.') }}
                                        </td>
                                        <td class="py-3 text-right font-black text-slate-900">
                                            Rp {{ number_format($asnaf->total_amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-8 text-center text-slate-400 font-bold uppercase tracking-wider">
                                            Belum ada data distribusi per asnaf.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Status Penyaluran Card --}}
                <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6 flex flex-col justify-between">
                    <div>
                        <h3 class="text-base font-black text-slate-900">Status Penyaluran</h3>
                        <p class="text-xs font-semibold text-slate-400">Status pengerjaan tim distribusi</p>

                        <div class="space-y-4 mt-6">
                            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center text-base">
                                        <i class="fa-solid fa-circle-check"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-black text-emerald-900">Tersalur</p>
                                        <p class="text-[11px] text-emerald-700 font-semibold">Telah sampai ke penerima</p>
                                    </div>
                                </div>
                                <span class="text-2xl font-black text-emerald-700">{{ $statusTersalur }}</span>
                            </div>

                            <div class="p-4 rounded-2xl bg-amber-50 border border-amber-100 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center text-base">
                                        <i class="fa-solid fa-clock-rotate-left"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-black text-amber-900">Dalam Proses</p>
                                        <p class="text-[11px] text-amber-700 font-semibold">Sedang diproses/dikirim</p>
                                    </div>
                                </div>
                                <span class="text-2xl font-black text-amber-700">{{ $statusProses }}</span>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('admin.distributions') }}"
                       class="w-full py-3 bg-slate-900 hover:bg-slate-950 text-white text-center rounded-2xl text-xs font-bold transition-all shadow-sm block">
                        <i class="fa-solid fa-plus-circle text-xs mr-1"></i> Input / Kelola Penyaluran
                    </a>
                </div>

            </div>

            {{-- Recent Distributions Table Card --}}
            <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-black text-slate-900">Penyaluran Bantuan Terbaru</h3>
                        <p class="text-xs font-semibold text-slate-400">8 riwayat laporan penyaluran bantuan terakhir</p>
                    </div>
                    <a href="{{ route('admin.distributions') }}" class="text-xs font-black text-primary-600 hover:text-primary-700 transition-all flex items-center gap-1">
                        Lihat Semua List <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-[10px] font-black uppercase tracking-wider text-slate-400">
                                <th class="pb-4">Program</th>
                                <th class="pb-4">Penerima Manfaat</th>
                                <th class="pb-4">Jumlah (Rp)</th>
                                <th class="pb-4">Asnaf</th>
                                <th class="pb-4">Tanggal</th>
                                <th class="pb-4 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm font-semibold text-slate-700">
                            @forelse($recentDistributions as $item)
                                <tr class="border-b border-slate-50 hover:bg-slate-50/60 transition-colors">
                                    <td class="py-4 font-bold text-slate-900 max-w-[220px] truncate">
                                        {{ $item->campaign ? $item->campaign->title : 'Program Umum' }}
                                    </td>
                                    <td class="py-4 text-slate-800">
                                        {{ $item->recipient_name }}
                                    </td>
                                    <td class="py-4 font-black text-emerald-600">
                                        Rp {{ number_format($item->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="py-4">
                                        <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 text-xs font-bold">
                                            {{ $item->asnaf ?: '-' }}
                                        </span>
                                    </td>
                                    <td class="py-4 text-xs text-slate-500">
                                        {{ $item->distribution_date ? $item->distribution_date->format('d M Y') : '-' }}
                                    </td>
                                    <td class="py-4 text-center">
                                        @if($item->status === 'Tersalur')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-[11px] font-black">
                                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Tersalur
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-amber-50 text-amber-700 text-[11px] font-black">
                                                <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span> Proses
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-14 text-center text-slate-400 font-bold uppercase tracking-wider">
                                        <i class="fa-solid fa-folder-open text-3xl block text-slate-300 mb-3"></i>
                                        Belum ada riwayat penyaluran.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>{{-- end main --}}

    </div>{{-- end grid --}}

</section>
@endsection
