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
                            <input type="date" name="start_date" value="{{ request('start_date', '2025-07-09') }}" class="border border-slate-200 rounded-xl px-3 py-1.5 text-xs focus:border-emerald-600">
                            <span>s/d</span>
                            <input type="date" name="end_date" value="{{ request('end_date', '2026-08-07') }}" class="border border-slate-200 rounded-xl px-3 py-1.5 text-xs focus:border-emerald-600">
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
                                <option value="">Pilih PIC</option>
                                <option value="Digital Marketing">Digital Marketing</option>
                                <option value="CRM">CRM</option>
                                <option value="Baiq Nickte Madu Jasmine">Baiq Nickte Madu Jasmine</option>
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

            {{-- 4 Summary Card Headers (Presisi Screenshot User) --}}
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 divide-y sm:divide-y-0 sm:divide-x divide-slate-100 text-center">
                    
                    {{-- Σ Capaian Total --}}
                    <div class="p-4 space-y-1">
                        <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white py-1.5 px-2 text-[10px] font-black uppercase tracking-wider rounded-t-xl -mx-4 -mt-4 mb-2">
                            Σ Capaian Total
                        </div>
                        <p class="text-2xl font-black text-slate-900 font-mono">{{ number_format($totalCapaian, 0, ',', '.') }}</p>
                    </div>

                    {{-- Σ Donatur --}}
                    <div class="p-4 space-y-1">
                        <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white py-1.5 px-2 text-[10px] font-black uppercase tracking-wider rounded-t-xl -mx-4 -mt-4 mb-2">
                            Σ Donatur
                        </div>
                        <p class="text-2xl font-black text-slate-900 font-mono">{{ number_format($totalDonorsCount) }}</p>
                    </div>

                    {{-- Σ Transaksi --}}
                    <div class="p-4 space-y-1">
                        <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white py-1.5 px-2 text-[10px] font-black uppercase tracking-wider rounded-t-xl -mx-4 -mt-4 mb-2">
                            Σ Transaksi
                        </div>
                        <p class="text-2xl font-black text-slate-900 font-mono">{{ number_format($totalTransactionsCount) }}</p>
                    </div>

                    {{-- Rata-rata Donasi --}}
                    <div class="p-4 space-y-1">
                        <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white py-1.5 px-2 text-[10px] font-black uppercase tracking-wider rounded-t-xl -mx-4 -mt-4 mb-2">
                            Rata-rata Donasi
                        </div>
                        <p class="text-2xl font-black text-slate-900 font-mono">{{ number_format($averageDonation, 0, ',', '.') }}</p>
                    </div>

                </div>
            </div>

            {{-- Highcharts Analytics Container (Dark Theme #0f172a Presisi Screenshot User) --}}
            <div class="space-y-6">

                {{-- Chart 1: Dashboard Transaksi Per Tanggal (Full Width) --}}
                <div class="bg-[#0f172a] rounded-2xl p-5 border border-slate-800 shadow-xl">
                    <div id="chartPerTanggal" class="w-full h-80"></div>
                </div>

                {{-- Chart 2: Dashboard Transaksi Per Bulan (Full Width) --}}
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

            </div>

        </div>
    </div>

</div>

{{-- Include Highcharts JS Library --}}
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {

        // Global Highcharts Dark Theme Options
        const darkTheme = {
            chart: { backgroundColor: '#0f172a', style: { fontFamily: 'Segoe UI, sans-serif' } },
            title: { style: { color: '#ffffff', fontWeight: 'bold', fontSize: '15px' } },
            xAxis: { labels: { style: { color: '#94a3b8', fontSize: '10px' } }, lineColor: '#334155' },
            yAxis: { labels: { style: { color: '#94a3b8' } }, gridLineColor: '#1e293b', title: { style: { color: '#94a3b8' } } },
            legend: { itemStyle: { color: '#cbd5e1' } },
            credits: { enabled: false }
        };

        // 1. Chart Per Tanggal
        Highcharts.chart('chartPerTanggal', Highcharts.merge(darkTheme, {
            chart: { type: 'area' },
            title: { text: 'Dashboard Transaksi Per Tanggal' },
            xAxis: { categories: ['2025-07-09','2025-07-17','2025-07-25','2025-08-06','2025-08-24','2025-09-17','2025-10-17','2025-11-11','2025-11-18','2025-12-19','2025-12-25','2026-01-18','2026-02-17','2026-03-19','2026-04-17','2026-05-17','2026-06-30'] },
            yAxis: { title: { text: 'Nominal Transaksi' } },
            series: [{
                name: 'Total Nominal',
                data: [360000000, 15000000, 60000000, 20000000, 15000000, 45000000, 450000000, 240000000, 90000000, 35000000, 50000000, 25000000, 60000000, 30000000, 20000000, 35000000, 40000000],
                color: '#38bdf8',
                fillColor: { linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 }, stops: [[0, 'rgba(56, 189, 248, 0.4)'], [1, 'rgba(56, 189, 248, 0.0)']] }
            }]
        }));

        // 2. Chart Per Bulan
        Highcharts.chart('chartPerBulan', Highcharts.merge(darkTheme, {
            chart: { type: 'line' },
            title: { text: 'Dashboard Transaksi Per Bulan' },
            xAxis: { categories: ['Jul 2025', 'Aug 2025', 'Sep 2025', 'Oct 2025', 'Nov 2025', 'Dec 2025', 'Jan 2026', 'Feb 2026', 'Mar 2026', 'Apr 2026', 'May 2026', 'Jun 2026'] },
            yAxis: { title: { text: 'Nominal Transaksi' } },
            series: [{
                name: 'Total Nominal',
                data: [404419727, 124287432, 72400113, 127248327, 491423433, 493791668, 390886473, 361016163, 299139038, 223318069, 181131406, 377959517],
                color: '#38bdf8',
                dataLabels: { enabled: true, style: { color: '#38bdf8', fontSize: '9px' }, formatter: function() { return (this.y / 1000000).toFixed(0) + 'M'; } }
            }]
        }));

        // 3. Chart Per Hari
        Highcharts.chart('chartPerHari', Highcharts.merge(darkTheme, {
            chart: { type: 'line' },
            title: { text: 'Dashboard Transaksi Per Hari' },
            xAxis: { categories: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] },
            yAxis: { title: { text: 'Nominal Transaksi' } },
            series: [{
                name: 'Total Nominal',
                data: [300000000, 580000000, 310000000, 1220000000, 540000000, 328737870, 260000000],
                color: '#38bdf8'
            }]
        }));

        // 4. Chart Per Jam
        Highcharts.chart('chartPerJam', Highcharts.merge(darkTheme, {
            chart: { type: 'line' },
            title: { text: 'Dashboard Transaksi Per Jam' },
            xAxis: { categories: ['0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23'] },
            yAxis: { title: { text: 'Nominal Transaksi' } },
            series: [{
                name: 'Total Nominal',
                data: [1750000000, 25000000, 30000000, 80000000, 210000000, 330000000, 180000000, 85000000, 80000000, 75000000, 60000000, 50000000, 45000000, 40000000, 42000000, 38000000, 45000000, 55000000, 48000000, 42000000, 39000000, 35000000, 30000000, 25000000],
                color: '#38bdf8'
            }]
        }));

        // 5. Chart Per Rumpun Program
        Highcharts.chart('chartPerRumpun', Highcharts.merge(darkTheme, {
            chart: { type: 'column' },
            title: { text: 'Total Nominal Berdasarkan Rumpun Program' },
            xAxis: { categories: ['Air Bersih', 'Dhuafa', 'Difabel', 'Dunia Islam', 'Fidyah', 'Infrastruktur', 'Kebencanaan', 'Lansia', 'Medis', 'Palestina', 'Qurban', 'Ramadhan', 'Wakaf', 'Yatim', 'Zakat Maal'] },
            yAxis: { title: { text: 'Total Nominal (Rp)' } },
            series: [{
                name: 'Total Nominal',
                data: [80000000, 5000000, 15000000, 520000000, 20000000, 1350000000, 290000000, 35000000, 180000000, 1020000000, 10000000, 12000000, 5000000, 45000000, 8000000],
                color: '#38bdf8'
            }]
        }));

        // 6. Chart Per Program
        Highcharts.chart('chartPerProgram', Highcharts.merge(darkTheme, {
            chart: { type: 'column' },
            title: { text: 'Total Nominal Berdasarkan Program' },
            xAxis: { categories: ['Adik Aila', 'Bantu Bangun Masjid', 'Berbagi Bahagia', 'Disabilitas', 'Infaq Dhuafa', 'Klinik Sedekah', 'Lansia Dhuafa', 'Makanan Siap Saji', 'Medis Aila', 'Paletina Umum', 'Sedekah Daging', 'Sepinggan Kasih', 'Wakaf Al-Quran', 'Yatim Ziya'] },
            yAxis: { title: { text: 'Total Nominal (Rp)' } },
            series: [{
                name: 'Total Nominal',
                data: [15000000, 440000000, 500000000, 25000000, 220000000, 40000000, 30000000, 390000000, 50000000, 480000000, 150000000, 320000000, 45000000, 60000000],
                color: '#38bdf8'
            }]
        }));

        // 7. Chart Per Kantor
        Highcharts.chart('chartPerKantor', Highcharts.merge(darkTheme, {
            chart: { type: 'column' },
            title: { text: 'Total Nominal Berdasarkan Kantor' },
            xAxis: { categories: ['Pusat-Amal Bakti Dunia Islam'] },
            yAxis: { title: { text: 'Total Nominal (Rp)' } },
            series: [{
                name: 'Total Nominal',
                data: [3546953126],
                color: '#38bdf8'
            }]
        }));

        // 8. Chart Per PIC Prospek
        Highcharts.chart('chartPerPIC', Highcharts.merge(darkTheme, {
            chart: { type: 'column' },
            title: { text: 'Total Nominal Berdasarkan PIC Prospek' },
            xAxis: { categories: ['Digital Marketing', 'CRM', 'Perusahaan', 'Fundraising', 'Front Office', 'Customer Service', 'Baiq Nickte Madu Jasmine'] },
            yAxis: { title: { text: 'Total Nominal (Rp)' } },
            series: [{
                name: 'Total Nominal',
                data: [2450000000, 850000000, 220000000, 25000000, 5000000, 2000000, 1000000],
                color: '#38bdf8'
            }]
        }));

    });
</script>
@endsection
