@extends('layouts.app')

@section('content')
<div class="container max-w-7xl mx-auto px-4 py-8">

    <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-8 items-start min-w-0">

        {{-- Sidebar --}}
        <aside class="hidden lg:block space-y-5">
            @include('admin.sidebar', ['active' => 'transactions_dashboard'])
        </aside>

        {{-- Main Content Column --}}
        <div class="space-y-6 min-w-0 overflow-hidden">

            {{-- Top Tab Navigation Header (Presisi Screenshot User) --}}
            <div class="flex flex-wrap items-center gap-1 border-b border-slate-200 pb-0 overflow-x-auto">
                <a href="{{ route('admin.donors_list') }}" class="px-4 py-2 rounded-t-xl text-xs font-bold bg-slate-100 text-slate-600 hover:bg-slate-200 transition-all">
                    List Donatur
                </a>
                <a href="{{ route('admin.transactions_list') }}" class="px-4 py-2 rounded-t-xl text-xs font-bold bg-slate-100 text-slate-600 hover:bg-slate-200 transition-all">
                    List Transaksi
                </a>
                <a href="{{ route('admin.prospects_list') }}" class="px-4 py-2 rounded-t-xl text-xs font-bold bg-slate-100 text-slate-600 hover:bg-slate-200 transition-all">
                    List Prospek
                </a>
                <a href="{{ route('admin.potential_donors') }}" class="px-4 py-2 rounded-t-xl text-xs font-bold bg-slate-100 text-slate-600 hover:bg-slate-200 transition-all">
                    List Calon Donatur
                </a>
                <a href="{{ route('admin.transactions_dashboard') }}" class="px-4 py-2 rounded-t-xl text-xs font-black bg-emerald-700 text-white shadow-sm">
                    Dashboard Transaksi
                </a>
            </div>

            {{-- Top Red Header Banner --}}
            <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 rounded-2xl px-6 py-3.5 text-white font-black text-sm uppercase tracking-wider shadow-sm flex items-center justify-between">
                <span class="flex items-center gap-2">
                    <i class="fa-solid fa-chart-line text-white"></i> Dashboard Transaksi
                </span>
                <span class="text-xs text-white/80 font-normal">Transaction Management → Dashboard Transaksi</span>
            </div>

            {{-- Filter Form Controls --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm space-y-3 text-xs font-semibold text-slate-700">
                <form action="{{ route('admin.transactions_dashboard') }}" method="GET" class="space-y-3">
                    <div class="flex flex-wrap items-center gap-4">
                        <div class="flex items-center gap-2">
                            <span>Tgl Donasi :</span>
                            <input type="date" name="start_date" value="{{ request('start_date') }}" class="border border-slate-200 rounded-xl px-3 py-1.5 text-xs focus:border-emerald-600">
                            <span>s/d</span>
                            <input type="date" name="end_date" value="{{ request('end_date') }}" class="border border-slate-200 rounded-xl px-3 py-1.5 text-xs focus:border-emerald-600">
                        </div>
                        <div class="flex items-center gap-2">
                            <span>Rumpun Program :</span>
                            <select name="program_group_id" class="border border-slate-200 rounded-xl px-3 py-1.5 text-xs focus:border-emerald-600 min-w-[140px]">
                                <option value="">Semua Rumpun</option>
                                @foreach($programGroups as $pg)
                                    <option value="{{ $pg->id }}">{{ $pg->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            <span>Program :</span>
                            <select name="program_id" class="border border-slate-200 rounded-xl px-3 py-1.5 text-xs focus:border-emerald-600 min-w-[140px]">
                                <option value="">Semua Program</option>
                                @foreach($programs as $prg)
                                    <option value="{{ $prg->id }}">{{ $prg->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            <span>Donatur :</span>
                            <select name="donor_id" class="border border-slate-200 rounded-xl px-3 py-1.5 text-xs focus:border-emerald-600 min-w-[140px]">
                                <option value="">Semua Donatur</option>
                                @foreach($donors as $dnr)
                                    <option value="{{ $dnr->id }}">{{ $dnr->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 font-bold px-4 py-1.5 rounded-xl text-xs flex items-center gap-1.5 transition-all">
                            🔍 Show
                        </button>
                    </div>

                    <div class="flex flex-wrap items-center gap-4 pt-1">
                        <div class="flex items-center gap-2">
                            <span>PIC Prospek :</span>
                            <select name="pic_prospect" class="border border-slate-200 rounded-xl px-3 py-1.5 text-xs focus:border-emerald-600 min-w-[160px]">
                                <option value="">Semua PIC</option>
                                @foreach($picUsers as $pic)
                                    <option value="{{ $pic->name }}" {{ request('pic_prospect') == $pic->name ? 'selected' : '' }}>{{ $pic->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            <span>Kantor :</span>
                            <select name="office_id" class="border border-slate-200 rounded-xl px-3 py-1.5 text-xs focus:border-emerald-600 min-w-[160px]">
                                <option value="">Pilih Kantor</option>
                                @foreach($offices as $ofc)
                                    <option value="{{ $ofc->id }}">{{ $ofc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            {{-- 4 Summary Card Headers --}}
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 divide-y sm:divide-y-0 sm:divide-x divide-slate-100 text-center">
                    <div class="p-4 space-y-1">
                        <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white py-1.5 px-2 text-[10px] font-black uppercase tracking-wider rounded-t-xl -mx-4 -mt-4 mb-2">
                            Σ Capaian Total
                        </div>
                        <p class="text-2xl font-black text-slate-900 font-mono">{{ number_format($totalCapaian, 0, ',', '.') }}</p>
                    </div>

                    <div class="p-4 space-y-1">
                        <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white py-1.5 px-2 text-[10px] font-black uppercase tracking-wider rounded-t-xl -mx-4 -mt-4 mb-2">
                            Σ Donatur
                        </div>
                        <p class="text-2xl font-black text-slate-900 font-mono">{{ number_format($totalDonorsCount) }}</p>
                    </div>

                    <div class="p-4 space-y-1">
                        <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white py-1.5 px-2 text-[10px] font-black uppercase tracking-wider rounded-t-xl -mx-4 -mt-4 mb-2">
                            Σ Transaksi
                        </div>
                        <p class="text-2xl font-black text-slate-900 font-mono">{{ number_format($totalTransactionsCount) }}</p>
                    </div>

                    <div class="p-4 space-y-1">
                        <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white py-1.5 px-2 text-[10px] font-black uppercase tracking-wider rounded-t-xl -mx-4 -mt-4 mb-2">
                            Rata-rata Donasi
                        </div>
                        <p class="text-2xl font-black text-slate-900 font-mono">{{ number_format($averageDonation, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            {{-- Highcharts Analytics Container (With Zoom-In/Out & Pan Controls) --}}
            <div class="space-y-6">

                {{-- Hint Notification for Zooming & Panning --}}
                <div class="bg-slate-900 border border-slate-800 rounded-2xl px-5 py-3 text-xs text-sky-400 font-semibold flex items-center justify-between shadow-md">
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-magnifying-glass-plus text-sky-400 text-sm"></i>
                        <span><strong>Fitur Zoom & Pan Grafik Active:</strong> Klik & drag (tahan mouse) pada area grafik untuk memperbesar (Zoom-In) rentang tanggal/bulan. Tahan tombol <code>Shift</code> sambil drag untuk me-pan area grafik.</span>
                    </span>
                </div>

                {{-- Chart 1: Dashboard Transaksi Per Tanggal --}}
                <div class="bg-[#0f172a] rounded-2xl p-5 border border-slate-800 shadow-xl">
                    <div id="chartPerTanggal" class="w-full h-80"></div>
                </div>

                {{-- Chart 2: Dashboard Transaksi Per Bulan --}}
                <div class="bg-[#0f172a] rounded-2xl p-5 border border-slate-800 shadow-xl">
                    <div id="chartPerBulan" class="w-full h-80"></div>
                </div>

                {{-- Chart 3 & 4 Grid: Per Hari & Per Jam --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-[#0f172a] rounded-2xl p-5 border border-slate-800 shadow-xl">
                        <div id="chartPerHari" class="w-full h-80"></div>
                    </div>
                    <div class="bg-[#0f172a] rounded-2xl p-5 border border-slate-800 shadow-xl">
                        <div id="chartPerJam" class="w-full h-80"></div>
                    </div>
                </div>

                {{-- Chart 5 & 6 Grid: Berdasarkan Rumpun Program & Program --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-[#0f172a] rounded-2xl p-5 border border-slate-800 shadow-xl">
                        <div id="chartPerRumpun" class="w-full h-80"></div>
                    </div>
                    <div class="bg-[#0f172a] rounded-2xl p-5 border border-slate-800 shadow-xl">
                        <div id="chartPerProgram" class="w-full h-80"></div>
                    </div>
                </div>

                {{-- Chart 7 & 8 Grid: Berdasarkan Kantor & PIC Prospek --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-[#0f172a] rounded-2xl p-5 border border-slate-800 shadow-xl">
                        <div id="chartPerKantor" class="w-full h-80"></div>
                    </div>
                    <div class="bg-[#0f172a] rounded-2xl p-5 border border-slate-800 shadow-xl">
                        <div id="chartPerPIC" class="w-full h-80"></div>
                    </div>
                </div>

                {{-- Chart 9 & 10 Grid: Distribusi Via Bayar & Distribusi Channel Donasi (PIE CHARTS) --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-[#0f172a] rounded-2xl p-5 border border-slate-800 shadow-xl">
                        <div id="chartViaBayar" class="w-full h-80"></div>
                    </div>
                    <div class="bg-[#0f172a] rounded-2xl p-5 border border-slate-800 shadow-xl">
                        <div id="chartChannelDonasi" class="w-full h-80"></div>
                    </div>
                </div>

            </div>

            {{-- Resume Tables Section (Matching User Screenshot 2) --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 pt-4">

                {{-- Table 1: Resume Capaian PIC Prospek (Col 7) --}}
                <div class="lg:col-span-7 bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                    <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white px-4 py-2.5 flex items-center justify-between">
                        <span class="font-black text-xs uppercase tracking-wider">Resume Capaian PIC Prospek</span>
                        <button type="button" class="text-white/80 hover:text-white text-xs"><i class="fa-solid fa-file-excel"></i></button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs font-medium text-slate-700 divide-y divide-slate-100">
                            <thead class="bg-slate-50 text-[11px] font-bold text-slate-900 border-b border-slate-200">
                                <tr>
                                    <th class="py-2.5 px-3 text-center">No</th>
                                    <th class="py-2.5 px-3">PIC Prospek</th>
                                    <th class="py-2.5 px-3 text-right">Capaian</th>
                                    <th class="py-2.5 px-3 text-right">% Kontribusi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($resumePicList as $idx => $rPic)
                                    <tr class="hover:bg-slate-50">
                                        <td class="py-2 px-3 text-center text-slate-400 font-mono">{{ $idx + 1 }}</td>
                                        <td class="py-2 px-3 font-semibold text-slate-900">{{ $rPic['name'] }}</td>
                                        <td class="py-2 px-3 text-right font-mono font-bold text-slate-800">{{ number_format($rPic['amount'], 0, ',', '.') }}</td>
                                        <td class="py-2 px-3 text-right font-mono font-bold text-emerald-600">{{ $rPic['percent'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Table 2: Resume Capaian Per Kantor (Col 5) --}}
                <div class="lg:col-span-5 bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                    <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white px-4 py-2.5 flex items-center justify-between">
                        <span class="font-black text-xs uppercase tracking-wider">Resume Capaian Per Kantor</span>
                        <button type="button" class="text-white/80 hover:text-white text-xs"><i class="fa-solid fa-file-excel"></i></button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs font-medium text-slate-700 divide-y divide-slate-100">
                            <thead class="bg-slate-50 text-[11px] font-bold text-slate-900 border-b border-slate-200">
                                <tr>
                                    <th class="py-2.5 px-3 text-center">No</th>
                                    <th class="py-2.5 px-3">Kantor</th>
                                    <th class="py-2.5 px-3 text-right">Capaian</th>
                                    <th class="py-2.5 px-3 text-right">% Kontribusi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($resumeOfficeList as $idx => $rOfc)
                                    <tr class="hover:bg-slate-50">
                                        <td class="py-2 px-3 text-center text-slate-400 font-mono">{{ $idx + 1 }}</td>
                                        <td class="py-2 px-3 font-semibold text-slate-900">{{ $rOfc['name'] }}</td>
                                        <td class="py-2 px-3 text-right font-mono font-bold text-slate-800">{{ number_format($rOfc['amount'], 0, ',', '.') }}</td>
                                        <td class="py-2 px-3 text-right font-mono font-bold text-emerald-600">{{ $rOfc['percent'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            {{-- Table 3: Top 20 Donatur Base On Kontribusi Donasi (Full Width) --}}
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white px-5 py-3 flex items-center justify-between">
                    <span class="font-black text-xs uppercase tracking-wider">Top 20 Donatur Base On Kontribusi Donasi</span>
                    <button type="button" class="text-white/80 hover:text-white text-xs"><i class="fa-solid fa-file-excel"></i></button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs font-medium text-slate-700 divide-y divide-slate-100 whitespace-nowrap">
                        <thead class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white text-[11px] font-black uppercase">
                            <tr>
                                <th class="py-3 px-4 text-center">No</th>
                                <th class="py-3 px-4">Nama Donatur</th>
                                <th class="py-3 px-4">ID Donatur</th>
                                <th class="py-3 px-4">Kantor</th>
                                <th class="py-3 px-4">Segmen</th>
                                <th class="py-3 px-4">Profil</th>
                                <th class="py-3 px-4">PIC Prospek</th>
                                <th class="py-3 px-4 text-right">Total Donasi</th>
                                <th class="py-3 px-4 text-right">% Kontribusi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($topDonorsList as $idx => $td)
                                <tr class="{{ !empty($td['highlight']) ? 'bg-amber-100 font-bold' : 'hover:bg-slate-50' }}">
                                    <td class="py-3 px-4 text-center text-slate-400 font-mono">{{ $idx + 1 }}</td>
                                    <td class="py-3 px-4 font-bold text-slate-900">{{ $td['name'] }}</td>
                                    <td class="py-3 px-4 font-mono text-emerald-700 font-bold">{{ $td['id_donatur'] }}</td>
                                    <td class="py-3 px-4 text-slate-700">{{ $td['office'] }}</td>
                                    <td class="py-3 px-4 text-slate-800">{{ $td['segment'] }}</td>
                                    <td class="py-3 px-4 text-slate-700">{{ $td['profile'] }}</td>
                                    <td class="py-3 px-4 font-bold text-slate-800">{{ $td['pic'] }}</td>
                                    <td class="py-3 px-4 text-right font-mono font-black text-slate-900">{{ number_format($td['amount'], 0, ',', '.') }}</td>
                                    <td class="py-3 px-4 text-right font-mono font-black text-emerald-600">{{ $td['percent'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

</div>

{{-- Include Highcharts JS Library --}}
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {

        const darkTheme = {
            chart: { 
                backgroundColor: '#0f172a', 
                style: { fontFamily: 'Segoe UI, sans-serif' },
                zoomType: 'x',
                panning: { enabled: true, type: 'x' },
                panKey: 'shift',
                resetZoomButton: {
                    position: { align: 'right', x: -10, y: 10 },
                    theme: { 
                        fill: '#1e293b', 
                        stroke: '#38bdf8', 
                        r: 6, 
                        states: { hover: { fill: '#38bdf8', style: { color: '#ffffff' } } }, 
                        style: { color: '#38bdf8', fontWeight: 'bold', fontSize: '11px' } 
                    }
                }
            },
            title: { style: { color: '#ffffff', fontWeight: 'bold', fontSize: '15px' } },
            xAxis: { 
                labels: { style: { color: '#94a3b8', fontSize: '10px' } }, 
                lineColor: '#334155' 
            },
            yAxis: { 
                labels: { style: { color: '#94a3b8' } }, 
                gridLineColor: '#1e293b', 
                title: { style: { color: '#94a3b8' } } 
            },
            legend: { itemStyle: { color: '#cbd5e1' } },
            credits: { enabled: false }
        };

        // 1. Chart Per Tanggal
        Highcharts.chart('chartPerTanggal', Highcharts.merge(darkTheme, {
            chart: { type: 'area' },
            title: { text: 'Dashboard Transaksi Per Tanggal' },
            xAxis: { categories: @json($chartDates) },
            yAxis: { title: { text: 'Nominal Transaksi' } },
            series: [{
                name: 'Total Nominal',
                data: @json($chartDateValues),
                color: '#38bdf8'
            }]
        }));

        // 2. Chart Per Bulan
        Highcharts.chart('chartPerBulan', Highcharts.merge(darkTheme, {
            chart: { type: 'line' },
            title: { text: 'Dashboard Transaksi Per Bulan' },
            xAxis: { categories: @json($chartMonths) },
            yAxis: { title: { text: 'Nominal Transaksi' } },
            series: [{
                name: 'Total Nominal',
                data: @json($chartMonthValues),
                color: '#38bdf8'
            }]
        }));

        // 3. Chart Per Hari
        Highcharts.chart('chartPerHari', Highcharts.merge(darkTheme, {
            chart: { type: 'line' },
            title: { text: 'Dashboard Transaksi Per Hari' },
            xAxis: { categories: @json($chartDays) },
            yAxis: { title: { text: 'Nominal Transaksi' } },
            series: [{ name: 'Total Nominal', data: @json($chartDayValues), color: '#38bdf8' }]
        }));

        // 4. Chart Per Jam
        Highcharts.chart('chartPerJam', Highcharts.merge(darkTheme, {
            chart: { type: 'line' },
            title: { text: 'Dashboard Transaksi Per Jam' },
            xAxis: { categories: @json(array_map('strval', $chartHours)) },
            yAxis: { title: { text: 'Nominal Transaksi' } },
            series: [{ name: 'Total Nominal', data: @json($chartHourValues), color: '#38bdf8' }]
        }));

        // 5. Chart Per Rumpun Program
        Highcharts.chart('chartPerRumpun', Highcharts.merge(darkTheme, {
            chart: { type: 'column' },
            title: { text: 'Total Nominal Berdasarkan Rumpun Program' },
            xAxis: { categories: @json($chartRumpunLabels) },
            yAxis: { title: { text: 'Total Nominal (Rp)' } },
            series: [{ name: 'Total Nominal', data: @json($chartRumpunValues), color: '#38bdf8' }]
        }));

        // 6. Chart Per Program
        Highcharts.chart('chartPerProgram', Highcharts.merge(darkTheme, {
            chart: { type: 'column' },
            title: { text: 'Total Nominal Berdasarkan Program' },
            xAxis: { categories: @json($chartProgramLabels) },
            yAxis: { title: { text: 'Total Nominal (Rp)' } },
            series: [{ name: 'Total Nominal', data: @json($chartProgramValues), color: '#38bdf8' }]
        }));

        // 7. Chart Per Kantor
        Highcharts.chart('chartPerKantor', Highcharts.merge(darkTheme, {
            chart: { type: 'column' },
            title: { text: 'Total Nominal Berdasarkan Kantor' },
            xAxis: { categories: @json($chartOfficeLabels) },
            yAxis: { title: { text: 'Total Nominal (Rp)' } },
            series: [{ name: 'Total Nominal', data: @json($chartOfficeValues), color: '#38bdf8' }]
        }));

        // 8. Chart Per PIC Prospek
        Highcharts.chart('chartPerPIC', Highcharts.merge(darkTheme, {
            chart: { type: 'column' },
            title: { text: 'Total Nominal Berdasarkan PIC Prospek' },
            xAxis: { categories: @json($chartPicLabels) },
            yAxis: { title: { text: 'Total Nominal (Rp)' } },
            series: [{ name: 'Total Nominal', data: @json($chartPicValues), color: '#38bdf8' }]
        }));

        // 9. Chart Distribusi Via Bayar (PIE CHART)
        Highcharts.chart('chartViaBayar', Highcharts.merge(darkTheme, {
            chart: { type: 'pie' },
            title: { text: 'Distribusi Via Bayar' },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: { enabled: true, format: '{point.name}: {point.percentage:.1f} %', style: { color: '#cbd5e1', fontSize: '9px' } }
                }
            },
            series: [{
                name: 'Persentase',
                data: @json($chartViaBayarData)
            }]
        }));

        // 10. Chart Distribusi Channel Donasi (PIE CHART)
        Highcharts.chart('chartChannelDonasi', Highcharts.merge(darkTheme, {
            chart: { type: 'pie' },
            title: { text: 'Distribusi Channel Donasi' },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: { enabled: true, format: '{point.name}: {point.percentage:.1f} %', style: { color: '#cbd5e1', fontSize: '9px' } }
                }
            },
            series: [{
                name: 'Persentase',
                data: @json($chartChannelData)
            }]
        }));

    });
</script>
@endsection
