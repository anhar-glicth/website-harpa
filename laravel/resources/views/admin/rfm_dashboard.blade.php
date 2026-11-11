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
            <div class="bg-emerald-700 rounded-2xl px-6 py-3.5 text-white font-black text-sm uppercase tracking-wider shadow-sm flex flex-wrap items-center justify-between gap-2">
                <span class="flex items-center gap-2">
                    <i class="fa-solid fa-chart-pie text-white"></i> Dashboard RFM Segmentation
                </span>
                <span class="text-xs text-emerald-100 font-normal">Dashboard / RFM Segmentation</span>
            </div>

            {{-- Filter Form Controls --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm space-y-3 text-xs font-semibold text-slate-700">
                <form action="{{ route('admin.rfm_dashboard') }}" method="GET" class="space-y-3">
                    
                    {{-- Row 1 --}}
                    <div class="flex flex-wrap items-center justify-center gap-4">
                        <div class="flex items-center gap-2">
                            <label for="rfm_start">Tgl Registrasi :</label>
                            <input id="rfm_start" type="date" name="start_date" value="{{ request('start_date') }}" class="border border-slate-300 rounded-xl px-3 py-2 min-h-[44px] text-xs focus:border-emerald-700">
                            <span>s/d</span>
                            <input id="rfm_end" type="date" name="end_date" aria-label="Tanggal akhir registrasi" value="{{ request('end_date') }}" class="border border-slate-300 rounded-xl px-3 py-2 min-h-[44px] text-xs focus:border-emerald-700">
                        </div>
                        <div class="flex items-center gap-2">
                            <label for="rfm_segment">Segmen :</label>
                            <select id="rfm_segment" name="segment" class="border border-slate-300 rounded-xl px-3 py-2 min-h-[44px] text-xs focus:border-emerald-700 min-w-[120px]">
                                <option value="All">All</option>
                                <option value="Retail">Retail</option>
                                <option value="Corporate">Corporate</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            <label for="rfm_gender">Jenis Kelamin :</label>
                            <select id="rfm_gender" name="gender" class="border border-slate-300 rounded-xl px-3 py-2 min-h-[44px] text-xs focus:border-emerald-700 min-w-[100px]">
                                <option value="All">All</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            <label for="rfm_90">RFM 90 Days :</label>
                            <select id="rfm_90" name="rfm_90" class="border border-slate-300 rounded-xl px-3 py-2 min-h-[44px] text-xs focus:border-emerald-700 min-w-[110px]">
                                <option value="All">All</option>
                                <option value="CHAMPION">CHAMPION</option>
                                <option value="LOYAL">LOYAL</option>
                                <option value="POTENTIAL">POTENTIAL</option>
                                <option value="DORMANT">DORMANT</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            <label for="rfm_alltime">RFM All Time :</label>
                            <select id="rfm_alltime" name="rfm_alltime" class="border border-slate-300 rounded-xl px-3 py-2 min-h-[44px] text-xs focus:border-emerald-700 min-w-[110px]">
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
                            <label for="rfm_pic">PIC Prospek :</label>
                            <select id="rfm_pic" name="pic_prospect" class="border border-slate-300 rounded-xl px-3 py-2 min-h-[44px] text-xs focus:border-emerald-700 min-w-[180px]">
                                <option value="">Semua PIC</option>
                                @foreach($picUsers as $pic)
                                    <option value="{{ $pic->name }}" {{ request('pic_prospect') == $pic->name ? 'selected' : '' }}>{{ $pic->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            <label for="rfm_office">Kantor :</label>
                            <select id="rfm_office" name="office_id" class="border border-slate-300 rounded-xl px-3 py-2 min-h-[44px] text-xs focus:border-emerald-700 min-w-[180px]">
                                <option value="">Pilih Kantor</option>
                                @foreach($offices as $ofc)
                                    <option value="{{ $ofc->id }}">{{ $ofc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-300 font-bold px-5 py-2 min-h-[44px] rounded-xl text-xs inline-flex items-center gap-1.5 transition-colors">
                            <i class="fa-solid fa-magnifying-glass"></i> Show
                        </button>
                    </div>

                </form>
            </div>

            {{-- 4 Summary Card Headers --}}
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 divide-y sm:divide-y-0 sm:divide-x divide-slate-100 text-center">
                    
                    {{-- Donatur All --}}
                    <div class="p-4 space-y-1">
                        <div class="bg-emerald-700 text-white py-1.5 px-2 text-xs font-black uppercase tracking-wider rounded-t-xl -mx-4 -mt-4 mb-2">
                            Total Donatur All
                        </div>
                        <p class="text-2xl font-black text-slate-900 font-mono">{{ number_format($totalDonors) }}</p>
                    </div>

                    {{-- Donatur Laki-laki --}}
                    <div class="p-4 space-y-1">
                        <div class="bg-emerald-700 text-white py-1.5 px-2 text-xs font-black uppercase tracking-wider rounded-t-xl -mx-4 -mt-4 mb-2">
                            Donatur Laki-laki
                        </div>
                        <p class="text-2xl font-black text-slate-900 font-mono">{{ number_format($maleDonors) }}</p>
                    </div>

                    {{-- Donatur Perempuan --}}
                    <div class="p-4 space-y-1">
                        <div class="bg-emerald-700 text-white py-1.5 px-2 text-xs font-black uppercase tracking-wider rounded-t-xl -mx-4 -mt-4 mb-2">
                            Donatur Perempuan
                        </div>
                        <p class="text-2xl font-black text-slate-900 font-mono">{{ number_format($femaleDonors) }}</p>
                    </div>

                    {{-- Jenis Kelamin Tidak Diketahui --}}
                    <div class="p-4 space-y-1">
                        <div class="bg-emerald-700 text-white py-1.5 px-2 text-xs font-black uppercase tracking-wider rounded-t-xl -mx-4 -mt-4 mb-2">
                            Gender Tidak Diketahui
                        </div>
                        <p class="text-2xl font-black text-slate-900 font-mono">{{ number_format($unknownSexDonors) }}</p>
                    </div>

                </div>
            </div>

            {{-- Panduan Singkat RFM --}}
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                <button type="button" @click="showGuide = !showGuide" :aria-expanded="showGuide.toString()" class="w-full text-left bg-slate-900 hover:bg-slate-800 text-white px-6 py-3.5 min-h-[44px] flex items-center justify-between transition-colors">
                    <span class="flex items-center gap-3">
                        <i class="fa-solid fa-chart-simple text-emerald-400 text-lg"></i>
                        <span class="text-xs font-black tracking-wide uppercase">RFM Segmentation - Panduan Singkat</span>
                    </span>
                    <span class="text-xs font-bold bg-slate-800 text-slate-200 px-3 py-1 rounded-lg border border-slate-700 inline-flex items-center gap-1.5">
                        <span x-text="showGuide ? 'Sembunyikan' : 'Tampilkan'"></span>
                        <i class="fa-solid" :class="showGuide ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </span>
                </button>

                <div x-show="showGuide" x-collapse class="p-5 space-y-5 text-xs text-slate-700 border-t border-slate-100">
                    <div class="bg-emerald-50/70 border border-emerald-200/80 rounded-xl p-4 space-y-2">
                        <h2 class="font-black text-emerald-900 text-xs">Apa itu RFM?</h2>
                        <p class="text-slate-700">RFM adalah metode segmentasi donatur berdasarkan: <strong>Recency</strong> (waktu donasi terakhir), <strong>Frequency</strong> (frekuensi donasi), &amp; <strong>Monetary</strong> (total nilai donasi).</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="border border-slate-200 rounded-xl p-3">
                            <h3 class="font-bold text-slate-900 mb-2 border-b pb-1">RFM Rolling 90 Days</h3>
                            <p class="text-xs text-slate-600 mb-2">Fokus pada aktivitas terkini untuk operasional &amp; follow-up cepat.</p>
                            <ul class="space-y-1 text-xs">
                                <li><strong class="text-emerald-700">CHAMPION:</strong> >= 3 transaksi &amp; total >= 500.000</li>
                                <li><strong class="text-sky-700">LOYAL:</strong> 2 transaksi</li>
                                <li><strong class="text-indigo-700">POTENTIAL:</strong> 1 transaksi</li>
                                <li><strong class="text-rose-700">DORMANT:</strong> Tidak ada transaksi</li>
                            </ul>
                        </div>
                        <div class="border border-slate-200 rounded-xl p-3">
                            <h3 class="font-bold text-slate-900 mb-2 border-b pb-1">RFM All Time</h3>
                            <p class="text-xs text-slate-600 mb-2">Fokus pada nilai historis &amp; potensi jangka panjang.</p>
                            <ul class="space-y-1 text-xs">
                                <li><strong class="text-teal-700">NEW:</strong> Belum pernah donasi</li>
                                <li><strong class="text-emerald-700">CHAMPION:</strong> &lt;= 90 hari, >= 3 transaksi, total >= 500.000</li>
                                <li><strong class="text-sky-700">LOYAL:</strong> &lt;= 120 hari, >= 2 transaksi</li>
                                <li><strong class="text-indigo-700">POTENTIAL:</strong> &lt;= 180 hari</li>
                                <li><strong class="text-rose-700">DORMANT:</strong> &gt; 180 hari tidak donasi</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Highcharts Analytics Container --}}
            <div class="space-y-6">

                {{-- Hint Notification --}}
                <div class="bg-slate-900 border border-slate-800 rounded-2xl px-5 py-3 text-xs text-sky-400 font-semibold flex items-center justify-between shadow-md">
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-magnifying-glass-plus text-sky-400 text-sm"></i>
                        <span><strong>Fitur Interactive RFM Charts Active:</strong> Perbandingan RFM 90 Days vs All Time, Distribusi Pie, & Heatmap Matrix.</span>
                    </span>
                </div>

                {{-- Chart 1: Perbandingan RFM 90 Days vs All Time --}}
                <div class="bg-[#0f172a] rounded-2xl p-5 border border-slate-800 shadow-xl">
                    <div id="chartRfmComparison" class="w-full h-80"></div>
                </div>

                {{-- Chart 2 & 3: Two Pie Charts --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-[#0f172a] rounded-2xl p-5 border border-slate-800 shadow-xl">
                        <div id="chartDistribusiRfm90" class="w-full h-80"></div>
                    </div>
                    <div class="bg-[#0f172a] rounded-2xl p-5 border border-slate-800 shadow-xl">
                        <div id="chartDistribusiRfmAllTime" class="w-full h-80"></div>
                    </div>
                </div>

                {{-- Chart 4: Heatmap Matrix RFM --}}
                <div class="bg-[#0f172a] rounded-2xl p-5 border border-slate-800 shadow-xl">
                    <div id="chartHeatmapRfm" class="w-full h-96"></div>
                </div>

            </div>

            {{-- Resume Tables Section (Presisi Screenshot User Terbaru) --}}
            <div class="space-y-6 pt-2">

                {{-- Table 1: Resume RFM 90 Days By PIC Prospek --}}
                <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                    <div class="bg-emerald-700 text-white px-5 py-3 flex items-center justify-between">
                        <span class="font-black text-xs uppercase tracking-wider">Resume RFM 90 Days By PIC Prospek</span>
                        <button type="button" aria-label="Export Resume RFM 90 Days" class="text-emerald-100 hover:text-white text-xs"><i class="fa-solid fa-file-excel"></i></button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs font-medium text-slate-700 divide-y divide-slate-100 whitespace-nowrap">
                            <thead class="bg-emerald-700 text-white text-xs font-black uppercase">
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
                                        <td class="py-2.5 px-4 text-center text-slate-600 font-mono">{{ $idx + 1 }}</td>
                                        <td class="py-2.5 px-4 font-bold text-slate-900">{{ $rPic['name'] }}</td>
                                        <td class="py-2.5 px-4 text-center font-mono font-bold text-slate-800">{{ number_format($rPic['champion']) }}</td>
                                        <td class="py-2.5 px-4 text-center font-mono font-bold text-slate-800">{{ number_format($rPic['loyal']) }}</td>
                                        <td class="py-2.5 px-4 text-center font-mono font-bold text-slate-800">{{ number_format($rPic['potential']) }}</td>
                                        <td class="py-2.5 px-4 text-center font-mono font-bold text-slate-800">{{ number_format($rPic['need_attention']) }}</td>
                                        <td class="py-2.5 px-4 text-center font-mono font-bold text-slate-800">{{ number_format($rPic['low_engagement']) }}</td>
                                        <td class="py-2.5 px-4 text-center font-mono font-bold text-slate-800">{{ number_format($rPic['dormant']) }}</td>
                                    </tr>
                                @endforeach
                                {{-- TOTAL Row Highlighted Amber --}}
                                @php $totPic90 = collect($resumeRfmByPic); @endphp
                                <tr class="bg-amber-400 text-slate-900 font-black">
                                    <td class="py-3 px-4 text-center font-mono">{{ $totPic90->count() ?: '-' }}</td>
                                    <td class="py-3 px-4 font-black tracking-wider uppercase">TOTAL</td>
                                    <td class="py-3 px-4 text-center font-mono font-black text-slate-900">{{ number_format($totPic90->sum('champion')) }}</td>
                                    <td class="py-3 px-4 text-center font-mono font-black text-slate-900">{{ number_format($totPic90->sum('loyal')) }}</td>
                                    <td class="py-3 px-4 text-center font-mono font-black text-slate-900">{{ number_format($totPic90->sum('potential')) }}</td>
                                    <td class="py-3 px-4 text-center font-mono font-black text-slate-900">{{ number_format($totPic90->sum('need_attention')) }}</td>
                                    <td class="py-3 px-4 text-center font-mono font-black text-slate-900">{{ number_format($totPic90->sum('low_engagement')) }}</td>
                                    <td class="py-3 px-4 text-center font-mono font-black text-slate-900">{{ number_format($totPic90->sum('dormant')) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Table 2: Resume RFM All Time By PIC Prospek --}}
                <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                    <div class="bg-emerald-700 text-white px-5 py-3 flex items-center justify-between">
                        <span class="font-black text-xs uppercase tracking-wider">Resume RFM All Time By PIC Prospek</span>
                        <button type="button" aria-label="Export Resume RFM All Time" class="text-emerald-100 hover:text-white text-xs"><i class="fa-solid fa-file-excel"></i></button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs font-medium text-slate-700 divide-y divide-slate-100 whitespace-nowrap">
                            <thead class="bg-emerald-700 text-white text-xs font-black uppercase">
                                <tr>
                                    <th class="py-3 px-4 text-center">No</th>
                                    <th class="py-3 px-4">PIC Prospek</th>
                                    <th class="py-3 px-4 text-center">Champion</th>
                                    <th class="py-3 px-4 text-center">Loyal</th>
                                    <th class="py-3 px-4 text-center">Potential</th>
                                    <th class="py-3 px-4 text-center">Need Attention</th>
                                    <th class="py-3 px-4 text-center">Low Engagement</th>
                                    <th class="py-3 px-4 text-center">Dormant</th>
                                    <th class="py-3 px-4 text-center">New</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($resumeRfmAllTimeByPic as $idx => $rPicAt)
                                    <tr class="hover:bg-slate-50">
                                        <td class="py-2.5 px-4 text-center text-slate-600 font-mono">{{ $idx + 1 }}</td>
                                        <td class="py-2.5 px-4 font-bold text-slate-900">{{ $rPicAt['name'] }}</td>
                                        <td class="py-2.5 px-4 text-center font-mono font-bold text-slate-800">{{ number_format($rPicAt['champion']) }}</td>
                                        <td class="py-2.5 px-4 text-center font-mono font-bold text-slate-800">{{ number_format($rPicAt['loyal']) }}</td>
                                        <td class="py-2.5 px-4 text-center font-mono font-bold text-slate-800">{{ number_format($rPicAt['potential']) }}</td>
                                        <td class="py-2.5 px-4 text-center font-mono font-bold text-slate-800">{{ number_format($rPicAt['need_attention']) }}</td>
                                        <td class="py-2.5 px-4 text-center font-mono font-bold text-slate-800">{{ number_format($rPicAt['low_engagement']) }}</td>
                                        <td class="py-2.5 px-4 text-center font-mono font-bold text-slate-800">{{ number_format($rPicAt['dormant']) }}</td>
                                        <td class="py-2.5 px-4 text-center font-mono font-bold text-slate-800">{{ number_format($rPicAt['new']) }}</td>
                                    </tr>
                                @endforeach
                                {{-- TOTAL Row Highlighted Amber/Orange --}}
                                @php $totPicAt = collect($resumeRfmAllTimeByPic); @endphp
                                <tr class="bg-amber-400 text-slate-900 font-black">
                                    <td class="py-3 px-4 text-center font-mono">{{ $totPicAt->count() ?: '-' }}</td>
                                    <td class="py-3 px-4 font-black tracking-wider uppercase">TOTAL</td>
                                    <td class="py-3 px-4 text-center font-mono font-black text-slate-900">{{ number_format($totPicAt->sum('champion')) }}</td>
                                    <td class="py-3 px-4 text-center font-mono font-black text-slate-900">{{ number_format($totPicAt->sum('loyal')) }}</td>
                                    <td class="py-3 px-4 text-center font-mono font-black text-slate-900">{{ number_format($totPicAt->sum('potential')) }}</td>
                                    <td class="py-3 px-4 text-center font-mono font-black text-slate-900">{{ number_format($totPicAt->sum('need_attention')) }}</td>
                                    <td class="py-3 px-4 text-center font-mono font-black text-slate-900">{{ number_format($totPicAt->sum('low_engagement')) }}</td>
                                    <td class="py-3 px-4 text-center font-mono font-black text-slate-900">{{ number_format($totPicAt->sum('dormant')) }}</td>
                                    <td class="py-3 px-4 text-center font-mono font-black text-slate-900">{{ number_format($totPicAt->sum('new')) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Table 3: Resume RFM 90 Days By Office --}}
                <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                    <div class="bg-emerald-700 text-white px-5 py-3 flex items-center justify-between">
                        <span class="font-black text-xs uppercase tracking-wider">Resume RFM 90 Days By Office</span>
                        <button type="button" aria-label="Export Resume RFM 90 Days By Office" class="text-emerald-100 hover:text-white text-xs"><i class="fa-solid fa-file-excel"></i></button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs font-medium text-slate-700 divide-y divide-slate-100 whitespace-nowrap">
                            <thead class="bg-emerald-700 text-white text-xs font-black uppercase">
                                <tr>
                                    <th class="py-3 px-4 text-center">No</th>
                                    <th class="py-3 px-4">Kantor</th>
                                    <th class="py-3 px-4 text-center">Champion</th>
                                    <th class="py-3 px-4 text-center">Loyal</th>
                                    <th class="py-3 px-4 text-center">Potential</th>
                                    <th class="py-3 px-4 text-center">Need Attention</th>
                                    <th class="py-3 px-4 text-center">Low Engagement</th>
                                    <th class="py-3 px-4 text-center">Dormant</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($resumeRfm90ByOffice as $idx => $rOfc90)
                                    <tr class="bg-amber-400 text-slate-900 font-bold">
                                        <td class="py-2.5 px-4 text-center font-mono">{{ $idx + 1 }}</td>
                                        <td class="py-2.5 px-4 font-bold text-slate-900">{{ $rOfc90['name'] }}</td>
                                        <td class="py-2.5 px-4 text-center font-mono font-bold text-slate-900">{{ number_format($rOfc90['champion']) }}</td>
                                        <td class="py-2.5 px-4 text-center font-mono font-bold text-slate-900">{{ number_format($rOfc90['loyal']) }}</td>
                                        <td class="py-2.5 px-4 text-center font-mono font-bold text-slate-900">{{ number_format($rOfc90['potential']) }}</td>
                                        <td class="py-2.5 px-4 text-center font-mono font-bold text-slate-900">{{ number_format($rOfc90['need_attention']) }}</td>
                                        <td class="py-2.5 px-4 text-center font-mono font-bold text-slate-900">{{ number_format($rOfc90['low_engagement']) }}</td>
                                        <td class="py-2.5 px-4 text-center font-mono font-bold text-slate-900">{{ number_format($rOfc90['dormant']) }}</td>
                                    </tr>
                                @endforeach
                                {{-- TOTAL Row Highlighted Amber --}}
                                @php $totOfc90 = collect($resumeRfm90ByOffice); @endphp
                                <tr class="bg-amber-400 text-slate-900 font-black border-t border-amber-500">
                                    <td class="py-3 px-4 text-center font-mono">{{ $totOfc90->count() ?: '-' }}</td>
                                    <td class="py-3 px-4 font-black tracking-wider uppercase">TOTAL</td>
                                    <td class="py-3 px-4 text-center font-mono font-black text-slate-900">{{ number_format($totOfc90->sum('champion')) }}</td>
                                    <td class="py-3 px-4 text-center font-mono font-black text-slate-900">{{ number_format($totOfc90->sum('loyal')) }}</td>
                                    <td class="py-3 px-4 text-center font-mono font-black text-slate-900">{{ number_format($totOfc90->sum('potential')) }}</td>
                                    <td class="py-3 px-4 text-center font-mono font-black text-slate-900">{{ number_format($totOfc90->sum('need_attention')) }}</td>
                                    <td class="py-3 px-4 text-center font-mono font-black text-slate-900">{{ number_format($totOfc90->sum('low_engagement')) }}</td>
                                    <td class="py-3 px-4 text-center font-mono font-black text-slate-900">{{ number_format($totOfc90->sum('dormant')) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Table 4: Resume RFM All Time By Office --}}
                <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                    <div class="bg-emerald-700 text-white px-5 py-3 flex items-center justify-between">
                        <span class="font-black text-xs uppercase tracking-wider">Resume RFM All Time By Office</span>
                        <button type="button" aria-label="Export Resume RFM All Time By Office" class="text-emerald-100 hover:text-white text-xs"><i class="fa-solid fa-file-excel"></i></button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs font-medium text-slate-700 divide-y divide-slate-100 whitespace-nowrap">
                            <thead class="bg-emerald-700 text-white text-xs font-black uppercase">
                                <tr>
                                    <th class="py-3 px-4 text-center">No</th>
                                    <th class="py-3 px-4">Kantor</th>
                                    <th class="py-3 px-4 text-center">Champion</th>
                                    <th class="py-3 px-4 text-center">Loyal</th>
                                    <th class="py-3 px-4 text-center">Potential</th>
                                    <th class="py-3 px-4 text-center">Need Attention</th>
                                    <th class="py-3 px-4 text-center">Low Engagement</th>
                                    <th class="py-3 px-4 text-center">Dormant</th>
                                    <th class="py-3 px-4 text-center">New</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($resumeRfmAllTimeByOffice as $idx => $rOfcAt)
                                    <tr class="hover:bg-slate-50">
                                        <td class="py-2.5 px-4 text-center text-slate-600 font-mono">{{ $idx + 1 }}</td>
                                        <td class="py-2.5 px-4 font-bold text-slate-900">{{ $rOfcAt['name'] }}</td>
                                        <td class="py-2.5 px-4 text-center font-mono font-bold text-slate-800">{{ number_format($rOfcAt['champion']) }}</td>
                                        <td class="py-2.5 px-4 text-center font-mono font-bold text-slate-800">{{ number_format($rOfcAt['loyal']) }}</td>
                                        <td class="py-2.5 px-4 text-center font-mono font-bold text-slate-800">{{ number_format($rOfcAt['potential']) }}</td>
                                        <td class="py-2.5 px-4 text-center font-mono font-bold text-slate-800">{{ number_format($rOfcAt['need_attention']) }}</td>
                                        <td class="py-2.5 px-4 text-center font-mono font-bold text-slate-800">{{ number_format($rOfcAt['low_engagement']) }}</td>
                                        <td class="py-2.5 px-4 text-center font-mono font-bold text-slate-800">{{ number_format($rOfcAt['dormant']) }}</td>
                                        <td class="py-2.5 px-4 text-center font-mono font-bold text-slate-800">{{ number_format($rOfcAt['new']) }}</td>
                                    </tr>
                                @endforeach
                                {{-- TOTAL Row Highlighted Amber/Orange --}}
                                @php $totOfcAt = collect($resumeRfmAllTimeByOffice); @endphp
                                <tr class="bg-amber-400 text-slate-900 font-black">
                                    <td class="py-3 px-4 text-center font-mono">{{ $totOfcAt->count() ?: '-' }}</td>
                                    <td class="py-3 px-4 font-black tracking-wider uppercase">TOTAL</td>
                                    <td class="py-3 px-4 text-center font-mono font-black text-slate-900">{{ number_format($totOfcAt->sum('champion')) }}</td>
                                    <td class="py-3 px-4 text-center font-mono font-black text-slate-900">{{ number_format($totOfcAt->sum('loyal')) }}</td>
                                    <td class="py-3 px-4 text-center font-mono font-black text-slate-900">{{ number_format($totOfcAt->sum('potential')) }}</td>
                                    <td class="py-3 px-4 text-center font-mono font-black text-slate-900">{{ number_format($totOfcAt->sum('need_attention')) }}</td>
                                    <td class="py-3 px-4 text-center font-mono font-black text-slate-900">{{ number_format($totOfcAt->sum('low_engagement')) }}</td>
                                    <td class="py-3 px-4 text-center font-mono font-black text-slate-900">{{ number_format($totOfcAt->sum('dormant')) }}</td>
                                    <td class="py-3 px-4 text-center font-mono font-black text-slate-900">{{ number_format($totOfcAt->sum('new')) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Table 5: Top 20 Donatur Base On Kontribusi Donasi (All Time Donation) --}}
                <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                    <div class="bg-emerald-700 text-white px-5 py-3 flex items-center justify-between">
                        <span class="font-black text-xs uppercase tracking-wider">Top 20 Donatur Base On Kontribusi Donasi (All Time Donation)</span>
                        <button type="button" aria-label="Export Top 20 Donatur Kontribusi" class="text-emerald-100 hover:text-white text-xs"><i class="fa-solid fa-file-excel"></i></button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs font-medium text-slate-700 divide-y divide-slate-100 whitespace-nowrap">
                            <thead class="bg-emerald-700 text-white text-xs font-black uppercase">
                                <tr>
                                    <th class="py-3 px-4 text-center">No</th>
                                    <th class="py-3 px-4">Nama Donatur</th>
                                    <th class="py-3 px-4">ID Donatur</th>
                                    <th class="py-3 px-4">Kantor</th>
                                    <th class="py-3 px-4 text-center">RFM 90 Days</th>
                                    <th class="py-3 px-4 text-center">RFM Alltime</th>
                                    <th class="py-3 px-4">PIC Prospek</th>
                                    <th class="py-3 px-4 text-center">Total Trx</th>
                                    <th class="py-3 px-4 text-right">Total Donasi</th>
                                    <th class="py-3 px-4 text-right">% Kontribusi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($topDonorsByContribution as $idx => $tDnc)
                                    <tr class="hover:bg-slate-50">
                                        <td class="py-3 px-4 text-center text-slate-600 font-mono">{{ $idx + 1 }}</td>
                                        <td class="py-3 px-4 font-bold text-slate-900">{{ $tDnc['name'] }}</td>
                                        <td class="py-3 px-4 font-mono text-emerald-700 font-bold">{{ $tDnc['id_donatur'] }}</td>
                                        <td class="py-3 px-4 text-slate-700">{{ $tDnc['office'] }}</td>
                                        <td class="py-3 px-4 text-center font-bold text-rose-700">{{ $tDnc['rfm_90'] }}</td>
                                        <td class="py-3 px-4 text-center font-bold text-rose-700">{{ $tDnc['rfm_alltime'] }}</td>
                                        <td class="py-3 px-4 font-bold text-slate-800">{{ $tDnc['pic'] }}</td>
                                        <td class="py-3 px-4 text-center font-mono font-bold text-slate-800">{{ number_format($tDnc['trx']) }}</td>
                                        <td class="py-3 px-4 text-right font-mono font-black text-emerald-700">{{ number_format($tDnc['donation'], 0, ',', '.') }}</td>
                                        <td class="py-3 px-4 text-right font-mono font-black text-sky-700">{{ $tDnc['percent'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
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
                style: { fontFamily: 'Plus Jakarta Sans, sans-serif' },
                zoomType: 'x',
                panning: { enabled: true, type: 'x' },
                panKey: 'shift',
                resetZoomButton: {
                    position: { align: 'right', x: -10, y: 10 },
                    theme: { fill: '#1e293b', stroke: '#38bdf8', r: 6, states: { hover: { fill: '#0e567d', style: { color: '#ffffff' } } }, style: { color: '#7dd3fc', fontWeight: 'bold', fontSize: '11px' } }
                }
            },
            title: { style: { color: '#ffffff', fontWeight: 'bold', fontSize: '15px' } },
            xAxis: { labels: { style: { color: '#94a3b8', fontSize: '11px' } }, lineColor: '#334155' },
            yAxis: { labels: { style: { color: '#94a3b8', fontSize: '11px' } }, gridLineColor: '#1e293b', title: { style: { color: '#94a3b8' } } },
            legend: { itemStyle: { color: '#cbd5e1' } },
            credits: { enabled: false }
        };

        const profiles = ['CHAMPION', 'LOYAL', 'POTENTIAL', 'NEED_ATTENTION', 'LOW_ENGAGEMENT', 'DORMANT'];

        // 1. Chart Perbandingan RFM 90 Days vs All Time
        Highcharts.chart('chartRfmComparison', Highcharts.merge(darkTheme, {
            chart: { type: 'column' },
            title: { text: 'Perbandingan RFM 90 Days vs All Time' },
            xAxis: { categories: profiles },
            yAxis: { title: { text: 'Jumlah Donatur' } },
            series: [
                { name: 'RFM 90 Days',  data: @json($chartRfmComparison90), color: '#0e567d' },
                { name: 'RFM All Time', data: @json($chartRfmComparisonAll), color: '#10b981' }
            ]
        }));

        // 2. Chart Distribusi RFM 90 Days
        Highcharts.chart('chartDistribusiRfm90', Highcharts.merge(darkTheme, {
            chart: { type: 'pie' },
            title: { text: 'Distribusi RFM 90 Days' },
            plotOptions: { pie: { allowPointSelect: true, cursor: 'pointer', dataLabels: { enabled: true, format: '{point.name}: {point.percentage:.1f} %', style: { color: '#cbd5e1', fontSize: '10px' } } } },
            series: [{
                name: 'Persentase',
                data: @json($chartDistribusiRfm90Data)
            }]
        }));

        // 3. Chart Distribusi RFM All Time
        Highcharts.chart('chartDistribusiRfmAllTime', Highcharts.merge(darkTheme, {
            chart: { type: 'pie' },
            title: { text: 'Distribusi RFM All Time' },
            plotOptions: { pie: { allowPointSelect: true, cursor: 'pointer', dataLabels: { enabled: true, format: '{point.name}: {point.percentage:.1f} %', style: { color: '#cbd5e1', fontSize: '10px' } } } },
            series: [{
                name: 'Persentase',
                data: @json($chartDistribusiRfmAllData)
            }]
        }));

        // 4. Heatmap Matrix RFM (90 Days vs All Time)
        Highcharts.chart('chartHeatmapRfm', Highcharts.merge(darkTheme, {
            chart: { type: 'heatmap' },
            title: { text: 'Heatmap Matrix RFM (90 Days vs All Time)' },
            xAxis: { categories: profiles, title: { text: 'RFM 90 Days' } },
            yAxis: { categories: ['DORMANT', 'LOW_ENGAGEMENT', 'NEED_ATTENTION', 'POTENTIAL', 'LOYAL', 'CHAMPION'], title: { text: 'RFM All Time' }, reversed: false },
            colorAxis: { min: 0, minColor: '#0f172a', maxColor: '#0e567d' },
            legend: { align: 'right', layout: 'vertical', margin: 0, verticalAlign: 'top', y: 25, symbolHeight: 280 },
            series: [{
                name: 'Total Donatur',
                borderWidth: 1,
                borderColor: '#1e293b',
                data: @json($chartHeatmapData),
                dataLabels: { enabled: true, color: '#ffffff', style: { textOutline: 'none', fontWeight: 'bold' } }
            }]
        }));

    });
</script>
@endsection
