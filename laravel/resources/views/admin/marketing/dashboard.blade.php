@extends('layouts.app')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-20">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar Menu -->
        <div class="hidden lg:block lg:col-span-1 space-y-6">
            @include('admin.sidebar', ['active' => 'marketing_dashboard'])
        </div>

        <!-- Dashboard Content -->
        <div class="lg:col-span-3 space-y-8">
            
            <!-- Header section -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div class="space-y-2">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-[#063f5c] text-white rounded-full text-xs font-black uppercase tracking-widest shadow-md">
                        <i class="fa-brands fa-meta"></i> Marketing &amp; Meta Ads Intelligence
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-montserrat font-black text-slate-900 uppercase tracking-tight italic">
                        Dashboard <span class="text-primary-600">Performa Iklan &amp; Keputusan</span>
                    </h1>
                    <p class="text-slate-600 font-bold text-xs tracking-widest uppercase">
                        Analisa Biaya Iklan, Hasil Donasi, ROAS, dan Rekomendasi Keputusan (Scale / Maintain / Stop)
                    </p>
                    <div class="w-12 h-1 bg-primary-500 rounded-full mt-2"></div>
                </div>

                <!-- Quick Action Buttons -->
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('admin.marketing.create') }}" 
                       class="px-5 py-2.5 min-h-[44px] bg-blue-700 hover:bg-blue-800 text-white rounded-2xl text-xs font-black uppercase tracking-wider shadow-lg shadow-blue-700/20 transition-colors inline-flex items-center gap-2">
                        <i class="fa-solid fa-plus"></i> Input Data Iklan
                    </a>
                    <a href="{{ route('admin.marketing.index') }}" 
                       class="px-4 py-2.5 min-h-[44px] bg-slate-100 text-slate-700 rounded-2xl text-xs font-black uppercase tracking-wider hover:bg-slate-200 transition-colors inline-flex items-center gap-2">
                        <i class="fa-solid fa-table-list"></i> Semua Data
                    </a>
                    <a href="{{ route('admin.marketing.export', request()->query()) }}" 
                       class="px-4 py-2.5 min-h-[44px] bg-white text-slate-800 border border-slate-300 rounded-xl text-xs font-black uppercase tracking-wider hover:border-[#063f5c] hover:text-[#063f5c] transition-colors inline-flex items-center gap-2">
                        <i class="fa-solid fa-file-csv" aria-hidden="true"></i> Unduh CSV
                    </a>
                </div>
            </div>

            <!-- Filter Card -->
            <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm">
                <form method="GET" action="{{ route('admin.marketing.dashboard') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label for="mkt_dash_platform" class="block text-xs font-black uppercase tracking-wider text-slate-600 mb-1.5">Platform Iklan</label>
                        <select id="mkt_dash_platform" name="platform" class="w-full min-h-[44px] text-xs font-bold text-slate-700 rounded-xl border-slate-300 focus:ring-primary-500 focus:border-primary-500">
                            <option value="ALL">Semua Platform</option>
                            <option value="Meta Ads" {{ $platform === 'Meta Ads' ? 'selected' : '' }}>Meta Ads (FB &amp; IG)</option>
                            <option value="Google Ads" {{ $platform === 'Google Ads' ? 'selected' : '' }}>Google Ads</option>
                            <option value="TikTok Ads" {{ $platform === 'TikTok Ads' ? 'selected' : '' }}>TikTok Ads</option>
                            <option value="YouTube Ads" {{ $platform === 'YouTube Ads' ? 'selected' : '' }}>YouTube Ads</option>
                            <option value="Lainnya" {{ $platform === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                    <div>
                        <label for="mkt_dash_campaign" class="block text-xs font-black uppercase tracking-wider text-slate-600 mb-1.5">Kampanye Terkait</label>
                        <select id="mkt_dash_campaign" name="campaign_id" class="w-full min-h-[44px] text-xs font-bold text-slate-700 rounded-xl border-slate-300 focus:ring-primary-500 focus:border-primary-500">
                            <option value="">Semua Kampanye</option>
                            @foreach($allCampaigns as $c)
                                <option value="{{ $c->id }}" {{ (string)$campaignId === (string)$c->id ? 'selected' : '' }}>{{ $c->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="mkt_dash_from" class="block text-xs font-black uppercase tracking-wider text-slate-600 mb-1.5">Rentang Tanggal</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input id="mkt_dash_from" type="date" name="date_from" value="{{ $dateFrom }}" class="w-full min-h-[44px] text-xs font-bold text-slate-700 rounded-xl border-slate-300">
                            <input id="mkt_dash_to" type="date" name="date_to" aria-label="Tanggal akhir" value="{{ $dateTo }}" class="w-full min-h-[44px] text-xs font-bold text-slate-700 rounded-xl border-slate-300">
                        </div>
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 py-2.5 px-4 min-h-[44px] bg-slate-900 text-white rounded-xl text-xs font-black uppercase tracking-wider hover:bg-slate-800 transition-colors inline-flex items-center justify-center gap-2">
                            <i class="fa-solid fa-filter"></i> Terapkan
                        </button>
                        <a href="{{ route('admin.marketing.dashboard') }}" aria-label="Reset Filter" class="w-11 h-11 bg-slate-100 text-slate-700 rounded-xl text-xs font-black hover:bg-slate-200 transition-colors inline-flex items-center justify-center" title="Reset Filter">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    </div>
                </form>
            </div>

            <!-- KPI Summary Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- 1. Total Spend -->
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden group">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-black uppercase tracking-widest text-slate-600">Total Biaya Iklan</span>
                        <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-700 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-wallet"></i>
                        </div>
                    </div>
                    <div class="text-xl sm:text-2xl font-black text-slate-800">
                        Rp {{ number_format($totalSpend, 0, ',', '.') }}
                    </div>
                    <div class="mt-2 text-xs font-bold text-slate-600">
                        CPM: <span class="text-slate-700 font-black">Rp {{ number_format($avgCpm, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- 2. Total Donasi Didapat -->
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden group">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-black uppercase tracking-widest text-slate-600">Hasil Donasi Didapat</span>
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-hand-holding-dollar"></i>
                        </div>
                    </div>
                    <div class="text-xl sm:text-2xl font-black text-emerald-700">
                        Rp {{ number_format($totalDonations, 0, ',', '.') }}
                    </div>
                    <div class="mt-2 text-xs font-bold text-slate-600">
                        Konversi Donatur: <span class="text-slate-700 font-black">{{ number_format($totalConversions) }}</span>
                    </div>
                </div>

                <!-- 3. Overall ROAS -->
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden group">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-black uppercase tracking-widest text-slate-600">Return On Ad Spend</span>
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-chart-line"></i>
                        </div>
                    </div>
                    <div class="text-xl sm:text-2xl font-black {{ $overallRoas >= 2.0 ? 'text-emerald-700' : ($overallRoas >= 1.0 ? 'text-amber-800' : 'text-rose-700') }}">
                        {{ number_format($overallRoas, 2) }}x
                    </div>
                    <div class="mt-2 text-xs font-bold text-slate-600">
                        CPA Rata-rata: <span class="text-slate-700 font-black">Rp {{ number_format($avgCpa, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- 4. Trafik Clicks & CTR -->
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden group">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-black uppercase tracking-widest text-slate-600">Trafik &amp; CTR</span>
                        <div class="w-8 h-8 rounded-xl bg-primary-50 text-[#063f5c] flex items-center justify-center text-xs">
                            <i class="fa-solid fa-arrow-pointer" aria-hidden="true"></i>
                        </div>
                    </div>
                    <div class="text-xl sm:text-2xl font-black text-slate-800">
                        {{ number_format($totalClicks) }} <span class="text-xs font-bold text-[#063f5c]">({{ $overallCtr }}%)</span>
                    </div>
                    <div class="mt-2 text-xs font-bold text-slate-600">
                        CPC: <span class="text-slate-700 font-black">Rp {{ number_format($avgCpc, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Decision Summary Banner -->
            <div class="bg-[#063f5c] rounded-[2rem] p-6 text-white shadow-xl relative overflow-hidden">
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-[#f7ad1a] mb-1">
                            <i class="fa-solid fa-microchip" aria-hidden="true"></i> Engine Rekomendasi Keputusan
                        </div>
                        <h2 class="text-xl font-black">Evaluasi Otomatis Berdasarkan ROAS, CPA &amp; CTR</h2>
                        <p class="text-xs text-slate-200 mt-1 max-w-xl">
                            Algoritma menganalisa performa iklan: <strong>SCALE</strong> jika ROAS &ge; 3.0x atau CPA murah, <strong>MAINTAIN</strong> jika performa stabil, dan <strong>STOP</strong> jika biaya tinggi tanpa hasil donasi memadai.
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <div class="bg-emerald-900 border border-emerald-700 px-4 py-3 rounded-2xl text-center min-w-[90px]">
                            <div class="text-xs font-black uppercase tracking-wider text-emerald-200">SCALE</div>
                            <div class="text-2xl font-black text-white mt-0.5">{{ $scaleCount }}</div>
                            <div class="text-xs text-emerald-100">Naikkan Budget</div>
                        </div>
                        <div class="bg-amber-900 border border-amber-700 px-4 py-3 rounded-2xl text-center min-w-[90px]">
                            <div class="text-xs font-black uppercase tracking-wider text-amber-200">MAINTAIN</div>
                            <div class="text-2xl font-black text-white mt-0.5">{{ $maintainCount }}</div>
                            <div class="text-xs text-amber-100">Optimasi Materi</div>
                        </div>
                        <div class="bg-rose-900 border border-rose-700 px-4 py-3 rounded-2xl text-center min-w-[90px]">
                            <div class="text-xs font-black uppercase tracking-wider text-rose-200">STOP</div>
                            <div class="text-2xl font-black text-white mt-0.5">{{ $stopCount }}</div>
                            <div class="text-xs text-rose-100">Matikan Iklan</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Visual Analytics: Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- 1. DIAGRAM BATANG (BAR CHART): Biaya Iklan vs Hasil Donasi -->
                <div class="lg:col-span-2 bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-base font-black text-slate-900 uppercase tracking-tight flex items-center gap-2">
                                <i class="fa-solid fa-chart-column text-primary-600"></i> Diagram Batang: Biaya Iklan vs Donasi
                            </h2>
                            <p class="text-xs text-slate-600 font-bold uppercase tracking-wider">Perbandingan harian biaya iklan yang dikeluarkan dengan donasi terkumpul</p>
                        </div>
                        <span class="px-3 py-1 bg-primary-50 text-primary-700 rounded-full text-xs font-black uppercase">
                            Harian
                        </span>
                    </div>

                    <div class="relative h-72 w-full">
                        <canvas id="barSpendVsDonationChart"></canvas>
                    </div>
                </div>

                <!-- 2. PIE / DONUT CHARTS: Distribusi Biaya Iklan -->
                <div class="space-y-6">
                    <!-- Pie Chart 1: Spend per Campaign -->
                    <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm space-y-4">
                        <div>
                            <h2 class="text-sm font-black text-slate-900 uppercase tracking-tight flex items-center gap-2">
                                <i class="fa-solid fa-chart-pie text-amber-600"></i> Porsi Biaya per Kampanye
                            </h2>
                            <p class="text-xs text-slate-600 font-bold uppercase tracking-wider">Distribusi alokasi anggaran iklan</p>
                        </div>
                        <div class="relative h-56 w-full flex items-center justify-center">
                            @if(count($pieCampaignData) > 0)
                                <canvas id="pieCampaignChart"></canvas>
                            @else
                                <div class="text-center text-slate-600 text-xs py-8">Belum ada data iklan</div>
                            @endif
                        </div>
                    </div>

                    <!-- Pie Chart 2: Spend per Platform -->
                    <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm space-y-4">
                        <div>
                            <h2 class="text-sm font-black text-slate-900 uppercase tracking-tight flex items-center gap-2">
                                <i class="fa-brands fa-meta text-blue-700"></i> Porsi per Platform
                            </h2>
                            <p class="text-xs text-slate-600 font-bold uppercase tracking-wider">Meta Ads vs Google vs Platform Lain</p>
                        </div>
                        <div class="relative h-56 w-full flex items-center justify-center">
                            @if(count($piePlatformData) > 0)
                                <canvas id="piePlatformChart"></canvas>
                            @else
                                <div class="text-center text-slate-600 text-xs py-8">Belum ada data iklan</div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

            <!-- Rekomendasi Keputusan Highlight (SCALE vs STOP) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- SCALE Highlights -->
                <div class="bg-white rounded-[2rem] p-6 border border-emerald-100 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-emerald-50">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-xl bg-emerald-700 text-white flex items-center justify-center text-xs">
                                <i class="fa-solid fa-arrow-trend-up"></i>
                            </div>
                            <div>
                                <h2 class="text-sm font-black text-slate-800 uppercase tracking-tight">Rekomendasi SCALE UP (Naikkan Budget)</h2>
                                <p class="text-xs text-emerald-700 font-bold">Iklan dengan ROAS tinggi dan CPA murah</p>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-slate-600 tabular-nums">{{ $scaleHighlights->count() }} Iklan</span>
                    </div>

                    <div class="space-y-3">
                        @forelse($scaleHighlights as $item)
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 hover:border-emerald-200 transition-colors flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <div class="text-xs font-black text-slate-800 truncate">{{ $item->campaign_name }}</div>
                                <div class="text-xs text-slate-600 flex items-center gap-2 mt-0.5">
                                    <span class="font-bold text-blue-700">{{ $item->platform }}</span>
                                    <span>-</span>
                                    <span>Spend: Rp {{ number_format($item->spend, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <div class="text-xs font-black text-emerald-700">{{ $item->roas }}x ROAS</div>
                                <div class="text-xs font-bold text-slate-600">Hasil: Rp {{ number_format($item->donation_amount, 0, ',', '.') }}</div>
                            </div>
                        </div>
                        @empty
                        <div class="py-6 text-center text-slate-600 text-xs">Belum ada iklan dengan status SCALE</div>
                        @endforelse
                    </div>
                </div>

                <!-- STOP Highlights -->
                <div class="bg-white rounded-[2rem] p-6 border border-rose-100 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-rose-50">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-xl bg-rose-700 text-white flex items-center justify-center text-xs">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            </div>
                            <div>
                                <h2 class="text-sm font-black text-slate-800 uppercase tracking-tight">Rekomendasi STOP (Evaluasi / Matikan)</h2>
                                <p class="text-xs text-rose-700 font-bold">Biaya sudah keluar namun ROAS rendah (&lt; 1.0x)</p>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-slate-600 tabular-nums">{{ $stopHighlights->count() }} Iklan</span>
                    </div>

                    <div class="space-y-3">
                        @forelse($stopHighlights as $item)
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 hover:border-rose-200 transition-all flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <div class="text-xs font-black text-slate-800 truncate">{{ $item->campaign_name }}</div>
                                <div class="text-xs text-slate-600 flex items-center gap-2 mt-0.5">
                                    <span class="font-bold text-blue-700">{{ $item->platform }}</span>
                                    <span>-</span>
                                    <span>Spend: Rp {{ number_format($item->spend, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <div class="text-xs font-black text-rose-700">{{ $item->roas }}x ROAS</div>
                                <div class="text-xs font-bold text-slate-600">CTR: {{ $item->ctr }}%</div>
                            </div>
                        </div>
                        @empty
                        <div class="py-6 text-center text-slate-600 text-xs">Tidak ada iklan yang perlu di-stop</div>
                        @endforelse
                    </div>
                </div>

            </div>

            <!-- Tabel Data Iklan Terbaru -->
            <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-black text-slate-900 uppercase tracking-tight flex items-center gap-2">
                            <i class="fa-solid fa-clock-rotate-left text-primary-600"></i> Catatan Iklan Terbaru
                        </h2>
                        <p class="text-xs text-slate-600 font-bold uppercase tracking-wider">Menampilkan 8 input data performa iklan terakhir</p>
                    </div>
                    <a href="{{ route('admin.marketing.index') }}" class="min-h-[44px] inline-flex items-center text-xs font-black text-primary-700 hover:text-primary-800 gap-1">
                        Lihat Semua <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-slate-100 text-xs font-black uppercase tracking-wider text-slate-600">
                                <th class="py-3 px-3">Tanggal</th>
                                <th class="py-3 px-3">Platform</th>
                                <th class="py-3 px-3">Nama Campaign</th>
                                <th class="py-3 px-3 text-right">Biaya (Spend)</th>
                                <th class="py-3 px-3 text-right">Donasi Didapat</th>
                                <th class="py-3 px-3 text-center">ROAS</th>
                                <th class="py-3 px-3 text-center">CTR</th>
                                <th class="py-3 px-3 text-center">Keputusan</th>
                                <th class="py-3 px-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($recentAds as $ad)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="py-3 px-3 font-bold text-slate-700">{{ $ad->date ? $ad->date->format('d M Y') : '-' }}</td>
                                <td class="py-3 px-3">
                                    <span class="text-xs font-bold {{ $ad->platform === 'Meta Ads' ? 'text-[#063f5c]' : ($ad->platform === 'Google Ads' ? 'text-rose-700' : 'text-slate-600') }}">
                                        {{ $ad->platform }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 font-black text-slate-800 max-w-xs truncate">
                                    {{ $ad->campaign_name }}
                                    @if($ad->campaign)
                                        <div class="text-xs text-slate-600 font-bold truncate">Web: {{ $ad->campaign->title }}</div>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-right font-black text-slate-800">Rp {{ number_format($ad->spend, 0, ',', '.') }}</td>
                                <td class="py-3 px-3 text-right font-black text-emerald-700">Rp {{ number_format($ad->donation_amount, 0, ',', '.') }}</td>
                                <td class="py-3 px-3 text-center">
                                    <span class="font-black {{ $ad->roas >= 2.0 ? 'text-emerald-700' : ($ad->roas >= 1.0 ? 'text-amber-800' : 'text-rose-700') }}">
                                        {{ $ad->roas }}x
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-center font-bold text-slate-600">{{ $ad->ctr }}%</td>
                                <td class="py-3 px-3 text-center">
                                    @if($ad->decision === 'SCALE')
                                        <span class="text-xs font-black uppercase text-emerald-700">SCALE</span>
                                    @elseif($ad->decision === 'STOP')
                                        <span class="text-xs font-black uppercase text-rose-700">STOP</span>
                                    @else
                                        <span class="text-xs font-black uppercase text-amber-800">MAINTAIN</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-center">
                                    <a href="{{ route('admin.marketing.edit', $ad->id) }}" aria-label="Edit Data" class="w-11 h-11 inline-flex items-center justify-center rounded-xl text-slate-600 hover:text-primary-700 hover:bg-slate-100 transition-colors" title="Edit Data">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="py-8 text-center text-slate-600 font-bold">
                                    Belum ada data performa iklan. Klik "Input Data Iklan" di atas untuk menambahkan data pertama.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- ChartJS Library & Initialization -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // 1. Diagram Batang (Bar Chart): Spend vs Donasi
        const barCtx = document.getElementById('barSpendVsDonationChart');
        if (barCtx) {
            const barLabels = @json($barDates);
            const barSpends = @json($barSpends);
            const barDonations = @json($barDonations);

            new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: barLabels,
                    datasets: [
                        {
                            label: 'Biaya Iklan (Spend)',
                            data: barSpends,
                            backgroundColor: '#063f5c',
                            borderRadius: 6,
                            maxBarThickness: 32
                        },
                        {
                            label: 'Hasil Donasi',
                            data: barDonations,
                            backgroundColor: '#f7ad1a',
                            borderRadius: 6,
                            maxBarThickness: 32
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                font: { size: 11, weight: 'bold' },
                                boxWidth: 12
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    if (value >= 1000000) {
                                        return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                                    } else if (value >= 1000) {
                                        return 'Rp ' + (value / 1000).toFixed(0) + 'K';
                                    }
                                    return 'Rp ' + value;
                                },
                                font: { size: 10 }
                            },
                            grid: {
                                color: '#f1f5f9'
                            }
                        },
                        x: {
                            ticks: {
                                font: { size: 10, weight: 'bold' }
                            },
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // 2. Pie Chart 1: Spend per Campaign
        const pieCampCtx = document.getElementById('pieCampaignChart');
        if (pieCampCtx) {
            const campLabels = @json($pieCampaignLabels);
            const campData = @json($pieCampaignData);

            new Chart(pieCampCtx, {
                type: 'doughnut',
                data: {
                    labels: campLabels,
                    datasets: [{
                        data: campData,
                        backgroundColor: ['#063f5c', '#f7ad1a', '#047857', '#0284c7', '#be123c', '#0f766e'],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: { size: 11, weight: 'bold' },
                                boxWidth: 10
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let val = context.parsed || 0;
                                    return context.label + ': Rp ' + val.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        }

        // 3. Pie Chart 2: Spend per Platform
        const piePlatCtx = document.getElementById('piePlatformChart');
        if (piePlatCtx) {
            const platLabels = @json($piePlatformLabels);
            const platData = @json($piePlatformData);

            new Chart(piePlatCtx, {
                type: 'pie',
                data: {
                    labels: platLabels,
                    datasets: [{
                        data: platData,
                        backgroundColor: ['#1877F2', '#EA4335', '#000000', '#FF0000', '#063f5c'],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: { size: 11, weight: 'bold' },
                                boxWidth: 10
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let val = context.parsed || 0;
                                    return context.label + ': Rp ' + val.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection

