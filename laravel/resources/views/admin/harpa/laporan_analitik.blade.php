@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50/60 pb-20" x-data="{ openModal: false, selectedDonatur: null, oldPhone: '', nomorBaru: '', picCrm: '', catatan: '' }">
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
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-600 uppercase tracking-widest">
                            <span>Sistem Data Harpa</span>
                            <span aria-hidden="true">/</span>
                            <span class="text-primary-600 font-black">Alur F: Laporan Analitik & CRM</span>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-montserrat font-black text-slate-900 tracking-tight mt-1 flex items-center gap-2">
                            <i class="fa-solid fa-chart-pie text-[#f7ad1a]" aria-hidden="true"></i> Laporan Analitik & Monitoring Donatur
                        </h1>
                        <p class="text-xs text-slate-600 font-medium mt-1">
                            Integrasi analitik donatur, matriks retensi kohort, serta pelacakan donasi bulanan dan mingguan.
                        </p>
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <form method="POST" action="{{ route('admin.harpa.laporan.sync') }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center min-h-[44px] gap-2 px-4 py-2.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200 font-bold text-xs uppercase tracking-wider transition-colors cursor-pointer" title="Sinkronkan transaksi live dari website">
                                <i class="fa-solid fa-rotate text-emerald-600" aria-hidden="true"></i>
                                Sinkron Transaksi Web
                            </button>
                        </form>
                        <a href="{{ route('admin.harpa.settings') }}" class="inline-flex items-center min-h-[44px] gap-2 px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs uppercase tracking-wider transition-colors">
                            <i class="fa-solid fa-sliders text-slate-600" aria-hidden="true"></i>
                            Atur Ambang Batas
                        </a>
                    </div>
                </div>

                @if(session('success'))
                    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-600" aria-hidden="true"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold flex items-center gap-2">
                        <i class="fa-solid fa-triangle-exclamation text-rose-600" aria-hidden="true"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                <!-- TABS NAVIGASI 6 LAPORAN ANALITIK -->
                <div class="flex items-center gap-2 overflow-x-auto pb-2 border-b border-slate-200 scrollbar-thin">
                    <a href="{{ route('admin.harpa.laporan', ['tab' => 'hitung_donatur']) }}" class="inline-flex items-center min-h-[44px] px-4 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition-colors whitespace-nowrap {{ $tab === 'hitung_donatur' ? 'bg-[#063f5c] text-white shadow-sm' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
                        <i class="fa-solid fa-users-viewfinder mr-1.5 {{ $tab === 'hitung_donatur' ? 'text-[#f7ad1a]' : 'text-slate-500' }}" aria-hidden="true"></i>
                        1. Hitung Donatur (3 Sumbu)
                    </a>

                    <a href="{{ route('admin.harpa.laporan', ['tab' => 'kohort']) }}" class="inline-flex items-center min-h-[44px] px-4 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition-colors whitespace-nowrap {{ $tab === 'kohort' ? 'bg-[#063f5c] text-white shadow-sm' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
                        <i class="fa-solid fa-table-cells mr-1.5 {{ $tab === 'kohort' ? 'text-[#f7ad1a]' : 'text-slate-500' }}" aria-hidden="true"></i>
                        2. Kohort Retensi
                    </a>

                    <a href="{{ route('admin.harpa.laporan', ['tab' => 'donatur_tak_baca']) }}" class="inline-flex items-center min-h-[44px] px-4 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition-colors whitespace-nowrap {{ $tab === 'donatur_tak_baca' ? 'bg-[#063f5c] text-white shadow-sm' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
                        <i class="fa-solid fa-envelope-open-text mr-1.5 {{ $tab === 'donatur_tak_baca' ? 'text-[#f7ad1a]' : 'text-slate-500' }}" aria-hidden="true"></i>
                        3. Donatur Tak Baca Blast
                    </a>

                    <a href="{{ route('admin.harpa.laporan', ['tab' => 'minta_nomor_baru']) }}" class="inline-flex items-center min-h-[44px] px-4 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition-colors whitespace-nowrap {{ $tab === 'minta_nomor_baru' ? 'bg-[#063f5c] text-white shadow-sm' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
                        <i class="fa-solid fa-phone-slash mr-1.5 {{ $tab === 'minta_nomor_baru' ? 'text-[#f7ad1a]' : 'text-slate-500' }}" aria-hidden="true"></i>
                        4. Minta Nomor Baru (CRM)
                    </a>

                    <a href="{{ route('admin.harpa.laporan', ['tab' => 'tracking_bulanan']) }}" class="inline-flex items-center min-h-[44px] px-4 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition-colors whitespace-nowrap {{ $tab === 'tracking_bulanan' ? 'bg-[#063f5c] text-white shadow-sm' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
                        <i class="fa-solid fa-calendar-days mr-1.5 {{ $tab === 'tracking_bulanan' ? 'text-[#f7ad1a]' : 'text-slate-500' }}" aria-hidden="true"></i>
                        5. Tracking Bulanan (12 Bulan)
                    </a>

                    <a href="{{ route('admin.harpa.laporan', ['tab' => 'tracking_jumat']) }}" class="inline-flex items-center min-h-[44px] px-4 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition-colors whitespace-nowrap {{ $tab === 'tracking_jumat' ? 'bg-[#063f5c] text-white shadow-sm' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
                        <i class="fa-solid fa-calendar-week mr-1.5 {{ $tab === 'tracking_jumat' ? 'text-[#f7ad1a]' : 'text-slate-500' }}" aria-hidden="true"></i>
                        6. Tracking Donasi Jumat
                    </a>
                </div>

                {{-- TAB 1: HITUNG DONATUR 3 SUMBU --}}
                @if($tab === 'hitung_donatur')
                    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-3 border-b border-slate-200">
                            <div>
                                <h2 class="text-sm font-montserrat font-black uppercase tracking-wider text-slate-800">
                                    Matriks 3 Sumbu Donatur (Kategori, Badge, Segmen RFM)
                                </h2>
                                <p class="text-xs text-slate-600 font-medium mt-0.5">
                                    Ketiga sumbu dinilai mandiri: Sumbu 1 (Frekuensi), Sumbu 2 (Akumulasi Nominal), dan Sumbu 3 (Recency & Nilai RFM).
                                </p>
                            </div>
                            <span class="text-xs font-bold text-slate-700">{{ count($hitungDonatur) }} Donatur Terdaftar</span>
                        </div>

                        @if(empty($hitungDonatur))
                            <div class="text-center py-12 text-slate-600">
                                <i class="fa-regular fa-user text-3xl mb-2 text-slate-400" aria-hidden="true"></i>
                                <p class="text-xs font-bold">Belum ada data donatur yang tercatat di sistem.</p>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-xs">
                                    <thead class="bg-slate-50 text-xs font-black uppercase tracking-wider text-slate-600 border-b border-slate-200">
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
                                    <tbody class="divide-y divide-slate-200">
                                        @foreach($hitungDonatur as $d)
                                            <tr class="hover:bg-slate-50">
                                                <td class="py-3 px-4 font-mono font-bold text-primary-700">{{ $d['donatur_id'] }}</td>
                                                <td class="py-3 px-4 font-semibold text-slate-800">{{ $d['nama_lengkap'] }}</td>
                                                <td class="py-3 px-4 font-mono text-slate-700">{{ $d['no_hp'] ?: '-' }}</td>
                                                <td class="py-3 px-4">
                                                    <span class="text-xs font-bold uppercase tracking-wider {{ $d['kategori'] === 'LOYAL' ? 'text-[#063f5c]' : 'text-slate-600' }}">
                                                        {{ $d['kategori'] }}
                                                    </span>
                                                </td>
                                                <td class="py-3 px-4">
                                                    <span class="text-xs font-bold uppercase tracking-wider
                                                        {{ $d['badge'] === 'PLATINUM' ? 'text-[#063f5c]' : '' }}
                                                        {{ $d['badge'] === 'GOLD' ? 'text-amber-800' : '' }}
                                                        {{ in_array($d['badge'], ['SILVER', 'NONE']) ? 'text-slate-600' : '' }}
                                                    ">
                                                        {{ $d['badge'] }}
                                                    </span>
                                                </td>
                                                <td class="py-3 px-4">
                                                    <span class="text-xs font-bold uppercase tracking-wider
                                                        {{ $d['segmen_rfm'] === 'CHAMPION' ? 'text-emerald-700' : '' }}
                                                        {{ $d['segmen_rfm'] === 'HIGH VALUE AT RISK' ? 'text-rose-700' : '' }}
                                                        {{ $d['segmen_rfm'] === 'LOYAL' ? 'text-[#063f5c]' : '' }}
                                                        {{ $d['segmen_rfm'] === 'FRESH' ? 'text-sky-700' : '' }}
                                                        {{ $d['segmen_rfm'] === 'POTENTIAL' ? 'text-amber-800' : '' }}
                                                        {{ in_array($d['segmen_rfm'], ['DORMANT', 'PROSPEK']) ? 'text-slate-600' : '' }}
                                                    ">
                                                        {{ $d['segmen_rfm'] }}
                                                    </span>
                                                </td>
                                                <td class="py-3 px-4 font-bold text-emerald-700">Rp {{ number_format($d['total_donasi'], 0, ',', '.') }}</td>
                                                <td class="py-3 px-4 text-slate-700">{{ $d['frekuensi'] }}x</td>
                                                <td class="py-3 px-4 text-slate-600">{{ $d['donasi_terakhir'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                {{-- TAB 2: KOHORT RETENSI --}}
                @elseif($tab === 'kohort')
                    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-4">
                        <div class="pb-3 border-b border-slate-200">
                            <h2 class="text-sm font-montserrat font-black uppercase tracking-wider text-slate-800">
                                Matriks Kohort Retensi Donatur per Bulan Donasi Pertama
                            </h2>
                            <p class="text-xs text-slate-600 font-medium mt-0.5">
                                Melacak persentase donatur baru yang kembali berdonasi pada bulan ke-M+1, M+2, hingga M+11.
                            </p>
                        </div>

                        @if(empty($kohort))
                            <div class="text-center py-12 text-slate-600">
                                <i class="fa-regular fa-calendar-xmark text-3xl mb-2 text-slate-400" aria-hidden="true"></i>
                                <p class="text-xs font-bold">Data transaksi belum mencukupi untuk membentuk tabel kohort.</p>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="w-full text-center text-xs">
                                    <thead class="bg-slate-50 text-xs font-black uppercase tracking-wider text-slate-600 border-b border-slate-200">
                                        <tr>
                                            <th class="py-3 px-4 text-left">Bulan Pertama</th>
                                            <th class="py-3 px-4">Donatur Baru</th>
                                            @for($m = 0; $m <= 11; $m++)
                                                <th class="py-3 px-2 font-mono">M+{{ $m }}</th>
                                            @endfor
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200">
                                        @foreach($kohort as $month => $info)
                                            <tr class="hover:bg-slate-50">
                                                <td class="py-3 px-4 font-mono font-bold text-slate-800 text-left">{{ $month }}</td>
                                                <td class="py-3 px-4 font-bold text-primary-700">{{ $info['cohort_size'] }}</td>
                                                @for($m = 0; $m <= 11; $m++)
                                                    @php
                                                        $activeCount = $info['months'][$m] ?? 0;
                                                        $pct = $info['cohort_size'] > 0 ? round(($activeCount / $info['cohort_size']) * 100) : 0;
                                                    @endphp
                                                    <td class="py-3 px-2 font-mono text-xs">
                                                        @if($activeCount > 0)
                                                            <span class="inline-block px-2 py-0.5 rounded text-xs font-bold 
                                                                {{ $pct >= 50 ? 'bg-[#063f5c] text-white' : ($pct >= 25 ? 'bg-slate-200 text-slate-900' : 'bg-slate-100 text-slate-700 border border-slate-200') }}">
                                                                {{ $pct }}%
                                                            </span>
                                                        @else
                                                            <span class="text-slate-400">-</span>
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

                {{-- TAB 3: DONATUR TAK BACA BLAST --}}
                @elseif($tab === 'donatur_tak_baca')
                    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
                            <div>
                                <h2 class="text-sm font-montserrat font-black uppercase tracking-wider text-slate-800">
                                    Donatur Berdonasi yang Tidak Pernah Membuka Blast
                                </h2>
                                <p class="text-xs text-slate-600 font-medium mt-0.5">
                                    Daftar telepon CRM untuk menghubungi donatur aktif yang tidak pernah berstatus READ sejak 12 Juni 2026.
                                </p>
                            </div>

                            <form method="GET" action="{{ route('admin.harpa.laporan') }}" class="flex flex-wrap items-center gap-2">
                                <input type="hidden" name="tab" value="donatur_tak_baca">
                                <label for="start_date" class="sr-only">Tanggal Mulai</label>
                                <input type="date" id="start_date" name="start_date" value="{{ $startDate ?: '2026-06-12' }}" class="min-h-[44px] bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 outline-none focus:border-primary-600">
                                <span class="text-xs font-bold text-slate-600">s/d</span>
                                <label for="end_date" class="sr-only">Tanggal Akhir</label>
                                <input type="date" id="end_date" name="end_date" value="{{ $endDate ?: date('Y-m-d') }}" class="min-h-[44px] bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 outline-none focus:border-primary-600">
                                <button type="submit" class="min-h-[44px] px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-slate-800 transition">
                                    Filter
                                </button>
                            </form>
                        </div>

                        @if(empty($donaturTakBaca))
                            <div class="text-center py-12 text-slate-600">
                                <i class="fa-regular fa-circle-check text-3xl mb-2 text-emerald-700" aria-hidden="true"></i>
                                <p class="text-xs font-bold text-slate-700">Tidak ada donatur dalam periode ini yang tidak pernah membuka blast.</p>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-xs">
                                    <thead class="bg-slate-50 text-xs font-black uppercase tracking-wider text-slate-600 border-b border-slate-200">
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
                                    <tbody class="divide-y divide-slate-200">
                                        @foreach($donaturTakBaca as $dt)
                                            <tr class="hover:bg-slate-50">
                                                <td class="py-3 px-4 font-mono font-bold text-primary-700">{{ $dt['donatur_id'] }}</td>
                                                <td class="py-3 px-4 font-semibold text-slate-800">{{ $dt['nama_lengkap'] }}</td>
                                                <td class="py-3 px-4 font-mono text-slate-700">{{ $dt['no_hp'] ?: '-' }}</td>
                                                <td class="py-3 px-4 font-bold text-emerald-700">Rp {{ number_format($dt['total_donasi'], 0, ',', '.') }}</td>
                                                <td class="py-3 px-4 text-slate-600">{{ $dt['donasi_terakhir'] }}</td>
                                                <td class="py-3 px-4 text-slate-700 font-semibold">{{ $dt['total_blast'] }}x terkirim (0 read)</td>
                                                <td class="py-3 px-4 text-slate-600 text-xs">{{ $dt['catatan'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                {{-- TAB 4: MINTA NOMOR BARU (CRM RECOVERY) --}}
                @elseif($tab === 'minta_nomor_baru')
                    <div class="space-y-6">
                        <!-- KPI Ringkasan Dana Tak Terjangkau -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="bg-white border border-rose-200 rounded-2xl p-5 shadow-sm">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Total Donasi Tak Terjangkau</span>
                                <span class="text-2xl font-black font-montserrat text-rose-700 mt-1 block">
                                    Rp {{ number_format($mintaNomorBaru['total_tak_terjangkau'] ?? 0, 0, ',', '.') }}
                                </span>
                                <span class="text-xs text-slate-500 mt-1 block">Dari donatur yang nomor WA-nya mati/tidak aktif</span>
                            </div>

                            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Total Donatur Terdampak</span>
                                <span class="text-2xl font-black font-montserrat text-slate-800 mt-1 block">
                                    {{ number_format($mintaNomorBaru['total_donatur'] ?? 0, 0, ',', '.') }} Donatur
                                </span>
                                <span class="text-xs text-slate-500 mt-1 block">Perlu tindak lanjut pemulihan oleh CRM</span>
                            </div>

                            <div class="bg-white border border-emerald-200 rounded-2xl p-5 shadow-sm">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Nomor Berhasil Dipulihkan</span>
                                <span class="text-2xl font-black font-montserrat text-emerald-700 mt-1 block">
                                    {{ number_format($mintaNomorBaru['total_recovered'] ?? 0, 0, ',', '.') }} Donatur
                                </span>
                                <span class="text-xs text-slate-500 mt-1 block">Sudah memiliki nomor baru aktif</span>
                            </div>
                        </div>

                        <!-- Panel Tabel & Filter -->
                        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-4">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
                                <div>
                                    <h2 class="text-sm font-montserrat font-black uppercase tracking-wider text-slate-800">
                                        Daftar Prioritas CRM: Reaktivasi Donatur Bernilai Tinggi
                                    </h2>
                                    <p class="text-xs text-slate-600 font-medium mt-0.5">
                                        Diurutkan dari total donasi terbesar. Hubungi via panggilan seluler biasa atau email untuk meminta nomor WhatsApp baru.
                                    </p>
                                </div>

                                <div class="flex items-center gap-2 flex-wrap">
                                    <form method="GET" action="{{ route('admin.harpa.laporan') }}" class="flex items-center gap-2">
                                        <input type="hidden" name="tab" value="minta_nomor_baru">
                                        <label for="search_donor" class="sr-only">Cari Donatur</label>
                                        <input type="text" id="search_donor" name="search" value="{{ $search }}" placeholder="Cari nama / ID / HP..." class="min-h-[44px] bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 outline-none focus:border-primary-600">
                                        
                                        <label for="min_donasi" class="sr-only">Filter Donasi Minimal</label>
                                        <select id="min_donasi" name="min_donasi" class="min-h-[44px] bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 outline-none focus:border-primary-600">
                                            <option value="">Semua Nominal</option>
                                            <option value="500000" {{ $minDonasi == '500000' ? 'selected' : '' }}>&ge; Rp 500.000</option>
                                            <option value="1000000" {{ $minDonasi == '1000000' ? 'selected' : '' }}>&ge; Rp 1.000.000</option>
                                        </select>
                                        <button type="submit" class="min-h-[44px] px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-slate-800 transition">
                                            Filter
                                        </button>
                                    </form>

                                    <a href="{{ route('admin.harpa.laporan.export', ['type' => 'minta_nomor_baru', 'min_donasi' => $minDonasi, 'search' => $search]) }}" class="inline-flex items-center min-h-[44px] gap-2 px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs uppercase tracking-wider transition-colors">
                                        <i class="fa-solid fa-file-csv text-emerald-700" aria-hidden="true"></i>
                                        Export CSV
                                    </a>
                                </div>
                            </div>

                            @if(empty($mintaNomorBaru['items']))
                                <div class="text-center py-12 text-slate-600">
                                    <i class="fa-regular fa-circle-check text-3xl mb-2 text-emerald-700" aria-hidden="true"></i>
                                    <p class="text-xs font-bold text-slate-700">Tidak ada donatur dengan nomor mati sesuai kriteria filter.</p>
                                </div>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="w-full text-left text-xs">
                                        <thead class="bg-slate-50 text-xs font-black uppercase tracking-wider text-slate-600 border-b border-slate-200">
                                            <tr>
                                                <th class="py-3 px-3">No</th>
                                                <th class="py-3 px-4">Donatur ID</th>
                                                <th class="py-3 px-4">Nama Lengkap</th>
                                                <th class="py-3 px-4">No. HP Lama</th>
                                                <th class="py-3 px-4">Total Donasi</th>
                                                <th class="py-3 px-4">Frekuensi</th>
                                                <th class="py-3 px-4">Donasi Terakhir</th>
                                                <th class="py-3 px-4">PIC CRM</th>
                                                <th class="py-3 px-4">Nomor Baru / Status</th>
                                                <th class="py-3 px-4 text-center">Aksi Tindak Lanjut</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-200">
                                            @foreach($mintaNomorBaru['items'] as $idx => $item)
                                                <tr class="hover:bg-slate-50 {{ $item['is_recovered'] ? 'bg-emerald-50/40' : '' }}">
                                                    <td class="py-3 px-3 text-slate-500 font-mono">{{ $idx + 1 }}</td>
                                                    <td class="py-3 px-4 font-mono font-bold text-primary-700">{{ $item['donatur_id'] }}</td>
                                                    <td class="py-3 px-4 font-semibold text-slate-900">{{ $item['nama_lengkap'] }}</td>
                                                    <td class="py-3 px-4 font-mono text-rose-700 font-semibold">
                                                        {{ $item['no_hp_lama'] }}
                                                        <span class="block text-[10px] text-slate-500 font-normal">({{ $item['status_wa'] }})</span>
                                                    </td>
                                                    <td class="py-3 px-4 font-bold text-emerald-700">
                                                        Rp {{ number_format($item['total_donasi'], 0, ',', '.') }}
                                                    </td>
                                                    <td class="py-3 px-4 text-slate-700">{{ $item['frekuensi'] }}x</td>
                                                    <td class="py-3 px-4 text-slate-600">{{ $item['donasi_terakhir'] }}</td>
                                                    <td class="py-3 px-4 text-slate-700 font-medium">{{ $item['pic_crm'] }}</td>
                                                    <td class="py-3 px-4">
                                                        @if($item['is_recovered'])
                                                            <span class="inline-flex items-center gap-1 font-mono font-bold text-emerald-800 bg-emerald-100 px-2 py-0.5 rounded text-xs">
                                                                <i class="fa-solid fa-check text-emerald-700" aria-hidden="true"></i> {{ $item['nomor_baru'] }}
                                                            </span>
                                                        @else
                                                            <span class="text-xs text-rose-600 font-semibold">Belum Diperbarui</span>
                                                        @endif
                                                    </td>
                                                    <td class="py-3 px-4 text-center">
                                                        <button type="button" 
                                                            @click="selectedDonatur = '{{ $item['donatur_id'] }}'; oldPhone = '{{ $item['no_hp_lama'] }}'; nomorBaru = '{{ $item['nomor_baru'] }}'; picCrm = '{{ $item['pic_crm'] }}'; openModal = true; $nextTick(() => $refs.newPhoneInput.focus())"
                                                            class="inline-flex items-center min-h-[44px] gap-1 px-3 py-1.5 rounded-lg bg-[#063f5c] hover:bg-slate-800 text-white text-xs font-bold transition-colors cursor-pointer"
                                                            aria-label="Input Nomor Baru untuk {{ $item['nama_lengkap'] }}">
                                                            <i class="fa-solid fa-pen-to-square text-[#f7ad1a]" aria-hidden="true"></i>
                                                            <span>Input Nomor</span>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>

                {{-- TAB 5: TRACKING BULANAN (12 BULAN) --}}
                @elseif($tab === 'tracking_bulanan')
                    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-6">
                        <!-- KPI Cards Tracking Bulanan -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="bg-white border border-primary-200 rounded-2xl p-5 shadow-sm">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Total Donasi Tahun {{ $trackingBulanan['year'] }}</span>
                                <span class="text-2xl font-black font-montserrat text-[#063f5c] mt-1 block">
                                    Rp {{ number_format($trackingBulanan['grand_total'] ?? 0, 0, ',', '.') }}
                                </span>
                                <span class="text-xs text-slate-500 mt-1 block">Akumulasi donasi 12 bulan kalender</span>
                            </div>

                            <div class="bg-white border border-amber-200 rounded-2xl p-5 shadow-sm">
                                @php
                                    $namaBulan = [1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'];
                                    $bMonth = $trackingBulanan['best_month'] ?? 1;
                                @endphp
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Bulan Donasi Tertinggi</span>
                                <span class="text-2xl font-black font-montserrat text-amber-900 mt-1 block">
                                    {{ $namaBulan[$bMonth] ?? 'Januari' }}
                                </span>
                                <span class="text-xs text-slate-600 font-semibold mt-1 block">Rp {{ number_format($trackingBulanan['best_month_val'] ?? 0, 0, ',', '.') }}</span>
                            </div>

                            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Donatur Aktif Tahun Ini</span>
                                <span class="text-2xl font-black font-montserrat text-slate-800 mt-1 block">
                                    {{ number_format($trackingBulanan['active_donors'] ?? 0, 0, ',', '.') }} Donatur
                                </span>
                                <span class="text-xs text-slate-500 mt-1 block">Berdonasi minimal 1x di tahun {{ $trackingBulanan['year'] }}</span>
                            </div>
                        </div>

                        <!-- Header Form Filter & Export -->
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
                            <div>
                                <h2 class="text-sm font-montserrat font-black uppercase tracking-wider text-slate-800">
                                    Matriks Donasi Jan - Des per Donatur (Tahun {{ $trackingBulanan['year'] }})
                                </h2>
                                <p class="text-xs text-slate-600 font-medium mt-0.5">
                                    Satu baris satu donatur dengan 12 kolom bulan untuk memantau konsistensi donasi sepanjang tahun.
                                </p>
                            </div>

                            <div class="flex items-center gap-2 flex-wrap">
                                <form method="GET" action="{{ route('admin.harpa.laporan') }}" class="flex items-center gap-2">
                                    <input type="hidden" name="tab" value="tracking_bulanan">
                                    
                                    <label for="year_select" class="sr-only">Pilih Tahun</label>
                                    <select id="year_select" name="year" class="min-h-[44px] bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 outline-none focus:border-primary-600">
                                        @foreach($trackingBulanan['available_years'] as $yr)
                                            <option value="{{ $yr }}" {{ $trackingBulanan['year'] == $yr ? 'selected' : '' }}>Tahun {{ $yr }}</option>
                                        @endforeach
                                    </select>

                                    <label for="search_bulanan" class="sr-only">Cari Donatur</label>
                                    <input type="text" id="search_bulanan" name="search" value="{{ $search }}" placeholder="Cari nama / ID..." class="min-h-[44px] bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 outline-none focus:border-primary-600">
                                    
                                    <button type="submit" class="min-h-[44px] px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-slate-800 transition">
                                        Filter
                                    </button>
                                </form>

                                <a href="{{ route('admin.harpa.laporan.export', ['type' => 'tracking_bulanan', 'year' => $trackingBulanan['year'], 'search' => $search]) }}" class="inline-flex items-center min-h-[44px] gap-2 px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs uppercase tracking-wider transition-colors">
                                    <i class="fa-solid fa-file-csv text-emerald-700" aria-hidden="true"></i>
                                    Export CSV
                                </a>
                            </div>
                        </div>

                        @if(empty($trackingBulanan['items']))
                            <div class="text-center py-12 text-slate-600">
                                <i class="fa-regular fa-calendar-xmark text-3xl mb-2 text-slate-400" aria-hidden="true"></i>
                                <p class="text-xs font-bold text-slate-700">Tidak ada transaksi donasi yang tercatat pada tahun {{ $trackingBulanan['year'] }}.</p>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="w-full text-right text-xs">
                                    <thead class="bg-slate-50 text-xs font-black uppercase tracking-wider text-slate-600 border-b border-slate-200">
                                        <tr>
                                            <th class="py-3 px-3 text-left">Donatur</th>
                                            <th class="py-3 px-2">Jan</th>
                                            <th class="py-3 px-2">Feb</th>
                                            <th class="py-3 px-2">Mar</th>
                                            <th class="py-3 px-2">Apr</th>
                                            <th class="py-3 px-2">Mei</th>
                                            <th class="py-3 px-2">Jun</th>
                                            <th class="py-3 px-2">Jul</th>
                                            <th class="py-3 px-2">Ags</th>
                                            <th class="py-3 px-2">Sep</th>
                                            <th class="py-3 px-2">Okt</th>
                                            <th class="py-3 px-2">Nov</th>
                                            <th class="py-3 px-2">Des</th>
                                            <th class="py-3 px-3 bg-slate-100 text-slate-800">Total</th>
                                            <th class="py-3 px-2 text-center">Freq</th>
                                            <th class="py-3 px-2 text-center">Badge</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200">
                                        @foreach($trackingBulanan['items'] as $item)
                                            <tr class="hover:bg-slate-50 font-mono text-[11px]">
                                                <td class="py-3 px-3 text-left font-sans">
                                                    <span class="font-bold text-primary-700 block">{{ $item['donatur_id'] }}</span>
                                                    <span class="text-slate-800 font-semibold block truncate max-w-[140px]">{{ $item['nama_lengkap'] }}</span>
                                                </td>
                                                @for($m = 1; $m <= 12; $m++)
                                                    @php $val = $item['monthly'][$m] ?? 0; @endphp
                                                    <td class="py-3 px-2 {{ $val > 0 ? 'text-slate-900 font-bold' : 'text-slate-300' }}">
                                                        {{ $val > 0 ? number_format($val, 0, ',', '.') : '-' }}
                                                    </td>
                                                @endfor
                                                <td class="py-3 px-3 bg-slate-50 font-bold text-emerald-700">
                                                    Rp {{ number_format($item['total'], 0, ',', '.') }}
                                                </td>
                                                <td class="py-3 px-2 text-center font-sans text-slate-700">{{ $item['frekuensi'] }}x</td>
                                                <td class="py-3 px-2 text-center font-sans">
                                                    <span class="text-[10px] font-bold uppercase tracking-wider
                                                        {{ $item['badge'] === 'PLATINUM' ? 'text-[#063f5c]' : '' }}
                                                        {{ $item['badge'] === 'GOLD' ? 'text-amber-800' : '' }}
                                                        {{ $item['badge'] === 'SILVER' ? 'text-slate-600' : '' }}
                                                    ">
                                                        {{ $item['badge'] }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-slate-100 text-slate-900 font-black border-t-2 border-slate-300">
                                        <tr class="font-mono text-xs">
                                            <td class="py-3 px-3 text-left font-sans uppercase">TOTAL BULANAN</td>
                                            @for($m = 1; $m <= 12; $m++)
                                                <td class="py-3 px-2 text-right">
                                                    {{ number_format($trackingBulanan['monthly_totals'][$m] ?? 0, 0, ',', '.') }}
                                                </td>
                                            @endfor
                                            <td class="py-3 px-3 bg-slate-200 text-emerald-800 font-black text-right">
                                                Rp {{ number_format($trackingBulanan['grand_total'], 0, ',', '.') }}
                                            </td>
                                            <td colspan="2"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @endif
                    </div>

                {{-- TAB 6: TRACKING DONASI JUMAT --}}
                @elseif($tab === 'tracking_jumat')
                    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-6">
                        @php
                            $namaBulan = [1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'];
                            $selMonth = $trackingJumat['month'] ?? (int)date('m');
                            $selYear = $trackingJumat['year'] ?? (int)date('Y');
                        @endphp

                        <!-- KPI Cards Tracking Jumat -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="bg-white border border-primary-200 rounded-2xl p-5 shadow-sm">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Total Donasi {{ $namaBulan[$selMonth] ?? '' }} {{ $selYear }}</span>
                                <span class="text-2xl font-black font-montserrat text-[#063f5c] mt-1 block">
                                    Rp {{ number_format($trackingJumat['grand_total'] ?? 0, 0, ',', '.') }}
                                </span>
                                <span class="text-xs text-slate-500 mt-1 block">Dari akumulasi 5 pekan Jumat bulan ini</span>
                            </div>

                            <div class="bg-white border border-amber-200 rounded-2xl p-5 shadow-sm">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Pekan Jumat Tertinggi</span>
                                <span class="text-2xl font-black font-montserrat text-amber-900 mt-1 block">
                                    Jumat Ke-{{ $trackingJumat['best_jumat'] ?? 1 }}
                                </span>
                                <span class="text-xs text-slate-600 font-semibold mt-1 block">Rp {{ number_format($trackingJumat['best_jumat_val'] ?? 0, 0, ',', '.') }}</span>
                            </div>

                            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Performa Broadcast WhatsApp</span>
                                <span class="text-2xl font-black font-montserrat text-slate-800 mt-1 block">
                                    {{ $trackingJumat['read_rate'] ?? 0 }}% Read Rate
                                </span>
                                <span class="text-xs text-slate-500 mt-1 block">{{ $trackingJumat['total_read'] ?? 0 }} dibaca dari {{ $trackingJumat['total_blast'] ?? 0 }} blast terkirim</span>
                            </div>
                        </div>

                        <!-- Form Filter Bulan & Tahun -->
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
                            <div>
                                <h2 class="text-sm font-montserrat font-black uppercase tracking-wider text-slate-800">
                                    Tracking Donasi per Pekan Jumat ({{ $namaBulan[$selMonth] ?? '' }} {{ $selYear }})
                                </h2>
                                <p class="text-xs text-slate-600 font-medium mt-0.5">
                                    Donasi dipecah menjadi 5 pekan Jumat (J1 - J5) disandingkan dengan status penerimaan blast Everpro.
                                </p>
                            </div>

                            <div class="flex items-center gap-2 flex-wrap">
                                <form method="GET" action="{{ route('admin.harpa.laporan') }}" class="flex items-center gap-2">
                                    <input type="hidden" name="tab" value="tracking_jumat">
                                    
                                    <label for="jumat_month" class="sr-only">Pilih Bulan</label>
                                    <select id="jumat_month" name="month" class="min-h-[44px] bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 outline-none focus:border-primary-600">
                                        @foreach($namaBulan as $mNum => $mLabel)
                                            <option value="{{ $mNum }}" {{ $selMonth == $mNum ? 'selected' : '' }}>{{ $mLabel }}</option>
                                        @endforeach
                                    </select>

                                    <label for="jumat_year" class="sr-only">Pilih Tahun</label>
                                    <input type="number" id="jumat_year" name="year" value="{{ $selYear }}" class="w-20 min-h-[44px] bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 outline-none focus:border-primary-600">

                                    <label for="search_jumat" class="sr-only">Cari Donatur</label>
                                    <input type="text" id="search_jumat" name="search" value="{{ $search }}" placeholder="Cari nama / ID..." class="min-h-[44px] bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 outline-none focus:border-primary-600">

                                    <button type="submit" class="min-h-[44px] px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-slate-800 transition">
                                        Filter
                                    </button>
                                </form>

                                <a href="{{ route('admin.harpa.laporan.export', ['type' => 'tracking_jumat', 'year' => $selYear, 'month' => $selMonth, 'search' => $search]) }}" class="inline-flex items-center min-h-[44px] gap-2 px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs uppercase tracking-wider transition-colors">
                                    <i class="fa-solid fa-file-csv text-emerald-700" aria-hidden="true"></i>
                                    Export CSV
                                </a>
                            </div>
                        </div>

                        @if(empty($trackingJumat['items']))
                            <div class="text-center py-12 text-slate-600">
                                <i class="fa-regular fa-calendar-xmark text-3xl mb-2 text-slate-400" aria-hidden="true"></i>
                                <p class="text-xs font-bold text-slate-700">Tidak ada transaksi donasi yang tercatat pada {{ $namaBulan[$selMonth] ?? '' }} {{ $selYear }}.</p>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="w-full text-right text-xs">
                                    <thead class="bg-slate-50 text-xs font-black uppercase tracking-wider text-slate-600 border-b border-slate-200">
                                        <tr>
                                            <th class="py-3 px-3 text-left">Donatur</th>
                                            <th class="py-3 px-3">J1 (Pekan 1)</th>
                                            <th class="py-3 px-3">J2 (Pekan 2)</th>
                                            <th class="py-3 px-3">J3 (Pekan 3)</th>
                                            <th class="py-3 px-3">J4 (Pekan 4)</th>
                                            <th class="py-3 px-3">J5 (Pekan 5)</th>
                                            <th class="py-3 px-3 bg-slate-100 text-slate-800">Total Bulan</th>
                                            <th class="py-3 px-3 text-center">Blast WA</th>
                                            <th class="py-3 px-3 text-center">Status Blast</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200">
                                        @foreach($trackingJumat['items'] as $item)
                                            <tr class="hover:bg-slate-50 font-mono text-[11px]">
                                                <td class="py-3 px-3 text-left font-sans">
                                                    <span class="font-bold text-primary-700 block">{{ $item['donatur_id'] }}</span>
                                                    <span class="text-slate-800 font-semibold block truncate max-w-[160px]">{{ $item['nama_lengkap'] }}</span>
                                                </td>
                                                <td class="py-3 px-3 {{ $item['j1'] > 0 ? 'text-slate-900 font-bold' : 'text-slate-300' }}">
                                                    {{ $item['j1'] > 0 ? number_format($item['j1'], 0, ',', '.') : '-' }}
                                                </td>
                                                <td class="py-3 px-3 {{ $item['j2'] > 0 ? 'text-slate-900 font-bold' : 'text-slate-300' }}">
                                                    {{ $item['j2'] > 0 ? number_format($item['j2'], 0, ',', '.') : '-' }}
                                                </td>
                                                <td class="py-3 px-3 {{ $item['j3'] > 0 ? 'text-slate-900 font-bold' : 'text-slate-300' }}">
                                                    {{ $item['j3'] > 0 ? number_format($item['j3'], 0, ',', '.') : '-' }}
                                                </td>
                                                <td class="py-3 px-3 {{ $item['j4'] > 0 ? 'text-slate-900 font-bold' : 'text-slate-300' }}">
                                                    {{ $item['j4'] > 0 ? number_format($item['j4'], 0, ',', '.') : '-' }}
                                                </td>
                                                <td class="py-3 px-3 {{ $item['j5'] > 0 ? 'text-slate-900 font-bold' : 'text-slate-300' }}">
                                                    {{ $item['j5'] > 0 ? number_format($item['j5'], 0, ',', '.') : '-' }}
                                                </td>
                                                <td class="py-3 px-3 bg-slate-50 font-bold text-emerald-700">
                                                    Rp {{ number_format($item['total_bulan'], 0, ',', '.') }}
                                                </td>
                                                <td class="py-3 px-3 text-center font-sans text-slate-700">
                                                    @if($item['jml_blast'] > 0)
                                                        <span class="font-semibold">{{ $item['jml_blast'] }}x</span>
                                                        <span class="text-[10px] text-slate-500 block">({{ $item['jml_read'] }} dibaca)</span>
                                                    @else
                                                        <span class="text-slate-400">Tidak ada</span>
                                                    @endif
                                                </td>
                                                <td class="py-3 px-3 text-center font-sans">
                                                    @if($item['status_terakhir'] === 'READ')
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">READ</span>
                                                    @elseif($item['status_terakhir'] === 'DELIVERED')
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-sky-100 text-sky-800">DELIVERED</span>
                                                    @elseif($item['status_terakhir'] === 'FAILED')
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800">FAILED</span>
                                                    @else
                                                        <span class="text-slate-400">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-slate-100 text-slate-900 font-black border-t-2 border-slate-300">
                                        <tr class="font-mono text-xs">
                                            <td class="py-3 px-3 text-left font-sans uppercase">TOTAL PEKAN JUMAT</td>
                                            <td class="py-3 px-3">{{ number_format($trackingJumat['jumat_totals'][1], 0, ',', '.') }}</td>
                                            <td class="py-3 px-3">{{ number_format($trackingJumat['jumat_totals'][2], 0, ',', '.') }}</td>
                                            <td class="py-3 px-3">{{ number_format($trackingJumat['jumat_totals'][3], 0, ',', '.') }}</td>
                                            <td class="py-3 px-3">{{ number_format($trackingJumat['jumat_totals'][4], 0, ',', '.') }}</td>
                                            <td class="py-3 px-3">{{ number_format($trackingJumat['jumat_totals'][5], 0, ',', '.') }}</td>
                                            <td class="py-3 px-3 bg-slate-200 text-emerald-800 font-black text-right">
                                                Rp {{ number_format($trackingJumat['grand_total'], 0, ',', '.') }}
                                            </td>
                                            <td colspan="2"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- MODAL INPUT NOMOR BARU CRM -->
    <div x-show="openModal" 
        x-cloak
        @keydown.escape.window="openModal = false"
        class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        
        <div class="bg-white rounded-2xl border border-slate-200 max-w-md w-full p-6 shadow-xl space-y-4" @click.outside="openModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                <h3 class="text-sm font-montserrat font-black uppercase tracking-wider text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-phone text-[#f7ad1a]" aria-hidden="true"></i> Perbarui Nomor Donatur
                </h3>
                <button type="button" @click="openModal = false" class="text-slate-400 hover:text-slate-700 min-h-[44px] min-w-[44px] flex items-center justify-center cursor-pointer" aria-label="Tutup Dialog">
                    <i class="fa-solid fa-xmark text-lg" aria-hidden="true"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.harpa.laporan.update_nomor_baru') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="donatur_id" :value="selectedDonatur">
                <input type="hidden" name="no_hp_lama" :value="oldPhone">

                <div>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">ID Donatur</span>
                    <span class="font-mono font-bold text-primary-700 text-sm" x-text="selectedDonatur"></span>
                </div>

                <div>
                    <label for="modal_new_phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Nomor WhatsApp Baru <span class="text-rose-600">*</span>
                    </label>
                    <input type="text" id="modal_new_phone" x-ref="newPhoneInput" name="nomor_baru" x-model="nomorBaru" required placeholder="Contoh: 08123456789 atau 62812..." class="w-full min-h-[44px] bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 outline-none focus:border-primary-600 font-mono">
                    <span class="text-[11px] text-slate-500 mt-1 block">Nomor akan otomatis dinormalisasi ke format internasional (628...).</span>
                </div>

                <div>
                    <label for="modal_pic_crm" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        PIC CRM yang Menghubungi
                    </label>
                    <input type="text" id="modal_pic_crm" name="pic_crm" x-model="picCrm" placeholder="Nama Petugas CRM" class="w-full min-h-[44px] bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 outline-none focus:border-primary-600">
                </div>

                <div>
                    <label for="modal_catatan" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Catatan Hasil Komunikasi
                    </label>
                    <textarea id="modal_catatan" name="catatan" rows="2" placeholder="Contoh: Ditelepon biasa, donatur mengonfirmasi nomor baru ini aktif WA." class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 outline-none focus:border-primary-600"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-200">
                    <button type="button" @click="openModal = false" class="min-h-[44px] px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs uppercase tracking-wider cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="min-h-[44px] px-5 py-2.5 rounded-xl bg-[#063f5c] hover:bg-slate-800 text-white font-bold text-xs uppercase tracking-wider transition-colors cursor-pointer flex items-center gap-2">
                        <i class="fa-solid fa-floppy-disk text-[#f7ad1a]" aria-hidden="true"></i>
                        Simpan Nomor Baru
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
