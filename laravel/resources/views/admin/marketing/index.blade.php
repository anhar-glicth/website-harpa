@extends('layouts.app')

@section('content')
<section x-data="{ showImportModal: false }" class="max-w-7xl mx-auto px-6 py-20">
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
                    <button type="button" 
                            @click="showImportModal = true"
                            class="px-4 py-2.5 min-h-[44px] bg-[#063f5c] hover:bg-[#053953] text-white rounded-xl text-xs font-black uppercase tracking-wider transition-colors inline-flex items-center gap-2 shadow-sm">
                        <i class="fa-solid fa-file-arrow-up" aria-hidden="true"></i> Impor CSV
                    </button>
                    <a href="{{ route('admin.marketing.create') }}" 
                       class="px-4 py-2.5 min-h-[44px] bg-blue-700 hover:bg-blue-800 text-white rounded-xl text-xs font-black uppercase tracking-wider shadow-sm transition-colors inline-flex items-center gap-2">
                        <i class="fa-solid fa-plus"></i> Input Baru
                    </a>
                    <a href="{{ route('admin.marketing.dashboard') }}" 
                       class="px-4 py-2.5 min-h-[44px] bg-slate-100 text-slate-700 rounded-xl text-xs font-black uppercase tracking-wider hover:bg-slate-200 transition-colors inline-flex items-center gap-2">
                        <i class="fa-solid fa-chart-line"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.marketing.export', request()->query()) }}" 
                       class="px-4 py-2.5 min-h-[44px] bg-white text-slate-800 border border-slate-300 rounded-xl text-xs font-black uppercase tracking-wider hover:border-[#063f5c] hover:text-[#063f5c] transition-colors inline-flex items-center gap-2">
                        <i class="fa-solid fa-file-csv" aria-hidden="true"></i> Unduh CSV
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

            @if(session('error'))
            <div role="alert" class="p-4 bg-rose-50 border border-rose-200 rounded-2xl flex items-center gap-3 text-rose-800 text-xs font-bold">
                <i class="fa-solid fa-triangle-exclamation text-rose-700 text-base"></i>
                <span>{{ session('error') }}</span>
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

                    @if(isset($programsList) && count($programsList) > 0)
                    <!-- Filter Program Donasi -->
                    <div>
                        <label for="mkt_idx_program" class="block text-xs font-black uppercase tracking-wider text-slate-600 mb-1.5">Program Donasi</label>
                        <select id="mkt_idx_program" name="program" class="w-full min-h-[44px] text-xs font-bold text-slate-700 py-2 rounded-xl border-slate-300">
                            <option value="ALL">Semua Program</option>
                            @foreach($programsList as $p)
                                <option value="{{ $p }}" {{ ($program ?? '') === $p ? 'selected' : '' }}>{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    @if(isset($picsList) && count($picsList) > 0)
                    <!-- Filter PIC -->
                    <div>
                        <label for="mkt_idx_pic" class="block text-xs font-black uppercase tracking-wider text-slate-600 mb-1.5">PIC Pengelola</label>
                        <select id="mkt_idx_pic" name="pic" class="w-full min-h-[44px] text-xs font-bold text-slate-700 py-2 rounded-xl border-slate-300">
                            <option value="ALL">Semua PIC</option>
                            @foreach($picsList as $picItem)
                                <option value="{{ $picItem }}" {{ ($pic ?? '') === $picItem ? 'selected' : '' }}>{{ $picItem }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

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
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <div class="font-bold text-slate-800">{{ $ad->date ? $ad->date->format('d M Y') : '-' }}</div>
                                    <span class="inline-block text-xs font-bold mt-0.5 {{ $ad->platform === 'Meta Ads' ? 'text-[#063f5c]' : ($ad->platform === 'Google Ads' ? 'text-rose-700' : 'text-slate-600') }}">
                                        {{ $ad->platform }}
                                    </span>
                                </td>

                                <!-- Campaign & Adset -->
                                <td class="py-3.5 px-4 max-w-xs">
                                    <div class="font-black text-slate-800 truncate" title="{{ $ad->campaign_name }}">
                                        {{ $ad->campaign_name }}
                                    </div>
                                    @if($ad->program_name || $ad->pic || $ad->account_name)
                                        <div class="text-[11px] font-bold text-slate-500 truncate mt-0.5">
                                            @if($ad->program_name)
                                                <span class="text-[#063f5c] font-black">{{ $ad->program_name }}</span>
                                            @endif
                                            @if($ad->pic)
                                                <span class="text-slate-500">&bull; PIC: {{ $ad->pic }}</span>
                                            @endif
                                            @if($ad->account_name)
                                                <span class="text-slate-500">&bull; {{ Str::limit($ad->account_name, 25) }}</span>
                                            @endif
                                        </div>
                                    @endif
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
                                    @if($ad->landing_page_views)
                                        <div class="text-[10px] text-slate-500 font-medium">LP: {{ number_format($ad->landing_page_views) }}</div>
                                    @endif
                                </td>

                                <!-- Clicks / CTR -->
                                <td class="py-3.5 px-4 text-center">
                                    <div class="font-bold text-slate-800">{{ number_format($ad->clicks) }}</div>
                                    <div class="text-xs font-black text-[#063f5c]">{{ $ad->ctr }}% (Rp {{ number_format($ad->cpc, 0, ',', '.') }})</div>
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
                                    <span class="font-black text-xs {{ $ad->roas >= 2.0 ? 'text-emerald-700' : ($ad->roas >= 1.0 ? 'text-amber-800' : 'text-rose-700') }}">
                                        {{ $ad->roas }}x
                                    </span>
                                </td>

                                <!-- Decision -->
                                <td class="py-3.5 px-4 text-center">
                                    @if($ad->decision === 'SCALE')
                                        <span class="text-xs font-black uppercase text-emerald-700">SCALE</span>
                                    @elseif($ad->decision === 'STOP')
                                        <span class="text-xs font-black uppercase text-rose-700">STOP</span>
                                    @else
                                        <span class="text-xs font-black uppercase text-amber-800">MAINTAIN</span>
                                    @endif
                                </td>

                                <!-- Status -->
                                <td class="py-3.5 px-4 text-center">
                                    <span class="text-xs font-bold uppercase {{ $ad->status === 'ACTIVE' ? 'text-emerald-700' : ($ad->status === 'PAUSED' ? 'text-amber-800' : 'text-slate-600') }}">
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

    <!-- Modal Import CSV Meta Ads -->
    <div x-show="showImportModal"
         x-cloak
         role="dialog"
         aria-modal="true"
         aria-labelledby="import-modal-title"
         @keydown.escape.window="showImportModal = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div @click.outside="showImportModal = false"
             class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 space-y-6 shadow-2xl border border-slate-200">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="space-y-1">
                    <h3 id="import-modal-title" class="text-lg font-black font-montserrat text-slate-900 uppercase tracking-tight">
                        Impor Data Performa Meta Ads
                    </h3>
                    <p class="text-xs text-slate-600 font-bold">
                        Format CSV mendukung ekspor Ads Manager &amp; spreadsheet Google Sheets
                    </p>
                </div>
                <button type="button"
                        @click="showImportModal = false"
                        aria-label="Tutup Modal"
                        class="w-11 h-11 inline-flex items-center justify-center rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-colors">
                    <i class="fa-solid fa-xmark text-lg" aria-hidden="true"></i>
                </button>
            </div>

            <form action="{{ route('admin.marketing.import') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                <div class="space-y-2">
                    <label for="import_file_input" class="block text-xs font-black uppercase tracking-wider text-slate-700">
                        Pilih File CSV Performa Iklan
                    </label>
                    <input id="import_file_input"
                           type="file"
                           name="file"
                           required
                           accept=".csv,.txt"
                           class="w-full min-h-[44px] text-xs font-bold text-slate-700 py-2.5 px-3 rounded-xl border border-slate-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-black file:uppercase file:bg-[#063f5c] file:text-white hover:file:bg-[#053953] cursor-pointer">
                    <p class="text-[11px] text-slate-500 font-medium">
                        Ukuran file maksimal 50 MB. Header kolom seperti <code>Date, PIC, Program, Nama Kampanye, Spend, Impresi, Pembelian</code> akan dipetakan otomatis.
                    </p>
                </div>

                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 space-y-2 text-xs text-slate-700">
                    <div class="font-black text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-circle-info text-[#063f5c]" aria-hidden="true"></i> Panduan &amp; Template Format
                    </div>
                    <p class="text-[11px] leading-relaxed">
                        Data impor akan otomatis dihitung metriknya (CPC, CTR, CPM, CPA, ROAS) dan diklasifikasikan ke rekomendasi SCALE, MAINTAIN, atau STOP.
                    </p>
                    <a href="{{ route('admin.marketing.template') }}" class="inline-flex items-center gap-1.5 text-xs font-black text-[#063f5c] hover:underline pt-1">
                        <i class="fa-solid fa-download" aria-hidden="true"></i> Unduh File Contoh Template CSV
                    </a>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button"
                            @click="showImportModal = false"
                            class="px-4 py-2.5 min-h-[44px] rounded-xl text-xs font-black uppercase tracking-wider text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-5 py-2.5 min-h-[44px] rounded-xl text-xs font-black uppercase tracking-wider text-white bg-[#063f5c] hover:bg-[#053953] shadow-md transition-colors inline-flex items-center gap-2">
                        <i class="fa-solid fa-cloud-arrow-up" aria-hidden="true"></i> Mulai Impor Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

