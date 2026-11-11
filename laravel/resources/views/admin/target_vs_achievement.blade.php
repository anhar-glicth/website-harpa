@extends('layouts.app')

@section('content')
<div class="container max-w-7xl mx-auto px-4 py-8">

    <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-8 items-start min-w-0">

        {{-- Sidebar --}}
        <aside class="hidden lg:block space-y-5">
            @include('admin.sidebar', ['active' => 'target_vs_achievement'])
        </aside>

        {{-- Main Content Column --}}
        <div class="space-y-6 min-w-0 overflow-hidden">

            {{-- Top Emerald Header Banner --}}
            <div class="bg-emerald-700 rounded-2xl px-6 py-3.5 text-white font-black text-sm uppercase tracking-wider shadow-sm flex flex-wrap items-center justify-between gap-2">
                <span class="flex items-center gap-2">
                    <i class="fa-solid fa-bullseye text-white"></i> Target vs Capaian
                </span>
                <span class="text-xs text-emerald-100 font-normal">Dashboard / Target vs Capaian</span>
            </div>

            {{-- Filter Form Controls --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm space-y-3 text-xs font-semibold text-slate-700">
                <form action="{{ route('admin.target_vs_achievement') }}" method="GET" class="flex flex-wrap items-center justify-center gap-3">
                    <label for="tva_start" class="text-xs font-bold text-slate-700">Bulan (tgl berapapun mewakili Bulan) :</label>
                    <input id="tva_start" type="date" name="start_date" value="{{ request('start_date') }}" class="border border-slate-300 rounded-xl px-3 py-2 min-h-[44px] text-xs focus:border-emerald-700">
                    <span>s/d :</span>
                    <input id="tva_end" type="date" name="end_date" aria-label="Tanggal akhir periode" value="{{ request('end_date') }}" class="border border-slate-300 rounded-xl px-3 py-2 min-h-[44px] text-xs focus:border-emerald-700">
                    
                    <button type="submit" class="bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-300 font-bold px-5 py-2 min-h-[44px] rounded-xl text-xs inline-flex items-center gap-1.5 transition-colors">
                        <i class="fa-solid fa-magnifying-glass"></i> Show
                    </button>
                </form>
            </div>

            {{-- 4 Summary Card Headers --}}
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 divide-y sm:divide-y-0 sm:divide-x divide-slate-100 text-center">
                    
                    {{-- Target --}}
                    <div class="p-4 space-y-1">
                        <div class="bg-emerald-700 text-white py-1.5 px-2 text-xs font-black uppercase tracking-wider rounded-t-xl -mx-4 -mt-4 mb-2">
                            Total Target
                        </div>
                        <p class="text-2xl font-black text-slate-900 font-mono">{{ number_format($totalTarget, 0, ',', '.') }}</p>
                    </div>

                    {{-- Capaian --}}
                    <div class="p-4 space-y-1">
                        <div class="bg-emerald-700 text-white py-1.5 px-2 text-xs font-black uppercase tracking-wider rounded-t-xl -mx-4 -mt-4 mb-2">
                            Total Capaian
                        </div>
                        <p class="text-2xl font-black text-slate-900 font-mono">{{ number_format($totalCapaian, 0, ',', '.') }}</p>
                    </div>

                    {{-- % Target Vs Capaian --}}
                    <div class="p-4 space-y-1">
                        <div class="bg-emerald-700 text-white py-1.5 px-2 text-xs font-black uppercase tracking-wider rounded-t-xl -mx-4 -mt-4 mb-2">
                            % Target Vs Capaian
                        </div>
                        <p class="text-2xl font-black text-emerald-700 font-mono">{{ $achievementPct }}</p>
                    </div>

                    {{-- Sisa Target --}}
                    <div class="p-4 space-y-1">
                        <div class="bg-emerald-700 text-white py-1.5 px-2 text-xs font-black uppercase tracking-wider rounded-t-xl -mx-4 -mt-4 mb-2">
                            Sisa Target
                        </div>
                        <p class="text-2xl font-black text-emerald-700 font-mono">{{ $sisaTarget }}</p>
                    </div>

                </div>
            </div>

            {{-- Highcharts Analytics Container --}}
            <div class="space-y-6">

                {{-- Hint Notification --}}
                <div class="bg-slate-900 border border-slate-800 rounded-2xl px-5 py-3 text-xs text-sky-300 font-semibold flex items-center justify-between shadow-md">
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-magnifying-glass-plus text-sky-300 text-sm"></i>
                        <span><strong>Fitur Zoom &amp; Pan Grafik Active:</strong> Klik &amp; drag pada area grafik untuk memperbesar (Zoom-In) rentang bulan target vs capaian.</span>
                    </span>
                </div>

                {{-- Chart 1: Target vs Capaian Per Bulan (Column Chart) --}}
                <div class="bg-[#0f172a] rounded-2xl p-5 border border-slate-800 shadow-xl">
                    <div id="chartTargetVsCapaianColumn" class="w-full h-80"></div>
                </div>

                {{-- Chart 2: Target vs Capaian Per Bulan (Line Chart) --}}
                <div class="bg-[#0f172a] rounded-2xl p-5 border border-slate-800 shadow-xl">
                    <div id="chartTargetVsCapaianLine" class="w-full h-80"></div>
                </div>

            </div>

            {{-- Resume Table Section --}}
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                <div class="bg-emerald-700 text-white px-5 py-3 flex items-center justify-between">
                    <span class="font-black text-xs uppercase tracking-wider">Resume Target Vs Capaian Per PIC Prospek</span>
                    <button type="button" aria-label="Export Target Vs Capaian" class="text-emerald-100 hover:text-white text-xs"><i class="fa-solid fa-file-excel"></i></button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs font-medium text-slate-700 divide-y divide-slate-100 whitespace-nowrap">
                        <thead class="bg-emerald-700 text-white text-xs font-black uppercase">
                            <tr>
                                <th class="py-3 px-4 text-center">No</th>
                                <th class="py-3 px-4">Bulan</th>
                                <th class="py-3 px-4 text-right">Total Target</th>
                                <th class="py-3 px-4 text-right">Total Capaian</th>
                                <th class="py-3 px-4 text-right">Sisa Target</th>
                                <th class="py-3 px-4 text-right">% Capaian</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($resumeTargetVsCapaian as $idx => $rTvc)
                                <tr class="{{ !empty($rTvc['highlight']) ? 'bg-amber-100 font-bold' : 'hover:bg-slate-50' }}">
                                    <td class="py-3 px-4 text-center text-slate-600 font-mono">{{ $idx + 1 }}</td>
                                    <td class="py-3 px-4 font-bold text-slate-900">{{ $rTvc['month'] }}</td>
                                    <td class="py-3 px-4 text-right font-mono font-bold text-slate-800">{{ number_format($rTvc['target'], 0, ',', '.') }}</td>
                                    <td class="py-3 px-4 text-right font-mono font-bold text-slate-900">{{ number_format($rTvc['capaian'], 0, ',', '.') }}</td>
                                    <td class="py-3 px-4 text-right font-mono font-bold {{ str_starts_with($rTvc['sisa'], '+') ? 'text-emerald-700' : 'text-rose-700' }}">{{ $rTvc['sisa'] }}</td>
                                    <td class="py-3 px-4 text-right font-mono font-black text-emerald-700">{{ $rTvc['pct'] }}</td>
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
                style: { fontFamily: 'Plus Jakarta Sans, sans-serif' },
                zoomType: 'x',
                panning: { enabled: true, type: 'x' },
                panKey: 'shift',
                resetZoomButton: {
                    position: { align: 'right', x: -10, y: 10 },
                    theme: { fill: '#1e293b', stroke: '#38bdf8', r: 6, style: { color: '#7dd3fc', fontWeight: 'bold' } }
                }
            },
            title: { style: { color: '#ffffff', fontWeight: 'bold', fontSize: '15px' } },
            xAxis: { labels: { style: { color: '#94a3b8', fontSize: '10px' } }, lineColor: '#334155' },
            yAxis: { labels: { style: { color: '#94a3b8' } }, gridLineColor: '#1e293b', title: { style: { color: '#94a3b8' } } },
            legend: { itemStyle: { color: '#cbd5e1' } },
            credits: { enabled: false }
        };

        const months = @json($chartMonths);

        // 1. Chart Target vs Capaian Per Bulan (Column Chart)
        Highcharts.chart('chartTargetVsCapaianColumn', Highcharts.merge(darkTheme, {
            chart: { type: 'column' },
            title: { text: 'Target vs Capaian Per Bulan (Column Chart)' },
            xAxis: { categories: months },
            yAxis: { title: { text: 'Nominal (Rp)' } },
            series: [
                { name: 'Capaian', data: @json($chartCapaianData), color: '#0e567d' },
                { name: 'Target',  data: @json($chartTargetData), color: '#10b981' }
            ]
        }));

        // 2. Chart Target vs Capaian Per Bulan (Line Chart)
        Highcharts.chart('chartTargetVsCapaianLine', Highcharts.merge(darkTheme, {
            chart: { type: 'line' },
            title: { text: 'Target vs Capaian Per Bulan (Line Chart)' },
            xAxis: { categories: months },
            yAxis: { title: { text: 'Nominal (Rp)' } },
            series: [
                { name: 'Total Donasi', data: @json($chartCapaianData), color: '#0d9488', marker: { symbol: 'circle' } },
                { name: 'Total Target', data: @json($chartTargetData), color: '#f43f5e', dashStyle: 'Dash', marker: { symbol: 'diamond' } }
            ]
        }));

    });
</script>
@endsection
