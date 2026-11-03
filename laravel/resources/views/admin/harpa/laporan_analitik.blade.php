@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50/60 pb-20">
    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-8 items-start min-w-0">
            <!-- Sidebar Menu -->
            <aside class="hidden lg:block space-y-6 shrink-0 w-[280px]">
                @include('admin.sidebar', ['active' => 'harpa_laporan'])
            </aside>

            <!-- Main Content Area -->
            <div class="space-y-6 min-w-0">
                <!-- Top Header Bar -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-widest">
                            <span>Sistem Data Harpa</span>
                            <span>/</span>
                            <span class="text-primary-600 font-black">Alur F: Laporan Analitik Donatur</span>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-montserrat font-black text-slate-900 tracking-tight mt-1 flex items-center gap-2">
                            <i class="fa-solid fa-chart-pie text-[#f7ad1a]"></i> Laporan Analitik & 3 Sumbu Donatur
                        </h1>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.harpa.settings') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs uppercase tracking-wider transition-all">
                            <i class="fa-solid fa-sliders"></i>
                            Atur Ambang Batas
                        </a>
                    </div>
                </div>
        <!-- TABS NAVIGASI 3 LAPORAN -->
        <div class="flex items-center gap-2 overflow-x-auto pb-2 border-b border-slate-200">
            <a href="{{ route('admin.harpa.laporan', ['tab' => 'hitung_donatur']) }}" class="px-5 py-3 rounded-2xl text-xs font-black uppercase tracking-wider transition-all whitespace-nowrap {{ $tab === 'hitung_donatur' ? 'bg-[#063f5c] text-white shadow-lg' : 'bg-white text-slate-600 hover:bg-slate-100' }}">
                <i class="fa-solid fa-users-viewfinder mr-1.5 {{ $tab === 'hitung_donatur' ? 'text-[#f7ad1a]' : 'text-slate-400' }}"></i>
                1. Hitung Donatur (3 Sumbu)
            </a>

            <a href="{{ route('admin.harpa.laporan', ['tab' => 'kohort']) }}" class="px-5 py-3 rounded-2xl text-xs font-black uppercase tracking-wider transition-all whitespace-nowrap {{ $tab === 'kohort' ? 'bg-[#063f5c] text-white shadow-lg' : 'bg-white text-slate-600 hover:bg-slate-100' }}">
                <i class="fa-solid fa-table-cells mr-1.5 {{ $tab === 'kohort' ? 'text-[#f7ad1a]' : 'text-slate-400' }}"></i>
                2. Kohort Retensi Bulanan
            </a>

            <a href="{{ route('admin.harpa.laporan', ['tab' => 'donatur_tak_baca']) }}" class="px-5 py-3 rounded-2xl text-xs font-black uppercase tracking-wider transition-all whitespace-nowrap {{ $tab === 'donatur_tak_baca' ? 'bg-[#063f5c] text-white shadow-lg' : 'bg-white text-slate-600 hover:bg-slate-100' }}">
                <i class="fa-solid fa-envelope-open-text mr-1.5 {{ $tab === 'donatur_tak_baca' ? 'text-[#f7ad1a]' : 'text-slate-400' }}"></i>
                3. Donatur Tak Pernah Buka Blast
            </a>
        </div>

        @if($tab === 'hitung_donatur')
            <!-- TABEL HITUNG DONATUR (3 SUMBU TERPISAH) -->
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-montserrat font-black uppercase tracking-wider text-slate-800">
                            Matriks 3 Sumbu Donatur (Kategori, Badge, Segmen RFM)
                        </h3>
                        <p class="text-xs text-slate-400 font-medium">Ketiga sumbu dinilai terpisah dan tidak digabung menjadi satu label.</p>
                    </div>
                    <span class="text-xs font-bold text-slate-600">{{ count($hitungDonatur) }} Donatur</span>
                </div>

                @if(empty($hitungDonatur))
                    <div class="text-center py-12 text-slate-400">
                        <i class="fa-regular fa-user text-3xl mb-2"></i>
                        <p class="text-xs font-bold">Belum ada data donatur di master_donatur.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50 text-[10px] font-black uppercase tracking-wider text-slate-500 border-b border-slate-100">
                                <tr>
                                    <th class="py-3 px-4">Donatur ID</th>
                                    <th class="py-3 px-4">Nama Lengkap</th>
                                    <th class="py-3 px-4">Nomor HP</th>
                                    <th class="py-3 px-4">Sumbu 1: Kategori</th>
                                    <th class="py-3 px-4">Sumbu 2: Badge</th>
                                    <th class="py-3 px-4">Sumbu 3: Segmen RFM</th>
                                    <th class="py-3 px-4">Total Donasi</th>
                                    <th class="py-3 px-4">Frekuensi</th>
                                    <th class="py-3 px-4">Donasi Terakhir</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($hitungDonatur as $d)
                                    <tr class="hover:bg-slate-50/50">
                                        <td class="py-3 px-4 font-mono font-bold text-primary-600">{{ $d['donatur_id'] }}</td>
                                        <td class="py-3 px-4 font-semibold text-slate-800">{{ $d['nama_lengkap'] }}</td>
                                        <td class="py-3 px-4 font-mono text-slate-600">{{ $d['no_hp'] ?: '-' }}</td>
                                        <td class="py-3 px-4">
                                            <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-wider {{ $d['kategori'] === 'LOYAL' ? 'bg-primary-100 text-primary-800' : 'bg-slate-100 text-slate-700' }}">
                                                {{ $d['kategori'] }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-wider 
                                                {{ $d['badge'] === 'PLATINUM' ? 'bg-amber-100 text-amber-900 font-black ring-1 ring-amber-300' : '' }}
                                                {{ $d['badge'] === 'GOLD' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $d['badge'] === 'SILVER' ? 'bg-slate-200 text-slate-700' : '' }}
                                                {{ $d['badge'] === 'NONE' ? 'text-slate-400' : '' }}
                                            ">
                                                {{ $d['badge'] }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider
                                                {{ $d['segmen_rfm'] === 'CHAMPION' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                                {{ $d['segmen_rfm'] === 'HIGH VALUE AT RISK' ? 'bg-rose-100 text-rose-800' : '' }}
                                                {{ $d['segmen_rfm'] === 'LOYAL' ? 'bg-primary-100 text-primary-800' : '' }}
                                                {{ $d['segmen_rfm'] === 'FRESH' ? 'bg-sky-100 text-sky-800' : '' }}
                                                {{ $d['segmen_rfm'] === 'POTENTIAL' ? 'bg-amber-100 text-amber-800' : '' }}
                                                {{ $d['segmen_rfm'] === 'DORMANT' ? 'bg-slate-200 text-slate-700' : '' }}
                                                {{ $d['segmen_rfm'] === 'PROSPEK' ? 'bg-zinc-100 text-zinc-600' : '' }}
                                            ">
                                                {{ $d['segmen_rfm'] }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 font-bold text-emerald-700">Rp {{ number_format($d['total_donasi'], 0, ',', '.') }}</td>
                                        <td class="py-3 px-4 text-slate-600">{{ $d['frekuensi'] }}x</td>
                                        <td class="py-3 px-4 text-slate-500">{{ $d['donasi_terakhir'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        @elseif($tab === 'kohort')
            <!-- TABEL KOHORT RETENSI -->
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-4">
                <div class="pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-montserrat font-black uppercase tracking-wider text-slate-800">
                        Matriks Kohort Retensi Donatur per Bulan Donasi Pertama
                    </h3>
                    <p class="text-xs text-slate-400 font-medium">Melacak berapa banyak donatur yang kembali berdonasi pada bulan ke-M+1, M+2, dst.</p>
                </div>

                @if(empty($kohort))
                    <div class="text-center py-12 text-slate-400">
                        <i class="fa-regular fa-calendar-xmark text-3xl mb-2"></i>
                        <p class="text-xs font-bold">Data transaksi belum mencukupi untuk membentuk tabel kohort.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-center text-xs">
                            <thead class="bg-slate-50 text-[10px] font-black uppercase tracking-wider text-slate-500 border-b border-slate-100">
                                <tr>
                                    <th class="py-3 px-4 text-left">Bulan Pertama</th>
                                    <th class="py-3 px-4">Donatur Baru</th>
                                    @for($m = 0; $m <= 11; $m++)
                                        <th class="py-3 px-2 font-mono">M+{{ $m }}</th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($kohort as $month => $info)
                                    <tr class="hover:bg-slate-50/50">
                                        <td class="py-3 px-4 font-mono font-bold text-slate-800 text-left">{{ $month }}</td>
                                        <td class="py-3 px-4 font-bold text-primary-600">{{ $info['cohort_size'] }}</td>
                                        @for($m = 0; $m <= 11; $m++)
                                            @php
                                                $activeCount = $info['months'][$m] ?? 0;
                                                $pct = $info['cohort_size'] > 0 ? round(($activeCount / $info['cohort_size']) * 100) : 0;
                                            @endphp
                                            <td class="py-3 px-2 font-mono text-[11px]">
                                                @if($activeCount > 0)
                                                    <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-bold 
                                                        {{ $pct >= 50 ? 'bg-emerald-600 text-white' : ($pct >= 25 ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700') }}">
                                                        {{ $pct }}%
                                                    </span>
                                                @else
                                                    <span class="text-slate-300">-</span>
                                                @endif
                                            </td>
                                        @endfor
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        @elseif($tab === 'donatur_tak_baca')
            <!-- TABEL DONATUR TAK BACA BLAST -->
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-montserrat font-black uppercase tracking-wider text-slate-800">
                            Donatur Berdonasi yang Tidak Pernah Membuka Blast
                        </h3>
                        <p class="text-xs text-slate-400 font-medium">Berdonasi dalam periode lacak tetapi tidak pernah berstatus READ sejak 12 Juni 2026.</p>
                    </div>

                    <form method="GET" action="{{ route('admin.harpa.laporan') }}" class="flex items-center gap-2">
                        <input type="hidden" name="tab" value="donatur_tak_baca">
                        <input type="date" name="start_date" value="{{ $startDate ?: '2026-06-12' }}" class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 outline-none">
                        <span class="text-xs text-slate-400">s/d</span>
                        <input type="date" name="end_date" value="{{ $endDate ?: date('Y-m-d') }}" class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 outline-none">
                        <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-slate-800 transition">
                            Terapkan
                        </button>
                    </form>
                </div>

                @if(empty($donaturTakBaca))
                    <div class="text-center py-12 text-slate-400">
                        <i class="fa-regular fa-circle-check text-3xl mb-2 text-emerald-500"></i>
                        <p class="text-xs font-bold text-slate-700">Tidak ada donatur dalam periode ini yang tidak pernah membuka blast.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50 text-[10px] font-black uppercase tracking-wider text-slate-500 border-b border-slate-100">
                                <tr>
                                    <th class="py-3 px-4">Donatur ID</th>
                                    <th class="py-3 px-4">Nama Lengkap</th>
                                    <th class="py-3 px-4">Nomor HP</th>
                                    <th class="py-3 px-4">Total Donasi</th>
                                    <th class="py-3 px-4">Donasi Terakhir</th>
                                    <th class="py-3 px-4">Pesan Diterima</th>
                                    <th class="py-3 px-4">Catatan Analisis</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($donaturTakBaca as $dt)
                                    <tr class="hover:bg-slate-50/50">
                                        <td class="py-3 px-4 font-mono font-bold text-primary-600">{{ $dt['donatur_id'] }}</td>
                                        <td class="py-3 px-4 font-semibold text-slate-800">{{ $dt['nama_lengkap'] }}</td>
                                        <td class="py-3 px-4 font-mono text-slate-600">{{ $dt['no_hp'] ?: '-' }}</td>
                                        <td class="py-3 px-4 font-bold text-emerald-700">Rp {{ number_format($dt['total_donasi'], 0, ',', '.') }}</td>
                                        <td class="py-3 px-4 text-slate-500">{{ $dt['donasi_terakhir'] }}</td>
                                        <td class="py-3 px-4 text-slate-700 font-semibold">{{ $dt['total_blast'] }}x terkirim (0 read)</td>
                                        <td class="py-3 px-4 text-slate-500 text-[11px]">{{ $dt['catatan'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        @endif
            </div>
        </div>
    </div>
</div>
@endsection

