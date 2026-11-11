@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50/60 pb-20" x-data="trackerData(@js($clientData), '{{ $tab }}', '{{ $batchDate }}')">
    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-8 items-start min-w-0">
            <!-- Sidebar Menu -->
            <aside class="hidden lg:block space-y-6 shrink-0 w-[280px]">
                @include('admin.sidebar', ['active' => 'harpa_daftar_kirim'])
            </aside>

            <!-- Main Content Area -->
            <div class="space-y-6 min-w-0">
                <!-- Top Header Bar -->
                <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-600 uppercase tracking-widest">
                            <span>Sistem Data Harpa</span>
                            <span aria-hidden="true">/</span>
                            <span class="text-primary-600 font-black">Alur D: Daftar Kirim & Live Tracker CRM</span>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-montserrat font-black text-slate-900 tracking-tight mt-1 flex items-center gap-2">
                            <i class="fa-solid fa-list-check text-primary-600" aria-hidden="true"></i> Daftar Siap Kirim & Tracker Broadcast
                        </h1>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('admin.harpa.daftar_kirim.export', ['type' => $tab, 'bulan' => $bulan, 'sumber' => $sumber]) }}" class="inline-flex items-center min-h-[44px] gap-2 px-4 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-black text-xs uppercase tracking-wider shadow-sm transition-colors">
                            <i class="fa-solid fa-file-csv" aria-hidden="true"></i>
                            Unduh CSV CRM
                        </a>
                        <a href="{{ route('admin.harpa.daftar_kirim.export', ['type' => $tab, 'bulan' => $bulan, 'sumber' => $sumber, 'mask' => 1]) }}" class="inline-flex items-center min-h-[44px] gap-2 px-3.5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs uppercase tracking-wider transition-colors" title="Ekspor Aman Tanpa Nomor Lengkap untuk Laporan Pihak Ketiga">
                            <i class="fa-solid fa-user-shield" aria-hidden="true"></i>
                            Ekspor Aman
                        </a>
                    </div>
                </div>

                <!-- TABS NAVIGASI 5 DAFTAR KIRIM -->
                <div class="flex items-center gap-2 overflow-x-auto pb-2 border-b border-slate-200">
                    <a href="{{ route('admin.harpa.daftar_kirim', ['tab' => 'jumat', 'batch_date' => $batchDate]) }}" class="inline-flex items-center min-h-[44px] px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-colors whitespace-nowrap {{ $tab === 'jumat' ? 'bg-[#063f5c] text-white shadow-sm' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
                        <i class="fa-solid fa-calendar-week mr-1.5 {{ $tab === 'jumat' ? 'text-[#f7ad1a]' : 'text-slate-600' }}" aria-hidden="true"></i>
                        1. Kirim Jumat (Donatur)
                    </a>

                    <a href="{{ route('admin.harpa.daftar_kirim', ['tab' => 'payday', 'batch_date' => $batchDate]) }}" class="inline-flex items-center min-h-[44px] px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-colors whitespace-nowrap {{ $tab === 'payday' ? 'bg-[#063f5c] text-white shadow-sm' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
                        <i class="fa-solid fa-money-check-dollar mr-1.5 {{ $tab === 'payday' ? 'text-[#f7ad1a]' : 'text-slate-600' }}" aria-hidden="true"></i>
                        2. Kirim Payday (Tgl 1-5)
                    </a>

                    <a href="{{ route('admin.harpa.daftar_kirim', ['tab' => 'perkenalan', 'batch_date' => $batchDate]) }}" class="inline-flex items-center min-h-[44px] px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-colors whitespace-nowrap {{ $tab === 'perkenalan' ? 'bg-[#063f5c] text-white shadow-sm' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
                        <i class="fa-solid fa-handshake mr-1.5 {{ $tab === 'perkenalan' ? 'text-[#f7ad1a]' : 'text-slate-600' }}" aria-hidden="true"></i>
                        3. Kirim Perkenalan (Web Baru)
                    </a>

                    <a href="{{ route('admin.harpa.daftar_kirim', ['tab' => 'jumat_baru', 'batch_date' => $batchDate]) }}" class="inline-flex items-center min-h-[44px] px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-colors whitespace-nowrap {{ $tab === 'jumat_baru' ? 'bg-[#063f5c] text-white shadow-sm' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
                        <i class="fa-solid fa-user-tag mr-1.5 {{ $tab === 'jumat_baru' ? 'text-[#f7ad1a]' : 'text-slate-600' }}" aria-hidden="true"></i>
                        4. Kirim Jumat Baru (28 Hari)
                    </a>

                    <a href="{{ route('admin.harpa.daftar_kirim', ['tab' => 'nomor_mati', 'batch_date' => $batchDate]) }}" class="inline-flex items-center min-h-[44px] px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-colors whitespace-nowrap {{ $tab === 'nomor_mati' ? 'bg-rose-700 text-white shadow-sm' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
                        <i class="fa-solid fa-triangle-exclamation mr-1.5 {{ $tab === 'nomor_mati' ? 'text-white' : 'text-rose-700' }}" aria-hidden="true"></i>
                        5. Alert Nomor Mati
                    </a>
                </div>

                <!-- LIVE PROGRESS & BATCH TRACKER CARD -->
                <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-6">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-slate-200">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-black uppercase tracking-wider text-[#063f5c]">
                                    Tracker Progres Kirim
                                </span>
                                <span class="text-slate-400" aria-hidden="true">&bull;</span>
                                <span class="text-xs text-slate-600 font-medium">Batch: <strong class="text-slate-800">{{ $batchLabel }}</strong></span>
                            </div>
                            <h2 class="text-lg font-montserrat font-black text-slate-900 mt-1">
                                @if($tab === 'jumat')
                                    Kirim Jumat: Segmen Aktif Donatur (Nomor Hidup)
                                @elseif($tab === 'payday')
                                    Kirim Payday: Segmen Aktif Pembaca &amp; Pantau (Tanggal 1-5)
                                @elseif($tab === 'perkenalan')
                                    Kirim Perkenalan: Donatur Web Baru Belum Dibroadcast
                                @elseif($tab === 'jumat_baru')
                                    Kirim Jumat Baru: Nomor Dikenalkan dalam 28 Hari Terakhir
                                @else
                                    Alert Nomor Donatur Mati: Tindak Lanjut Pembaruan Nomor
                                @endif
                            </h2>
                        </div>

                        <!-- Date Switcher & Perkenalan Filters -->
                        <div class="flex flex-wrap items-center gap-3">
                            @if($tab === 'perkenalan')
                                <form method="GET" action="{{ route('admin.harpa.daftar_kirim') }}" class="flex flex-wrap items-center gap-2">
                                    <input type="hidden" name="tab" value="perkenalan">
                                    <input type="hidden" name="batch_date" value="{{ $batchDate }}">
                                    <input type="month" name="bulan" aria-label="Filter bulan donasi" value="{{ $bulan }}" class="min-h-[44px] bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 outline-none focus:border-primary-600">
                                    <select name="sumber" aria-label="Filter sumber donasi" class="min-h-[44px] bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 outline-none focus:border-primary-600">
                                        <option value="">Semua Sumber</option>
                                        <option value="IM" {{ $sumber === 'IM' ? 'selected' : '' }}>Indonesia Madani (IM)</option>
                                        <option value="FH" {{ $sumber === 'FH' ? 'selected' : '' }}>ForHumanity (FH)</option>
                                    </select>
                                    <button type="submit" class="min-h-[44px] px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-slate-800 transition">
                                        Filter
                                    </button>
                                </form>
                            @endif

                            <form method="GET" action="{{ route('admin.harpa.daftar_kirim') }}" class="flex items-center gap-2 bg-slate-50 border border-slate-300 rounded-2xl p-1.5 px-3 min-h-[44px]">
                                <input type="hidden" name="tab" value="{{ $tab }}">
                                @if($bulan)<input type="hidden" name="bulan" value="{{ $bulan }}">@endif
                                @if($sumber)<input type="hidden" name="sumber" value="{{ $sumber }}">@endif
                                <label for="batch_date_input" class="text-xs font-bold text-slate-700 whitespace-nowrap flex items-center gap-1.5">
                                    <i class="fa-regular fa-calendar text-primary-600" aria-hidden="true"></i> Tanggal Batch:
                                </label>
                                <input type="date" id="batch_date_input" name="batch_date" value="{{ $batchDate }}" onchange="this.form.submit()" class="bg-white border border-slate-300 rounded-xl px-2.5 py-1.5 text-xs font-bold text-slate-800 outline-none focus:border-primary-600">
                            </form>
                        </div>
                    </div>

                    <!-- PROGRESS BAR TRACKER -->
                    <div class="space-y-2">
                        <div class="flex flex-wrap items-center justify-between gap-2 text-xs font-bold">
                            <span class="text-slate-700 flex items-center gap-2">
                                <i class="fa-solid fa-chart-line text-emerald-700" aria-hidden="true"></i>
                                Progres Pengiriman Batch Hari Ini
                            </span>
                            <div class="flex items-center gap-3">
                                <span x-show="isUpdating" x-cloak class="inline-flex items-center gap-1.5 text-xs font-bold text-[#063f5c]">
                                    <i class="fa-solid fa-spinner fa-spin" aria-hidden="true"></i> Menyimpan status...
                                </span>
                                <span class="font-mono text-slate-900 font-black">
                                    <span x-text="stats.percent"></span>% Selesai
                                    <span class="text-slate-600 font-medium">
                                        (<span x-text="stats.finished"></span> dari <span x-text="stats.total"></span> kontak)
                                    </span>
                                </span>
                            </div>
                        </div>
                        <div class="w-full h-3.5 bg-slate-200 rounded-full overflow-hidden p-0.5 border border-slate-300">
                            <div class="h-full rounded-full bg-primary-600 transition-all duration-500" :style="'width: ' + stats.percent + '%'"></div>
                        </div>
                    </div>

                    <!-- 5 STATS KPI PILLS (Clickable Filters) -->
                    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 pt-2">
                        <!-- Total -->
                        <button type="button" @click="statusFilter = 'ALL'" :class="statusFilter === 'ALL' ? 'ring-2 ring-slate-800 shadow-sm bg-slate-50' : 'bg-white hover:bg-slate-50'" class="border border-slate-200 rounded-2xl p-3.5 text-left transition-colors min-h-[44px]">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-black uppercase tracking-wider text-slate-600">Total Kontak</span>
                                <i class="fa-solid fa-users text-slate-600 text-xs" aria-hidden="true"></i>
                            </div>
                            <div class="text-xl font-montserrat font-black text-slate-900 mt-1" x-text="stats.total"></div>
                            <span class="text-xs text-slate-600 font-medium">100% Target Siap</span>
                        </button>

                        <!-- Pending -->
                        <button type="button" @click="statusFilter = 'PENDING'" :class="statusFilter === 'PENDING' ? 'ring-2 ring-[#063f5c] bg-slate-50 border-[#063f5c]' : 'bg-white border-slate-200 hover:bg-slate-50'" class="border rounded-2xl p-3.5 text-left transition-colors min-h-[44px]">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-black uppercase tracking-wider text-slate-700">Belum Kirim</span>
                                <i class="fa-regular fa-clock text-[#f7ad1a] text-xs" aria-hidden="true"></i>
                            </div>
                            <div class="text-xl font-montserrat font-black text-slate-900 mt-1" x-text="stats.pending"></div>
                            <span class="text-xs text-slate-600 font-medium">Antrean Menunggu</span>
                        </button>

                        <!-- Delivered -->
                        <button type="button" @click="statusFilter = 'DELIVERED'" :class="statusFilter === 'DELIVERED' ? 'ring-2 ring-[#063f5c] bg-slate-50 border-[#063f5c]' : 'bg-white border-slate-200 hover:bg-slate-50'" class="border rounded-2xl p-3.5 text-left transition-colors min-h-[44px]">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-black uppercase tracking-wider text-slate-700">Terkirim</span>
                                <i class="fa-solid fa-check-double text-blue-700 text-xs" aria-hidden="true"></i>
                            </div>
                            <div class="text-xl font-montserrat font-black text-slate-900 mt-1" x-text="stats.delivered"></div>
                            <span class="text-xs text-slate-600 font-medium">Centang 2 Abu</span>
                        </button>

                        <!-- Read -->
                        <button type="button" @click="statusFilter = 'READ'" :class="statusFilter === 'READ' ? 'ring-2 ring-[#063f5c] bg-slate-50 border-[#063f5c]' : 'bg-white border-slate-200 hover:bg-slate-50'" class="border rounded-2xl p-3.5 text-left transition-colors min-h-[44px]">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-black uppercase tracking-wider text-slate-700">Sudah Dibaca</span>
                                <i class="fa-solid fa-check-double text-emerald-700 text-xs" aria-hidden="true"></i>
                            </div>
                            <div class="text-xl font-montserrat font-black text-slate-900 mt-1" x-text="stats.read"></div>
                            <span class="text-xs text-slate-600 font-medium">Centang 2 Biru</span>
                        </button>

                        <!-- Failed -->
                        <button type="button" @click="statusFilter = 'FAILED'" :class="statusFilter === 'FAILED' ? 'ring-2 ring-[#063f5c] bg-slate-50 border-[#063f5c]' : 'bg-white border-slate-200 hover:bg-slate-50'" class="border rounded-2xl p-3.5 text-left transition-colors col-span-2 sm:col-span-1 min-h-[44px]">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-black uppercase tracking-wider text-slate-700">Gagal Kirim</span>
                                <i class="fa-solid fa-circle-xmark text-rose-700 text-xs" aria-hidden="true"></i>
                            </div>
                            <div class="text-xl font-montserrat font-black text-slate-900 mt-1" x-text="stats.failed"></div>
                            <span class="text-xs text-slate-600 font-medium">Gagal Terkirim</span>
                        </button>
                    </div>
                </div>

                <!-- TABLE TOOLBAR & SEARCH BAR -->
                <div class="bg-white rounded-3xl border border-slate-200 p-4 sm:p-5 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
                    <!-- Search Input -->
                    <div class="relative w-full md:w-80">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 text-xs" aria-hidden="true"></i>
                        <input type="text" x-model="searchQuery" aria-label="Cari nama, nomor HP, atau Donatur ID" placeholder="Cari nama, nomor HP, atau Donatur ID..." class="w-full min-h-[44px] pl-9 pr-10 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold text-slate-800 placeholder:text-slate-500 outline-none focus:border-primary-600 focus:bg-white transition">
                        <button type="button" x-show="searchQuery" @click="searchQuery = ''" aria-label="Hapus pencarian" class="absolute right-1.5 top-1/2 -translate-y-1/2 min-w-[36px] min-h-[36px] flex items-center justify-center text-slate-600 hover:text-slate-900 text-xs">
                            <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                        </button>
                    </div>

                    <!-- Status Filter Tabs -->
                    <div class="flex items-center gap-1.5 overflow-x-auto w-full md:w-auto pb-1 md:pb-0">
                        <button type="button" @click="statusFilter = 'ALL'" :class="statusFilter === 'ALL' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'" class="min-h-[44px] px-3.5 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap">
                            Semua (<span x-text="stats.total"></span>)
                        </button>
                        <button type="button" @click="statusFilter = 'PENDING'" :class="statusFilter === 'PENDING' ? 'bg-amber-900 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'" class="min-h-[44px] px-3.5 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap">
                            Belum (<span x-text="stats.pending"></span>)
                        </button>
                        <button type="button" @click="statusFilter = 'DELIVERED'" :class="statusFilter === 'DELIVERED' ? 'bg-blue-700 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'" class="min-h-[44px] px-3.5 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap">
                            Terkirim (<span x-text="stats.delivered"></span>)
                        </button>
                        <button type="button" @click="statusFilter = 'READ'" :class="statusFilter === 'READ' ? 'bg-emerald-700 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'" class="min-h-[44px] px-3.5 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap">
                            Dibaca (<span x-text="stats.read"></span>)
                        </button>
                        <button type="button" @click="statusFilter = 'FAILED'" :class="statusFilter === 'FAILED' ? 'bg-rose-700 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'" class="min-h-[44px] px-3.5 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap">
                            Gagal (<span x-text="stats.failed"></span>)
                        </button>
                    </div>
                </div>

                <!-- FLOATING BULK ACTIONS TOOLBAR (Appears when 1+ rows selected) -->
                <div x-show="selectedCount > 0" x-transition class="sticky top-4 z-40 bg-slate-900 text-white border border-slate-700 rounded-2xl p-3.5 px-5 shadow-xl flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <span class="w-7 h-7 rounded-lg bg-[#f7ad1a] text-[#063f5c] font-black text-xs flex items-center justify-center font-mono">
                            <span x-text="selectedCount"></span>
                        </span>
                        <span class="text-xs font-bold text-slate-100">Kontak dipilih untuk update massal:</span>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <!-- Mark Delivered -->
                        <button type="button" @click="updateBulk('DELIVERED')" :disabled="isUpdating" class="min-h-[40px] px-3.5 py-1.5 rounded-xl bg-blue-700 hover:bg-blue-800 text-white font-black text-xs uppercase tracking-wider transition flex items-center gap-1.5 shadow-sm disabled:opacity-50">
                            <i class="fa-solid fa-check-double text-blue-100" aria-hidden="true"></i>
                            Tandai Terkirim
                        </button>

                        <!-- Mark Read -->
                        <button type="button" @click="updateBulk('READ')" :disabled="isUpdating" class="min-h-[40px] px-3.5 py-1.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-black text-xs uppercase tracking-wider transition flex items-center gap-1.5 shadow-sm disabled:opacity-50">
                            <i class="fa-solid fa-check-double text-emerald-100" aria-hidden="true"></i>
                            Tandai Dibaca (Read)
                        </button>

                        <!-- Mark Failed -->
                        <button type="button" @click="updateBulk('FAILED')" :disabled="isUpdating" class="min-h-[40px] px-3.5 py-1.5 rounded-xl bg-rose-700 hover:bg-rose-800 text-white font-bold text-xs uppercase tracking-wider transition flex items-center gap-1.5 shadow-sm disabled:opacity-50">
                            <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                            Tandai Gagal
                        </button>

                        <!-- Reset Pending -->
                        <button type="button" @click="updateBulk('PENDING')" :disabled="isUpdating" class="min-h-[40px] px-3.5 py-1.5 rounded-xl bg-slate-700 hover:bg-slate-600 text-slate-100 font-bold text-xs uppercase tracking-wider transition flex items-center gap-1.5 disabled:opacity-50">
                            <i class="fa-solid fa-rotate-left" aria-hidden="true"></i>
                            Reset Belum Kirim
                        </button>

                        <button type="button" @click="clearSelection()" class="min-h-[40px] text-slate-200 hover:text-white text-xs font-bold px-3 py-1 transition ml-1">
                            Batal
                        </button>
                    </div>
                </div>

                <!-- MAIN INTERACTIVE DATA TABLE -->
                <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm">
                    <!-- Empty State -->
                    <div x-show="filteredItems.length === 0" class="text-center py-16 text-slate-600">
                        <i class="fa-regular fa-clipboard text-4xl mb-3 text-slate-500" aria-hidden="true"></i>
                        <p class="text-sm font-bold text-slate-700">Tidak ada data donatur yang cocok.</p>
                        <p class="text-xs text-slate-600 mt-1" x-show="searchQuery || statusFilter !== 'ALL'">
                            Coba sesuaikan kata kunci pencarian atau tab filter status Anda.
                        </p>
                    </div>

                    <!-- Table Container -->
                    <div x-show="filteredItems.length > 0" class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50 text-xs font-black uppercase tracking-wider text-slate-600 border-b border-slate-200">
                                <tr>
                                    <!-- Select All Checkbox -->
                                    <th class="py-3 px-3 w-12 text-center">
                                        <input type="checkbox" aria-label="Pilih semua kontak yang ditampilkan" :checked="isAllFilteredSelected()" @change="toggleSelectAll()" class="w-4 h-4 rounded text-primary-600 focus:ring-primary-600 border-slate-400 cursor-pointer">
                                    </th>
                                    <!-- Status Tracker Column -->
                                    <th class="py-3 px-4 min-w-[160px]">Status Pengiriman</th>
                                    <!-- Action Chat WA -->
                                    <th class="py-3 px-4 min-w-[130px]">Aksi Langsung</th>
                                    <!-- Tab-specific headers -->
                                    @if($tab === 'nomor_mati')
                                        <th class="py-3 px-4">Nomor HP Lama</th>
                                        <th class="py-3 px-4">Donatur ID</th>
                                        <th class="py-3 px-4">Nama Lengkap</th>
                                        <th class="py-3 px-4">Total Donasi</th>
                                        <th class="py-3 px-4">Donasi Terakhir</th>
                                        <th class="py-3 px-4">PIC CRM</th>
                                        <th class="py-3 px-4 min-w-[220px]">Tindak Lanjut CRM</th>
                                    @elseif($tab === 'perkenalan')
                                        <th class="py-3 px-4">Nomor WhatsApp</th>
                                        <th class="py-3 px-4">Total Donasi</th>
                                        <th class="py-3 px-4">Frekuensi</th>
                                        <th class="py-3 px-4">Donasi Terakhir</th>
                                        <th class="py-3 px-4">Donatur ID / Nama</th>
                                        <th class="py-3 px-4">Sumber / Bulan</th>
                                        <th class="py-3 px-4">Program Terakhir</th>
                                    @elseif($tab === 'jumat_baru')
                                        <th class="py-3 px-4">Nomor WhatsApp</th>
                                        <th class="py-3 px-4">Donatur ID</th>
                                        <th class="py-3 px-4">Nama Lengkap</th>
                                        <th class="py-3 px-4">Tgl Dikenalkan</th>
                                        <th class="py-3 px-4">Sisa Masa Baru</th>
                                        <th class="py-3 px-4">Catatan Sistem</th>
                                    @else
                                        <th class="py-3 px-4">Nomor WhatsApp</th>
                                        <th class="py-3 px-4">Donatur ID / Nama</th>
                                        <th class="py-3 px-4">Segmen</th>
                                        <th class="py-3 px-4">Jadwal Kirim</th>
                                        <th class="py-3 px-4">Total Donasi</th>
                                        <th class="py-3 px-4">Terakhir Dibaca</th>
                                        <th class="py-3 px-4 min-w-[200px]">Alasan Waterfall</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                <template x-for="(row, index) in filteredItems" :key="row.no_hp">
                                    <tr class="hover:bg-slate-50 transition-colors" :class="row.selected ? 'bg-slate-50' : ''">
                                        <!-- Row Checkbox -->
                                        <td class="py-3 px-3 text-center">
                                            <input type="checkbox" x-model="row.selected" :aria-label="'Pilih kontak ' + (row.nama || row.no_hp)" class="w-4 h-4 rounded text-primary-600 focus:ring-primary-600 border-slate-400 cursor-pointer">
                                        </td>

                                        <!-- Status Tracker Selector (Dropdown) -->
                                        <td class="py-3 px-4 relative" @click.outside="row.dropdownOpen = false">
                                            <!-- Status Button Badge -->
                                            <button type="button" @click="row.dropdownOpen = !row.dropdownOpen" :aria-expanded="row.dropdownOpen ? 'true' : 'false'" class="inline-flex items-center gap-1.5 min-h-[36px] px-2.5 py-1 rounded-lg text-xs font-bold bg-white text-slate-800 border border-slate-300 hover:bg-slate-50 transition">
                                                <template x-if="row.status === 'PENDING'">
                                                    <span class="inline-flex items-center gap-1.5 text-amber-800"><i class="fa-regular fa-clock" aria-hidden="true"></i>Belum Kirim</span>
                                                </template>
                                                <template x-if="row.status === 'DELIVERED'">
                                                    <span class="inline-flex items-center gap-1.5 text-blue-700"><i class="fa-solid fa-check-double" aria-hidden="true"></i>Terkirim</span>
                                                </template>
                                                <template x-if="row.status === 'READ'">
                                                    <span class="inline-flex items-center gap-1.5 text-emerald-700"><i class="fa-solid fa-check-double" aria-hidden="true"></i>Dibaca</span>
                                                </template>
                                                <template x-if="row.status === 'FAILED'">
                                                    <span class="inline-flex items-center gap-1.5 text-rose-700"><i class="fa-solid fa-circle-xmark" aria-hidden="true"></i>Gagal</span>
                                                </template>
                                                <i class="fa-solid fa-chevron-down text-[10px] text-slate-600 ml-1" aria-hidden="true"></i>
                                            </button>

                                            <!-- Dropdown Menu -->
                                            <div x-show="row.dropdownOpen" x-transition class="absolute left-4 top-12 z-30 w-52 bg-white border border-slate-300 rounded-2xl shadow-xl py-1 text-xs font-semibold divide-y divide-slate-200">
                                                <div class="px-3 py-1.5 text-[11px] font-black uppercase text-slate-600 tracking-wider">
                                                    Ubah Status Donatur
                                                </div>
                                                <div class="py-1">
                                                    <button type="button" @click="updateSingle(row.no_hp, 'READ')" class="w-full text-left px-3 py-2 min-h-[40px] hover:bg-slate-50 text-slate-800 flex items-center gap-2">
                                                        <i class="fa-solid fa-check-double text-emerald-700 w-4" aria-hidden="true"></i>
                                                        <span><strong>Dibaca</strong> (Read / Centang 2 Biru)</span>
                                                    </button>
                                                    <button type="button" @click="updateSingle(row.no_hp, 'DELIVERED')" class="w-full text-left px-3 py-2 min-h-[40px] hover:bg-slate-50 text-slate-800 flex items-center gap-2">
                                                        <i class="fa-solid fa-check-double text-blue-700 w-4" aria-hidden="true"></i>
                                                        <span><strong>Terkirim</strong> (Delivered / Centang 2 Abu)</span>
                                                    </button>
                                                    <button type="button" @click="updateSingle(row.no_hp, 'FAILED')" class="w-full text-left px-3 py-2 min-h-[40px] hover:bg-slate-50 text-rose-700 flex items-center gap-2">
                                                        <i class="fa-solid fa-circle-xmark text-rose-700 w-4" aria-hidden="true"></i>
                                                        <span><strong>Gagal</strong> (Failed)</span>
                                                    </button>
                                                    <button type="button" @click="updateSingle(row.no_hp, 'PENDING')" class="w-full text-left px-3 py-2 min-h-[40px] hover:bg-slate-50 text-slate-700 flex items-center gap-2">
                                                        <i class="fa-regular fa-clock text-slate-600 w-4" aria-hidden="true"></i>
                                                        <span><strong>Belum Kirim</strong> (Pending)</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Action Chat WA Button -->
                                        <td class="py-3 px-4 whitespace-nowrap">
                                            <button type="button" @click="openWa(row)" class="inline-flex items-center min-h-[38px] gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs shadow-xs transition-colors" title="Buka WhatsApp Web/App dengan Template Pesan">
                                                <i class="fa-brands fa-whatsapp text-sm" aria-hidden="true"></i>
                                                <span>Chat WA</span>
                                            </button>
                                        </td>

                                        <!-- TAB: NOMOR MATI -->
                                        @if($tab === 'nomor_mati')
                                            <td class="py-3 px-4 font-mono font-bold text-rose-700" x-text="row.no_hp"></td>
                                            <td class="py-3 px-4 font-mono text-primary-700 font-bold" x-text="row.donatur_id"></td>
                                            <td class="py-3 px-4 font-semibold text-slate-800" x-text="row.nama"></td>
                                            <td class="py-3 px-4 font-bold text-emerald-700" x-text="formatRupiah(row.total_donasi)"></td>
                                            <td class="py-3 px-4 text-slate-600" x-text="row.donasi_terakhir"></td>
                                            <td class="py-3 px-4 text-slate-700" x-text="row.pic_crm"></td>
                                            <td class="py-3 px-4 text-slate-600 text-xs" x-text="row.catatan"></td>

                                        <!-- TAB: PERKENALAN -->
                                        @elseif($tab === 'perkenalan')
                                            <td class="py-3 px-4 font-mono font-bold text-slate-900" x-text="row.no_hp"></td>
                                            <td class="py-3 px-4 font-bold text-emerald-700" x-text="formatRupiah(row.total_donasi)"></td>
                                            <td class="py-3 px-4 text-slate-700 font-semibold">
                                                <span x-text="row.frekuensi"></span>x donasi
                                            </td>
                                            <td class="py-3 px-4 text-slate-600" x-text="row.donasi_terakhir"></td>
                                            <td class="py-3 px-4">
                                                <span class="text-slate-800 font-semibold break-words">{{ $tab === 'perkenalan' ? '' : '' }}<span class="font-mono text-xs text-primary-700 font-bold block" x-text="row.donatur_id"></span><span x-text="row.nama"></span></span>
                                            </td>
                                            <td class="py-3 px-4">
                                                <span class="text-xs font-bold uppercase tracking-wider" :class="row.sumber === 'IM' ? 'text-[#063f5c]' : 'text-amber-800'">
                                                    <span x-text="row.sumber"></span> &bull; <span x-text="row.bulan_donasi"></span>
                                                </span>
                                            </td>
                                            <td class="py-3 px-4 text-slate-700 max-w-xs break-words" x-text="row.program"></td>

                                        <!-- TAB: JUMAT BARU -->
                                        @elseif($tab === 'jumat_baru')
                                            <td class="py-3 px-4 font-mono font-bold text-slate-900" x-text="row.no_hp"></td>
                                            <td class="py-3 px-4 font-mono text-primary-700 font-bold" x-text="row.donatur_id"></td>
                                            <td class="py-3 px-4 font-semibold text-slate-800" x-text="row.nama"></td>
                                            <td class="py-3 px-4 text-slate-700" x-text="row.tgl_dikenalkan"></td>
                                            <td class="py-3 px-4">
                                                <span class="text-xs font-bold text-amber-800">
                                                    <span x-text="row.sisa_hari_baru"></span> Hari Lagi
                                                </span>
                                            </td>
                                            <td class="py-3 px-4 text-slate-600 text-xs" x-text="row.catatan"></td>

                                        <!-- TAB: JUMAT & PAYDAY -->
                                        @else
                                            <td class="py-3 px-4 font-mono font-bold text-slate-900" x-text="row.no_hp"></td>
                                            <td class="py-3 px-4">
                                                <span class="font-mono text-xs text-primary-700 font-bold block" x-text="row.donatur_id"></span>
                                                <span class="text-slate-800 font-semibold break-words block" x-text="row.nama"></span>
                                            </td>
                                            <td class="py-3 px-4">
                                                <span class="text-xs font-bold uppercase tracking-wider text-slate-700" x-text="row.segmen"></span>
                                            </td>
                                            <td class="py-3 px-4 font-bold text-xs" :class="row.jadwal && row.jadwal.includes('CEK') ? 'text-amber-800' : 'text-emerald-700'" x-text="row.jadwal"></td>
                                            <td class="py-3 px-4 font-bold text-emerald-700 text-xs" x-text="formatRupiah(row.total_donasi)"></td>
                                            <td class="py-3 px-4 text-slate-600 text-xs" x-text="row.read_terakhir"></td>
                                            <td class="py-3 px-4 text-slate-700 text-xs leading-snug" x-text="row.alasan"></td>
                                        @endif
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FLOATING TOAST NOTIFICATION -->
    <div x-show="toast.show" role="status" aria-live="polite" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-3" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-3" class="fixed bottom-6 right-6 z-50 max-w-md bg-slate-900 text-white border border-slate-700 rounded-2xl p-4 shadow-2xl flex items-center gap-3">
        <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0" :class="toast.type === 'success' ? 'bg-emerald-500/20 text-emerald-300' : 'bg-rose-500/20 text-rose-300'">
            <i class="fa-solid" :class="toast.type === 'success' ? 'fa-check' : 'fa-triangle-exclamation'" aria-hidden="true"></i>
        </div>
        <div class="text-xs font-semibold" x-text="toast.message"></div>
        <button type="button" @click="toast.show = false" aria-label="Tutup notifikasi" class="min-w-[36px] min-h-[36px] flex items-center justify-center text-slate-200 hover:text-white text-xs ml-auto">
            <i class="fa-solid fa-xmark" aria-hidden="true"></i>
        </button>
    </div>
</div>

<script>
function trackerData(initialItems, tabType, batchDate) {
    return {
        items: (initialItems || []).map(item => ({ ...item, selected: false, dropdownOpen: false })),
        tab: tabType,
        batchDate: batchDate,
        searchQuery: '',
        statusFilter: 'ALL',
        isUpdating: false,
        toast: {
            show: false,
            message: '',
            type: 'success'
        },

        get filteredItems() {
            return this.items.filter(item => {
                if (this.statusFilter !== 'ALL' && item.status !== this.statusFilter) {
                    return false;
                }
                if (this.searchQuery && this.searchQuery.trim() !== '') {
                    const q = this.searchQuery.toLowerCase().trim();
                    const phone = (item.no_hp || '').toLowerCase();
                    const name = (item.nama || '').toLowerCase();
                    const id = (item.donatur_id || '').toLowerCase();
                    return phone.includes(q) || name.includes(q) || id.includes(q);
                }
                return true;
            });
        },

        get stats() {
            const total = this.items.length;
            let pending = 0, delivered = 0, read = 0, failed = 0;
            for (const item of this.items) {
                if (item.status === 'READ') read++;
                else if (item.status === 'DELIVERED') delivered++;
                else if (item.status === 'FAILED') failed++;
                else pending++;
            }
            const finished = delivered + read + failed;
            const percent = total > 0 ? Math.round((finished / total) * 100) : 0;
            return { total, pending, delivered, read, failed, finished, percent };
        },

        get selectedCount() {
            return this.items.filter(i => i.selected).length;
        },

        isAllFilteredSelected() {
            if (this.filteredItems.length === 0) return false;
            return this.filteredItems.every(i => i.selected);
        },

        toggleSelectAll() {
            const target = !this.isAllFilteredSelected();
            const visiblePhones = new Set(this.filteredItems.map(i => i.no_hp));
            this.items.forEach(i => {
                if (visiblePhones.has(i.no_hp)) {
                    i.selected = target;
                }
            });
        },

        clearSelection() {
            this.items.forEach(i => i.selected = false);
        },

        formatRupiah(amount) {
            return 'Rp ' + Number(amount || 0).toLocaleString('id-ID');
        },

        getWaUrl(row) {
            const phone = (row.no_hp || '').replace(/[^0-9]/g, '');
            let greeting = 'Assalamu\'alaikum';
            if (row.nama && row.nama !== 'Donatur') {
                greeting += ' Kak ' + row.nama;
            } else {
                greeting += ' Kak';
            }

            let text = '';
            if (this.tab === 'jumat') {
                text = greeting + ', salam silaturahmi dari Harapan Palestina di hari Jumat yang mulia ini. Semoga Kakak dan keluarga selalu dilimpahi kesehatan dan keberkahan rezeki. Jazakallahu khair atas kebaikan Kakak selama ini.';
            } else if (this.tab === 'payday') {
                text = greeting + ', salam hangat dari Harapan Palestina. Memasuki awal bulan ini, semoga Allah mudahkan segala urusan dan limpahkan kelapangan rezeki untuk Kakak sekeluarga.';
            } else if (this.tab === 'perkenalan') {
                text = greeting + ', terima kasih banyak telah menyalurkan kepedulian melalui website resmi Harapan Palestina. Ini adalah nomor layanan resmi CRM kami untuk silaturahmi dan kabar program.';
            } else if (this.tab === 'jumat_baru') {
                text = greeting + ', salam silaturahmi dari Harapan Palestina. Semoga hari Jumat ini penuh berkah untuk Kakak sekeluarga.';
            } else {
                text = greeting + ', salam silaturahmi dari Tim Harapan Palestina.';
            }
            return 'https://wa.me/' + phone + '?text=' + encodeURIComponent(text);
        },

        openWa(row) {
            const url = this.getWaUrl(row);
            window.open(url, '_blank');
            if (row.status === 'PENDING') {
                this.updateStatus([row.no_hp], 'DELIVERED', false);
            }
        },

        updateSingle(phone, newStatus) {
            this.updateStatus([phone], newStatus, false);
        },

        updateBulk(newStatus) {
            const selected = this.items.filter(i => i.selected).map(i => i.no_hp);
            if (selected.length === 0) return;
            this.updateStatus(selected, newStatus, true);
        },

        async updateStatus(phones, newStatus, isBulk = false) {
            if (!phones || phones.length === 0) return;
            this.isUpdating = true;
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const response = await fetch("{{ route('admin.harpa.daftar_kirim.update_status') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        phones: phones,
                        status: newStatus,
                        tab: this.tab,
                        batch_date: this.batchDate
                    })
                });

                const result = await response.json();
                if (result.success) {
                    const phoneSet = new Set(phones);
                    this.items.forEach(i => {
                        if (phoneSet.has(i.no_hp)) {
                            i.status = newStatus;
                            i.dropdownOpen = false;
                            if (isBulk) i.selected = false;
                        }
                    });
                    this.showToast(result.message, 'success');
                } else {
                    this.showToast(result.message || 'Gagal memperbarui status', 'error');
                }
            } catch (err) {
                console.error(err);
                this.showToast('Terjadi gangguan koneksi.', 'error');
            } finally {
                this.isUpdating = false;
            }
        },

        showToast(message, type = 'success') {
            this.toast.message = message;
            this.toast.type = type;
            this.toast.show = true;
            setTimeout(() => {
                this.toast.show = false;
            }, 3500);
        }
    };
}
</script>
@endsection
