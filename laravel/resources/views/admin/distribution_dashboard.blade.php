@extends('layouts.app')

@section('content')
{{-- ApexCharts CDN for interactive charts & native ☰ menu export (SVG, PNG, CSV) --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<section class="max-w-7xl mx-auto px-6 py-20">

    <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-8 items-start">

        {{-- Sidebar --}}
        <aside class="hidden lg:block space-y-5">
            @include('admin.sidebar', ['active' => 'distribution_dashboard'])
        </aside>

        {{-- Main Content --}}
        <div class="space-y-6">

            {{-- Page Header --}}
            <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 shadow-sm flex items-center justify-between gap-4">
                <div class="space-y-1">
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Dashboard Penyaluran</h1>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest">Distribusi Management → Dashboard Analytics & Grafik Penyaluran</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 shadow-sm">
                    <i class="fa-solid fa-chart-line text-lg"></i>
                </div>
            </div>

            {{-- Filter Bar (Presisi Sesuai Screenshot 1) --}}
            <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 shadow-sm space-y-4">
                <form method="GET" action="{{ route('admin.distribution_dashboard') }}" class="space-y-4">

                    {{-- Row 1: Tgl Penyaluran, Program Penyaluran, Nama Program, Asnaf --}}
                    <div class="flex flex-wrap items-center gap-3 text-xs font-bold text-slate-700">

                        {{-- Tgl Penyaluran Range --}}
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <span class="text-slate-500 font-bold whitespace-nowrap">Tgl Penyaluran :</span>
                            <input type="date"
                                   name="start_date"
                                   value="{{ request('start_date', $startDate) }}"
                                   class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                            <span class="text-slate-400 font-bold">s/d</span>
                            <input type="date"
                                   name="end_date"
                                   value="{{ request('end_date', $endDate) }}"
                                   class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                        </div>

                        {{-- Program Penyaluran (Group) --}}
                        <div class="flex items-center gap-1.5">
                            <span class="text-slate-500 font-bold whitespace-nowrap">Program Penyaluran :</span>
                            <select name="program_group_id" class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none max-w-[180px]">
                                <option value="">All</option>
                                @foreach($programGroups as $pg)
                                    <option value="{{ $pg->id }}" {{ request('program_group_id') == $pg->id ? 'selected' : '' }}>
                                        {{ $pg->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Nama Program --}}
                        <div class="flex items-center gap-1.5">
                            <span class="text-slate-500 font-bold whitespace-nowrap">Nama Program :</span>
                            <select name="program_id" class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none max-w-[200px]">
                                <option value="">All</option>
                                @foreach($programs as $p)
                                    <option value="{{ $p->id }}" {{ request('program_id') == $p->id ? 'selected' : '' }}>
                                        {{ Str::limit($p->title, 25) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Asnaf --}}
                        <div class="flex items-center gap-1.5">
                            <span class="text-slate-500 font-bold whitespace-nowrap">Asnaf :</span>
                            <select name="asnaf" class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none min-w-[100px]">
                                <option value="All">All</option>
                                <option value="Fakir" {{ request('asnaf') === 'Fakir' ? 'selected' : '' }}>Fakir</option>
                                <option value="Miskin" {{ request('asnaf') === 'Miskin' ? 'selected' : '' }}>Miskin</option>
                                <option value="Fisabilillah" {{ request('asnaf') === 'Fisabilillah' ? 'selected' : '' }}>Fisabilillah</option>
                                <option value="Mualaf" {{ request('asnaf') === 'Mualaf' ? 'selected' : '' }}>Mualaf</option>
                                <option value="Ibnusabil" {{ request('asnaf') === 'Ibnusabil' ? 'selected' : '' }}>Ibnusabil</option>
                                <option value="Yatim" {{ request('asnaf') === 'Yatim' ? 'selected' : '' }}>Yatim</option>
                                <option value="Non Asnaf" {{ request('asnaf') === 'Non Asnaf' ? 'selected' : '' }}>Non Asnaf</option>
                            </select>
                        </div>

                    </div>

                    {{-- Row 2: Kota/Kab, Provinsi, Sumber Dana, Action Buttons --}}
                    <div class="flex flex-wrap items-center gap-3 text-xs font-bold text-slate-700">

                        {{-- Kota / Kab --}}
                        <div class="flex items-center gap-1.5">
                            <span class="text-slate-500 font-bold whitespace-nowrap">Kota / Kab :</span>
                            <select name="city" class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none min-w-[140px]">
                                <option value="All">All</option>
                                @foreach($cities as $c)
                                    <option value="{{ $c }}" {{ request('city') === $c ? 'selected' : '' }}>{{ $c }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Provinsi --}}
                        <div class="flex items-center gap-1.5">
                            <span class="text-slate-500 font-bold whitespace-nowrap">Provinsi :</span>
                            <select name="province" class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none min-w-[160px]">
                                <option value="All">All</option>
                                @foreach($provinces as $pr)
                                    <option value="{{ $pr }}" {{ request('province') === $pr ? 'selected' : '' }}>{{ $pr }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Sumber Dana --}}
                        <div class="flex items-center gap-1.5">
                            <span class="text-slate-500 font-bold whitespace-nowrap">Sumber Dana :</span>
                            <select name="source_of_fund" class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none min-w-[140px]">
                                <option value="All">All</option>
                                @foreach($sourceList as $s)
                                    <option value="{{ $s }}" {{ request('source_of_fund') === $s ? 'selected' : '' }}>{{ $s }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center gap-2 ml-auto">
                            <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-slate-900 hover:bg-slate-950 text-white rounded-xl text-xs font-bold transition-all shadow-sm">
                                <i class="fa-solid fa-magnifying-glass text-xs"></i> Show
                            </button>
                            <a href="{{ route('admin.distribution_dashboard') }}" class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-all" title="Reset Filter">
                                <i class="fa-solid fa-rotate text-xs"></i>
                            </a>
                        </div>

                    </div>

                </form>
            </div>

            {{-- Summary Banner Bar (Presisi Sesuai Website Primary Theme - Emerald Green) --}}
            <div class="bg-white border border-emerald-200 rounded-3xl overflow-hidden shadow-sm">
                <div class="grid grid-cols-2 sm:grid-cols-5 text-center divide-x divide-emerald-100 bg-emerald-50/50">

                    {{-- Σ Quantity --}}
                    <div class="p-4 space-y-1">
                        <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white py-1.5 px-2 text-[10px] font-black uppercase tracking-wider rounded-t-xl -mx-4 -mt-4 mb-2">
                            Σ Quantity
                        </div>
                        <p class="text-2xl font-black text-slate-900">{{ number_format($sumQuantity) }}</p>
                    </div>

                    {{-- Σ Kota --}}
                    <div class="p-4 space-y-1">
                        <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white py-1.5 px-2 text-[10px] font-black uppercase tracking-wider rounded-t-xl -mx-4 -mt-4 mb-2">
                            Σ Kota
                        </div>
                        <p class="text-2xl font-black text-slate-900">{{ number_format($sumKota) }}</p>
                    </div>

                    {{-- Σ Provinsi --}}
                    <div class="p-4 space-y-1">
                        <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white py-1.5 px-2 text-[10px] font-black uppercase tracking-wider rounded-t-xl -mx-4 -mt-4 mb-2">
                            Σ Provinsi
                        </div>
                        <p class="text-2xl font-black text-slate-900">{{ number_format($sumProvinsi) }}</p>
                    </div>

                    {{-- Σ Penerima Manfaat --}}
                    <div class="p-4 space-y-1">
                        <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white py-1.5 px-2 text-[10px] font-black uppercase tracking-wider rounded-t-xl -mx-4 -mt-4 mb-2">
                            Σ Penerima Manfaat
                        </div>
                        <p class="text-2xl font-black text-slate-900">{{ number_format($sumPM) }}</p>
                    </div>

                    {{-- Σ Dana Penyaluran --}}
                    <div class="p-4 space-y-1 col-span-2 sm:col-span-1">
                        <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white py-1.5 px-2 text-[10px] font-black uppercase tracking-wider rounded-t-xl -mx-4 -mt-4 mb-2">
                            Σ Dana Penyaluran
                        </div>
                        <p class="text-2xl font-black text-emerald-600">
                            Rp {{ number_format($sumDana, 0, ',', '.') }}
                        </p>
                    </div>

                </div>
            </div>

            {{-- ==================== CHARTS CONTAINER (CLEAN WHITE DESIGN SYSTEM - MODERN & HIGH READABILITY) ==================== --}}
            <div class="space-y-6">

                {{-- Chart 1: Dashboard Dana Penyaluran Per Tanggal --}}
                <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 shadow-sm space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-chart-area text-sky-500"></i>
                            Dashboard Dana Penyaluran Per Tanggal
                        </h3>
                        <span class="text-[10px] font-bold text-slate-500 bg-slate-100 px-3 py-1 rounded-xl">
                            <i class="fa-solid fa-download mr-1 text-sky-600"></i> Klik Menu ☰ di Atas Kanan Grafik untuk Download PNG/SVG/CSV
                        </span>
                    </div>
                    <div id="chartDailyNominal" class="w-full h-[320px]"></div>
                </div>

                {{-- Chart 2: Dashboard Jumlah PM Per Tanggal --}}
                <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 shadow-sm space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-users text-sky-500"></i>
                            Dashboard Jumlah PM Per Tanggal
                        </h3>
                        <span class="text-[10px] font-bold text-slate-500 bg-slate-100 px-3 py-1 rounded-xl">
                            <i class="fa-solid fa-download mr-1 text-sky-600"></i> Klik Menu ☰ di Atas Kanan Grafik untuk Download PNG/SVG/CSV
                        </span>
                    </div>
                    <div id="chartDailyPM" class="w-full h-[320px]"></div>
                </div>

                {{-- Monthly Charts Row --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    {{-- Chart 3: Dashboard Dana Penyaluran Per Bulan --}}
                    <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 shadow-sm space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                            <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">
                                Dashboard Dana Penyaluran Per Bulan
                            </h3>
                        </div>
                        <div id="chartMonthlyNominal" class="w-full h-[300px]"></div>
                    </div>

                    {{-- Chart 4: Dashboard Jumlah PM Per Bulan --}}
                    <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 shadow-sm space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                            <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">
                                Dashboard Jumlah PM Per Bulan
                            </h3>
                        </div>
                        <div id="chartMonthlyPM" class="w-full h-[300px]"></div>
                    </div>

                </div>

                {{-- Asnaf & Source of Fund Pie Charts Row --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    {{-- Chart 5: Distribusi Asnaf --}}
                    <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 shadow-sm space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                            <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">
                                Distribusi Asnaf
                            </h3>
                        </div>
                        <div id="chartAsnafPie" class="w-full h-[320px] flex items-center justify-center"></div>
                    </div>

                    {{-- Chart 6: Distribusi Sumber Dana --}}
                    <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 shadow-sm space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                            <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">
                                Distribusi Sumber Dana
                            </h3>
                        </div>
                        <div id="chartSourcePie" class="w-full h-[320px] flex items-center justify-center"></div>
                    </div>

                </div>

                {{-- Program Group & Program Name Pie Charts Row --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    {{-- Chart 7: Distribusi Program Penyaluran --}}
                    <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 shadow-sm space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                            <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">
                                Distribusi Program Penyaluran
                            </h3>
                        </div>
                        <div id="chartProgramGroupPie" class="w-full h-[320px] flex items-center justify-center"></div>
                    </div>

                    {{-- Chart 8: Distribusi Nama Program --}}
                    <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 shadow-sm space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                            <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">
                                Distribusi Nama Program
                            </h3>
                        </div>
                        <div id="chartProgramNamePie" class="w-full h-[320px] flex items-center justify-center"></div>
                    </div>

                </div>

            </div>

            {{-- ==================== 4 RESUME TABLES (Presisi Sesuai Website Primary Theme - Emerald Green) ==================== --}}
            <div class="space-y-6 pt-4">

                {{-- 1. Resume Penyaluran By Asnaf --}}
                <div class="bg-white border border-slate-100 rounded-3xl overflow-hidden shadow-sm">
                    <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 px-6 py-3 text-white flex items-center justify-between">
                        <span class="text-xs font-black uppercase tracking-wider">Resume Penyaluran By Asnaf</span>
                        <a href="{{ route('admin.distributions.export', array_merge(request()->query(), ['export_type' => 'asnaf'])) }}" class="text-white/80 hover:text-white transition-colors" title="Export Data Resume Asnaf Excel">
                            <i class="fa-solid fa-file-excel text-base"></i>
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-emerald-50/50 border-b border-slate-100 text-[10px] font-black uppercase tracking-wider text-slate-700">
                                    <th class="py-3 px-6">Asnaf</th>
                                    <th class="py-3 px-4 text-center">Jumlah Penyaluran</th>
                                    <th class="py-3 px-4 text-center">Total PM</th>
                                    <th class="py-3 px-4 text-right">Nominal Penyaluran</th>
                                    <th class="py-3 px-6 text-right">% Dana Penyaluran</th>
                                </tr>
                            </thead>
                            <tbody class="text-xs font-semibold text-slate-700 divide-y divide-slate-50">
                                @foreach($resumeAsnaf as $item)
                                    @php $pct = $denomDana > 0 ? ($item->total_amount / $denomDana) * 100 : 0; @endphp
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="py-3 px-6 font-bold text-slate-900">{{ $item->asnaf_name }}</td>
                                        <td class="py-3 px-4 text-center font-mono">{{ number_format($item->total_count) }}</td>
                                        <td class="py-3 px-4 text-center font-mono">{{ number_format($item->total_pm) }}</td>
                                        <td class="py-3 px-4 text-right font-black text-slate-900">Rp {{ number_format($item->total_amount, 0, ',', '.') }}</td>
                                        <td class="py-3 px-6 text-right font-mono font-bold text-emerald-600">{{ number_format($pct, 1) }}%</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- 2. Resume Penyaluran By Kota --}}
                <div class="bg-white border border-slate-100 rounded-3xl overflow-hidden shadow-sm">
                    <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 px-6 py-3 text-white flex items-center justify-between">
                        <span class="text-xs font-black uppercase tracking-wider">Resume Penyaluran By Kota</span>
                        <a href="{{ route('admin.distributions.export', array_merge(request()->query(), ['export_type' => 'kota'])) }}" class="text-white/80 hover:text-white transition-colors" title="Export Data Resume Kota Excel">
                            <i class="fa-solid fa-file-excel text-base"></i>
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-emerald-50/50 border-b border-slate-100 text-[10px] font-black uppercase tracking-wider text-slate-700">
                                    <th class="py-3 px-6">Kota</th>
                                    <th class="py-3 px-4">Provinsi</th>
                                    <th class="py-3 px-4 text-center">Jumlah Penyaluran</th>
                                    <th class="py-3 px-4 text-center">Total PM</th>
                                    <th class="py-3 px-4 text-right">Nominal Penyaluran</th>
                                    <th class="py-3 px-6 text-right">% Dana Penyaluran</th>
                                </tr>
                            </thead>
                            <tbody class="text-xs font-semibold text-slate-700 divide-y divide-slate-50">
                                @foreach($resumeKota as $item)
                                    @php $pct = $denomDana > 0 ? ($item->total_amount / $denomDana) * 100 : 0; @endphp
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="py-3 px-6 font-bold text-slate-900">{{ $item->city_name }}</td>
                                        <td class="py-3 px-4 text-slate-600">{{ $item->province_name }}</td>
                                        <td class="py-3 px-4 text-center font-mono">{{ number_format($item->total_count) }}</td>
                                        <td class="py-3 px-4 text-center font-mono">{{ number_format($item->total_pm) }}</td>
                                        <td class="py-3 px-4 text-right font-black text-slate-900">Rp {{ number_format($item->total_amount, 0, ',', '.') }}</td>
                                        <td class="py-3 px-6 text-right font-mono font-bold text-emerald-600">{{ number_format($pct, 1) }}%</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- 3. Resume Penyaluran By Kategori Program --}}
                <div class="bg-white border border-slate-100 rounded-3xl overflow-hidden shadow-sm">
                    <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 px-6 py-3 text-white flex items-center justify-between">
                        <span class="text-xs font-black uppercase tracking-wider">Resume Penyaluran By Kategori Program</span>
                        <a href="{{ route('admin.distributions.export', array_merge(request()->query(), ['export_type' => 'kategori'])) }}" class="text-white/80 hover:text-white transition-colors" title="Export Data Resume Kategori Excel">
                            <i class="fa-solid fa-file-excel text-base"></i>
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-emerald-50/50 border-b border-slate-100 text-[10px] font-black uppercase tracking-wider text-slate-700">
                                    <th class="py-3 px-6">Kategori Program</th>
                                    <th class="py-3 px-4 text-center">Jumlah Penyaluran</th>
                                    <th class="py-3 px-4 text-center">Total PM</th>
                                    <th class="py-3 px-4 text-right">Nominal Penyaluran</th>
                                    <th class="py-3 px-6 text-right">% Dana Penyaluran</th>
                                </tr>
                            </thead>
                            <tbody class="text-xs font-semibold text-slate-700 divide-y divide-slate-50">
                                @foreach($resumeKategori as $item)
                                    @php $pct = $denomDana > 0 ? ($item->total_amount / $denomDana) * 100 : 0; @endphp
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="py-3 px-6 font-bold text-slate-900">{{ $item->category_name }}</td>
                                        <td class="py-3 px-4 text-center font-mono">{{ number_format($item->total_count) }}</td>
                                        <td class="py-3 px-4 text-center font-mono">{{ number_format($item->total_pm) }}</td>
                                        <td class="py-3 px-4 text-right font-black text-slate-900">Rp {{ number_format($item->total_amount, 0, ',', '.') }}</td>
                                        <td class="py-3 px-6 text-right font-mono font-bold text-emerald-600">{{ number_format($pct, 1) }}%</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- 4. Resume Penyaluran By Program --}}
                <div class="bg-white border border-slate-100 rounded-3xl overflow-hidden shadow-sm">
                    <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 px-6 py-3 text-white flex items-center justify-between">
                        <span class="text-xs font-black uppercase tracking-wider">Resume Penyaluran By Program</span>
                        <a href="{{ route('admin.distributions.export', array_merge(request()->query(), ['export_type' => 'program'])) }}" class="text-white/80 hover:text-white transition-colors" title="Export Data Resume Program Excel">
                            <i class="fa-solid fa-file-excel text-base"></i>
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-emerald-50/50 border-b border-slate-100 text-[10px] font-black uppercase tracking-wider text-slate-700">
                                    <th class="py-3 px-6">Nama Program</th>
                                    <th class="py-3 px-4 text-center">Jumlah Penyaluran</th>
                                    <th class="py-3 px-4 text-center">Total PM</th>
                                    <th class="py-3 px-4 text-right">Nominal Penyaluran</th>
                                    <th class="py-3 px-6 text-right">% Dana Penyaluran</th>
                                </tr>
                            </thead>
                            <tbody class="text-xs font-semibold text-slate-700 divide-y divide-slate-50">
                                @foreach($resumeProgram as $item)
                                    @php $pct = $denomDana > 0 ? ($item->total_amount / $denomDana) * 100 : 0; @endphp
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="py-3 px-6 font-bold text-slate-900">{{ $item->program_title }}</td>
                                        <td class="py-3 px-4 text-center font-mono">{{ number_format($item->total_count) }}</td>
                                        <td class="py-3 px-4 text-center font-mono">{{ number_format($item->total_pm) }}</td>
                                        <td class="py-3 px-4 text-right font-black text-slate-900">Rp {{ number_format($item->total_amount, 0, ',', '.') }}</td>
                                        <td class="py-3 px-6 text-right font-mono font-bold text-emerald-600">{{ number_format($pct, 1) }}%</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>{{-- end main --}}

    </div>{{-- end grid --}}

</section>

{{-- ==================== APEXCHARTS INITIALIZATION & CONFIGURATION (LIGHT MODE WHITE CARD THEME) ==================== --}}
<script>
document.addEventListener("DOMContentLoaded", function () {

    // Common Light Theme Configuration
    const themeOptions = {
        theme: { mode: 'light' },
        chart: {
            fontFamily: 'Inter, sans-serif',
            background: 'transparent',
            toolbar: {
                show: true,
                tools: {
                    download: '<i class="fa-solid fa-bars text-slate-600 text-lg hover:text-slate-900 transition-colors cursor-pointer" title="Download Graphic / CSV"></i>',
                    selection: false,
                    zoom: true,
                    zoomin: true,
                    zoomout: true,
                    pan: true,
                    reset: true
                },
                export: {
                    csv: { filename: 'Data_Grafik_Penyaluran' },
                    svg: { filename: 'Grafik_Penyaluran' },
                    png: { filename: 'Grafik_Penyaluran' }
                }
            }
        },
        colors: ['#0284c7', '#10b981', '#f59e0b', '#ec4899', '#8b5cf6', '#06b6d4', '#f97316'],
        grid: { borderColor: '#f1f5f9' }
    };

    // 1. Chart Daily Nominal (Line Chart)
    const dailyNominalOptions = {
        ...themeOptions,
        series: [{
            name: 'Nominal Penyaluran (Rp)',
            data: @json($dailyNominalData)
        }],
        chart: { ...themeOptions.chart, type: 'line', height: 320 },
        stroke: { curve: 'smooth', width: 3 },
        colors: ['#0284c7'],
        markers: { size: 5, hover: { size: 7 } },
        xaxis: { categories: @json($dailyDates), labels: { style: { colors: '#64748b', fontWeight: 600 } } },
        yaxis: {
            labels: {
                style: { colors: '#64748b', fontWeight: 600 },
                formatter: (val) => val >= 1000000 ? (val / 1000000).toFixed(0) + 'M' : val
            }
        },
        tooltip: {
            y: { formatter: (val) => 'Rp ' + new Intl.NumberFormat('id-ID').format(val) }
        }
    };
    new ApexCharts(document.querySelector("#chartDailyNominal"), dailyNominalOptions).render();

    // 2. Chart Daily PM (Line Chart)
    const dailyPMOptions = {
        ...themeOptions,
        series: [{
            name: 'Jumlah PM',
            data: @json($dailyPMData)
        }],
        chart: { ...themeOptions.chart, type: 'line', height: 320 },
        stroke: { curve: 'smooth', width: 3 },
        colors: ['#0284c7'],
        markers: { size: 5, hover: { size: 7 } },
        xaxis: { categories: @json($dailyDates), labels: { style: { colors: '#64748b', fontWeight: 600 } } },
        yaxis: {
            labels: {
                style: { colors: '#64748b', fontWeight: 600 },
                formatter: (val) => val >= 1000 ? (val / 1000).toFixed(0) + 'k' : val
            }
        },
        tooltip: {
            y: { formatter: (val) => new Intl.NumberFormat('id-ID').format(val) + ' PM' }
        }
    };
    new ApexCharts(document.querySelector("#chartDailyPM"), dailyPMOptions).render();

    // 3. Chart Monthly Nominal (Line Chart with Data Labels)
    const monthlyNominalOptions = {
        ...themeOptions,
        series: [{
            name: 'Total Nominal (Rp)',
            data: @json($monthlyNominalData)
        }],
        chart: { ...themeOptions.chart, type: 'line', height: 300 },
        stroke: { curve: 'smooth', width: 3 },
        colors: ['#0284c7'],
        markers: { size: 6 },
        dataLabels: {
            enabled: true,
            formatter: (val) => new Intl.NumberFormat('id-ID').format(val),
            style: { fontSize: '10px', colors: ['#0f172a'], fontWeight: 'bold' }
        },
        xaxis: { categories: @json($monthlyLabels), labels: { style: { colors: '#64748b', fontWeight: 600 } } },
        yaxis: { labels: { style: { colors: '#64748b', fontWeight: 600 } } }
    };
    new ApexCharts(document.querySelector("#chartMonthlyNominal"), monthlyNominalOptions).render();

    // 4. Chart Monthly PM (Line Chart with Data Labels)
    const monthlyPMOptions = {
        ...themeOptions,
        series: [{
            name: 'Total PM',
            data: @json($monthlyPMData)
        }],
        chart: { ...themeOptions.chart, type: 'line', height: 300 },
        stroke: { curve: 'smooth', width: 3 },
        colors: ['#0284c7'],
        markers: { size: 6 },
        dataLabels: {
            enabled: true,
            formatter: (val) => new Intl.NumberFormat('id-ID').format(val),
            style: { fontSize: '10px', colors: ['#0f172a'], fontWeight: 'bold' }
        },
        xaxis: { categories: @json($monthlyLabels), labels: { style: { colors: '#64748b', fontWeight: 600 } } },
        yaxis: { labels: { style: { colors: '#64748b', fontWeight: 600 } } }
    };
    new ApexCharts(document.querySelector("#chartMonthlyPM"), monthlyPMOptions).render();

    // 5. Chart Asnaf Pie
    const asnafPieOptions = {
        ...themeOptions,
        series: @json($asnafValues),
        labels: @json($asnafLabels),
        chart: { ...themeOptions.chart, type: 'pie', height: 320 },
        colors: ['#38bdf8', '#10b981', '#f59e0b', '#f97316', '#a855f7', '#ec4899', '#c084fc'],
        legend: { position: 'right', labels: { colors: '#334155' } },
        tooltip: {
            y: { formatter: (val) => 'Rp ' + new Intl.NumberFormat('id-ID').format(val) }
        }
    };
    new ApexCharts(document.querySelector("#chartAsnafPie"), asnafPieOptions).render();

    // 6. Chart Source Pie
    const sourcePieOptions = {
        ...themeOptions,
        series: @json($sourceValues),
        labels: @json($sourceLabels),
        chart: { ...themeOptions.chart, type: 'pie', height: 320 },
        colors: ['#0284c7', '#10b981', '#f59e0b', '#f97316'],
        legend: { position: 'right', labels: { colors: '#334155' } },
        tooltip: {
            y: { formatter: (val) => 'Rp ' + new Intl.NumberFormat('id-ID').format(val) }
        }
    };
    new ApexCharts(document.querySelector("#chartSourcePie"), sourcePieOptions).render();

    // 7. Chart Program Group Pie
    const programGroupPieOptions = {
        ...themeOptions,
        series: @json($programGroupValues),
        labels: @json($programGroupLabels),
        chart: { ...themeOptions.chart, type: 'pie', height: 320 },
        colors: ['#f97316', '#0284c7', '#10b981', '#eab308', '#ec4899', '#8b5cf6'],
        legend: { position: 'right', labels: { colors: '#334155' } },
        tooltip: {
            y: { formatter: (val) => 'Rp ' + new Intl.NumberFormat('id-ID').format(val) }
        }
    };
    new ApexCharts(document.querySelector("#chartProgramGroupPie"), programGroupPieOptions).render();

    // 8. Chart Program Name Pie
    const programNamePieOptions = {
        ...themeOptions,
        series: @json($programNameValues),
        labels: @json($programNameLabels),
        chart: { ...themeOptions.chart, type: 'pie', height: 320 },
        colors: ['#0284c7', '#10b981', '#f59e0b', '#ec4899', '#8b5cf6', '#06b6d4', '#f97316', '#a855f7'],
        legend: { position: 'right', labels: { colors: '#334155' } },
        tooltip: {
            y: { formatter: (val) => 'Rp ' + new Intl.NumberFormat('id-ID').format(val) }
        }
    };
    new ApexCharts(document.querySelector("#chartProgramNamePie"), programNamePieOptions).render();

});
</script>
@endsection
