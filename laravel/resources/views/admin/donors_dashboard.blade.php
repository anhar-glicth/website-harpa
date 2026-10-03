@extends('layouts.app')

@section('content')
<div class="container max-w-7xl mx-auto px-4 py-8">

    <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-8 items-start min-w-0">

        {{-- Sidebar --}}
        <aside class="hidden lg:block space-y-5">
            @include('admin.sidebar', ['active' => 'donors_dashboard'])
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
                <a href="{{ route('admin.donors_dashboard') }}" class="px-4 py-2 rounded-t-xl text-xs font-black bg-emerald-700 text-white shadow-sm">
                    Dashboard Donatur
                </a>
            </div>

            {{-- Top Red Header Banner --}}
            <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 rounded-2xl px-6 py-3.5 text-white font-black text-sm uppercase tracking-wider shadow-sm flex items-center justify-between">
                <span class="flex items-center gap-2">
                    <i class="fa-solid fa-users-gear text-white"></i> Dashboard Donatur
                </span>
                <span class="text-xs text-white/80 font-normal">Transaction Management → Dashboard Donatur</span>
            </div>

            {{-- Filter Form Controls (2 Rows - Presisi Screenshot User) --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm space-y-3 text-xs font-semibold text-slate-700">
                <form action="{{ route('admin.donors_dashboard') }}" method="GET" class="space-y-3">
                    
                    {{-- Row 1 --}}
                    <div class="flex flex-wrap items-center gap-4">
                        <div class="flex items-center gap-2">
                            <span>Tgl Registrasi :</span>
                            <input type="date" name="start_reg_date" value="{{ request('start_reg_date') }}" class="border border-slate-200 rounded-xl px-3 py-1.5 text-xs focus:border-emerald-600">
                            <span>s/d</span>
                            <input type="date" name="end_reg_date" value="{{ request('end_reg_date') }}" class="border border-slate-200 rounded-xl px-3 py-1.5 text-xs focus:border-emerald-600">
                        </div>
                        <div class="flex items-center gap-2">
                            <span>Segmen :</span>
                            <select name="segment" class="border border-slate-200 rounded-xl px-3 py-1.5 text-xs focus:border-emerald-600 min-w-[140px]">
                                <option value="">Semua Segmen</option>
                                @foreach($segments as $sg)
                                    <option value="{{ $sg }}">{{ $sg }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            <span>Jenis Kelamin :</span>
                            <select name="gender" class="border border-slate-200 rounded-xl px-3 py-1.5 text-xs focus:border-emerald-600 min-w-[100px]">
                                <option value="All">All</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            <span>Profil :</span>
                            <select name="profile" class="border border-slate-200 rounded-xl px-3 py-1.5 text-xs focus:border-emerald-600 min-w-[120px]">
                                <option value="">Semua Profil</option>
                                @foreach($profiles as $pf)
                                    <option value="{{ $pf }}">{{ $pf }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 font-bold px-4 py-1.5 rounded-xl text-xs flex items-center gap-1.5 transition-all">
                            🔍 Show
                        </button>
                    </div>

                    {{-- Row 2 --}}
                    <div class="flex flex-wrap items-center gap-4 pt-1">
                        <div class="flex items-center gap-2">
                            <span>PIC Prospek :</span>
                            <select name="pic_prospect" class="border border-slate-200 rounded-xl px-3 py-1.5 text-xs focus:border-emerald-600 min-w-[160px]">
                                <option value="">Pilih PIC</option>
                                <option value="Digital Marketing">Digital Marketing</option>
                                <option value="CRM">CRM</option>
                                <option value="Fundraising">Fundraising</option>
                                <option value="Customer Service">Customer Service</option>
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
                        <div class="flex items-center gap-2">
                            <span>Kategori :</span>
                            <select name="category" class="border border-slate-200 rounded-xl px-3 py-1.5 text-xs focus:border-emerald-600 min-w-[100px]">
                                <option value="All">All</option>
                                @foreach($categories as $ct)
                                    <option value="{{ $ct }}">{{ $ct }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            <span>Badge :</span>
                            <select name="badge" class="border border-slate-200 rounded-xl px-3 py-1.5 text-xs focus:border-emerald-600 min-w-[100px]">
                                <option value="All">All</option>
                                @foreach($badges as $bd)
                                    <option value="{{ $bd }}">{{ $bd }}</option>
                                @endforeach
                            </select>
                        </div>
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
                        <p class="text-2xl font-black text-slate-900 font-mono">{{ number_format($totalDonorsAll) }}</p>
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
                        <p class="text-2xl font-black text-slate-900 font-mono">{{ number_format($unknownGenderDonors) }}</p>
                    </div>

                </div>
            </div>

            {{-- Highcharts Analytics Container (Dark Theme #0f172a Presisi Screenshot User 1 & 2 & 3) --}}
            <div class="space-y-6">

                {{-- Hint Notification --}}
                <div class="bg-slate-900 border border-slate-800 rounded-2xl px-5 py-3 text-xs text-sky-400 font-semibold flex items-center justify-between shadow-md">
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-magnifying-glass-plus text-sky-400 text-sm"></i>
                        <span><strong>Fitur Zoom & Pan Grafik Active:</strong> Klik & drag pada grafik untuk memperbesar (Zoom-In) segmen/kategori tertentu.</span>
                    </span>
                </div>

                {{-- Row 1: Pie Chart Distribusi Segmen & Distribusi Profil --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-[#0f172a] rounded-2xl p-5 border border-slate-800 shadow-xl">
                        <div id="chartDistribusiSegmen" class="w-full h-80"></div>
                    </div>
                    <div class="bg-[#0f172a] rounded-2xl p-5 border border-slate-800 shadow-xl">
                        <div id="chartDistribusiProfil" class="w-full h-80"></div>
                    </div>
                </div>

                {{-- Row 2: Pie Chart Distribusi Jenis Kelamin & Distribusi Contact --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-[#0f172a] rounded-2xl p-5 border border-slate-800 shadow-xl">
                        <div id="chartDistribusiGender" class="w-full h-80"></div>
                    </div>
                    <div class="bg-[#0f172a] rounded-2xl p-5 border border-slate-800 shadow-xl">
                        <div id="chartDistribusiContact" class="w-full h-80"></div>
                    </div>
                </div>

                {{-- Row 3: Pie Chart Distribusi Donasi & Distribusi Prospek --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-[#0f172a] rounded-2xl p-5 border border-slate-800 shadow-xl">
                        <div id="chartDistribusiDonasi" class="w-full h-80"></div>
                    </div>
                    <div class="bg-[#0f172a] rounded-2xl p-5 border border-slate-800 shadow-xl">
                        <div id="chartDistribusiProspek" class="w-full h-80"></div>
                    </div>
                </div>

                {{-- Row 4: Column Charts - Total Data Berdasarkan Kantor & PIC Prospek --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-[#0f172a] rounded-2xl p-5 border border-slate-800 shadow-xl">
                        <div id="chartTotalByKantor" class="w-full h-80"></div>
                    </div>
                    <div class="bg-[#0f172a] rounded-2xl p-5 border border-slate-800 shadow-xl">
                        <div id="chartTotalByPIC" class="w-full h-80"></div>
                    </div>
                </div>

            </div>

            {{-- Resume Tables Section (Presisi Screenshot User 3) --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 pt-4">

                {{-- Table 1: Resume Donatur By Kantor (Col 5) --}}
                <div class="lg:col-span-5 bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                    <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white px-4 py-2.5 flex items-center justify-between">
                        <span class="font-black text-xs uppercase tracking-wider">Resume Donatur By Kantor</span>
                        <button type="button" class="text-white/80 hover:text-white text-xs"><i class="fa-solid fa-file-excel"></i></button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs font-medium text-slate-700 divide-y divide-slate-100">
                            <thead class="bg-slate-50 text-[11px] font-bold text-slate-900 border-b border-slate-200">
                                <tr>
                                    <th class="py-2.5 px-3 text-center">No</th>
                                    <th class="py-2.5 px-3">Kantor</th>
                                    <th class="py-2.5 px-3 text-right">Total Donatur</th>
                                    <th class="py-2.5 px-3 text-right">% Kontribusi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($resumeDonorsOffice as $idx => $rOfc)
                                    <tr class="hover:bg-slate-50">
                                        <td class="py-2 px-3 text-center text-slate-400 font-mono">{{ $idx + 1 }}</td>
                                        <td class="py-2 px-3 font-semibold text-slate-900">{{ $rOfc['name'] }}</td>
                                        <td class="py-2 px-3 text-right font-mono font-bold text-slate-800">{{ number_format($rOfc['count']) }}</td>
                                        <td class="py-2 px-3 text-right font-mono font-bold text-emerald-600">{{ $rOfc['percent'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Table 2: Resume Donatur By PIC Prospek (Col 7) --}}
                <div class="lg:col-span-7 bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                    <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white px-4 py-2.5 flex items-center justify-between">
                        <span class="font-black text-xs uppercase tracking-wider">Resume Donatur By PIC Prospek</span>
                        <button type="button" class="text-white/80 hover:text-white text-xs"><i class="fa-solid fa-file-excel"></i></button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs font-medium text-slate-700 divide-y divide-slate-100">
                            <thead class="bg-slate-50 text-[11px] font-bold text-slate-900 border-b border-slate-200">
                                <tr>
                                    <th class="py-2.5 px-3 text-center">No</th>
                                    <th class="py-2.5 px-3">PIC Prospek</th>
                                    <th class="py-2.5 px-3 text-right">Total Donatur</th>
                                    <th class="py-2.5 px-3 text-right">% Kontribusi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($resumeDonorsPic as $idx => $rPic)
                                    <tr class="hover:bg-slate-50">
                                        <td class="py-2 px-3 text-center text-slate-400 font-mono">{{ $idx + 1 }}</td>
                                        <td class="py-2 px-3 font-semibold text-slate-900">{{ $rPic['name'] }}</td>
                                        <td class="py-2 px-3 text-right font-mono font-bold text-slate-800">{{ number_format($rPic['count']) }}</td>
                                        <td class="py-2 px-3 text-right font-mono font-bold text-emerald-600">{{ $rPic['percent'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            {{-- Table 3: Top 20 Donatur Base On Kontribusi Donasi (All Time Donation) --}}
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white px-5 py-3 flex items-center justify-between">
                    <span class="font-black text-xs uppercase tracking-wider">Top 20 Donatur Base On Kontribusi Donasi (All Time Donation)</span>
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
                                <th class="py-3 px-4">Kategori</th>
                                <th class="py-3 px-4">Badge</th>
                                <th class="py-3 px-4">PIC Prospek</th>
                                <th class="py-3 px-4 text-right">Total Donasi</th>
                                <th class="py-3 px-4 text-right">% Kontribusi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($topDonorsAllTime as $idx => $td)
                                <tr class="hover:bg-slate-50">
                                    <td class="py-3 px-4 text-center text-slate-400 font-mono">{{ $idx + 1 }}</td>
                                    <td class="py-3 px-4 font-bold text-slate-900">{{ $td['name'] }}</td>
                                    <td class="py-3 px-4 font-mono text-emerald-700 font-bold">{{ $td['id_donatur'] }}</td>
                                    <td class="py-3 px-4 text-slate-700">{{ $td['office'] }}</td>
                                    <td class="py-3 px-4 text-slate-800">{{ $td['segment'] }}</td>
                                    <td class="py-3 px-4 font-bold text-emerald-600">{{ $td['category'] }}</td>
                                    <td class="py-3 px-4">
                                        <span class="px-2.5 py-1 bg-amber-50 text-amber-700 rounded-full text-[10px] font-bold border border-amber-200">
                                            {{ $td['badge'] }}
                                        </span>
                                    </td>
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
                    theme: { fill: '#1e293b', stroke: '#38bdf8', r: 6, style: { color: '#38bdf8', fontWeight: 'bold' } }
                }
            },
            title: { style: { color: '#ffffff', fontWeight: 'bold', fontSize: '15px' } },
            xAxis: { labels: { style: { color: '#94a3b8', fontSize: '10px' } }, lineColor: '#334155' },
            yAxis: { labels: { style: { color: '#94a3b8' } }, gridLineColor: '#1e293b', title: { style: { color: '#94a3b8' } } },
            legend: { itemStyle: { color: '#cbd5e1' } },
            credits: { enabled: false }
        };

        // 1. Chart Distribusi Segmen
        Highcharts.chart('chartDistribusiSegmen', Highcharts.merge(darkTheme, {
            chart: { type: 'pie' },
            title: { text: 'Distribusi Segmen' },
            plotOptions: { pie: { allowPointSelect: true, cursor: 'pointer', dataLabels: { enabled: true, format: '{point.name}: {point.percentage:.1f} %', style: { color: '#cbd5e1', fontSize: '9px' } } } },
            series: [{
                name: 'Persentase',
                data: @json($chartSegmenData)
            }]
        }));

        // 2. Chart Distribusi Profil
        Highcharts.chart('chartDistribusiProfil', Highcharts.merge(darkTheme, {
            chart: { type: 'pie' },
            title: { text: 'Distribusi Profil' },
            plotOptions: { pie: { allowPointSelect: true, cursor: 'pointer', dataLabels: { enabled: true, format: '{point.name}: {point.percentage:.1f} %', style: { color: '#cbd5e1', fontSize: '9px' } } } },
            series: [{
                name: 'Persentase',
                data: @json($chartProfilData)
            }]
        }));

        // 3. Chart Distribusi Jenis Kelamin
        Highcharts.chart('chartDistribusiGender', Highcharts.merge(darkTheme, {
            chart: { type: 'pie' },
            title: { text: 'Distribusi Jenis Kelamin' },
            plotOptions: { pie: { allowPointSelect: true, cursor: 'pointer', dataLabels: { enabled: true, format: '{point.name}: {point.percentage:.1f} %', style: { color: '#cbd5e1', fontSize: '9px' } } } },
            series: [{
                name: 'Persentase',
                data: @json($chartGenderData)
            }]
        }));

        // 4. Chart Distribusi Contact
        Highcharts.chart('chartDistribusiContact', Highcharts.merge(darkTheme, {
            chart: { type: 'pie' },
            title: { text: 'Distribusi Contact' },
            plotOptions: { pie: { allowPointSelect: true, cursor: 'pointer', dataLabels: { enabled: true, format: '{point.name}: {point.percentage:.1f} %', style: { color: '#cbd5e1', fontSize: '9px' } } } },
            series: [{
                name: 'Persentase',
                data: @json($chartContactData)
            }]
        }));

        // 5. Chart Distribusi Donasi
        Highcharts.chart('chartDistribusiDonasi', Highcharts.merge(darkTheme, {
            chart: { type: 'pie' },
            title: { text: 'Distribusi Donasi' },
            plotOptions: { pie: { allowPointSelect: true, cursor: 'pointer', dataLabels: { enabled: true, format: '{point.name}: {point.percentage:.1f} %', style: { color: '#cbd5e1', fontSize: '9px' } } } },
            series: [{
                name: 'Persentase',
                data: @json($chartDonasiData)
            }]
        }));

        // 6. Chart Distribusi Prospek
        Highcharts.chart('chartDistribusiProspek', Highcharts.merge(darkTheme, {
            chart: { type: 'pie' },
            title: { text: 'Distribusi Prospek' },
            plotOptions: { pie: { allowPointSelect: true, cursor: 'pointer', dataLabels: { enabled: true, format: '{point.name}: {point.percentage:.1f} %', style: { color: '#cbd5e1', fontSize: '9px' } } } },
            series: [{
                name: 'Persentase',
                data: @json($chartProspekData)
            }]
        }));

        // 7. Chart Total Data Berdasarkan Kantor
        Highcharts.chart('chartTotalByKantor', Highcharts.merge(darkTheme, {
            chart: { type: 'column' },
            title: { text: 'Total Data Berdasarkan Kantor' },
            xAxis: { categories: @json($chartDonorsOfficeLabels) },
            yAxis: { title: { text: 'Total Data' } },
            series: [{ name: 'Total Data', data: @json($chartDonorsOfficeValues), color: '#38bdf8' }]
        }));

        // 8. Chart Total Data Berdasarkan PIC Prospek
        Highcharts.chart('chartTotalByPIC', Highcharts.merge(darkTheme, {
            chart: { type: 'column' },
            title: { text: 'Total Data Berdasarkan PIC Prospek' },
            xAxis: { categories: @json($chartDonorsPicLabels) },
            yAxis: { title: { text: 'Total Data' } },
            series: [{ name: 'Total Data', data: @json($chartDonorsPicValues), color: '#38bdf8' }]
        }));

    });
</script>
@endsection
