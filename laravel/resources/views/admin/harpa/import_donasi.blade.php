@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50/60 pb-20">
    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-8 items-start min-w-0">
            <!-- Sidebar Menu -->
            <aside class="hidden lg:block space-y-6 shrink-0 w-[280px]">
                @include('admin.sidebar', ['active' => 'harpa_import_donasi'])
            </aside>

            <!-- Main Content Area -->
            <div class="space-y-6 min-w-0">
                <!-- Top Header Bar -->
                <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-600 uppercase tracking-widest">
                            <span>Sistem Data Harpa</span>
                            <span aria-hidden="true">/</span>
                            <span class="text-primary-600 font-black">Alur A: Masuk Data Donasi</span>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-montserrat font-black text-slate-900 tracking-tight mt-1">
                            Impor Data Donasi (FH, IM, WA)
                        </h1>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.harpa.template.download', 'donasi') }}" class="inline-flex items-center min-h-[44px] gap-2 px-4 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-black text-xs uppercase tracking-wider shadow-sm transition-colors">
                            <i class="fa-solid fa-file-arrow-down text-sm" aria-hidden="true"></i>
                            Unduh Template (.CSV)
                        </a>
                        <a href="{{ route('admin.harpa.papan_kerja') }}" class="inline-flex items-center min-h-[44px] gap-2 px-4 py-2.5 rounded-xl bg-amber-50 border border-amber-300 text-amber-800 hover:bg-amber-100 font-bold text-xs uppercase tracking-wider transition-colors">
                            <i class="fa-solid fa-clipboard-check text-sm" aria-hidden="true"></i>
                            Buka Papan Kerja
                        </a>
                    </div>
                </div>
        <!-- Flash Messages -->
        @if(session('success'))
            <div role="status" class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-300 text-emerald-800 text-xs font-bold flex items-center gap-3 shadow-sm">
                <i class="fa-solid fa-circle-check text-emerald-700 text-base" aria-hidden="true"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('warning'))
            <div role="alert" class="mb-6 p-4 rounded-2xl bg-amber-50 border border-amber-300 text-amber-900 text-xs font-bold flex items-center gap-3 shadow-sm">
                <i class="fa-solid fa-triangle-exclamation text-amber-700 text-base" aria-hidden="true"></i>
                <span>{{ session('warning') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div role="alert" class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-300 text-rose-900 text-xs font-bold flex items-center gap-3 shadow-sm">
                <i class="fa-solid fa-circle-xmark text-rose-700 text-base" aria-hidden="true"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left 2 Cols: Switcher Tabs (Bulk Upload / Manual Input) -->
            <div class="lg:col-span-2 space-y-6">
                <div x-data="{ activeTab: '{{ old('nama_lengkap') ? 'manual' : 'bulk' }}' }" class="space-y-6">
                    
                    <!-- Tab Switcher Header -->
                    <div class="flex flex-wrap items-center gap-2 p-1.5 bg-slate-200 rounded-2xl w-fit">
                        <button type="button" 
                                @click="activeTab = 'bulk'" 
                                :aria-pressed="activeTab === 'bulk' ? 'true' : 'false'"
                                :class="activeTab === 'bulk' ? 'bg-white text-primary-700 shadow-sm' : 'text-slate-700 hover:text-slate-900'"
                                class="min-h-[44px] flex items-center gap-2 px-5 py-2.5 rounded-xl font-montserrat font-black text-xs uppercase tracking-wider transition-colors cursor-pointer">
                            <i class="fa-solid fa-file-excel text-emerald-700" aria-hidden="true"></i>
                            Unggah File Excel/CSV (Massal)
                        </button>
                        <button type="button" 
                                @click="activeTab = 'manual'" 
                                :aria-pressed="activeTab === 'manual' ? 'true' : 'false'"
                                :class="activeTab === 'manual' ? 'bg-white text-emerald-700 shadow-sm' : 'text-slate-700 hover:text-slate-900'"
                                class="min-h-[44px] flex items-center gap-2 px-5 py-2.5 rounded-xl font-montserrat font-black text-xs uppercase tracking-wider transition-colors cursor-pointer">
                            <i class="fa-solid fa-pen-to-square text-primary-600" aria-hidden="true"></i>
                            Input Donasi Manual (Satuan)
                        </button>
                    </div>

                    <!-- TAB 1: UNGGAH FILE EXCEL/CSV (MASSAL) -->
                    <div x-show="activeTab === 'bulk'" x-transition:enter="transition ease-out duration-200" class="space-y-8">
                        <!-- Upload Card -->
                        <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm">
                            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-200">
                                <div class="w-10 h-10 rounded-2xl bg-primary-50 text-primary-700 flex items-center justify-center text-lg">
                                    <i class="fa-solid fa-cloud-arrow-up" aria-hidden="true"></i>
                                </div>
                                <div>
                                    <h2 class="text-base font-montserrat font-black text-slate-800">Unggah File Ekspor Donasi</h2>
                                    <p class="text-xs text-slate-600 font-medium">Mendukung format .xlsx, .xls, .csv dari ForHumanity, Indonesia Madani, atau CRM WA</p>
                                </div>
                            </div>

                            <form action="{{ route('admin.harpa.import_donasi.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                                @csrf

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <!-- Sumber Data -->
                                    <div>
                                        <label for="import_source" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                            Sumber Data <span class="text-rose-700">*</span>
                                        </label>
                                        <select id="import_source" name="source" required class="w-full min-h-[44px] bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-700 focus:bg-white focus:ring-2 focus:ring-primary-600 outline-none transition">
                                            <option value="IM">Indonesia Madani (Web Sendiri - Bulanan)</option>
                                            <option value="FH">ForHumanity (Web Mitra - Bulanan)</option>
                                            <option value="WA">Catatan CRM WhatsApp (Mingguan)</option>
                                        </select>
                                    </div>

                                    <!-- Periode Kalender -->
                                    <div>
                                        <label for="import_periode" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                            Periode Kalender (YYYY-MM) <span class="text-rose-700">*</span>
                                        </label>
                                        <input id="import_periode" type="month" name="periode" value="{{ date('Y-m', strtotime('-1 month')) }}" required class="w-full min-h-[44px] bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-700 focus:bg-white focus:ring-2 focus:ring-primary-600 outline-none transition">
                                        <span class="text-xs text-slate-600 mt-1 block">Periode wajib satu bulan penuh untuk mencegah tumpang tindih.</span>
                                    </div>
                                </div>

                                <!-- Download Template Callout Banner -->
                                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-300 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-emerald-700 text-white flex items-center justify-center text-lg shrink-0 shadow-sm">
                                            <i class="fa-solid fa-file-csv" aria-hidden="true"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-xs font-bold text-slate-800">Format File Donasi Standar (.CSV / .Excel)</h3>
                                            <p class="text-xs text-slate-700 font-medium">Unduh template acuan dengan 9 kolom standar agar data terbaca 100% tanpa error.</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('admin.harpa.template.download', 'donasi') }}" class="inline-flex items-center justify-center min-h-[44px] gap-2 px-4 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-black text-xs uppercase tracking-wider shadow-sm transition whitespace-nowrap">
                                        <i class="fa-solid fa-download" aria-hidden="true"></i>
                                        Unduh Template CSV
                                    </a>
                                </div>

                                <!-- File Input -->
                                <div>
                                    <label for="import_file" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                        Pilih File (.xlsx / .csv) <span class="text-rose-700">*</span>
                                    </label>
                                    <div class="border-2 border-dashed border-slate-300 hover:border-primary-600 rounded-2xl p-6 text-center transition-colors bg-slate-50">
                                        <i class="fa-solid fa-file-excel text-3xl text-emerald-700 mb-2" aria-hidden="true"></i>
                                        <input id="import_file" type="file" name="file" accept=".xlsx,.xls,.csv,.txt" required class="block w-full text-xs text-slate-700 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:bg-primary-600 file:text-white hover:file:bg-primary-700 cursor-pointer">
                                        <p class="text-xs text-slate-600 mt-2">Maksimal ukuran file: 20 MB. Data mentah akan disimpan apa adanya di database.</p>
                                    </div>
                                </div>

                                <!-- Checkbox Commit Langsung jika Lolos -->
                                <div class="flex items-start gap-3 p-4 bg-slate-50 rounded-2xl border border-slate-200">
                                    <input type="checkbox" id="commit_directly" name="commit_directly" value="1" checked class="w-4 h-4 mt-0.5 rounded border-slate-400 text-primary-600 focus:ring-primary-600">
                                    <label for="commit_directly" class="text-xs text-slate-700 font-semibold cursor-pointer">
                                        <strong>Proses &amp; Daftarkan Donatur Otomatis:</strong> Jika nomor belum pernah terdaftar, otomatis daftarkan ke <code class="text-primary-700 font-bold">master_donatur</code> (DNRxxxxxx) dan <code class="text-primary-700 font-bold">master_nomor</code> dalam satu transaksi database.
                                    </label>
                                </div>

                                <div class="pt-2 flex justify-end">
                                    <button type="submit" class="min-h-[44px] bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-widest px-8 py-3.5 rounded-xl shadow-md transition-colors flex items-center gap-2 cursor-pointer">
                                        <i class="fa-solid fa-gears" aria-hidden="true"></i>
                                        Periksa &amp; Masukkan Data
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Panduan Format Kolom & Contoh Tabel Donasi -->
                        <div x-data="{ openPreview: true, viewMode: 'table' }" class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm space-y-5">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-200">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-2xl bg-primary-50 text-primary-700 flex items-center justify-center text-lg shrink-0">
                                        <i class="fa-solid fa-table-columns" aria-hidden="true"></i>
                                    </div>
                                    <div>
                                        <h2 class="text-base font-montserrat font-black text-slate-800 flex items-center gap-2">
                                            Panduan Format Kolom &amp; Contoh Tabel
                                        </h2>
                                        <p class="text-xs text-slate-600 font-medium">Susunan header dan contoh baris data yang dikenali oleh Harpa Data Engine</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.harpa.template.download', 'donasi') }}" class="min-h-[40px] px-3.5 py-2 rounded-xl bg-emerald-50 text-emerald-800 hover:bg-emerald-100 text-xs font-black uppercase tracking-wider transition flex items-center gap-1.5 border border-emerald-300">
                                        <i class="fa-solid fa-download" aria-hidden="true"></i> Unduh File Contoh (.CSV)
                                    </a>
                                    <button type="button" @click="openPreview = !openPreview" :aria-expanded="openPreview ? 'true' : 'false'" class="min-h-[40px] px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition flex items-center gap-1 cursor-pointer">
                                        <span x-text="openPreview ? 'Sembunyikan' : 'Buka Panduan'"></span>
                                        <i class="fa-solid text-[10px]" :class="openPreview ? 'fa-chevron-up' : 'fa-chevron-down'" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>

                            <div x-show="openPreview" x-transition class="space-y-5">
                                <!-- Mode Switcher: Penjelasan Kolom vs Contoh Data -->
                                <div class="flex items-center gap-2 p-1 bg-slate-100 rounded-xl w-fit text-xs font-bold">
                                    <button type="button" @click="viewMode = 'table'" :class="viewMode === 'table' ? 'bg-white text-primary-700 shadow-sm' : 'text-slate-700 hover:text-slate-900'" class="min-h-[38px] px-3 py-1.5 rounded-lg transition cursor-pointer">
                                        <i class="fa-solid fa-table mr-1" aria-hidden="true"></i> Preview Contoh Data (Tabel)
                                    </button>
                                    <button type="button" @click="viewMode = 'columns'" :class="viewMode === 'columns' ? 'bg-white text-primary-700 shadow-sm' : 'text-slate-700 hover:text-slate-900'" class="min-h-[38px] px-3 py-1.5 rounded-lg transition cursor-pointer">
                                        <i class="fa-solid fa-list-check mr-1" aria-hidden="true"></i> Penjelasan 9 Kolom Standar
                                    </button>
                                </div>

                                <!-- VIEW 1: PREVIEW CONTOH DATA (TABEL) -->
                                <div x-show="viewMode === 'table'" class="overflow-x-auto rounded-2xl border border-slate-200">
                                    <table class="w-full text-left text-xs whitespace-nowrap">
                                        <thead class="bg-slate-50 text-xs font-black uppercase tracking-wider text-slate-600 border-b border-slate-200">
                                            <tr>
                                                <th class="py-3 px-3.5 bg-slate-100">invoice_id *</th>
                                                <th class="py-3 px-3.5">tanggal *</th>
                                                <th class="py-3 px-3.5">campaign *</th>
                                                <th class="py-3 px-3.5">nama *</th>
                                                <th class="py-3 px-3.5">no_hp *</th>
                                                <th class="py-3 px-3.5 text-right">nominal *</th>
                                                <th class="py-3 px-3.5 text-right">kode_unik</th>
                                                <th class="py-3 px-3.5">status *</th>
                                                <th class="py-3 px-3.5">via_bayar</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-200 text-slate-700 font-medium">
                                            <tr class="hover:bg-slate-50">
                                                <td class="py-2.5 px-3.5 font-mono font-bold text-primary-700">INV-202611-001</td>
                                                <td class="py-2.5 px-3.5 font-mono text-xs">2026-11-01 08:30:15</td>
                                                <td class="py-2.5 px-3.5 font-semibold text-slate-800">Sedekah Subuh Palestina</td>
                                                <td class="py-2.5 px-3.5">Ahmad Fadhilah</td>
                                                <td class="py-2.5 px-3.5 font-mono font-bold text-emerald-700">081234567890</td>
                                                <td class="py-2.5 px-3.5 text-right font-mono font-bold">100000</td>
                                                <td class="py-2.5 px-3.5 text-right font-mono text-slate-600">125</td>
                                                <td class="py-2.5 px-3.5"><span class="px-2 py-0.5 rounded text-xs font-bold bg-emerald-100 text-emerald-800">success</span></td>
                                                <td class="py-2.5 px-3.5">BCA</td>
                                            </tr>
                                            <tr class="hover:bg-slate-50">
                                                <td class="py-2.5 px-3.5 font-mono font-bold text-primary-700">INV-202611-002</td>
                                                <td class="py-2.5 px-3.5 font-mono text-xs">2026-11-01 10:15:40</td>
                                                <td class="py-2.5 px-3.5 font-semibold text-slate-800">Bantuan Air Bersih Gaza</td>
                                                <td class="py-2.5 px-3.5">Siti Rahmawati</td>
                                                <td class="py-2.5 px-3.5 font-mono font-bold text-emerald-700">6285712345678</td>
                                                <td class="py-2.5 px-3.5 text-right font-mono font-bold">250000</td>
                                                <td class="py-2.5 px-3.5 text-right font-mono text-slate-600">340</td>
                                                <td class="py-2.5 px-3.5"><span class="px-2 py-0.5 rounded text-xs font-bold bg-emerald-100 text-emerald-800">success</span></td>
                                                <td class="py-2.5 px-3.5">Mandiri</td>
                                            </tr>
                                            <tr class="hover:bg-slate-50">
                                                <td class="py-2.5 px-3.5 font-mono font-bold text-primary-700">INV-202611-003</td>
                                                <td class="py-2.5 px-3.5 font-mono text-xs">2026-11-02 14:05:12</td>
                                                <td class="py-2.5 px-3.5 font-semibold text-slate-800">Paket Pangan Darurat Jabalia</td>
                                                <td class="py-2.5 px-3.5">Budi Santoso</td>
                                                <td class="py-2.5 px-3.5 font-mono font-bold text-emerald-700">081987654321</td>
                                                <td class="py-2.5 px-3.5 text-right font-mono font-bold">500000</td>
                                                <td class="py-2.5 px-3.5 text-right font-mono text-slate-600">0</td>
                                                <td class="py-2.5 px-3.5"><span class="px-2 py-0.5 rounded text-xs font-bold bg-emerald-100 text-emerald-800">success</span></td>
                                                <td class="py-2.5 px-3.5">QRIS</td>
                                            </tr>
                                            <tr class="hover:bg-slate-50">
                                                <td class="py-2.5 px-3.5 font-mono font-bold text-primary-700">INV-202611-004</td>
                                                <td class="py-2.5 px-3.5 font-mono text-xs">2026-11-02 19:45:00</td>
                                                <td class="py-2.5 px-3.5 font-semibold text-slate-800">Hangatkan Gaza Musim Dingin</td>
                                                <td class="py-2.5 px-3.5">Hj. Nurhayati</td>
                                                <td class="py-2.5 px-3.5 font-mono font-bold text-emerald-700">6281399887766</td>
                                                <td class="py-2.5 px-3.5 text-right font-mono font-bold">1000000</td>
                                                <td class="py-2.5 px-3.5 text-right font-mono text-slate-600">500</td>
                                                <td class="py-2.5 px-3.5"><span class="px-2 py-0.5 rounded text-xs font-bold bg-emerald-100 text-emerald-800">success</span></td>
                                                <td class="py-2.5 px-3.5">BSI</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- VIEW 2: PENJELASAN 9 KOLOM STANDAR -->
                                <div x-show="viewMode === 'columns'" class="overflow-x-auto rounded-2xl border border-slate-200">
                                    <table class="w-full text-left text-xs">
                                        <thead class="bg-slate-50 text-xs font-black uppercase tracking-wider text-slate-600 border-b border-slate-200">
                                            <tr>
                                                <th class="py-2.5 px-3.5">Nama Kolom Standar</th>
                                                <th class="py-2.5 px-3.5">Variasi Header Dikenali</th>
                                                <th class="py-2.5 px-3.5 text-center">Status</th>
                                                <th class="py-2.5 px-3.5">Format &amp; Contoh Nilai</th>
                                                <th class="py-2.5 px-3.5">Penjelasan</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-200 text-slate-700">
                                            <tr>
                                                <td class="py-2 px-3.5 font-mono font-bold text-primary-700">invoice_id</td>
                                                <td class="py-2 px-3.5 text-slate-600 font-mono text-xs">id_transaksi, invoice, transaksi_id</td>
                                                <td class="py-2 px-3.5 text-center"><span class="px-2 py-0.5 rounded text-[11px] font-black bg-rose-100 text-rose-800">Wajib</span></td>
                                                <td class="py-2 px-3.5 font-mono text-xs">INV-202611-001</td>
                                                <td class="py-2 px-3.5 text-slate-700 text-xs">ID unik transaksi untuk mencegah duplikasi.</td>
                                            </tr>
                                            <tr>
                                                <td class="py-2 px-3.5 font-mono font-bold text-primary-700">tanggal</td>
                                                <td class="py-2 px-3.5 text-slate-600 font-mono text-xs">date, waktu, created_at</td>
                                                <td class="py-2 px-3.5 text-center"><span class="px-2 py-0.5 rounded text-[11px] font-black bg-rose-100 text-rose-800">Wajib</span></td>
                                                <td class="py-2 px-3.5 font-mono text-xs">2026-11-01 08:30:00</td>
                                                <td class="py-2 px-3.5 text-slate-700 text-xs">Waktu donasi (YYYY-MM-DD atau DD/MM/YYYY).</td>
                                            </tr>
                                            <tr>
                                                <td class="py-2 px-3.5 font-mono font-bold text-primary-700">campaign</td>
                                                <td class="py-2 px-3.5 text-slate-600 font-mono text-xs">program, judul_campaign, judul_program</td>
                                                <td class="py-2 px-3.5 text-center"><span class="px-2 py-0.5 rounded text-[11px] font-black bg-rose-100 text-rose-800">Wajib</span></td>
                                                <td class="py-2 px-3.5 font-semibold text-slate-800">Sedekah Subuh Palestina</td>
                                                <td class="py-2 px-3.5 text-slate-700 text-xs">Judul program donasi tujuan.</td>
                                            </tr>
                                            <tr>
                                                <td class="py-2 px-3.5 font-mono font-bold text-primary-700">nama</td>
                                                <td class="py-2 px-3.5 text-slate-600 font-mono text-xs">nama_lengkap, name, donatur</td>
                                                <td class="py-2 px-3.5 text-center"><span class="px-2 py-0.5 rounded text-[11px] font-black bg-rose-100 text-rose-800">Wajib</span></td>
                                                <td class="py-2 px-3.5">Ahmad Fadhilah</td>
                                                <td class="py-2 px-3.5 text-slate-700 text-xs">Nama lengkap donatur.</td>
                                            </tr>
                                            <tr>
                                                <td class="py-2 px-3.5 font-mono font-bold text-primary-700">no_hp</td>
                                                <td class="py-2 px-3.5 text-slate-600 font-mono text-xs">phone, telepon, whatsapp</td>
                                                <td class="py-2 px-3.5 text-center"><span class="px-2 py-0.5 rounded text-[11px] font-black bg-rose-100 text-rose-800">Wajib</span></td>
                                                <td class="py-2 px-3.5 font-mono font-bold text-emerald-700">081234567890 / 628...</td>
                                                <td class="py-2 px-3.5 text-slate-700 text-xs">Nomor WhatsApp donatur (11–14 digit). Otomatis dinormalisasi ke 628.</td>
                                            </tr>
                                            <tr>
                                                <td class="py-2 px-3.5 font-mono font-bold text-primary-700">nominal</td>
                                                <td class="py-2 px-3.5 text-slate-600 font-mono text-xs">amount, donasi</td>
                                                <td class="py-2 px-3.5 text-center"><span class="px-2 py-0.5 rounded text-[11px] font-black bg-rose-100 text-rose-800">Wajib</span></td>
                                                <td class="py-2 px-3.5 font-mono font-bold">100000</td>
                                                <td class="py-2 px-3.5 text-slate-700 text-xs">Nominal donasi murni (angka tanpa titik/Rp).</td>
                                            </tr>
                                            <tr>
                                                <td class="py-2 px-3.5 font-mono font-bold text-primary-700">kode_unik</td>
                                                <td class="py-2 px-3.5 text-slate-600 font-mono text-xs">unique_code</td>
                                                <td class="py-2 px-3.5 text-center"><span class="px-2 py-0.5 rounded text-[11px] font-bold bg-slate-200 text-slate-700">Opsional</span></td>
                                                <td class="py-2 px-3.5 font-mono">125</td>
                                                <td class="py-2 px-3.5 text-slate-700 text-xs">Kode unik transfer (jika ada).</td>
                                            </tr>
                                            <tr>
                                                <td class="py-2 px-3.5 font-mono font-bold text-primary-700">status</td>
                                                <td class="py-2 px-3.5 text-slate-600 font-mono text-xs">payment_status</td>
                                                <td class="py-2 px-3.5 text-center"><span class="px-2 py-0.5 rounded text-[11px] font-black bg-rose-100 text-rose-800">Wajib</span></td>
                                                <td class="py-2 px-3.5 font-mono">success / waiting</td>
                                                <td class="py-2 px-3.5 text-slate-700 text-xs">Status verifikasi bayar.</td>
                                            </tr>
                                            <tr>
                                                <td class="py-2 px-3.5 font-mono font-bold text-primary-700">via_bayar</td>
                                                <td class="py-2 px-3.5 text-slate-600 font-mono text-xs">payment_method, bank</td>
                                                <td class="py-2 px-3.5 text-center"><span class="px-2 py-0.5 rounded text-[11px] font-bold bg-slate-200 text-slate-700">Opsional</span></td>
                                                <td class="py-2 px-3.5">BCA / Mandiri / QRIS</td>
                                                <td class="py-2 px-3.5 text-slate-700 text-xs">Metode transfer / channel bank.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Info Aturan Data -->
                        <div class="bg-[#063f5c] text-white rounded-3xl p-6 sm:p-8 space-y-4 shadow-sm">
                            <div class="flex items-center gap-2 text-xs font-black uppercase tracking-widest text-[#f7ad1a]">
                                <i class="fa-solid fa-shield-halved" aria-hidden="true"></i>
                                Aturan Data Harpa (SOP-DA-001)
                            </div>
                            <ul class="text-xs text-slate-200 space-y-2 list-disc list-inside leading-relaxed font-medium">
                                <li><strong>Identitas adalah <code class="text-[#f7ad1a]">donatur_id</code></strong>, bukan nomor HP. Satu orang boleh memiliki lebih dari satu nomor.</li>
                                <li><strong>Nomor HP selalu teks</strong> format <code class="text-[#f7ad1a]">628...</code> (11–14 digit). Kolom nomor dilarang bertipe angka.</li>
                                <li><strong>Data mentah tidak pernah diubah</strong> atau dihapus. Transaksi yang nomornya tidak valid disimpan dengan ID <code class="text-[#f7ad1a]">TANPA-NOMOR</code>.</li>
                                <li><strong>Gerbang Berhenti (Papan Kerja)</strong> akan menahan data jika ada judul campaign belum dipetakan atau tanggal gagal dibaca.</li>
                            </ul>
                        </div>
                    </div>

                    <!-- TAB 2: INPUT DONASI MANUAL (SATUAN) -->
                    <div x-show="activeTab === 'manual'" x-transition:enter="transition ease-out duration-200" x-cloak class="space-y-8">
                        <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm">
                            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-200">
                                <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-lg">
                                    <i class="fa-solid fa-hand-holding-dollar" aria-hidden="true"></i>
                                </div>
                                <div>
                                    <h2 class="text-base font-montserrat font-black text-slate-800">Formulir Input Donasi Manual</h2>
                                    <p class="text-xs text-slate-600 font-medium">Input langsung transaksi donasi satuan. Otomatis mendaftarkan donatur &amp; terintegrasi ke Rekap Waterfall Harpa</p>
                                </div>
                            </div>

                            <form action="{{ route('admin.harpa.import_donasi.manual') }}" method="POST" class="space-y-5">
                                @csrf

                                <!-- Section: Informasi Donatur -->
                                <div class="bg-slate-50 p-4 sm:p-5 rounded-2xl border border-slate-200 space-y-4">
                                    <span class="text-xs font-black uppercase tracking-wider text-slate-700 flex items-center gap-2">
                                        <i class="fa-solid fa-user-tag text-primary-600" aria-hidden="true"></i> Data Donatur
                                    </span>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label for="manual_nama_lengkap" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                                Nama Lengkap Donatur <span class="text-rose-700">*</span>
                                            </label>
                                            <input id="manual_nama_lengkap" type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required placeholder="Contoh: Bpk. H. Rahmat Hidayat" class="w-full min-h-[44px] bg-white border border-slate-300 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-800 focus:ring-2 focus:ring-emerald-600 outline-none transition">
                                        </div>

                                        <div>
                                            <label for="manual_no_hp" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                                Nomor WhatsApp / HP <span class="text-rose-700">*</span>
                                            </label>
                                            <input id="manual_no_hp" type="text" name="no_hp" value="{{ old('no_hp') }}" required placeholder="08xxxxxxxxxx atau 628xxxxxxxxxx" class="w-full min-h-[44px] bg-white border border-slate-300 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-800 focus:ring-2 focus:ring-emerald-600 outline-none transition">
                                            <span class="text-xs text-slate-600 mt-1 block">Otomatis dinormalisasi ke format 628 (11–14 digit).</span>
                                        </div>

                                        <div>
                                            <label for="manual_email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                                Email Donatur <span class="text-slate-600 font-normal">(Opsional)</span>
                                            </label>
                                            <input id="manual_email" type="email" name="email" value="{{ old('email') }}" placeholder="donatur@email.com" class="w-full min-h-[44px] bg-white border border-slate-300 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-800 focus:ring-2 focus:ring-emerald-600 outline-none transition">
                                        </div>

                                        <div>
                                            <label for="manual_sumber_data" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                                Sumber Data / Saluran <span class="text-rose-700">*</span>
                                            </label>
                                            <select id="manual_sumber_data" name="sumber_data" required class="w-full min-h-[44px] bg-white border border-slate-300 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-800 focus:ring-2 focus:ring-emerald-600 outline-none transition">
                                                <option value="WA" {{ old('sumber_data') === 'WA' ? 'selected' : '' }}>Catatan CRM WhatsApp (WA)</option>
                                                <option value="IM" {{ old('sumber_data') === 'IM' ? 'selected' : '' }}>Indonesia Madani (IM)</option>
                                                <option value="FH" {{ old('sumber_data') === 'FH' ? 'selected' : '' }}>ForHumanity (FH)</option>
                                                <option value="MANUAL" {{ old('sumber_data') === 'MANUAL' ? 'selected' : '' }}>Langsung / Kantor Harpa (MANUAL)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Section: Informasi Transaksi -->
                                <div class="bg-slate-50 p-4 sm:p-5 rounded-2xl border border-slate-200 space-y-4">
                                    <span class="text-xs font-black uppercase tracking-wider text-slate-700 flex items-center gap-2">
                                        <i class="fa-solid fa-receipt text-emerald-700" aria-hidden="true"></i> Detail Transaksi &amp; Donasi
                                    </span>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div class="sm:col-span-2">
                                            <label for="manual_campaign_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                                Program / Campaign Tujuan
                                            </label>
                                            <select id="manual_campaign_id" name="campaign_id" class="w-full min-h-[44px] bg-white border border-slate-300 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-800 focus:ring-2 focus:ring-emerald-600 outline-none transition">
                                                <option value="">-- Donasi Umum / Pilih Campaign Terdaftar --</option>
                                                @foreach($campaigns as $camp)
                                                    <option value="{{ $camp->id }}" {{ old('campaign_id') == $camp->id ? 'selected' : '' }}>
                                                        {{ $camp->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label for="manual_nominal" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                                Nominal Donasi (Rp) <span class="text-rose-700">*</span>
                                            </label>
                                            <div class="relative">
                                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-xs font-black text-slate-600">Rp</span>
                                                <input id="manual_nominal" type="number" name="nominal" value="{{ old('nominal') }}" min="1000" step="1000" required placeholder="100000" class="w-full min-h-[44px] bg-white border border-slate-300 rounded-xl pl-11 pr-4 py-2.5 text-sm font-bold text-slate-800 focus:ring-2 focus:ring-emerald-600 outline-none transition">
                                            </div>
                                        </div>

                                        <div>
                                            <label for="manual_via_bayar" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                                Metode Pembayaran (Via Bayar)
                                            </label>
                                            <select id="manual_via_bayar" name="via_bayar" class="w-full min-h-[44px] bg-white border border-slate-300 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-800 focus:ring-2 focus:ring-emerald-600 outline-none transition">
                                                <option value="Transfer BCA" {{ old('via_bayar') == 'Transfer BCA' ? 'selected' : '' }}>Transfer BCA</option>
                                                <option value="Transfer Mandiri" {{ old('via_bayar') == 'Transfer Mandiri' ? 'selected' : '' }}>Transfer Bank Mandiri</option>
                                                <option value="Transfer BSI" {{ old('via_bayar') == 'Transfer BSI' ? 'selected' : '' }}>Transfer BSI (Bank Syariah Indonesia)</option>
                                                <option value="Transfer BRI" {{ old('via_bayar') == 'Transfer BRI' ? 'selected' : '' }}>Transfer BRI</option>
                                                <option value="QRIS" {{ old('via_bayar') == 'QRIS' ? 'selected' : '' }}>QRIS / E-Wallet</option>
                                                <option value="Tunai" {{ old('via_bayar') == 'Tunai' ? 'selected' : '' }}>Tunai / Cash Langsung</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label for="manual_tanggal" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                                Tanggal Donasi <span class="text-rose-700">*</span>
                                            </label>
                                            <input id="manual_tanggal" type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required class="w-full min-h-[44px] bg-white border border-slate-300 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-800 focus:ring-2 focus:ring-emerald-600 outline-none transition">
                                        </div>

                                        <div>
                                            <label for="manual_jam" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                                Jam Donasi
                                            </label>
                                            <input id="manual_jam" type="time" name="jam" value="{{ old('jam', date('H:i')) }}" class="w-full min-h-[44px] bg-white border border-slate-300 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-800 focus:ring-2 focus:ring-emerald-600 outline-none transition">
                                        </div>

                                        <div>
                                            <label for="manual_status_bayar" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                                Status Pembayaran <span class="text-rose-700">*</span>
                                            </label>
                                            <select id="manual_status_bayar" name="status_bayar" required class="w-full min-h-[44px] bg-white border border-slate-300 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-800 focus:ring-2 focus:ring-emerald-600 outline-none transition">
                                                <option value="success" {{ old('status_bayar', 'success') === 'success' ? 'selected' : '' }}>Success (Berhasil / Lunas)</option>
                                                <option value="waiting" {{ old('status_bayar') === 'waiting' ? 'selected' : '' }}>Waiting (Menunggu Konfirmasi)</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label for="manual_catatan" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                                Catatan / Keterangan
                                            </label>
                                            <input id="manual_catatan" type="text" name="catatan" value="{{ old('catatan') }}" placeholder="Keterangan tambahan..." class="w-full min-h-[44px] bg-white border border-slate-300 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-800 focus:ring-2 focus:ring-emerald-600 outline-none transition">
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-2 flex justify-end">
                                    <button type="submit" class="min-h-[44px] bg-emerald-700 hover:bg-emerald-800 text-white font-black text-xs uppercase tracking-widest px-8 py-3.5 rounded-xl shadow-md transition-colors flex items-center gap-2 cursor-pointer">
                                        <i class="fa-solid fa-floppy-disk" aria-hidden="true"></i>
                                        Simpan Donasi Manual
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Right Col: Log Tarikan Terakhir & Donasi Terbaru -->
            <div class="space-y-6">
                <!-- Donasi Terbaru Masuk -->
                @if(isset($latestManual) && $latestManual->isNotEmpty())
                    <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-200">
                            <h2 class="text-xs font-montserrat font-black uppercase tracking-wider text-slate-800 flex items-center gap-2">
                                <i class="fa-solid fa-clock-rotate-left text-emerald-700" aria-hidden="true"></i> Donasi Terbaru Masuk
                            </h2>
                            <span class="text-xs font-bold text-slate-600">transaksi_donasi</span>
                        </div>
                        <div class="space-y-3">
                            @foreach($latestManual as $lm)
                                <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200 hover:bg-slate-100 transition space-y-1">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-slate-800 truncate max-w-[150px]">
                                            {{ $lm->donatur?->nama_lengkap ?: 'Donatur' }}
                                        </span>
                                        <span class="text-xs font-black text-emerald-800 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-md">
                                            Rp {{ number_format($lm->nominal, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between text-xs text-slate-600">
                                        <span class="font-mono text-primary-700 font-bold">{{ $lm->donasi_id }}</span>
                                        <span>{{ $lm->tanggal?->format('d M Y') }}</span>
                                    </div>
                                    <div class="text-xs text-slate-600 flex items-center gap-1.5 pt-0.5">
                                        <span class="px-1.5 py-0.5 rounded bg-slate-200 text-slate-800 font-black uppercase text-[11px]">{{ $lm->sumber_data }}</span>
                                        <span class="truncate">{{ $lm->campaign_title_raw }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-200">
                        <h2 class="text-xs font-montserrat font-black uppercase tracking-wider text-slate-800">
                            Log Tarikan Terakhir
                        </h2>
                        <span class="text-xs font-bold text-slate-600">Tabel kontrol_tarik</span>
                    </div>

                    @if($kontrolTarik->isEmpty())
                        <div class="text-center py-8 text-slate-600">
                            <i class="fa-regular fa-folder-open text-2xl mb-2 text-slate-400" aria-hidden="true"></i>
                            <p class="text-xs font-bold">Belum ada riwayat tarikan donasi.</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($kontrolTarik as $kt)
                                <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200 space-y-1.5 hover:bg-slate-100 transition">
                                    <div class="flex items-center justify-between">
                                        <span class="px-2 py-0.5 rounded-lg text-[11px] font-black uppercase tracking-wider {{ $kt->sumber === 'IM' ? 'bg-primary-50 text-primary-700' : ($kt->sumber === 'FH' ? 'bg-amber-50 text-amber-800' : 'bg-emerald-50 text-emerald-800') }}">
                                            {{ $kt->sumber }} &bull; {{ $kt->periode }}
                                        </span>
                                        <span class="text-xs font-bold text-slate-700">{{ number_format($kt->jml_baris) }} baris</span>
                                    </div>
                                    <div class="text-xs text-slate-600 font-medium">
                                        <span>Ditarik: {{ $kt->ditarik_oleh ?: 'Sistem' }}</span>
                                        <span class="text-slate-400 mx-1" aria-hidden="true">&bull;</span>
                                        <span>{{ $kt->tgl_tarik->format('d M Y H:i') }}</span>
                                    </div>
                                    @if($kt->id_pertama && $kt->id_terakhir)
                                        <div class="text-xs font-mono text-slate-600 truncate">
                                            ID: {{ $kt->id_pertama }} &rarr; {{ $kt->id_terakhir }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
@endsection

