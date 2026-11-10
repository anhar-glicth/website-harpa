@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50/60 pb-20">
    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-8 items-start min-w-0">
            <!-- Sidebar Menu -->
            <aside class="hidden lg:block space-y-6 shrink-0 w-[280px]">
                @include('admin.sidebar', ['active' => 'harpa_wbs'])
            </aside>

            <!-- Main Content Area -->
            <div class="space-y-6 min-w-0">
                <!-- Top Header Bar -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-widest">
                            <span>Sistem Data Harpa</span>
                            <span>/</span>
                            <span class="text-primary-600 font-black">Alur E: Cek Nomor WhatsApp</span>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-montserrat font-black text-slate-900 tracking-tight mt-1 flex items-center gap-2">
                            <i class="fa-solid fa-phone-slash text-rose-500"></i> Nomor Mati & Hasil WBS Pro
                        </h1>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.harpa.template.download', 'wbs') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-black text-xs uppercase tracking-wider shadow-md transition-all">
                            <i class="fa-solid fa-file-arrow-down text-emerald-400"></i>
                            Unduh Template WBS (.CSV)
                        </a>
                        <button onclick="document.getElementById('modal-manual-wbs').classList.remove('hidden')" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs uppercase tracking-wider shadow-md transition-all cursor-pointer">
                            <i class="fa-solid fa-pen-to-square"></i>
                            + Tandai Nomor Manual
                        </button>
                        <button onclick="document.getElementById('modal-upload-wbs').classList.remove('hidden')" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-black text-xs uppercase tracking-wider shadow-md transition-all cursor-pointer">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                            Unggah Hasil WBS Pro
                        </button>
                    </div>
                </div>
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center gap-3 shadow-sm">
                <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold flex items-center gap-3 shadow-sm">
                <i class="fa-solid fa-circle-xmark text-rose-600 text-base"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- STATS & INFO ALUR E -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-2xl shrink-0">
                    <i class="fa-solid fa-ban"></i>
                </div>
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Total Nomor Mati Tercatat</span>
                    <h3 class="text-2xl font-montserrat font-black text-slate-900 mt-0.5">{{ number_format($totalDead) }}</h3>
                    <p class="text-[10px] text-slate-400 font-semibold mt-0.5">Dikeluarkan otomatis dari daftar kirim</p>
                </div>
            </div>

            <div class="md:col-span-2 bg-gradient-to-br from-slate-900 to-[#063f5c] text-white rounded-3xl p-6 flex flex-col justify-between shadow-sm">
                <div class="space-y-1.5">
                    <span class="text-[9px] font-black uppercase tracking-widest text-[#f7ad1a]">Aturan Alur E (WBS Pro)</span>
                    <h4 class="text-sm font-bold leading-relaxed">
                        Nomor yang tidak aktif di WhatsApp otomatis ditandai <code class="text-[#f7ad1a] bg-black/20 px-1.5 py-0.5 rounded">wa_aktif = 0</code> dan tidak pernah dihapus.
                    </h4>
                    <p class="text-xs text-slate-300">
                        Nomor mati otomatis dikeluarkan dari KIRIM_JUMAT, KIRIM_PAYDAY, KIRIM_PERKENALAN, dan memicu ALERT agar CRM meminta nomor baru jika pemiliknya adalah donatur.
                    </p>
                </div>
            </div>
        </div>

        <!-- TABEL DAFTAR NOMOR MATI -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-sm font-montserrat font-black uppercase tracking-wider text-slate-800">
                        Daftar Nomor Tidak Aktif (wa_aktif = 0)
                    </h3>
                    <p class="text-xs text-slate-400 font-medium">Nomor-nomor ini aman dari pengiriman pesan dan menunggu tindak lanjut CRM jika memiliki transaksi.</p>
                </div>
            </div>

            @if($deadNumbers->isEmpty())
                <div class="text-center py-12 text-slate-400">
                    <i class="fa-regular fa-circle-check text-3xl mb-2 text-emerald-500"></i>
                    <p class="text-xs font-bold text-slate-600">Tidak ada nomor mati yang tercatat.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-[10px] font-black uppercase tracking-wider text-slate-500 border-b border-slate-100">
                            <tr>
                                <th class="py-3 px-4">Nomor HP</th>
                                <th class="py-3 px-4">Donatur ID</th>
                                <th class="py-3 px-4">Nama Donatur</th>
                                <th class="py-3 px-4">Status Nomor</th>
                                <th class="py-3 px-4">WA Aktif</th>
                                <th class="py-3 px-4">Terakhir Diperbarui</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($deadNumbers as $dn)
                                <tr class="hover:bg-slate-50/50">
                                    <td class="py-3 px-4 font-mono font-bold text-rose-600">{{ $dn->no_hp }}</td>
                                    <td class="py-3 px-4 font-mono text-primary-600 font-bold">{{ $dn->donatur_id ?: '-' }}</td>
                                    <td class="py-3 px-4 font-semibold text-slate-800">{{ $dn->donatur ? $dn->donatur->nama_lengkap : '-' }}</td>
                                    <td class="py-3 px-4">
                                        <span class="px-2 py-0.5 rounded-md text-[9px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600">
                                            {{ $dn->status_nomor }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider bg-rose-100 text-rose-800">
                                            0 (Mati)
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-slate-400">{{ $dn->updated_at ? $dn->updated_at->format('d M Y H:i') : '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pt-4 border-t border-slate-100">
                    {{ $deadNumbers->links() }}
                </div>
            @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Upload WBS Pro -->
<div id="modal-upload-wbs" class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-8 space-y-6 shadow-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h3 class="text-base font-montserrat font-black text-slate-800">Unggah Hasil WBS Pro (Cek Nomor WhatsApp)</h3>
                <p class="text-xs text-slate-400 font-medium">Impor data sapuan nomor WhatsApp aktif & tidak aktif (mati)</p>
            </div>
            <button onclick="document.getElementById('modal-upload-wbs').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Download Template Banner -->
        <div class="p-4 rounded-2xl bg-emerald-50/70 border border-emerald-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center text-lg shrink-0 shadow-sm">
                    <i class="fa-solid fa-file-csv"></i>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-slate-800">Format File Cek Nomor WBS (.CSV)</h4>
                    <p class="text-[11px] text-slate-500 font-medium">Gunakan template resmi untuk format kolom nomor HP dan status keaktifan.</p>
                </div>
            </div>
            <a href="{{ route('admin.harpa.template.download', 'wbs') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs uppercase tracking-wider shadow-sm transition whitespace-nowrap">
                <i class="fa-solid fa-download"></i>
                Unduh Template WBS
            </a>
        </div>

        <form action="{{ route('admin.harpa.wbs.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Label Sapuan (Batch Label)</label>
                <input type="text" name="batch_label" value="wbs-sapuan-{{ date('Y-m-d') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-700 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Pilih File Hasil WBS Pro (.xlsx / .csv)</label>
                <input type="file" name="file" accept=".xlsx,.xls,.csv,.txt" required class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:bg-rose-600 file:text-white cursor-pointer">
                <p class="text-[10px] text-slate-400 mt-1">Unggah file utuh dari WBS Pro; sistem otomatis mengenali nomor yang tidak aktif/mati.</p>
            </div>

            <!-- Panduan Format Kolom & Contoh Preview Data WBS -->
            <div x-data="{ showWbsGuide: true }" class="rounded-2xl border border-slate-200/80 p-4 bg-slate-50/70 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-black uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-info text-rose-500"></i>
                        Format 2 Kolom Standar WBS Pro
                    </span>
                    <button type="button" @click="showWbsGuide = !showWbsGuide" class="text-[10px] text-primary-600 font-bold hover:underline cursor-pointer">
                        <span x-text="showWbsGuide ? 'Tutup' : 'Lihat'"></span>
                    </button>
                </div>

                <div x-show="showWbsGuide" x-transition class="space-y-3">
                    <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white">
                        <table class="w-full text-left text-[11px]">
                            <thead class="bg-slate-100 text-[10px] font-black uppercase tracking-wider text-slate-600 border-b border-slate-200">
                                <tr>
                                    <th class="py-2 px-3">Kolom</th>
                                    <th class="py-2 px-3">Alias Header</th>
                                    <th class="py-2 px-3 text-center">Status</th>
                                    <th class="py-2 px-3">Aturan Nilai</th>
                                    <th class="py-2 px-3">Contoh Nilai</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                                <tr>
                                    <td class="py-1.5 px-3 font-mono font-bold text-primary-600">no_hp</td>
                                    <td class="py-1.5 px-3 text-slate-500 font-mono text-[10px]">phone, nomor, whatsapp</td>
                                    <td class="py-1.5 px-3 text-center"><span class="px-1.5 py-0.5 rounded text-[9px] font-black bg-rose-100 text-rose-700">Wajib</span></td>
                                    <td class="py-1.5 px-3 text-slate-600 text-[10px]">Format 628 atau 08 (11–14 digit)</td>
                                    <td class="py-1.5 px-3 font-mono font-bold text-slate-800">081234567890</td>
                                </tr>
                                <tr>
                                    <td class="py-1.5 px-3 font-mono font-bold text-primary-600">status</td>
                                    <td class="py-1.5 px-3 text-slate-500 font-mono text-[10px]">wa_status, status_wa</td>
                                    <td class="py-1.5 px-3 text-center"><span class="px-1.5 py-0.5 rounded text-[9px] font-black bg-rose-100 text-rose-700">Wajib</span></td>
                                    <td class="py-1.5 px-3 text-slate-600 text-[10px]">Kata kunci "mati", "invalid", "tidak aktif", "bukan wa", atau "aktif"</td>
                                    <td class="py-1.5 px-3"><span class="px-1.5 py-0.5 rounded text-[9px] font-black bg-rose-100 text-rose-700">Mati / Invalid</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="pt-4 flex justify-end gap-2 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('modal-upload-wbs').classList.add('hidden')" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold uppercase tracking-wider cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-black uppercase tracking-widest shadow-md cursor-pointer">
                    Proses Hasil WBS
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Tandai Nomor Mati / Aktif Manual -->
<div id="modal-manual-wbs" class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 space-y-6 shadow-2xl">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h3 class="text-base font-montserrat font-black text-slate-800">Tandai Status Nomor Manual</h3>
                <p class="text-xs text-slate-400 font-medium">Ubah status WhatsApp nomor donatur secara langsung</p>
            </div>
            <button onclick="document.getElementById('modal-manual-wbs').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form action="{{ route('admin.harpa.wbs.manual') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Nomor WhatsApp / HP <span class="text-rose-500">*</span></label>
                <input type="text" name="no_hp" required placeholder="08xxxxxxxxxx atau 628xxxxxxxxxx" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-800 outline-none focus:bg-white focus:ring-2 focus:ring-rose-500">
                <span class="text-[10px] text-slate-400 mt-1 block">Otomatis dinormalisasi ke format 628 (11–14 digit).</span>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Status WhatsApp <span class="text-rose-500">*</span></label>
                <select name="status_wa" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-800 outline-none">
                    <option value="0">❌ Nomor Mati / Hangus / Tidak Terdaftar di WhatsApp</option>
                    <option value="1">✅ Nomor Aktif / Sudah Hidup Kembali</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Tanggal Pengecekan</label>
                <input type="date" name="tgl_cek" value="{{ date('Y-m-d') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-xs font-bold text-slate-700 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Catatan / Alasan</label>
                <input type="text" name="catatan" placeholder="Contoh: Nomor tidak aktif saat dihubungi telepon seluler..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-medium text-slate-700 outline-none">
            </div>

            <div class="pt-3 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modal-manual-wbs').classList.add('hidden')" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold uppercase tracking-wider">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-black uppercase tracking-widest shadow-md cursor-pointer">
                    Simpan Status Nomor
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

