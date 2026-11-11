@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50/60 pb-20">
    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-8 items-start min-w-0">
            <!-- Sidebar Menu -->
            <aside class="hidden lg:block space-y-6 shrink-0 w-[280px]">
                @include('admin.sidebar', ['active' => 'harpa_rekap'])
            </aside>

            <!-- Main Content Area -->
            <div class="space-y-6 min-w-0">
                <!-- Top Header Bar -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-600 uppercase tracking-widest">
                            <span>Sistem Data Harpa</span>
                            <span aria-hidden="true">/</span>
                            <span class="text-primary-600 font-black">Alur C: Rekap & Waterfall Keputusan</span>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-montserrat font-black text-slate-900 tracking-tight mt-1 flex items-center gap-2">
                            <i class="fa-solid fa-filter-circle-dollar text-[#f7ad1a]" aria-hidden="true"></i> Rekap Segmen Per Nomor
                        </h1>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.harpa.daftar_kirim') }}" class="inline-flex items-center min-h-[44px] gap-2 px-5 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-wider shadow-md transition-colors">
                            <i class="fa-solid fa-list-check" aria-hidden="true"></i>
                            Buka Daftar Kirim CRM
                        </a>
                    </div>
                </div>
        <!-- KARTU RINGKASAN SEGMEN WATERFALL -->
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-3">
            <!-- 1. AKTIF Donatur -->
            <a href="{{ route('admin.harpa.rekap', ['segmen' => 'AKTIF Donatur']) }}" class="p-4 rounded-2xl border transition-colors {{ $filterSegmen === 'AKTIF Donatur' ? 'bg-[#063f5c] text-white border-[#063f5c] ring-2 ring-[#063f5c]' : 'bg-white hover:bg-slate-50 text-slate-800 border-slate-200' }}">
                <span class="text-xs font-black uppercase tracking-wider block {{ $filterSegmen === 'AKTIF Donatur' ? 'text-slate-100' : 'text-slate-600' }}">AKTIF Donatur</span>
                <span class="text-xl font-montserrat font-black mt-1 block">{{ number_format($counts['AKTIF Donatur'] ?? 0) }}</span>
                <span class="text-xs font-semibold {{ $filterSegmen === 'AKTIF Donatur' ? 'text-slate-200' : 'text-slate-600' }}">Kirim Tiap Jumat</span>
            </a>

            <!-- 2. AKTIF Pembaca -->
            <a href="{{ route('admin.harpa.rekap', ['segmen' => 'AKTIF Pembaca']) }}" class="p-4 rounded-2xl border transition-colors {{ $filterSegmen === 'AKTIF Pembaca' ? 'bg-[#063f5c] text-white border-[#063f5c] ring-2 ring-[#063f5c]' : 'bg-white hover:bg-slate-50 text-slate-800 border-slate-200' }}">
                <span class="text-xs font-black uppercase tracking-wider block {{ $filterSegmen === 'AKTIF Pembaca' ? 'text-slate-100' : 'text-slate-600' }}">AKTIF Pembaca</span>
                <span class="text-xl font-montserrat font-black mt-1 block">{{ number_format($counts['AKTIF Pembaca'] ?? 0) }}</span>
                <span class="text-xs font-semibold {{ $filterSegmen === 'AKTIF Pembaca' ? 'text-slate-200' : 'text-slate-600' }}">Kirim Saat Payday</span>
            </a>

            <!-- 3. PANTAU -->
            <a href="{{ route('admin.harpa.rekap', ['segmen' => 'PANTAU Data Belum Cukup']) }}" class="p-4 rounded-2xl border transition-colors {{ $filterSegmen === 'PANTAU Data Belum Cukup' ? 'bg-[#063f5c] text-white border-[#063f5c] ring-2 ring-[#063f5c]' : 'bg-white hover:bg-slate-50 text-slate-800 border-slate-200' }}">
                <span class="text-xs font-black uppercase tracking-wider block {{ $filterSegmen === 'PANTAU Data Belum Cukup' ? 'text-slate-100' : 'text-slate-600' }}">PANTAU</span>
                <span class="text-xl font-montserrat font-black mt-1 block">{{ number_format($counts['PANTAU Data Belum Cukup'] ?? 0) }}</span>
                <span class="text-xs font-semibold {{ $filterSegmen === 'PANTAU Data Belum Cukup' ? 'text-slate-200' : 'text-slate-600' }}">Kirim s.d Cukup</span>
            </a>

            <!-- 4. ARSIP Bermasalah -->
            <a href="{{ route('admin.harpa.rekap', ['segmen' => 'ARSIP Nomor Bermasalah']) }}" class="p-4 rounded-2xl border transition-colors {{ $filterSegmen === 'ARSIP Nomor Bermasalah' ? 'bg-[#063f5c] text-white border-[#063f5c] ring-2 ring-[#063f5c]' : 'bg-white hover:bg-slate-50 text-slate-800 border-slate-200' }}">
                <span class="text-xs font-black uppercase tracking-wider block {{ $filterSegmen === 'ARSIP Nomor Bermasalah' ? 'text-slate-100' : 'text-slate-600' }}">Bermasalah</span>
                <span class="text-xl font-montserrat font-black mt-1 block">{{ number_format($counts['ARSIP Nomor Bermasalah'] ?? 0) }}</span>
                <span class="text-xs font-semibold {{ $filterSegmen === 'ARSIP Nomor Bermasalah' ? 'text-slate-200' : 'text-slate-600' }}">Gagal &ge; 2x</span>
            </a>

            <!-- 5. ARSIP Tdk Baca -->
            <a href="{{ route('admin.harpa.rekap', ['segmen' => 'ARSIP Tidak Pernah Baca']) }}" class="p-4 rounded-2xl border transition-colors {{ $filterSegmen === 'ARSIP Tidak Pernah Baca' ? 'bg-[#063f5c] text-white border-[#063f5c] ring-2 ring-[#063f5c]' : 'bg-white hover:bg-slate-50 text-slate-800 border-slate-200' }}">
                <span class="text-xs font-black uppercase tracking-wider block {{ $filterSegmen === 'ARSIP Tidak Pernah Baca' ? 'text-slate-100' : 'text-slate-600' }}">Tdk Baca</span>
                <span class="text-xl font-montserrat font-black mt-1 block">{{ number_format($counts['ARSIP Tidak Pernah Baca'] ?? 0) }}</span>
                <span class="text-xs font-semibold {{ $filterSegmen === 'ARSIP Tidak Pernah Baca' ? 'text-slate-200' : 'text-slate-600' }}">&ge; 3x read=0</span>
            </a>

            <!-- 6. ARSIP Berhenti Baca -->
            <a href="{{ route('admin.harpa.rekap', ['segmen' => 'ARSIP Berhenti Baca']) }}" class="p-4 rounded-2xl border transition-colors {{ $filterSegmen === 'ARSIP Berhenti Baca' ? 'bg-[#063f5c] text-white border-[#063f5c] ring-2 ring-[#063f5c]' : 'bg-white hover:bg-slate-50 text-slate-800 border-slate-200' }}">
                <span class="text-xs font-black uppercase tracking-wider block {{ $filterSegmen === 'ARSIP Berhenti Baca' ? 'text-slate-100' : 'text-slate-600' }}">Berhenti</span>
                <span class="text-xl font-montserrat font-black mt-1 block">{{ number_format($counts['ARSIP Berhenti Baca'] ?? 0) }}</span>
                <span class="text-xs font-semibold {{ $filterSegmen === 'ARSIP Berhenti Baca' ? 'text-slate-200' : 'text-slate-600' }}">&ge; 3x pasca-read</span>
            </a>

            <!-- 7. ARSIP Tidak Valid -->
            <a href="{{ route('admin.harpa.rekap', ['segmen' => 'ARSIP Nomor Tidak Valid']) }}" class="p-4 rounded-2xl border transition-colors {{ $filterSegmen === 'ARSIP Nomor Tidak Valid' ? 'bg-[#063f5c] text-white border-[#063f5c] ring-2 ring-[#063f5c]' : 'bg-white hover:bg-slate-50 text-slate-800 border-slate-200' }}">
                <span class="text-[11px] font-black uppercase tracking-wider block {{ $filterSegmen === 'ARSIP Nomor Tidak Valid' ? 'text-zinc-200' : 'text-slate-600' }}">Tidak Valid</span>
                <span class="text-xl font-montserrat font-black mt-1 block">{{ number_format($counts['ARSIP Nomor Tidak Valid'] ?? 0) }}</span>
                <span class="text-[11px] font-semibold {{ $filterSegmen === 'ARSIP Nomor Tidak Valid' ? 'text-zinc-200' : 'text-slate-600' }}">Bukan 628</span>
            </a>
        </div>

        <!-- TABEL DATA SEGMEN PER NOMOR -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
                <div>
                    <h2 class="text-sm font-montserrat font-black uppercase tracking-wider text-slate-800 flex items-center gap-2">
                        <span>Hasil Waterfall Keputusan</span>
                        @if($filterSegmen)
                            <span class="px-2 py-0.5 rounded-lg text-xs font-bold bg-primary-100 text-primary-800">
                                Filter: {{ $filterSegmen }}
                            </span>
                        @endif
                    </h2>
                    <p class="text-xs text-slate-600 font-medium mt-0.5">Menampilkan {{ count($segments) }} nomor/orang berdasarkan aturan waterfall Harpa</p>
                </div>

                <div class="flex items-center gap-2">
                    @if($filterSegmen)
                        <a href="{{ route('admin.harpa.rekap') }}" class="inline-flex items-center min-h-[44px] px-4 py-2 bg-slate-100 hover:bg-slate-200 rounded-xl text-xs font-bold text-slate-700 transition">
                            Reset Filter
                        </a>
                    @endif
                    <form method="GET" action="{{ route('admin.harpa.rekap') }}" class="flex items-center gap-2">
                        @if($filterSegmen)
                            <input type="hidden" name="segmen" value="{{ $filterSegmen }}">
                        @endif
                        <input type="text" name="search" value="{{ $search }}" aria-label="Cari nomor HP" placeholder="Cari No HP..." class="min-h-[44px] bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2 text-xs font-medium text-slate-700 placeholder:text-slate-500 outline-none focus:bg-white focus:ring-2 focus:ring-primary-600">
                        <button type="submit" class="min-h-[44px] px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-slate-800 transition">
                            Cari
                        </button>
                    </form>
                </div>
            </div>

            @if(empty($segments))
                <div class="text-center py-12 text-slate-600">
                    <i class="fa-regular fa-folder-open text-3xl mb-2 text-slate-500" aria-hidden="true"></i>
                    <p class="text-xs font-bold">Tidak ada data nomor yang cocok.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-xs font-black uppercase tracking-wider text-slate-600 border-b border-slate-200">
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
                        <tbody class="divide-y divide-slate-200">
                            @foreach($segments as $s)
                                <tr class="hover:bg-slate-50">
                                    <td class="py-3 px-4 font-mono font-bold text-slate-900">{{ $s['no_hp'] }}</td>
                                    <td class="py-3 px-4">
                                        <span class="font-mono text-xs text-primary-700 font-bold block">{{ $s['donatur_id'] }}</span>
                                        <span class="text-slate-800 font-semibold break-words block">{{ $s['nama'] }}</span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md border border-slate-200 bg-slate-50 text-xs font-bold uppercase tracking-wider text-slate-800">
                                            <span class="w-1.5 h-1.5 rounded-full shrink-0
                                                {{ $s['segmen'] === 'AKTIF Donatur' ? 'bg-emerald-700' : '' }}
                                                {{ $s['segmen'] === 'AKTIF Pembaca' ? 'bg-[#063f5c]' : '' }}
                                                {{ $s['segmen'] === 'PANTAU Data Belum Cukup' ? 'bg-[#f7ad1a]' : '' }}
                                                {{ $s['segmen'] === 'ARSIP Nomor Bermasalah' ? 'bg-rose-700' : '' }}
                                                {{ in_array($s['segmen'], ['ARSIP Tidak Pernah Baca', 'ARSIP Berhenti Baca', 'ARSIP Nomor Tidak Valid']) ? 'bg-slate-400' : '' }}
                                            " aria-hidden="true"></span>
                                            {{ $s['segmen'] }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 font-bold text-xs {{ str_contains($s['jadwal'], 'CEK') ? 'text-amber-800' : (str_contains($s['jadwal'], 'BERHENTI') ? 'text-slate-600' : 'text-emerald-700') }}">
                                        {{ $s['jadwal'] }}
                                    </td>
                                    <td class="py-3 px-4 text-xs text-slate-700 space-y-0.5">
                                        <div>Total: <strong>{{ $s['total_blast'] }}x</strong></div>
                                        <div class="text-[11px] text-slate-600">R:{{ $s['read'] }} | D:{{ $s['delivered'] }} | F:{{ $s['failed'] }}</div>
                                    </td>
                                    <td class="py-3 px-4 text-xs">
                                        @if($s['pernah_donasi'])
                                            <span class="font-bold text-emerald-700 block">Rp {{ number_format($s['total_donasi'], 0, ',', '.') }}</span>
                                            <span class="text-[11px] text-slate-600">({{ $s['donasi_count'] }} transaksi)</span>
                                        @else
                                            <span class="text-slate-600">-</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md border border-slate-200 bg-slate-50 text-xs font-bold uppercase tracking-wider text-slate-800">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $s['wa_aktif'] ? 'bg-emerald-700' : 'bg-rose-700' }}" aria-hidden="true"></span>
                                            {{ $s['wa_aktif'] ? 'Aktif' : 'Mati' }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-xs text-slate-700 leading-snug">
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
</div>
@endsection

