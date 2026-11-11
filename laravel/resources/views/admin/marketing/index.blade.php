@extends('layouts.app')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-20">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar Menu -->
        <div class="hidden lg:block lg:col-span-1 space-y-6">
            @include('admin.sidebar', ['active' => 'marketing_index'])
        </div>

        <!-- Content Area -->
        <div class="lg:col-span-3 space-y-8">
            
            <!-- Header section -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div class="space-y-2">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-[#063f5c] text-white rounded-full text-xs font-black uppercase tracking-widest shadow-md">
                        <i class="fa-brands fa-meta"></i> Manajemen Data Iklan
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-montserrat font-black text-slate-900 uppercase tracking-tight italic">
                        Input &amp; Evaluasi <span class="text-primary-600">Meta Ads &amp; Campaign</span>
                    </h1>
                    <p class="text-slate-600 font-bold text-xs tracking-widest uppercase">
                        Kelola data biaya, impresi, konversi donasi, dan keputusan strategi per campaign iklan
                    </p>
                    <div class="w-12 h-1 bg-primary-500 rounded-full mt-2"></div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('admin.marketing.create') }}" 
                       class="px-5 py-2.5 min-h-[44px] bg-blue-700 hover:bg-blue-800 text-white rounded-2xl text-xs font-black uppercase tracking-wider shadow-lg shadow-blue-700/20 transition-colors inline-flex items-center gap-2">
                        <i class="fa-solid fa-plus"></i> Input Data Iklan Baru
                    </a>
                    <a href="{{ route('admin.marketing.dashboard') }}" 
                       class="px-4 py-2.5 min-h-[44px] bg-slate-100 text-slate-700 rounded-2xl text-xs font-black uppercase tracking-wider hover:bg-slate-200 transition-colors inline-flex items-center gap-2">
                        <i class="fa-solid fa-chart-line"></i> Dashboard Analisa
                    </a>
                    <a href="{{ route('admin.marketing.export', request()->query()) }}" 
                       class="px-4 py-2.5 min-h-[44px] bg-emerald-50 text-emerald-800 border border-emerald-300 rounded-2xl text-xs font-black uppercase tracking-wider hover:bg-emerald-100 transition-colors inline-flex items-center gap-2">
                        <i class="fa-solid fa-file-csv"></i> Unduh CSV
                    </a>
                </div>
            </div>

            <!-- Flash Alert -->
            @if(session('success'))
            <div role="status" class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-3 text-emerald-800 text-xs font-bold">
                <i class="fa-solid fa-circle-check text-emerald-700 text-base"></i>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            <!-- Filter & Search Card -->
            <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm space-y-4">
                <form method="GET" action="{{ route('admin.marketing.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                    <!-- Search Input -->
                    <div class="lg:col-span-2">
                        <label for="mkt_idx_search" class="block text-xs font-black uppercase tracking-wider text-slate-600 mb-1.5">Pencarian Campaign / Ad</label>
                        <div class="relative">
                            <input id="mkt_idx_search" type="text" name="search" value="{{ $search }}" placeholder="Cari nama iklan, adset, campaign..." 
                                   class="w-full min-h-[44px] text-xs font-bold text-slate-700 pl-9 pr-4 py-2 rounded-xl border-slate-300 focus:ring-primary-500 focus:border-primary-500">
                            <i class="fa-solid fa-magnifying-glass absolute left-3 top-3.5 text-slate-600 text-xs"></i>
                        </div>
                    </div>

                    <!-- Filter Platform -->
                    <div>
                        <label for="mkt_idx_platform" class="block text-xs font-black uppercase tracking-wider text-slate-600 mb-1.5">Platform</label>
                        <select id="mkt_idx_platform" name="platform" class="w-full min-h-[44px] text-xs font-bold text-slate-700 py-2 rounded-xl border-slate-300">
                            <option value="ALL">Semua Platform</option>
                            <option value="Meta Ads" {{ $platform === 'Meta Ads' ? 'selected' : '' }}>Meta Ads (FB/IG)</option>
                            <option value="Google Ads" {{ $platform === 'Google Ads' ? 'selected' : '' }}>Google Ads</option>
                            <option value="TikTok Ads" {{ $platform === 'TikTok Ads' ? 'selected' : '' }}>TikTok Ads</option>
                            <option value="YouTube Ads" {{ $platform === 'YouTube Ads' ? 'selected' : '' }}>YouTube Ads</option>
                            <option value="Lainnya" {{ $platform === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                    <!-- Filter Decision -->
                    <div>
                        <label for="mkt_idx_decision" class="block text-xs font-black uppercase tracking-wider text-slate-600 mb-1.5">Keputusan</label>
                        <select id="mkt_idx_decision" name="decision" class="w-full min-h-[44px] text-xs font-bold text-slate-700 py-2 rounded-xl border-slate-300">
                            <option value="ALL">Semua Keputusan</option>
                            <option value="SCALE" {{ $decision === 'SCALE' ? 'selected' : '' }}>SCALE (Naikkan Budget)</option>
                            <option value="MAINTAIN" {{ $decision === 'MAINTAIN' ? 'selected' : '' }}>MAINTAIN (Pertahankan)</option>
                            <option value="STOP" {{ $decision === 'STOP' ? 'selected' : '' }}>STOP (Matikan Segera)</option>
                        </select>
                    </div>

                    <!-- Filter Status -->
                    <div>
                        <label for="mkt_idx_status" class="block text-xs font-black uppercase tracking-wider text-slate-600 mb-1.5">Status Iklan</label>
                        <select id="mkt_idx_status" name="status" class="w-full min-h-[44px] text-xs font-bold text-slate-700 py-2 rounded-xl border-slate-300">
                            <option value="ALL">Semua Status</option>
                            <option value="ACTIVE" {{ $status === 'ACTIVE' ? 'selected' : '' }}>ACTIVE (Aktif)</option>
                            <option value="PAUSED" {{ $status === 'PAUSED' ? 'selected' : '' }}>PAUSED (Jeda)</option>
                            <option value="ARCHIVED" {{ $status === 'ARCHIVED' ? 'selected' : '' }}>ARCHIVED (Arsip)</option>
                        </select>
                    </div>

                    <div class="sm:col-span-2 lg:col-span-5 flex flex-wrap items-center justify-between gap-3 pt-2 border-t border-slate-50">
                        <div class="flex flex-wrap items-center gap-2">
                            <label for="mkt_idx_from" class="text-xs font-black uppercase tracking-wider text-slate-600">Periode:</label>
                            <input id="mkt_idx_from" type="date" name="date_from" value="{{ $dateFrom }}" class="text-xs font-bold text-slate-700 py-2 px-3 min-h-[44px] rounded-xl border-slate-300">
                            <span class="text-xs text-slate-600">-</span>
                            <input id="mkt_idx_to" type="date" name="date_to" aria-label="Tanggal akhir periode" value="{{ $dateTo }}" class="text-xs font-bold text-slate-700 py-2 px-3 min-h-[44px] rounded-xl border-slate-300">
                        </div>

                        <div class="flex items-center gap-2">
                            <button type="submit" class="py-2 px-4 min-h-[44px] bg-slate-900 text-white rounded-xl text-xs font-black uppercase tracking-wider hover:bg-slate-800 transition-colors inline-flex items-center gap-2">
                                <i class="fa-solid fa-filter"></i> Saring Data
                            </button>
                            <a href="{{ route('admin.marketing.index') }}" aria-label="Reset Filter" class="w-11 h-11 bg-slate-100 text-slate-700 rounded-xl text-xs font-black hover:bg-slate-200 transition-colors inline-flex items-center justify-center" title="Reset">
                                <i class="fa-solid fa-rotate-left"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Main Table Card -->
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100 text-xs font-black uppercase tracking-wider text-slate-600">
                                <th class="py-3.5 px-4">Tanggal &amp; Platform</th>
                                <th class="py-3.5 px-4">Nama Campaign &amp; Ad</th>
                                <th class="py-3.5 px-4 text-right">Biaya (Spend)</th>
                                <th class="py-3.5 px-4 text-center">Impr. / Reach</th>
                                <th class="py-3.5 px-4 text-center">Klik / CTR</th>
                                <th class="py-3.5 px-4 text-center">Konversi / CPA</th>
                                <th class="py-3.5 px-4 text-right">Donasi Didapat</th>
                                <th class="py-3.5 px-4 text-center">ROAS</th>
                                <th class="py-3.5 px-4 text-center">Keputusan</th>
                                <th class="py-3.5 px-4 text-center">Status</th>
                                <th class="py-3.5 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($ads as $ad)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <!-- Tanggal & Platform -->
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-slate-800 whitespace-nowrap">{{ $ad->date ? $ad->date->format('d M Y') : '-' }}</div>
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-black mt-1
                                        {{ $ad->platform === 'Meta Ads' ? 'bg-blue-50 text-blue-800 border border-blue-200' : ($ad->platform === 'Google Ads' ? 'bg-rose-50 text-rose-800 border border-rose-200' : 'bg-slate-100 text-slate-700') }}">
                                        {{ $ad->platform }}
                                    </span>
                                </td>

                                <!-- Campaign & Adset -->
                                <td class="py-3.5 px-4 max-w-xs">
                                    <div class="font-black text-slate-800 truncate" title="{{ $ad->campaign_name }}">
                                        {{ $ad->campaign_name }}
                                    </div>
                                    @if($ad->adset_name || $ad->ad_name)
                                        <div class="text-xs text-slate-600 truncate mt-0.5">
                                            {{ $ad->adset_name }} {{ $ad->ad_name ? '/ ' . $ad->ad_name : '' }}
                                        </div>
                                    @endif
                                    @if($ad->campaign)
                                        <div class="text-xs font-bold text-primary-700 truncate mt-0.5">
                                            <i class="fa-solid fa-link text-xs"></i> Web: {{ $ad->campaign->title }}
                                        </div>
                                    @endif
                                </td>

                                <!-- Spend -->
                                <td class="py-3.5 px-4 text-right">
                                    <div class="font-black text-slate-900">Rp {{ number_format($ad->spend, 0, ',', '.') }}</div>
                                    <div class="text-xs font-bold text-slate-600">CPM: Rp {{ number_format($ad->cpm, 0, ',', '.') }}</div>
                                </td>

                                <!-- Impressions / Reach -->
                                <td class="py-3.5 px-4 text-center">
                                    <div class="font-bold text-slate-800">{{ number_format($ad->impressions) }}</div>
                                    <div class="text-xs text-slate-600">Reach: {{ number_format($ad->reach) }}</div>
                                </td>

                                <!-- Clicks / CTR -->
                                <td class="py-3.5 px-4 text-center">
                                    <div class="font-bold text-slate-800">{{ number_format($ad->clicks) }}</div>
                                    <div class="text-xs font-black text-indigo-700">{{ $ad->ctr }}% (Rp {{ number_format($ad->cpc, 0, ',', '.') }})</div>
                                </td>

                                <!-- Conversions / CPA -->
                                <td class="py-3.5 px-4 text-center">
                                    <div class="font-black text-slate-800">{{ number_format($ad->conversions) }}</div>
                                    <div class="text-xs font-bold text-slate-600">CPA: Rp {{ number_format($ad->cost_per_conversion, 0, ',', '.') }}</div>
                                </td>

                                <!-- Donasi Didapat -->
                                <td class="py-3.5 px-4 text-right">
                                    <div class="font-black text-emerald-700">Rp {{ number_format($ad->donation_amount, 0, ',', '.') }}</div>
                                </td>

                                <!-- ROAS -->
                                <td class="py-3.5 px-4 text-center">
                                    <div class="inline-block px-2.5 py-1 rounded-xl font-black text-xs
                                        {{ $ad->roas >= 2.0 ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : ($ad->roas >= 1.0 ? 'bg-amber-50 text-amber-800 border border-amber-200' : 'bg-rose-50 text-rose-800 border border-rose-200') }}">
                                        {{ $ad->roas }}x
                                    </div>
                                </td>

                                <!-- Decision -->
                                <td class="py-3.5 px-4 text-center">
                                    @if($ad->decision === 'SCALE')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-full text-xs font-black uppercase">
                                            <i class="fa-solid fa-arrow-trend-up"></i> SCALE
                                        </span>
                                    @elseif($ad->decision === 'STOP')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-rose-100 text-rose-800 rounded-full text-xs font-black uppercase">
                                            <i class="fa-solid fa-ban"></i> STOP
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-100 text-amber-800 rounded-full text-xs font-black uppercase">
                                            <i class="fa-solid fa-wrench"></i> MAINTAIN
                                        </span>
                                    @endif
                                </td>

                                <!-- Status -->
                                <td class="py-3.5 px-4 text-center">
                                    <span class="px-2 py-0.5 rounded-full text-xs font-bold uppercase
                                        {{ $ad->status === 'ACTIVE' ? 'bg-emerald-50 text-emerald-800' : ($ad->status === 'PAUSED' ? 'bg-amber-50 text-amber-800' : 'bg-slate-100 text-slate-700') }}">
                                        {{ $ad->status }}
                                    </span>
                                </td>

                                <!-- Aksi -->
                                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('admin.marketing.edit', $ad->id) }}" aria-label="Edit Data Iklan" class="w-11 h-11 inline-flex items-center justify-center rounded-xl text-slate-600 hover:text-primary-700 hover:bg-slate-100 transition-colors" title="Edit Data Iklan">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.marketing.destroy', $ad->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan iklan ini?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" aria-label="Hapus Data Iklan" class="w-11 h-11 inline-flex items-center justify-center rounded-xl text-slate-600 hover:text-rose-700 hover:bg-rose-50 transition-colors" title="Hapus Data">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="py-12 text-center text-slate-600 font-bold">
                                    Tidak ada data iklan yang sesuai dengan filter atau kata kunci pencarian.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($ads->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $ads->links() }}
                </div>
                @endif
            </div>

        </div>

    </div>
</section>
@endsection

