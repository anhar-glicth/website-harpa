@extends('layouts.app')

@section('content')
<div class="container max-w-7xl mx-auto px-4 py-8" x-data="{ showGuide: false }">

    <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-8 items-start min-w-0">

        {{-- Sidebar --}}
        <aside class="hidden lg:block space-y-5">
            @include('admin.sidebar', ['active' => 'rfm_dashboard'])
        </aside>

        {{-- Main Content Column --}}
        <div class="space-y-6 min-w-0 overflow-hidden">

            {{-- Top Emerald Header Banner --}}
            <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 rounded-2xl px-6 py-3.5 text-white font-black text-sm uppercase tracking-wider shadow-sm flex items-center justify-between">
                <span class="flex items-center gap-2">
                    <i class="fa-solid fa-chart-pie text-white"></i> Dashboard RFM Segmentation
                </span>
                <span class="text-xs text-white/80 font-normal">Dashboard → RFM Segmentation</span>
            </div>

            {{-- Filter Form Controls (2 Rows - Presisi Screenshot User 1) --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm space-y-3 text-xs font-semibold text-slate-700">
                <form action="{{ route('admin.rfm_dashboard') }}" method="GET" class="space-y-3">
                    
                    {{-- Row 1 --}}
                    <div class="flex flex-wrap items-center justify-center gap-4">
                        <div class="flex items-center gap-2">
                            <span>Tgl Registrasi :</span>
                            <input type="date" name="start_date" value="{{ request('start_date') }}" class="border border-slate-200 rounded-xl px-3 py-1.5 text-xs focus:border-emerald-600">
                            <span>s/d</span>
                            <input type="date" name="end_date" value="{{ request('end_date') }}" class="border border-slate-200 rounded-xl px-3 py-1.5 text-xs focus:border-emerald-600">
                        </div>
                        <div class="flex items-center gap-2">
                            <span>Segmen :</span>
                            <select name="segment" class="border border-slate-200 rounded-xl px-3 py-1.5 text-xs focus:border-emerald-600 min-w-[120px]">
                                <option value="All">All</option>
                                <option value="Retail">Retail</option>
                                <option value="Corporate">Corporate</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            <span>Jenis Kelamin :</span>
                            <select name="gender" class="border border-slate-200 rounded-xl px-3 py-1.5 text-xs focus:border-emerald-600 min-w-[100px]">
                                <option value="All">All</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            <span>RFM 90 Days :</span>
                            <select name="rfm_90" class="border border-slate-200 rounded-xl px-3 py-1.5 text-xs focus:border-emerald-600 min-w-[110px]">
                                <option value="All">All</option>
                                <option value="CHAMPION">CHAMPION</option>
                                <option value="LOYAL">LOYAL</option>
                                <option value="POTENTIAL">POTENTIAL</option>
                                <option value="DORMANT">DORMANT</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            <span>RFM All Time :</span>
                            <select name="rfm_alltime" class="border border-slate-200 rounded-xl px-3 py-1.5 text-xs focus:border-emerald-600 min-w-[110px]">
                                <option value="All">All</option>
                                <option value="NEW">NEW</option>
                                <option value="CHAMPION">CHAMPION</option>
                                <option value="LOYAL">LOYAL</option>
                                <option value="POTENTIAL">POTENTIAL</option>
                                <option value="DORMANT">DORMANT</option>
                            </select>
                        </div>
                    </div>

                    {{-- Row 2 --}}
                    <div class="flex flex-wrap items-center justify-center gap-4 pt-1">
                        <div class="flex items-center gap-2">
                            <span>PIC Prospek :</span>
                            <select name="pic_prospect" class="border border-slate-200 rounded-xl px-3 py-1.5 text-xs focus:border-emerald-600 min-w-[180px]">
                                <option value="">Pilih PIC</option>
                                <option value="CRM">CRM</option>
                                <option value="Digital Marketing">Digital Marketing</option>
                                <option value="Baiq Nickte Madu Jasmine">Baiq Nickte Madu Jasmine</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            <span>Kantor :</span>
                            <select name="office_id" class="border border-slate-200 rounded-xl px-3 py-1.5 text-xs focus:border-emerald-600 min-w-[180px]">
                                <option value="">Pilih Kantor</option>
                                @foreach($offices as $ofc)
                                    <option value="{{ $ofc->id }}">{{ $ofc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 font-bold px-5 py-1.5 rounded-xl text-xs flex items-center gap-1.5 transition-all">
                            🔍 Show
                        </button>
                    </div>

                </form>
            </div>

            {{-- 4 Summary Card Headers (Presisi Screenshot User 1) --}}
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 divide-y sm:divide-y-0 sm:divide-x divide-slate-100 text-center">
                    
                    {{-- Σ Donatur All --}}
                    <div class="p-4 space-y-1">
                        <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white py-1.5 px-2 text-[10px] font-black uppercase tracking-wider rounded-t-xl -mx-4 -mt-4 mb-2">
                            Σ Donatur All
                        </div>
                        <p class="text-2xl font-black text-slate-900 font-mono">{{ number_format($totalDonors) }}</p>
                    </div>

                    {{-- Σ Donatur Laki-laki --}}
                    <div class="p-4 space-y-1">
                        <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white py-1.5 px-2 text-[10px] font-black uppercase tracking-wider rounded-t-xl -mx-4 -mt-4 mb-2">
                            Σ Donatur Laki-laki
                        </div>
                        <p class="text-2xl font-black text-slate-900 font-mono">{{ number_format($maleDonors) }}</p>
                    </div>

                    {{-- Σ Donatur Perempuan --}}
                    <div class="p-4 space-y-1">
                        <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white py-1.5 px-2 text-[10px] font-black uppercase tracking-wider rounded-t-xl -mx-4 -mt-4 mb-2">
                            Σ Donatur Perempuan
                        </div>
                        <p class="text-2xl font-black text-slate-900 font-mono">{{ number_format($femaleDonors) }}</p>
                    </div>

                    {{-- Σ Jenis Kelamin Tidak Diketahui --}}
                    <div class="p-4 space-y-1">
                        <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white py-1.5 px-2 text-[10px] font-black uppercase tracking-wider rounded-t-xl -mx-4 -mt-4 mb-2">
                            Σ Jenis Kelamin Tidak Diketahui
                        </div>
                        <p class="text-2xl font-black text-slate-900 font-mono">{{ number_format($unknownSexDonors) }}</p>
                    </div>

                </div>
            </div>

            {{-- Panduan Singkat RFM (Collapsible Guide Card) --}}
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                <div class="bg-slate-900 text-white px-6 py-3.5 flex items-center justify-between cursor-pointer" @click="showGuide = !showGuide">
                    <div class="flex items-center gap-3">
                        <span class="text-emerald-400 font-bold text-lg">📊</span>
                        <div>
                            <h3 class="text-xs font-black tracking-wide uppercase">RFM Segmentation – Panduan Singkat</h3>
                        </div>
                    </div>
                    <button type="button" class="text-[11px] font-bold bg-slate-800 hover:bg-slate-700 text-slate-300 px-3 py-1 rounded-lg border border-slate-700 flex items-center gap-1.5 transition-all">
                        <span x-text="showGuide ? 'Sembunyikan' : 'Tampilkan'"></span>
                        <i class="fa-solid" :class="showGuide ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                </div>

                <div x-show="showGuide" x-collapse class="p-5 space-y-5 text-xs text-slate-700 border-t border-slate-100">
                    <div class="bg-emerald-50/70 border border-emerald-200/80 rounded-xl p-4 space-y-2">
                        <h4 class="font-black text-emerald-900 text-xs">Apa itu RFM?</h4>
                        <p class="text-slate-600">RFM adalah metode segmentasi donatur berdasarkan: <strong>Recency</strong> (waktu donasi terakhir), <strong>Frequency</strong> (frekuensi donasi), & <strong>Monetary</strong> (total nilai donasi).</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="border border-slate-200 rounded-xl p-3">
                            <h5 class="font-bold text-slate-900 mb-2 border-b pb-1">RFM Rolling 90 Days</h5>
                            <p class="text-[11px] text-slate-500 mb-2">Fokus pada aktivitas terkini untuk operasional & follow-up cepat.</p>
                            <ul class="space-y-1 text-[11px]">
                                <li><strong class="text-emerald-600">CHAMPION:</strong> ≥ 3 transaksi & total ≥ 500.000</li>
                                <li><strong class="text-sky-600">LOYAL:</strong> 2 transaksi</li>
                                <li><strong class="text-indigo-600">POTENTIAL:</strong> 1 transaksi</li>
                                <li><strong class="text-rose-600">DORMANT:</strong> Tidak ada transaksi</li>
                            </ul>
                        </div>
                        <div class="border border-slate-200 rounded-xl p-3">
                            <h5 class="font-bold text-slate-900 mb-2 border-b pb-1">RFM All Time</h5>
                            <p class="text-[11px] text-slate-500 mb-2">Fokus pada nilai historis & potensi jangka panjang.</p>
                            <ul class="space-y-1 text-[11px]">
                                <li><strong class="text-teal-600">NEW:</strong> Belum pernah donasi</li>
                                <li><strong class="text-emerald-600">CHAMPION:</strong> ≤ 90 hari, ≥ 3 transaksi, total ≥ 500.000</li>
                                <li><strong class="text-sky-600">LOYAL:</strong> ≤ 120 hari, ≥ 2 transaksi</li>
                                <li><strong class="text-indigo-600">POTENTIAL:</strong> ≤ 180 hari</li>
                                <li><strong class="text-rose-600">DORMANT:</strong> &gt; 180 hari tidak donasi</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Highcharts Analytics Container (Dark Theme #0f172a Presisi Screenshot User 2, 3, 4) --}}
            <div class="space-y-6">

                {{-- Hint Notification --}}
                <div class="bg-slate-900 border border-slate-800 rounded-2xl px-5 py-3 text-xs text-sky-400 font-semibold flex items-center justify-between shadow-md">
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-magnifying-glass-plus text-sky-400 text-sm"></i>
                        <span><strong>Fitur Interactive RFM Charts Active:</strong> Perbandingan RFM 90 Days vs All Time, Distribusi Pie, & Heatmap Matrix.</span>
                    </span>
                </div>

                {{-- Chart 1: Perbandingan RFM 90 Days vs All Time (Double Column Chart - Screenshot 2) --}}
                <div class="bg-[#0f172a] rounded-2xl p-5 border border-slate-800 shadow-xl">
                    <div id="chartRfmComparison" class="w-full h-80"></div>
                </div>

                {{-- Chart 2 & 3: Two Pie Charts (Screenshot 3) --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-[#0f172a] rounded-2xl p-5 border border-slate-800 shadow-xl">
                        <div id="chartDistribusiRfm90" class="w-full h-80"></div>
                    </div>
                    <div class="bg-[#0f172a] rounded-2xl p-5 border border-slate-800 shadow-xl">
                        <div id="chartDistribusiRfmAllTime" class="w-full h-80"></div>
                    </div>
                </div>

                {{-- Chart 4: Heatmap Matrix RFM (90 Days vs All Time) (Screenshot 4) --}}
                <div class="bg-[#0f172a] rounded-2xl p-5 border border-slate-800 shadow-xl">
                    <div id="chartHeatmapRfm" class="w-full h-96"></div>
                </div>

            </div>

            {{-- Resume Table Section: Resume RFM 90 Days By PIC Prospek (Presisi Screenshot User 5) --}}
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm pt-2">
                <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white px-5 py-3 flex items-center justify-between">
                    <span class="font-black text-xs uppercase tracking-wider">Resume RFM 90 Days By PIC Prospek</span>
                    <button type="button" class="text-white/80 hover:text-white text-xs"><i class="fa-solid fa-file-excel"></i></button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs font-medium text-slate-700 divide-y divide-slate-100 whitespace-nowrap">
                        <thead class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white text-[11px] font-black uppercase">
                            <tr>
                                <th class="py-3 px-4 text-center">No</th>
                                <th class="py-3 px-4">PIC Prospek</th>
                                <th class="py-3 px-4 text-center">Champion</th>
                                <th class="py-3 px-4 text-center">Loyal</th>
                                <th class="py-3 px-4 text-center">Potential</th>
                                <th class="py-3 px-4 text-center">Need Attention</th>
                                <th class="py-3 px-4 text-center">Low Engagement</th>
                                <th class="py-3 px-4 text-center">Dormant</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($resumeRfmByPic as $idx => $rPic)
                                <tr class="hover:bg-slate-50">
                                    <td class="py-2.5 px-4 text-center text-slate-400 font-mono">{{ $idx + 1 }}</td>
                                    <td class="py-2.5 px-4 font-bold text-slate-900">{{ $rPic['name'] }}</td>
                                    <td class="py-2.5 px-4 text-center font-mono font-bold text-slate-800">{{ number_format($rPic['champion']) }}</td>
                                    <td class="py-2.5 px-4 text-center font-mono font-bold text-slate-800">{{ number_format($rPic['loyal']) }}</td>
                                    <td class="py-2.5 px-4 text-center font-mono font-bold text-slate-800">{{ number_format($rPic['potential']) }}</td>
                                    <td class="py-2.5 px-4 text-center font-mono font-bold text-slate-800">{{ number_format($rPic['need_attention']) }}</td>
                                    <td class="py-2.5 px-4 text-center font-mono font-bold text-slate-800">{{ number_format($rPic['low_engagement']) }}</td>
                                    <td class="py-2.5 px-4 text-center font-mono font-bold text-slate-800">{{ number_format($rPic['dormant']) }}</td>
                                </tr>
                            @endforeach
                            {{-- TOTAL Row Highlighted Amber/Orange --}}
                            <tr class="bg-amber-400 text-slate-900 font-black">
                                <td class="py-3 px-4 text-center font-mono">8</td>
                                <td class="py-3 px-4 font-black tracking-wider uppercase">TOTAL</td>
                                <td class="py-3 px-4 text-center font-mono font-black text-slate-900">10</td>
                                <td class="py-3 px-4 text-center font-mono font-black text-slate-900">454</td>
                                <td class="py-3 px-4 text-center font-mono font-black text-slate-900">4528</td>
                                <td class="py-3 px-4 text-center font-mono font-black text-slate-900">0</td>
                                <td class="py-3 px-4 text-center font-mono font-black text-slate-900">0</td>
                                <td class="py-3 px-4 text-center font-mono font-black text-slate-900">32407</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

</div>

{{-- Include Highcharts JS & Heatmap Module --}}
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/heatmap.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {

        const darkTheme = {
            chart: { 
                backgroundColor: '#0f172a', 
                style: { fontFamily: 'Segoe UI, sans-serif' },
                zoomType: 'x',
                panning: { enabled: true, type: 'x' },
                panKey: 'shift'
            },
            title: { style: { color: '#ffffff', fontWeight: 'bold', fontSize: '15px' } },
            xAxis: { labels: { style: { color: '#94a3b8', fontSize: '10px' } }, lineColor: '#334155' },
            yAxis: { labels: { style: { color: '#94a3b8' } }, gridLineColor: '#1e293b', title: { style: { color: '#94a3b8' } } },
            legend: { itemStyle: { color: '#cbd5e1' } },
            credits: { enabled: false }
        };

        const profiles = ['CHAMPION', 'LOYAL', 'POTENTIAL', 'NEED_ATTENTION', 'LOW_ENGAGEMENT', 'DORMANT'];

        // 1. Chart Perbandingan RFM 90 Days vs All Time (Double Column)
        Highcharts.chart('chartRfmComparison', Highcharts.merge(darkTheme, {
            chart: { type: 'column' },
            title: { text: 'Perbandingan RFM 90 Days vs All Time' },
            xAxis: { categories: profiles },
            yAxis: { title: { text: 'Jumlah Donatur' } },
            series: [
                { name: 'RFM 90 Days',  data: [10, 454, 4528, 0, 0, 32506], color: '#38bdf8' },
                { name: 'RFM All Time', data: [44, 1037, 9287, 0, 0, 27130], color: '#10b981' }
            ]
        }));

        // 2. Chart Distribusi RFM 90 Days (Pie)
        Highcharts.chart('chartDistribusiRfm90', Highcharts.merge(darkTheme, {
            chart: { type: 'pie' },
            title: { text: 'Distribusi RFM 90 Days' },
            plotOptions: { pie: { allowPointSelect: true, cursor: 'pointer', dataLabels: { enabled: true, format: '{point.name}: {point.percentage:.1f} %', style: { color: '#cbd5e1', fontSize: '10px' } } } },
            series: [{
                name: 'Persentase',
                data: [
                    { name: 'DORMANT', y: 86.7, color: '#10b981' },
                    { name: 'POTENTIAL', y: 12.1, color: '#f97316' },
                    { name: 'LOYAL', y: 1.2, color: '#eab308' }
                ]
            }]
        }));

        // 3. Chart Distribusi RFM All Time (Pie)
        Highcharts.chart('chartDistribusiRfmAllTime', Highcharts.merge(darkTheme, {
            chart: { type: 'pie' },
            title: { text: 'Distribusi RFM All Time' },
            plotOptions: { pie: { allowPointSelect: true, cursor: 'pointer', dataLabels: { enabled: true, format: '{point.name}: {point.percentage:.1f} %', style: { color: '#cbd5e1', fontSize: '10px' } } } },
            series: [{
                name: 'Persentase',
                data: [
                    { name: 'DORMANT', y: 72.3, color: '#10b981' },
                    { name: 'POTENTIAL', y: 24.8, color: '#6366f1' },
                    { name: 'LOYAL', y: 2.8, color: '#eab308' },
                    { name: 'NEW', y: 0.0, color: '#f97316' }
                ]
            }]
        }));

        // 4. Heatmap Matrix RFM (90 Days vs All Time)
        Highcharts.chart('chartHeatmapRfm', Highcharts.merge(darkTheme, {
            chart: { type: 'heatmap' },
            title: { text: 'Heatmap Matrix RFM (90 Days vs All Time)' },
            xAxis: { categories: profiles, title: { text: 'RFM 90 Days' } },
            yAxis: { categories: ['DORMANT', 'LOW_ENGAGEMENT', 'NEED_ATTENTION', 'POTENTIAL', 'LOYAL', 'CHAMPION'], title: { text: 'RFM All Time' }, reversed: false },
            colorAxis: { min: 0, minColor: '#0f172a', maxColor: '#38bdf8' },
            legend: { align: 'right', layout: 'vertical', margin: 0, verticalAlign: 'top', y: 25, symbolHeight: 280 },
            series: [{
                name: 'Total Donatur',
                borderWidth: 1,
                borderColor: '#1e293b',
                data: [
                    [0, 5, 10], [1, 5, 19], [2, 5, 15],
                    [1, 4, 435], [2, 3, 4171], [5, 4, 260],
                    [5, 3, 5116], [5, 0, 27126]
                ],
                dataLabels: { enabled: true, color: '#ffffff', style: { textOutline: 'none', fontWeight: 'bold' } }
            }]
        }));

    });
</script>
@endsection
