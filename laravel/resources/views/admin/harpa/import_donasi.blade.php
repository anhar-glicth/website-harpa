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
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-widest">
                            <span>Sistem Data Harpa</span>
                            <span>/</span>
                            <span class="text-primary-600 font-black">Alur A: Masuk Data Donasi</span>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-montserrat font-black text-slate-900 tracking-tight mt-1">
                            Impor Data Donasi (FH, IM, WA)
                        </h1>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.harpa.papan_kerja') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-50 text-amber-700 hover:bg-amber-100 font-bold text-xs uppercase tracking-wider transition-all">
                            <i class="fa-solid fa-clipboard-check text-sm"></i>
                            Buka Papan Kerja
                        </a>
                    </div>
                </div>
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center gap-3 shadow-sm">
                <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('warning'))
            <div class="mb-6 p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 text-xs font-bold flex items-center gap-3 shadow-sm">
                <i class="fa-solid fa-triangle-exclamation text-amber-600 text-base"></i>
                <span>{{ session('warning') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold flex items-center gap-3 shadow-sm">
                <i class="fa-solid fa-circle-xmark text-rose-600 text-base"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left 2 Cols: Switcher Tabs (Bulk Upload / Manual Input) -->
            <div class="lg:col-span-2 space-y-6">
                <div x-data="{ activeTab: '{{ old('nama_lengkap') ? 'manual' : 'bulk' }}' }" class="space-y-6">
                    
                    <!-- Tab Switcher Header -->
                    <div class="flex flex-wrap items-center gap-2 p-1.5 bg-slate-200/80 rounded-2xl w-fit">
                        <button type="button" 
                                @click="activeTab = 'bulk'" 
                                :class="activeTab === 'bulk' ? 'bg-white text-primary-600 shadow-sm' : 'text-slate-600 hover:text-slate-900'"
                                class="flex items-center gap-2 px-5 py-2.5 rounded-xl font-montserrat font-black text-xs uppercase tracking-wider transition-all cursor-pointer">
                            <i class="fa-solid fa-file-excel text-emerald-600"></i>
                            Unggah File Excel/CSV (Massal)
                        </button>
                        <button type="button" 
                                @click="activeTab = 'manual'" 
                                :class="activeTab === 'manual' ? 'bg-white text-emerald-600 shadow-sm' : 'text-slate-600 hover:text-slate-900'"
                                class="flex items-center gap-2 px-5 py-2.5 rounded-xl font-montserrat font-black text-xs uppercase tracking-wider transition-all cursor-pointer">
                            <i class="fa-solid fa-pen-to-square text-primary-600"></i>
                            Input Donasi Manual (Satuan)
                        </button>
                    </div>

                    <!-- TAB 1: UNGGAH FILE EXCEL/CSV (MASSAL) -->
                    <div x-show="activeTab === 'bulk'" x-transition:enter="transition ease-out duration-200" class="space-y-8">
                        <!-- Upload Card -->
                        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm">
                            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                                <div class="w-10 h-10 rounded-2xl bg-primary-50 text-primary-600 flex items-center justify-center text-lg">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                </div>
                                <div>
                                    <h2 class="text-base font-montserrat font-black text-slate-800">Unggah File Ekspor Donasi</h2>
                                    <p class="text-xs text-slate-400 font-medium">Mendukung format .xlsx, .xls, .csv dari ForHumanity, Indonesia Madani, atau CRM WA</p>
                                </div>
                            </div>

                            <form action="{{ route('admin.harpa.import_donasi.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                                @csrf

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <!-- Sumber Data -->
                                    <div>
                                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">
                                            Sumber Data <span class="text-rose-500">*</span>
                                        </label>
                                        <select name="source" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs font-bold text-slate-700 focus:bg-white focus:ring-2 focus:ring-primary-500 outline-none transition">
                                            <option value="IM">Indonesia Madani (Web Sendiri - Bulanan)</option>
                                            <option value="FH">ForHumanity (Web Mitra - Bulanan)</option>
                                            <option value="WA">Catatan CRM WhatsApp (Mingguan)</option>
                                        </select>
                                    </div>

                                    <!-- Periode Kalender -->
                                    <div>
                                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">
                                            Periode Kalender (YYYY-MM) <span class="text-rose-500">*</span>
                                        </label>
                                        <input type="month" name="periode" value="{{ date('Y-m', strtotime('-1 month')) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-700 focus:bg-white focus:ring-2 focus:ring-primary-500 outline-none transition">
                                        <span class="text-[10px] text-slate-400 mt-1 block">Periode wajib satu bulan penuh untuk mencegah tumpang tindih.</span>
                                    </div>
                                </div>

                                <!-- File Input -->
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">
                                        Pilih File (.xlsx / .csv) <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="border-2 border-dashed border-slate-200 hover:border-primary-500 rounded-2xl p-6 text-center transition-colors bg-slate-50/50">
                                        <i class="fa-solid fa-file-excel text-3xl text-emerald-600 mb-2"></i>
                                        <input type="file" name="file" accept=".xlsx,.xls,.csv,.txt" required class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:bg-primary-600 file:text-white hover:file:bg-primary-700 cursor-pointer">
                                        <p class="text-[10px] text-slate-400 mt-2">Maksimal ukuran file: 20 MB. Data mentah akan disimpan apa adanya di database.</p>
                                    </div>
                                </div>

                                <!-- Checkbox Commit Langsung jika Lolos -->
                                <div class="flex items-start gap-3 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                    <input type="checkbox" id="commit_directly" name="commit_directly" value="1" checked class="mt-0.5 rounded text-primary-600 focus:ring-primary-500">
                                    <label for="commit_directly" class="text-xs text-slate-600 font-semibold cursor-pointer">
                                        <strong>Proses & Daftarkan Donatur Otomatis:</strong> Jika nomor belum pernah terdaftar, otomatis daftarkan ke <code class="text-primary-600 font-bold">master_donatur</code> (DNRxxxxxx) dan <code class="text-primary-600 font-bold">master_nomor</code> dalam satu transaksi database.
                                    </label>
                                </div>

                                <div class="pt-2 flex justify-end">
                                    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-widest px-8 py-3.5 rounded-xl shadow-lg shadow-primary-600/20 hover:shadow-primary-600/30 transition-all flex items-center gap-2 cursor-pointer">
                                        <i class="fa-solid fa-gears"></i>
                                        Periksa & Masukkan Data
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Info Aturan Data -->
                        <div class="bg-gradient-to-br from-slate-900 to-[#063f5c] text-white rounded-3xl p-6 sm:p-8 space-y-4 shadow-sm">
                            <div class="flex items-center gap-2 text-xs font-black uppercase tracking-widest text-[#f7ad1a]">
                                <i class="fa-solid fa-shield-halved"></i>
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
                        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm">
                            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                                <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                                    <i class="fa-solid fa-hand-holding-dollar"></i>
                                </div>
                                <div>
                                    <h2 class="text-base font-montserrat font-black text-slate-800">Formulir Input Donasi Manual</h2>
                                    <p class="text-xs text-slate-400 font-medium">Input langsung transaksi donasi satuan. Otomatis mendaftarkan donatur & terintegrasi ke Rekap Waterfall Harpa</p>
                                </div>
                            </div>

                            <form action="{{ route('admin.harpa.import_donasi.manual') }}" method="POST" class="space-y-5">
                                @csrf

                                <!-- Section: Informasi Donatur -->
                                <div class="bg-slate-50/70 p-4 sm:p-5 rounded-2xl border border-slate-100 space-y-4">
                                    <span class="text-[11px] font-black uppercase tracking-wider text-slate-500 flex items-center gap-2">
                                        <i class="fa-solid fa-user-tag text-primary-600"></i> Data Donatur
                                    </span>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                                                Nama Lengkap Donatur <span class="text-rose-500">*</span>
                                            </label>
                                            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required placeholder="Contoh: Bpk. H. Rahmat Hidayat" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-800 focus:ring-2 focus:ring-emerald-500 outline-none transition">
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                                                Nomor WhatsApp / HP <span class="text-rose-500">*</span>
                                            </label>
                                            <input type="text" name="no_hp" value="{{ old('no_hp') }}" required placeholder="08xxxxxxxxxx atau 628xxxxxxxxxx" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-800 focus:ring-2 focus:ring-emerald-500 outline-none transition">
                                            <span class="text-[10px] text-slate-400 mt-1 block">Otomatis dinormalisasi ke format 628 (11–14 digit).</span>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                                                Email Donatur <span class="text-slate-400 font-normal">(Opsional)</span>
                                            </label>
                                            <input type="email" name="email" value="{{ old('email') }}" placeholder="donatur@email.com" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-800 focus:ring-2 focus:ring-emerald-500 outline-none transition">
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                                                Sumber Data / Saluran <span class="text-rose-500">*</span>
                                            </label>
                                            <select name="sumber_data" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-800 focus:ring-2 focus:ring-emerald-500 outline-none transition">
                                                <option value="WA" {{ old('sumber_data') === 'WA' ? 'selected' : '' }}>Catatan CRM WhatsApp (WA)</option>
                                                <option value="IM" {{ old('sumber_data') === 'IM' ? 'selected' : '' }}>Indonesia Madani (IM)</option>
                                                <option value="FH" {{ old('sumber_data') === 'FH' ? 'selected' : '' }}>ForHumanity (FH)</option>
                                                <option value="MANUAL" {{ old('sumber_data') === 'MANUAL' ? 'selected' : '' }}>Langsung / Kantor Harpa (MANUAL)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Section: Informasi Transaksi -->
                                <div class="bg-slate-50/70 p-4 sm:p-5 rounded-2xl border border-slate-100 space-y-4">
                                    <span class="text-[11px] font-black uppercase tracking-wider text-slate-500 flex items-center gap-2">
                                        <i class="fa-solid fa-receipt text-emerald-600"></i> Detail Transaksi & Donasi
                                    </span>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div class="sm:col-span-2">
                                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                                                Program / Campaign Tujuan
                                            </label>
                                            <select name="campaign_id" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-800 focus:ring-2 focus:ring-emerald-500 outline-none transition">
                                                <option value="">-- Donasi Umum / Pilih Campaign Terdaftar --</option>
                                                @foreach($campaigns as $camp)
                                                    <option value="{{ $camp->id }}" {{ old('campaign_id') == $camp->id ? 'selected' : '' }}>
                                                        {{ $camp->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                                                Nominal Donasi (Rp) <span class="text-rose-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <span class="absolute left-4 top-2.5 text-xs font-black text-slate-400">Rp</span>
                                                <input type="number" name="nominal" value="{{ old('nominal') }}" min="1000" step="1000" required placeholder="100000" class="w-full bg-white border border-slate-200 rounded-xl pl-11 pr-4 py-2.5 text-xs font-bold text-slate-800 focus:ring-2 focus:ring-emerald-500 outline-none transition">
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                                                Metode Pembayaran (Via Bayar)
                                            </label>
                                            <select name="via_bayar" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-800 focus:ring-2 focus:ring-emerald-500 outline-none transition">
                                                <option value="Transfer BCA" {{ old('via_bayar') == 'Transfer BCA' ? 'selected' : '' }}>Transfer BCA</option>
                                                <option value="Transfer Mandiri" {{ old('via_bayar') == 'Transfer Mandiri' ? 'selected' : '' }}>Transfer Bank Mandiri</option>
                                                <option value="Transfer BSI" {{ old('via_bayar') == 'Transfer BSI' ? 'selected' : '' }}>Transfer BSI (Bank Syariah Indonesia)</option>
                                                <option value="Transfer BRI" {{ old('via_bayar') == 'Transfer BRI' ? 'selected' : '' }}>Transfer BRI</option>
                                                <option value="QRIS" {{ old('via_bayar') == 'QRIS' ? 'selected' : '' }}>QRIS / E-Wallet</option>
                                                <option value="Tunai" {{ old('via_bayar') == 'Tunai' ? 'selected' : '' }}>Tunai / Cash Langsung</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                                                Tanggal Donasi <span class="text-rose-500">*</span>
                                            </label>
                                            <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-800 focus:ring-2 focus:ring-emerald-500 outline-none transition">
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                                                Jam Donasi
                                            </label>
                                            <input type="time" name="jam" value="{{ old('jam', date('H:i')) }}" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-800 focus:ring-2 focus:ring-emerald-500 outline-none transition">
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                                                Status Pembayaran <span class="text-rose-500">*</span>
                                            </label>
                                            <select name="status_bayar" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-800 focus:ring-2 focus:ring-emerald-500 outline-none transition">
                                                <option value="success" {{ old('status_bayar', 'success') === 'success' ? 'selected' : '' }}>Success (Berhasil / Lunas)</option>
                                                <option value="waiting" {{ old('status_bayar') === 'waiting' ? 'selected' : '' }}>Waiting (Menunggu Konfirmasi)</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                                                Catatan / Keterangan
                                            </label>
                                            <input type="text" name="catatan" value="{{ old('catatan') }}" placeholder="Keterangan tambahan..." class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-800 focus:ring-2 focus:ring-emerald-500 outline-none transition">
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-2 flex justify-end">
                                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs uppercase tracking-widest px-8 py-3.5 rounded-xl shadow-lg shadow-emerald-600/20 hover:shadow-emerald-600/30 transition-all flex items-center gap-2 cursor-pointer">
                                        <i class="fa-solid fa-floppy-disk"></i>
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
                    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm">
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                            <h3 class="text-xs font-montserrat font-black uppercase tracking-wider text-slate-800 flex items-center gap-2">
                                <i class="fa-solid fa-clock-rotate-left text-emerald-600"></i> Donasi Terbaru Masuk
                            </h3>
                            <span class="text-[10px] font-bold text-slate-400">transaksi_donasi</span>
                        </div>
                        <div class="space-y-3">
                            @foreach($latestManual as $lm)
                                <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100 hover:bg-slate-100/70 transition space-y-1">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-slate-800 truncate max-w-[150px]">
                                            {{ $lm->donatur?->nama_lengkap ?: 'Donatur' }}
                                        </span>
                                        <span class="text-[10px] font-black text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md">
                                            Rp {{ number_format($lm->nominal, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between text-[10px] text-slate-400">
                                        <span class="font-mono text-primary-600 font-bold">{{ $lm->donasi_id }}</span>
                                        <span>{{ $lm->tanggal?->format('d M Y') }}</span>
                                    </div>
                                    <div class="text-[9px] text-slate-500 flex items-center gap-1.5 pt-0.5">
                                        <span class="px-1.5 py-0.5 rounded bg-slate-200 text-slate-700 font-black uppercase text-[8px]">{{ $lm->sumber_data }}</span>
                                        <span class="truncate">{{ $lm->campaign_title_raw }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                        <h3 class="text-xs font-montserrat font-black uppercase tracking-wider text-slate-800">
                            Log Tarikan Terakhir
                        </h3>
                        <span class="text-[10px] font-bold text-slate-400">Tabel kontrol_tarik</span>
                    </div>

                    @if($kontrolTarik->isEmpty())
                        <div class="text-center py-8 text-slate-400">
                            <i class="fa-regular fa-folder-open text-2xl mb-2"></i>
                            <p class="text-xs font-bold">Belum ada riwayat tarikan donasi.</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($kontrolTarik as $kt)
                                <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-100 space-y-1.5 hover:bg-slate-100/70 transition">
                                    <div class="flex items-center justify-between">
                                        <span class="px-2 py-0.5 rounded-lg text-[9px] font-black uppercase tracking-wider {{ $kt->sumber === 'IM' ? 'bg-primary-50 text-primary-700' : ($kt->sumber === 'FH' ? 'bg-amber-50 text-amber-700' : 'bg-emerald-50 text-emerald-700') }}">
                                            {{ $kt->sumber }} &bull; {{ $kt->periode }}
                                        </span>
                                        <span class="text-[10px] font-bold text-slate-700">{{ number_format($kt->jml_baris) }} baris</span>
                                    </div>
                                    <div class="text-[10px] text-slate-500 font-medium">
                                        <span>Ditarik: {{ $kt->ditarik_oleh ?: 'Sistem' }}</span>
                                        <span class="text-slate-300 mx-1">&bull;</span>
                                        <span>{{ $kt->tgl_tarik->format('d M Y H:i') }}</span>
                                    </div>
                                    @if($kt->id_pertama && $kt->id_terakhir)
                                        <div class="text-[9px] font-mono text-slate-400 truncate">
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

