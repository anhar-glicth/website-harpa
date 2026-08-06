@extends('layouts.app')

@section('content')
<div class="container max-w-7xl mx-auto px-4 py-8">

    <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-8 items-start min-w-0">

        {{-- Sidebar --}}
        <aside class="hidden lg:block space-y-5">
            @include('admin.sidebar', ['active' => 'prospects_dashboard'])
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
                <a href="{{ route('admin.prospects_dashboard') }}" class="px-4 py-2 rounded-t-xl text-xs font-black bg-emerald-700 text-white shadow-sm">
                    Dashboard Prospek
                </a>
            </div>

            {{-- Top Red Header Banner --}}
            <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 rounded-2xl px-6 py-3.5 text-white font-black text-sm uppercase tracking-wider shadow-sm flex items-center justify-between">
                <span class="flex items-center gap-2">
                    <i class="fa-solid fa-bullhorn text-white"></i> Dashboard Prospek
                </span>
                <span class="text-xs text-white/80 font-normal">Transaction Management → Dashboard Prospek</span>
            </div>

            {{-- Filter Form Controls --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm space-y-3 text-xs font-semibold text-slate-700">
                <form action="{{ route('admin.prospects_dashboard') }}" method="GET" class="space-y-3">
                    
                    {{-- Row 1 --}}
                    <div class="flex flex-wrap items-center gap-4">
                        <div class="flex items-center gap-2">
                            <span>Tgl Prospek :</span>
                            <input type="date" name="start_date" value="{{ request('start_date', '2023-06-14') }}" class="border border-slate-200 rounded-xl px-3 py-1.5 text-xs focus:border-emerald-600">
                            <span>s/d</span>
                            <input type="date" name="end_date" value="{{ request('end_date', '2026-08-07') }}" class="border border-slate-200 rounded-xl px-3 py-1.5 text-xs focus:border-emerald-600">
                        </div>
                        <div class="flex items-center gap-2">
                            <span>Target :</span>
                            <select name="target" class="border border-slate-200 rounded-xl px-3 py-1.5 text-xs focus:border-emerald-600 min-w-[120px]">
                                <option value="All">All</option>
                                <option value="Calon Donatur">Calon Donatur</option>
                                <option value="Donatur Resmi">Donatur Resmi</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            <span>Media :</span>
                            <select name="media_id" class="border border-slate-200 rounded-xl px-3 py-1.5 text-xs focus:border-emerald-600 min-w-[140px]">
                                <option value="">Semua Media</option>
                                @foreach($prospectMedia as $pm)
                                    <option value="{{ $pm->id }}">{{ $pm->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            <span>Respon :</span>
                            <select name="response_id" class="border border-slate-200 rounded-xl px-3 py-1.5 text-xs focus:border-emerald-600 min-w-[140px]">
                                <option value="">Semua Respon</option>
                                @foreach($prospectResponses as $pr)
                                    <option value="{{ $pr->id }}">{{ $pr->name }}</option>
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
                            <span>Potensi Dana :</span>
                            <select name="potensi_dana" class="border border-slate-200 rounded-xl px-3 py-1.5 text-xs focus:border-emerald-600 min-w-[100px]">
                                <option value="All">All</option>
                                <option value=">0">Ada Potensi</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            <span>Metode :</span>
                            <select name="method_id" class="border border-slate-200 rounded-xl px-3 py-1.5 text-xs focus:border-emerald-600 min-w-[140px]">
                                <option value="">Semua Metode</option>
                                @foreach($prospectMethods as $pmt)
                                    <option value="{{ $pmt->id }}">{{ $pmt->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            <span>Jenis :</span>
                            <select name="type_id" class="border border-slate-200 rounded-xl px-3 py-1.5 text-xs focus:border-emerald-600 min-w-[120px]">
                                <option value="">Semua Jenis</option>
                                @foreach($prospectTypes as $pt)
                                    <option value="{{ $pt->id }}">{{ $pt->name }}</option>
                                @endforeach
                            </select>
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
                            <span>Campaign :</span>
                            <select name="program_id" class="border border-slate-200 rounded-xl px-3 py-1.5 text-xs focus:border-emerald-600 min-w-[140px]">
                                <option value="">Semua Campaign</option>
                                @foreach($programs as $prg)
                                    <option value="{{ $prg->id }}">{{ $prg->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Row 3 --}}
                    <div class="flex flex-wrap items-center gap-4 pt-1">
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
                    </div>

                </form>
            </div>

            {{-- 4 Summary Card Headers --}}
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 divide-y sm:divide-y-0 sm:divide-x divide-slate-100 text-center">
                    
                    {{-- Σ All Prospek --}}
                    <div class="p-4 space-y-1">
                        <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white py-1.5 px-2 text-[10px] font-black uppercase tracking-wider rounded-t-xl -mx-4 -mt-4 mb-2">
                            Σ All Prospek
                        </div>
                        <p class="text-2xl font-black text-slate-900 font-mono">{{ number_format($totalAllProspects) }}</p>
                    </div>

                    {{-- Σ Prospek Donatur --}}
                    <div class="p-4 space-y-1">
                        <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white py-1.5 px-2 text-[10px] font-black uppercase tracking-wider rounded-t-xl -mx-4 -mt-4 mb-2">
                            Σ Prospek Donatur
                        </div>
                        <p class="text-2xl font-black text-slate-900 font-mono">{{ number_format($prospectDonors) }}</p>
                    </div>

                    {{-- Σ Prospek Calon Donatur --}}
                    <div class="p-4 space-y-1">
                        <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white py-1.5 px-2 text-[10px] font-black uppercase tracking-wider rounded-t-xl -mx-4 -mt-4 mb-2">
                            Σ Prospek Calon Donatur
                        </div>
                        <p class="text-2xl font-black text-slate-900 font-mono">{{ number_format($prospectCalonDonors) }}</p>
                    </div>

                    {{-- Σ Potensi Dana --}}
                    <div class="p-4 space-y-1">
                        <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white py-1.5 px-2 text-[10px] font-black uppercase tracking-wider rounded-t-xl -mx-4 -mt-4 mb-2">
                            Σ Potensi Dana
                        </div>
                        <p class="text-2xl font-black text-slate-900 font-mono">{{ number_format($totalPotensiDana, 0, ',', '.') }}</p>
                    </div>

                </div>
            </div>

            {{-- Highcharts Analytics Container --}}
            <div class="space-y-6">

                {{-- Hint Notification --}}
                <div class="bg-slate-900 border border-slate-800 rounded-2xl px-5 py-3 text-xs text-sky-400 font-semibold flex items-center justify-between shadow-md">
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-magnifying-glass-plus text-sky-400 text-sm"></i>
                        <span><strong>Fitur Zoom & Pan Grafik Active:</strong> Klik & drag pada area grafik untuk memperbesar (Zoom-In) tanggal/kategori prospek.</span>
                    </span>
                </div>

                {{-- Chart 1: Dashboard Prospek Per Tanggal (Potensi Dana) --}}
                <div class="bg-[#0f172a] rounded-2xl p-5 border border-slate-800 shadow-xl">
                    <div id="chartProspekPotensiPerTanggal" class="w-full h-80"></div>
                </div>

                {{-- Chart 2: Dashboard Prospek Per Tanggal (Total) --}}
                <div class="bg-[#0f172a] rounded-2xl p-5 border border-slate-800 shadow-xl">
                    <div id="chartProspekTotalPerTanggal" class="w-full h-80"></div>
                </div>

                {{-- Row 1 Pie Charts --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-[#0f172a] rounded-2xl p-5 border border-slate-800 shadow-xl">
                        <div id="chartDistribusiTarget" class="w-full h-80"></div>
                    </div>
                    <div class="bg-[#0f172a] rounded-2xl p-5 border border-slate-800 shadow-xl">
                        <div id="chartDistribusiRespon" class="w-full h-80"></div>
                    </div>
                </div>

                {{-- Row 2 Pie Charts --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-[#0f172a] rounded-2xl p-5 border border-slate-800 shadow-xl">
                        <div id="chartDistribusiMedia" class="w-full h-80"></div>
                    </div>
                    <div class="bg-[#0f172a] rounded-2xl p-5 border border-slate-800 shadow-xl">
                        <div id="chartDistribusiMetode" class="w-full h-80"></div>
                    </div>
                </div>

                {{-- Row 3 Pie Charts --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-[#0f172a] rounded-2xl p-5 border border-slate-800 shadow-xl">
                        <div id="chartDistribusiJenis" class="w-full h-80"></div>
                    </div>
                    <div class="bg-[#0f172a] rounded-2xl p-5 border border-slate-800 shadow-xl">
                        <div id="chartDistribusiFundraiser" class="w-full h-80"></div>
                    </div>
                </div>

                {{-- Row 4 Pie Charts --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-[#0f172a] rounded-2xl p-5 border border-slate-800 shadow-xl">
                        <div id="chartDistribusiRumpun" class="w-full h-80"></div>
                    </div>
                    <div class="bg-[#0f172a] rounded-2xl p-5 border border-slate-800 shadow-xl">
                        <div id="chartDistribusiCampaign" class="w-full h-80"></div>
                    </div>
                </div>

                {{-- Row 5 Column Charts (Presisi Screenshot User Terbaru) --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-[#0f172a] rounded-2xl p-5 border border-slate-800 shadow-xl">
                        <div id="chartTotalByPicProspek" class="w-full h-80"></div>
                    </div>
                    <div class="bg-[#0f172a] rounded-2xl p-5 border border-slate-800 shadow-xl">
                        <div id="chartTotalByKantorProspek" class="w-full h-80"></div>
                    </div>
                </div>

            </div>

            {{-- Resume Tables Section (Presisi Screenshot User Terbaru) --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 pt-4">

                {{-- Table 1: Resume Prospek PIC Prospek (Col 6) --}}
                <div class="lg:col-span-6 bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                    <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white px-4 py-2.5 flex items-center justify-between">
                        <span class="font-black text-xs uppercase tracking-wider">Resume Prospek PIC Prospek</span>
                        <button type="button" class="text-white/80 hover:text-white text-xs"><i class="fa-solid fa-file-excel"></i></button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs font-medium text-slate-700 divide-y divide-slate-100">
                            <thead class="bg-slate-50 text-[11px] font-bold text-slate-900 border-b border-slate-200">
                                <tr>
                                    <th class="py-2.5 px-3 text-center">No</th>
                                    <th class="py-2.5 px-3">PIC Prospek</th>
                                    <th class="py-2.5 px-3 text-center">Total Prospek</th>
                                    <th class="py-2.5 px-3 text-right">Potensi Dana</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($resumeProspekPic as $idx => $rPic)
                                    <tr class="hover:bg-slate-50">
                                        <td class="py-2 px-3 text-center text-slate-400 font-mono">{{ $idx + 1 }}</td>
                                        <td class="py-2 px-3 font-semibold text-slate-900">{{ $rPic['name'] }}</td>
                                        <td class="py-2 px-3 text-center font-mono font-bold text-slate-800">{{ number_format($rPic['total']) }}</td>
                                        <td class="py-2 px-3 text-right font-mono font-bold text-emerald-600">{{ number_format($rPic['potensi'], 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Table 2: Resume Prospek Per Kantor (Col 6) --}}
                <div class="lg:col-span-6 bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                    <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white px-4 py-2.5 flex items-center justify-between">
                        <span class="font-black text-xs uppercase tracking-wider">Resume Prospek Per Kantor</span>
                        <button type="button" class="text-white/80 hover:text-white text-xs"><i class="fa-solid fa-file-excel"></i></button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs font-medium text-slate-700 divide-y divide-slate-100">
                            <thead class="bg-slate-50 text-[11px] font-bold text-slate-900 border-b border-slate-200">
                                <tr>
                                    <th class="py-2.5 px-3 text-center">No</th>
                                    <th class="py-2.5 px-3">Kantor</th>
                                    <th class="py-2.5 px-3 text-center">Total Prospek</th>
                                    <th class="py-2.5 px-3 text-right">Potensi Dana</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($resumeProspekOffice as $idx => $rOfc)
                                    <tr class="hover:bg-slate-50">
                                        <td class="py-2 px-3 text-center text-slate-400 font-mono">{{ $idx + 1 }}</td>
                                        <td class="py-2 px-3 font-semibold text-slate-900">{{ $rOfc['name'] }}</td>
                                        <td class="py-2 px-3 text-center font-mono font-bold text-slate-800">{{ number_format($rOfc['total']) }}</td>
                                        <td class="py-2 px-3 text-right font-mono font-bold text-emerald-600">{{ number_format($rOfc['potensi'], 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            {{-- Table 3: Top 20 Prospek Donatur Base On Potensi Dana --}}
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white px-5 py-3 flex items-center justify-between">
                    <span class="font-black text-xs uppercase tracking-wider">Top 20 Prospek Donatur Base On Potensi Dana</span>
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
                                <th class="py-3 px-4 text-center">Total Prospek</th>
                                <th class="py-3 px-4 text-right">Potensi Dana</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($topProspekDonors as $idx => $tpd)
                                <tr class="hover:bg-slate-50">
                                    <td class="py-3 px-4 text-center text-slate-400 font-mono">{{ $idx + 1 }}</td>
                                    <td class="py-3 px-4 font-bold text-slate-900">{{ $tpd['name'] }}</td>
                                    <td class="py-3 px-4 font-mono text-emerald-700 font-bold">{{ $tpd['id_donatur'] }}</td>
                                    <td class="py-3 px-4 text-slate-700">{{ $tpd['office'] }}</td>
                                    <td class="py-3 px-4 text-slate-800">{{ $tpd['segment'] }}</td>
                                    <td class="py-3 px-4 text-slate-700">{{ $tpd['profile'] }}</td>
                                    <td class="py-3 px-4 font-bold text-slate-800">{{ $tpd['pic'] }}</td>
                                    <td class="py-3 px-4 text-center font-mono font-bold text-slate-800">{{ number_format($tpd['total']) }}</td>
                                    <td class="py-3 px-4 text-right font-mono font-black text-emerald-600">{{ number_format($tpd['potensi'], 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="py-6 text-center text-slate-400 font-bold">Tidak ada data prospek donatur.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Table 4: Top 20 Prospek Calon Donatur Base On Potensi Dana --}}
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white px-5 py-3 flex items-center justify-between">
                    <span class="font-black text-xs uppercase tracking-wider">Top 20 Prospek Calon Donatur Base On Potensi Dana</span>
                    <button type="button" class="text-white/80 hover:text-white text-xs"><i class="fa-solid fa-file-excel"></i></button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs font-medium text-slate-700 divide-y divide-slate-100 whitespace-nowrap">
                        <thead class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white text-[11px] font-black uppercase">
                            <tr>
                                <th class="py-3 px-4 text-center">No</th>
                                <th class="py-3 px-4">Nama Donatur</th>
                                <th class="py-3 px-4">No HP</th>
                                <th class="py-3 px-4">Email</th>
                                <th class="py-3 px-4">PIC Prospek</th>
                                <th class="py-3 px-4 text-center">Total Prospek</th>
                                <th class="py-3 px-4 text-right">Potensi Dana</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($topProspekCalonDonors as $idx => $tpcd)
                                <tr class="hover:bg-slate-50">
                                    <td class="py-3 px-4 text-center text-slate-400 font-mono">{{ $idx + 1 }}</td>
                                    <td class="py-3 px-4 font-bold text-slate-900">{{ $tpcd['name'] }}</td>
                                    <td class="py-3 px-4 font-mono text-slate-600">{{ $tpcd['phone'] }}</td>
                                    <td class="py-3 px-4 text-slate-600">{{ $tpcd['email'] }}</td>
                                    <td class="py-3 px-4 font-bold text-slate-800">{{ $tpcd['pic'] }}</td>
                                    <td class="py-3 px-4 text-center font-mono font-bold text-slate-800">{{ number_format($tpcd['total']) }}</td>
                                    <td class="py-3 px-4 text-right font-mono font-black text-emerald-600">{{ number_format($tpcd['potensi'], 0, ',', '.') }}</td>
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

        // 1. Chart Prospek Per Tanggal (Potensi Dana)
        Highcharts.chart('chartProspekPotensiPerTanggal', Highcharts.merge(darkTheme, {
            chart: { type: 'line' },
            title: { text: 'Dashboard Prospek Per Tanggal (Potensi Dana)' },
            xAxis: { categories: ['2026-02-25'] },
            yAxis: { title: { text: 'Nominal Potensi Dana' } },
            series: [{ name: 'Total Nominal', data: [50000], color: '#38bdf8' }]
        }));

        // 2. Chart Prospek Per Tanggal (Total)
        Highcharts.chart('chartProspekTotalPerTanggal', Highcharts.merge(darkTheme, {
            chart: { type: 'line' },
            title: { text: 'Dashboard Prospek Per Tanggal (Total)' },
            xAxis: { categories: ['2026-02-25'] },
            yAxis: { title: { text: 'Total Prospek' } },
            series: [{ name: 'Total Prospek', data: [1], color: '#38bdf8' }]
        }));

        // 3. Chart Distribusi Target (Pie)
        Highcharts.chart('chartDistribusiTarget', Highcharts.merge(darkTheme, {
            chart: { type: 'pie' },
            title: { text: 'Distribusi Target' },
            plotOptions: { pie: { allowPointSelect: true, cursor: 'pointer', dataLabels: { enabled: true, format: '{point.name}: {point.percentage:.1f} %', style: { color: '#cbd5e1', fontSize: '9px' } } } },
            series: [{ name: 'Persentase', data: [{ name: 'Calon Donatur', y: 100.0, color: '#38bdf8' }] }]
        }));

        // 4. Chart Distribusi Respon (Pie)
        Highcharts.chart('chartDistribusiRespon', Highcharts.merge(darkTheme, {
            chart: { type: 'pie' },
            title: { text: 'Distribusi Respon' },
            plotOptions: { pie: { allowPointSelect: true, cursor: 'pointer', dataLabels: { enabled: true, format: '{point.name}: {point.percentage:.1f} %', style: { color: '#cbd5e1', fontSize: '9px' } } } },
            series: [{ name: 'Persentase', data: [{ name: 'Belum Diprospek', y: 100.0, color: '#38bdf8' }] }]
        }));

        // 5. Chart Distribusi Media (Pie)
        Highcharts.chart('chartDistribusiMedia', Highcharts.merge(darkTheme, {
            chart: { type: 'pie' },
            title: { text: 'Distribusi Media' },
            plotOptions: { pie: { allowPointSelect: true, cursor: 'pointer', dataLabels: { enabled: true, format: '{point.name}: {point.percentage:.1f} %', style: { color: '#cbd5e1', fontSize: '9px' } } } },
            series: [{ name: 'Persentase', data: [{ name: 'WhatsApp', y: 100.0, color: '#38bdf8' }] }]
        }));

        // 6. Chart Distribusi Metode (Pie)
        Highcharts.chart('chartDistribusiMetode', Highcharts.merge(darkTheme, {
            chart: { type: 'pie' },
            title: { text: 'Distribusi Metode' },
            plotOptions: { pie: { allowPointSelect: true, cursor: 'pointer', dataLabels: { enabled: true, format: '{point.name}: {point.percentage:.1f} %', style: { color: '#cbd5e1', fontSize: '9px' } } } },
            series: [{ name: 'Persentase', data: [{ name: 'Blast / Broadcast', y: 100.0, color: '#38bdf8' }] }]
        }));

        // 7. Chart Distribusi Jenis (Pie)
        Highcharts.chart('chartDistribusiJenis', Highcharts.merge(darkTheme, {
            chart: { type: 'pie' },
            title: { text: 'Distribusi Jenis' },
            plotOptions: { pie: { allowPointSelect: true, cursor: 'pointer', dataLabels: { enabled: true, format: '{point.name}: {point.percentage:.1f} %', style: { color: '#cbd5e1', fontSize: '9px' } } } },
            series: [{ name: 'Persentase', data: [{ name: 'FU 1', y: 100.0, color: '#38bdf8' }] }]
        }));

        // 8. Chart Distribusi Fundraiser (Pie)
        Highcharts.chart('chartDistribusiFundraiser', Highcharts.merge(darkTheme, {
            chart: { type: 'pie' },
            title: { text: 'Distribusi Fundraiser' },
            plotOptions: { pie: { allowPointSelect: true, cursor: 'pointer', dataLabels: { enabled: true, format: '{point.name}: {point.percentage:.1f} %', style: { color: '#cbd5e1', fontSize: '9px' } } } },
            series: [{ name: 'Persentase', data: [{ name: 'CRM', y: 100.0, color: '#38bdf8' }] }]
        }));

        // 9. Chart Distribusi Rumpun Program (Pie)
        Highcharts.chart('chartDistribusiRumpun', Highcharts.merge(darkTheme, {
            chart: { type: 'pie' },
            title: { text: 'Distribusi Rumpun Program' },
            plotOptions: { pie: { allowPointSelect: true, cursor: 'pointer', dataLabels: { enabled: true, format: '{point.name}: {point.percentage:.1f} %', style: { color: '#cbd5e1', fontSize: '9px' } } } },
            series: [{ name: 'Persentase', data: [{ name: 'Dunia Islam', y: 100.0, color: '#38bdf8' }] }]
        }));

        // 10. Chart Distribusi Campaign (Pie)
        Highcharts.chart('chartDistribusiCampaign', Highcharts.merge(darkTheme, {
            chart: { type: 'pie' },
            title: { text: 'Distribusi Campaign' },
            plotOptions: { pie: { allowPointSelect: true, cursor: 'pointer', dataLabels: { enabled: true, format: '{point.name}: {point.percentage:.1f} %', style: { color: '#cbd5e1', fontSize: '9px' } } } },
            series: [{ name: 'Persentase', data: [{ name: 'tehsgd', y: 100.0, color: '#38bdf8' }] }]
        }));

        // 11. Chart Total Prospek Berdasarkan PIC Prospek (Column)
        Highcharts.chart('chartTotalByPicProspek', Highcharts.merge(darkTheme, {
            chart: { type: 'column' },
            title: { text: 'Total Prospek Berdasarkan PIC Prospek' },
            xAxis: { categories: ['Baiq Nickte Madu Jasmine'] },
            yAxis: { title: { text: 'Total Data' } },
            series: [{ name: 'Total Prospek', data: [1], color: '#38bdf8' }]
        }));

        // 12. Chart Total Data Berdasarkan Kantor (Column)
        Highcharts.chart('chartTotalByKantorProspek', Highcharts.merge(darkTheme, {
            chart: { type: 'column' },
            title: { text: 'Total Data Berdasarkan Kantor' },
            xAxis: { categories: ['Pusat-Amal Bakti Dunia Islam'] },
            yAxis: { title: { text: 'Total Data' } },
            series: [{ name: 'Total Data', data: [1], color: '#38bdf8' }]
        }));

    });
</script>
@endsection
