@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50/60 pb-20">
    <!-- Top Header Bar -->
    <div class="bg-white border-b border-slate-200/80 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-widest">
                    <span>Sistem Data Harpa</span>
                    <span>/</span>
                    <span class="text-primary-600 font-black">Alur C: Rekap & Waterfall Keputusan</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-montserrat font-black text-slate-900 tracking-tight mt-1 flex items-center gap-2">
                    <i class="fa-solid fa-filter-circle-dollar text-[#f7ad1a]"></i> Rekap Segmen Per Nomor
                </h1>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.harpa.daftar_kirim') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-wider shadow-md transition-all">
                    <i class="fa-solid fa-list-check"></i>
                    Buka Daftar Kirim CRM
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 space-y-8">
        <!-- KARTU RINGKASAN SEGMEN WATERFALL -->
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-3">
            <!-- 1. AKTIF Donatur -->
            <a href="{{ route('admin.harpa.rekap', ['segmen' => 'AKTIF Donatur']) }}" class="p-4 rounded-2xl border transition-all {{ $filterSegmen === 'AKTIF Donatur' ? 'bg-primary-600 text-white ring-2 ring-primary-600' : 'bg-white hover:bg-primary-50/50 text-slate-800 border-slate-200' }}">
                <span class="text-[9px] font-black uppercase tracking-wider block opacity-75">AKTIF Donatur</span>
                <span class="text-xl font-montserrat font-black mt-1 block">{{ number_format($counts['AKTIF Donatur'] ?? 0) }}</span>
                <span class="text-[9px] font-semibold opacity-75">Kirim Tiap Jumat</span>
            </a>

            <!-- 2. AKTIF Pembaca -->
            <a href="{{ route('admin.harpa.rekap', ['segmen' => 'AKTIF Pembaca']) }}" class="p-4 rounded-2xl border transition-all {{ $filterSegmen === 'AKTIF Pembaca' ? 'bg-sky-600 text-white ring-2 ring-sky-600' : 'bg-white hover:bg-sky-50/50 text-slate-800 border-slate-200' }}">
                <span class="text-[9px] font-black uppercase tracking-wider block opacity-75">AKTIF Pembaca</span>
                <span class="text-xl font-montserrat font-black mt-1 block">{{ number_format($counts['AKTIF Pembaca'] ?? 0) }}</span>
                <span class="text-[9px] font-semibold opacity-75">Kirim Saat Payday</span>
            </a>

            <!-- 3. PANTAU -->
            <a href="{{ route('admin.harpa.rekap', ['segmen' => 'PANTAU Data Belum Cukup']) }}" class="p-4 rounded-2xl border transition-all {{ $filterSegmen === 'PANTAU Data Belum Cukup' ? 'bg-amber-500 text-white ring-2 ring-amber-500' : 'bg-white hover:bg-amber-50/50 text-slate-800 border-slate-200' }}">
                <span class="text-[9px] font-black uppercase tracking-wider block opacity-75">PANTAU</span>
                <span class="text-xl font-montserrat font-black mt-1 block">{{ number_format($counts['PANTAU Data Belum Cukup'] ?? 0) }}</span>
                <span class="text-[9px] font-semibold opacity-75">Kirim s.d Cukup</span>
            </a>

            <!-- 4. ARSIP Bermasalah -->
            <a href="{{ route('admin.harpa.rekap', ['segmen' => 'ARSIP Nomor Bermasalah']) }}" class="p-4 rounded-2xl border transition-all {{ $filterSegmen === 'ARSIP Nomor Bermasalah' ? 'bg-rose-600 text-white ring-2 ring-rose-600' : 'bg-white hover:bg-rose-50/50 text-slate-800 border-slate-200' }}">
                <span class="text-[9px] font-black uppercase tracking-wider block opacity-75">Bermasalah</span>
                <span class="text-xl font-montserrat font-black mt-1 block">{{ number_format($counts['ARSIP Nomor Bermasalah'] ?? 0) }}</span>
                <span class="text-[9px] font-semibold opacity-75">Gagal &ge; 2x</span>
            </a>

            <!-- 5. ARSIP Tdk Baca -->
            <a href="{{ route('admin.harpa.rekap', ['segmen' => 'ARSIP Tidak Pernah Baca']) }}" class="p-4 rounded-2xl border transition-all {{ $filterSegmen === 'ARSIP Tidak Pernah Baca' ? 'bg-slate-700 text-white ring-2 ring-slate-700' : 'bg-white hover:bg-slate-100 text-slate-800 border-slate-200' }}">
                <span class="text-[9px] font-black uppercase tracking-wider block opacity-75">Tdk Baca</span>
                <span class="text-xl font-montserrat font-black mt-1 block">{{ number_format($counts['ARSIP Tidak Pernah Baca'] ?? 0) }}</span>
                <span class="text-[9px] font-semibold opacity-75">&ge; 3x read=0</span>
            </a>

            <!-- 6. ARSIP Berhenti Baca -->
            <a href="{{ route('admin.harpa.rekap', ['segmen' => 'ARSIP Berhenti Baca']) }}" class="p-4 rounded-2xl border transition-all {{ $filterSegmen === 'ARSIP Berhenti Baca' ? 'bg-purple-700 text-white ring-2 ring-purple-700' : 'bg-white hover:bg-purple-50 text-slate-800 border-slate-200' }}">
                <span class="text-[9px] font-black uppercase tracking-wider block opacity-75">Berhenti</span>
                <span class="text-xl font-montserrat font-black mt-1 block">{{ number_format($counts['ARSIP Berhenti Baca'] ?? 0) }}</span>
                <span class="text-[9px] font-semibold opacity-75">&ge; 3x pasca-read</span>
            </a>

            <!-- 7. ARSIP Tidak Valid -->
            <a href="{{ route('admin.harpa.rekap', ['segmen' => 'ARSIP Nomor Tidak Valid']) }}" class="p-4 rounded-2xl border transition-all {{ $filterSegmen === 'ARSIP Nomor Tidak Valid' ? 'bg-zinc-800 text-white ring-2 ring-zinc-800' : 'bg-white hover:bg-zinc-100 text-slate-800 border-slate-200' }}">
                <span class="text-[9px] font-black uppercase tracking-wider block opacity-75">Tidak Valid</span>
                <span class="text-xl font-montserrat font-black mt-1 block">{{ number_format($counts['ARSIP Nomor Tidak Valid'] ?? 0) }}</span>
                <span class="text-[9px] font-semibold opacity-75">Bukan 628</span>
            </a>
        </div>

        <!-- TABEL DATA SEGMEN PER NOMOR -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-100">
                <div>
                    <h3 class="text-sm font-montserrat font-black uppercase tracking-wider text-slate-800 flex items-center gap-2">
                        <span>Hasil Waterfall Keputusan</span>
                        @if($filterSegmen)
                            <span class="px-2 py-0.5 rounded-lg text-[10px] font-bold bg-primary-100 text-primary-800">
                                Filter: {{ $filterSegmen }}
                            </span>
                        @endif
                    </h3>
                    <p class="text-xs text-slate-400 font-medium">Menampilkan {{ count($segments) }} nomor/orang berdasarkan aturan waterfall Harpa</p>
                </div>

                <div class="flex items-center gap-2">
                    @if($filterSegmen)
                        <a href="{{ route('admin.harpa.rekap') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 rounded-xl text-xs font-bold text-slate-600 transition">
                            Reset Filter
                        </a>
                    @endif
                    <form method="GET" action="{{ route('admin.harpa.rekap') }}" class="flex items-center gap-2">
                        @if($filterSegmen)
                            <input type="hidden" name="segmen" value="{{ $filterSegmen }}">
                        @endif
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari No HP..." class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-medium text-slate-700 outline-none focus:bg-white focus:ring-2 focus:ring-primary-500">
                        <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-slate-800 transition">
                            Cari
                        </button>
                    </form>
                </div>
            </div>

            @if(empty($segments))
                <div class="text-center py-12 text-slate-400">
                    <i class="fa-regular fa-folder-open text-3xl mb-2"></i>
                    <p class="text-xs font-bold">Tidak ada data nomor yang cocok.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-[10px] font-black uppercase tracking-wider text-slate-500 border-b border-slate-100">
                            <tr>
                                <th class="py-3 px-4">Nomor HP</th>
                                <th class="py-3 px-4">Donatur ID / Nama</th>
                                <th class="py-3 px-4">Segmen Waterfall</th>
                                <th class="py-3 px-4">Jadwal Kirim</th>
                                <th class="py-3 px-4">Riwayat Blast</th>
                                <th class="py-3 px-4">Donasi</th>
                                <th class="py-3 px-4">Status WA</th>
                                <th class="py-3 px-4 min-w-[250px]">Alasan Keputusan Sistem</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($segments as $s)
                                <tr class="hover:bg-slate-50/50">
                                    <td class="py-3 px-4 font-mono font-bold text-slate-900">{{ $s['no_hp'] }}</td>
                                    <td class="py-3 px-4">
                                        <span class="font-mono text-[10px] text-primary-600 font-bold block">{{ $s['donatur_id'] }}</span>
                                        <span class="text-slate-800 font-semibold truncate max-w-[150px] block">{{ $s['nama'] }}</span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="px-2 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider inline-block
                                            {{ $s['segmen'] === 'AKTIF Donatur' ? 'bg-primary-100 text-primary-800' : '' }}
                                            {{ $s['segmen'] === 'AKTIF Pembaca' ? 'bg-sky-100 text-sky-800' : '' }}
                                            {{ $s['segmen'] === 'PANTAU Data Belum Cukup' ? 'bg-amber-100 text-amber-800' : '' }}
                                            {{ $s['segmen'] === 'ARSIP Nomor Bermasalah' ? 'bg-rose-100 text-rose-800' : '' }}
                                            {{ $s['segmen'] === 'ARSIP Tidak Pernah Baca' ? 'bg-slate-200 text-slate-800' : '' }}
                                            {{ $s['segmen'] === 'ARSIP Berhenti Baca' ? 'bg-purple-100 text-purple-800' : '' }}
                                            {{ $s['segmen'] === 'ARSIP Nomor Tidak Valid' ? 'bg-zinc-200 text-zinc-800' : '' }}
                                        ">
                                            {{ $s['segmen'] }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 font-bold text-[10px] {{ str_contains($s['jadwal'], 'CEK') ? 'text-amber-600' : (str_contains($s['jadwal'], 'BERHENTI') ? 'text-slate-400' : 'text-emerald-700') }}">
                                        {{ $s['jadwal'] }}
                                    </td>
                                    <td class="py-3 px-4 text-[10px] text-slate-600 space-y-0.5">
                                        <div>Total: <strong>{{ $s['total_blast'] }}x</strong></div>
                                        <div class="text-[9px] text-slate-400">R:{{ $s['read'] }} | D:{{ $s['delivered'] }} | F:{{ $s['failed'] }}</div>
                                    </td>
                                    <td class="py-3 px-4 text-[10px]">
                                        @if($s['pernah_donasi'])
                                            <span class="font-bold text-emerald-700 block">Rp {{ number_format($s['total_donasi'], 0, ',', '.') }}</span>
                                            <span class="text-[9px] text-slate-400">({{ $s['donasi_count'] }} transaksi)</span>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="px-2 py-0.5 rounded-md text-[9px] font-bold uppercase tracking-wider {{ $s['wa_aktif'] ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                            {{ $s['wa_aktif'] ? 'Aktif' : 'Mati' }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-[11px] text-slate-600 leading-snug">
                                        {{ $s['alasan'] }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

