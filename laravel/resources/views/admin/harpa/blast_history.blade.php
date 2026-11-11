@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50/60 pb-20">
    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-8 items-start min-w-0">
            <!-- Sidebar Menu -->
            <aside class="hidden lg:block space-y-6 shrink-0 w-[280px]">
                @include('admin.sidebar', ['active' => 'harpa_blast'])
            </aside>

            <!-- Main Content Area -->
            <div class="space-y-6 min-w-0">
                <!-- Top Header Bar -->
                <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-600 uppercase tracking-widest">
                            <span>Sistem Data Harpa</span>
                            <span aria-hidden="true">/</span>
                            <span class="text-primary-600 font-black">Alur B: Riwayat Blast Everpro</span>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-montserrat font-black text-slate-900 tracking-tight mt-1 flex items-center gap-2">
                            <i class="fa-solid fa-paper-plane text-primary-600" aria-hidden="true"></i> Riwayat Blast &amp; Diagnosa
                        </h1>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('admin.harpa.template.download', 'blast') }}" class="inline-flex items-center min-h-[44px] gap-2 px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-black text-xs uppercase tracking-wider shadow-sm transition-colors">
                            <i class="fa-solid fa-file-arrow-down text-emerald-300" aria-hidden="true"></i>
                            Unduh Template Blast (.CSV)
                        </a>
                        <button type="button" onclick="document.getElementById('modal-manual-blast').classList.remove('hidden')" class="inline-flex items-center min-h-[44px] gap-2 px-4 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-black text-xs uppercase tracking-wider shadow-sm transition-colors cursor-pointer">
                            <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i>
                            + Catat Blast Manual
                        </button>
                        <button type="button" onclick="document.getElementById('modal-upload-blast').classList.remove('hidden')" class="inline-flex items-center min-h-[44px] gap-2 px-5 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-wider shadow-sm transition-colors cursor-pointer">
                            <i class="fa-solid fa-cloud-arrow-up" aria-hidden="true"></i>
                            Unggah Ekspor Everpro
                        </button>
                    </div>
                </div>
        <!-- Flash Messages -->
        @if(session('success'))
            <div role="status" class="p-4 rounded-2xl bg-emerald-50 border border-emerald-300 text-emerald-800 text-xs font-bold flex items-center gap-3 shadow-sm">
                <i class="fa-solid fa-circle-check text-emerald-700 text-base" aria-hidden="true"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div role="alert" class="p-4 rounded-2xl bg-rose-50 border border-rose-300 text-rose-900 text-xs font-bold flex items-center gap-3 shadow-sm">
                <i class="fa-solid fa-circle-xmark text-rose-700 text-base" aria-hidden="true"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- 6 CEK DIAGNOSA INTEGRITAS (Sheet DIAGNOSA) -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                <div>
                    <h2 class="text-sm font-montserrat font-black uppercase tracking-wider text-slate-800 flex items-center gap-2">
                        <i class="fa-solid fa-stethoscope text-primary-600" aria-hidden="true"></i>
                        6 Pemeriksa Wajib Diagnosa Integritas (Sheet DIAGNOSA)
                    </h2>
                    <p class="text-xs text-slate-600 font-medium mt-0.5">Daftar kirim tidak boleh dipakai selama ada pemeriksaan yang gagal.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($diagnosa as $key => $check)
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 flex items-start gap-3">
                        <i class="fa-solid {{ $check['passed'] ? 'fa-check text-emerald-700' : 'fa-xmark text-rose-700' }} text-sm mt-0.5 shrink-0" aria-hidden="true"></i>
                        <div class="space-y-1">
                            <h3 class="text-xs font-bold text-slate-800 leading-snug">{{ $check['name'] }}</h3>
                            <p class="text-xs font-mono font-bold {{ $check['passed'] ? 'text-emerald-700' : 'text-rose-700' }}">
                                Hasil: {{ $check['value'] }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- TABEL DATA BLAST & FILTER -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
                <div>
                    <h2 class="text-sm font-montserrat font-black uppercase tracking-wider text-slate-800">
                        Daftar Pesan Riwayat Blast
                    </h2>
                    <p class="text-xs text-slate-600 font-medium mt-0.5">Total riwayat tersimpan: {{ number_format($blasts->total()) }} pesan</p>
                </div>

                <!-- Filters -->
                <form method="GET" action="{{ route('admin.harpa.blast_history') }}" class="flex flex-wrap items-center gap-2">
                    <input type="text" name="search" aria-label="Cari No HP atau Broadcast ID" value="{{ request('search') }}" placeholder="Cari No HP / Broadcast ID..." class="min-h-[44px] bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2 text-xs font-medium text-slate-700 placeholder:text-slate-500 outline-none focus:bg-white focus:ring-2 focus:ring-primary-600">
                    <select name="status" aria-label="Filter status blast" class="min-h-[44px] bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2 text-xs font-semibold text-slate-700 outline-none focus:border-primary-600">
                        <option value="">Semua Status</option>
                        <option value="READ" {{ request('status') === 'READ' ? 'selected' : '' }}>READ</option>
                        <option value="DELIVERED" {{ request('status') === 'DELIVERED' ? 'selected' : '' }}>DELIVERED</option>
                        <option value="SUCCESS" {{ request('status') === 'SUCCESS' ? 'selected' : '' }}>SUCCESS</option>
                        <option value="FAILED" {{ request('status') === 'FAILED' ? 'selected' : '' }}>FAILED</option>
                        <option value="LAINNYA" {{ request('status') === 'LAINNYA' ? 'selected' : '' }}>LAINNYA</option>
                    </select>
                    <button type="submit" class="min-h-[44px] px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-slate-800 transition">
                        Filter
                    </button>
                </form>
            </div>

            @if($blasts->isEmpty())
                <div class="text-center py-12 text-slate-600">
                    <i class="fa-regular fa-paper-plane text-3xl mb-2 text-slate-500" aria-hidden="true"></i>
                    <p class="text-xs font-bold">Belum ada data blast Everpro yang diunggah.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-xs font-black uppercase tracking-wider text-slate-600 border-b border-slate-200">
                            <tr>
                                <th class="py-3 px-4">Receiver Number</th>
                                <th class="py-3 px-4">Broadcast ID</th>
                                <th class="py-3 px-4">Batch Label</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4">Waktu Blast</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($blasts as $b)
                                <tr class="hover:bg-slate-50">
                                    <td class="py-3 px-4 font-mono font-bold text-slate-800">{{ $b->receiver_number }}</td>
                                    <td class="py-3 px-4 font-mono text-slate-600">{{ $b->broadcast_id }}</td>
                                    <td class="py-3 px-4 font-semibold text-slate-700">{{ $b->batch_tarik }}</td>
                                    <td class="py-3 px-4">
                                        <span class="text-xs font-bold uppercase tracking-wider
                                            {{ $b->status === 'READ' ? 'text-emerald-700' : '' }}
                                            {{ $b->status === 'DELIVERED' ? 'text-sky-700' : '' }}
                                            {{ $b->status === 'SUCCESS' ? 'text-[#063f5c]' : '' }}
                                            {{ $b->status === 'FAILED' ? 'text-rose-700' : '' }}
                                            {{ $b->status === 'LAINNYA' ? 'text-amber-800' : '' }}
                                        ">
                                            {{ $b->status }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-slate-600">{{ $b->timestamp_blast ? $b->timestamp_blast->format('d M Y H:i') : '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pt-4 border-t border-slate-200">
                    {{ $blasts->links() }}
                </div>
            @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Upload Everpro Blast -->
<div id="modal-upload-blast" role="dialog" aria-modal="true" aria-labelledby="modal-upload-blast-title" onclick="if(event.target === this) this.classList.add('hidden')" class="hidden fixed inset-0 z-50 bg-slate-900/75 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-8 space-y-6 shadow-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-3 border-b border-slate-200">
            <div>
                <h2 id="modal-upload-blast-title" class="text-base font-montserrat font-black text-slate-800">Unggah Ekspor Riwayat Blast Everpro</h2>
                <p class="text-xs text-slate-600 font-medium">Impor data status pengiriman pesan WhatsApp massal ke sistem Harpa</p>
            </div>
            <button type="button" onclick="document.getElementById('modal-upload-blast').classList.add('hidden')" aria-label="Tutup modal unggah blast" class="min-w-[44px] min-h-[44px] inline-flex items-center justify-center rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition">
                <i class="fa-solid fa-xmark text-lg" aria-hidden="true"></i>
            </button>
        </div>

        <!-- Download Template Banner -->
        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-start gap-2.5">
                <i class="fa-solid fa-file-csv text-[#063f5c] text-base mt-0.5 shrink-0" aria-hidden="true"></i>
                <div>
                    <h3 class="text-xs font-bold text-slate-900">Format File Riwayat Blast (.CSV)</h3>
                    <p class="text-xs text-slate-600 font-medium">Gunakan template resmi acuan agar kolom dan status terbaca akurat.</p>
                </div>
            </div>
            <a href="{{ route('admin.harpa.template.download', 'blast') }}" class="inline-flex items-center justify-center min-h-[44px] gap-2 px-4 py-2 rounded-xl bg-[#063f5c] hover:bg-primary-700 text-white font-black text-xs uppercase tracking-wider transition whitespace-nowrap">
                <i class="fa-solid fa-download" aria-hidden="true"></i>
                Unduh Template Blast
            </a>
        </div>

        <form action="{{ route('admin.harpa.blast.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="upload_blast_batch_type" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Jenis Broadcast (Batch Label)</label>
                    <select id="upload_blast_batch_type" name="batch_type" required class="w-full min-h-[44px] bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-700 outline-none focus:border-primary-600">
                        <option value="jumat">Selling Jumat (jumat-YYYY-MM-DD)</option>
                        <option value="payday">Payday Bulanan (payday-YYYY-MM-DD)</option>
                        <option value="perkenalan">Perkenalan Donatur Baru (perkenalan-YYYY-MM-DD)</option>
                    </select>
                </div>

                <div>
                    <label for="upload_blast_batch_date" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Tanggal Broadcast</label>
                    <input id="upload_blast_batch_date" type="date" name="batch_date" value="{{ date('Y-m-d') }}" required class="w-full min-h-[44px] bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-700 outline-none focus:border-primary-600">
                </div>
            </div>

            <div>
                <label for="upload_blast_file" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Pilih File Ekspor Everpro (.xlsx / .csv)</label>
                <input id="upload_blast_file" type="file" name="file" accept=".xlsx,.xls,.csv,.txt" required class="block w-full text-xs text-slate-700 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:bg-primary-600 file:text-white cursor-pointer">
                <p class="text-xs text-slate-600 mt-1">Kunci unik: nomor HP + BROADCAST ID. Duplikat akan otomatis ditolak.</p>
            </div>

            <!-- Panduan Format Kolom & Contoh Preview Data -->
            <div x-data="{ showBlastGuide: true }" class="rounded-2xl border border-slate-200 p-4 bg-slate-50 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-black uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-info text-primary-600" aria-hidden="true"></i>
                        Format 5 Kolom yang Wajib / Dikenali
                    </span>
                    <button type="button" @click="showBlastGuide = !showBlastGuide" :aria-expanded="showBlastGuide ? 'true' : 'false'" class="text-xs text-primary-700 font-bold hover:underline cursor-pointer">
                        <span x-text="showBlastGuide ? 'Tutup' : 'Lihat'"></span>
                    </button>
                </div>

                <div x-show="showBlastGuide" x-transition class="space-y-3">
                    <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-100 text-xs font-black uppercase tracking-wider text-slate-600 border-b border-slate-200">
                                <tr>
                                    <th class="py-2 px-3">Kolom</th>
                                    <th class="py-2 px-3">Alias</th>
                                    <th class="py-2 px-3 text-center">Status</th>
                                    <th class="py-2 px-3">Contoh Nilai</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 text-slate-700 font-medium">
                                <tr>
                                    <td class="py-1.5 px-3 font-mono font-bold text-primary-700">receiver_number</td>
                                    <td class="py-1.5 px-3 text-slate-600 font-mono text-xs">no_hp, receiver</td>
                                    <td class="py-1.5 px-3 text-center"><span class="text-xs font-bold text-rose-700">Wajib</span></td>
                                    <td class="py-1.5 px-3 font-mono font-bold text-emerald-700">6281234567890</td>
                                </tr>
                                <tr>
                                    <td class="py-1.5 px-3 font-mono font-bold text-primary-700">broadcast_id</td>
                                    <td class="py-1.5 px-3 text-slate-600 font-mono text-xs">id_broadcast, broadcast</td>
                                    <td class="py-1.5 px-3 text-center"><span class="text-xs font-bold text-rose-700">Wajib</span></td>
                                    <td class="py-1.5 px-3 font-mono text-slate-800">SELLING_JUMAT_20261106</td>
                                </tr>
                                <tr>
                                    <td class="py-1.5 px-3 font-mono font-bold text-primary-700">message_id</td>
                                    <td class="py-1.5 px-3 text-slate-600 font-mono text-xs">id_pesan</td>
                                    <td class="py-1.5 px-3 text-center"><span class="px-1.5 py-0.5 rounded text-[11px] font-bold bg-slate-200 text-slate-700">Opsional</span></td>
                                    <td class="py-1.5 px-3 font-mono text-slate-600">MSG-EVR-001</td>
                                </tr>
                                <tr>
                                    <td class="py-1.5 px-3 font-mono font-bold text-primary-700">status</td>
                                    <td class="py-1.5 px-3 text-slate-600 font-mono text-xs">delivery_status</td>
                                    <td class="py-1.5 px-3 text-center"><span class="text-xs font-bold text-rose-700">Wajib</span></td>
                                    <td class="py-1.5 px-3"><span class="font-bold text-sky-700">READ</span> / <span class="font-bold text-blue-700">DELIVERED</span> / <span class="font-bold text-rose-700">FAILED</span></td>
                                </tr>
                                <tr>
                                    <td class="py-1.5 px-3 font-mono font-bold text-primary-700">timestamp</td>
                                    <td class="py-1.5 px-3 text-slate-600 font-mono text-xs">time, waktu</td>
                                    <td class="py-1.5 px-3 text-center"><span class="text-xs font-bold text-rose-700">Wajib</span></td>
                                    <td class="py-1.5 px-3 font-mono text-slate-700">2026-11-06 09:15:30</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="pt-4 flex justify-end gap-2 border-t border-slate-200">
                <button type="button" onclick="document.getElementById('modal-upload-blast').classList.add('hidden')" class="min-h-[44px] px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold uppercase tracking-wider cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="min-h-[44px] px-6 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-700 text-white text-xs font-black uppercase tracking-widest shadow-sm cursor-pointer">
                    Unggah &amp; Periksa
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Catat Blast Manual -->
<div id="modal-manual-blast" role="dialog" aria-modal="true" aria-labelledby="modal-manual-blast-title" onclick="if(event.target === this) this.classList.add('hidden')" class="hidden fixed inset-0 z-50 bg-slate-900/75 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 space-y-6 shadow-2xl">
        <div class="flex items-center justify-between pb-3 border-b border-slate-200">
            <div>
                <h2 id="modal-manual-blast-title" class="text-base font-montserrat font-black text-slate-800">Catat Riwayat Blast Manual</h2>
                <p class="text-xs text-slate-600 font-medium">Input riwayat broadcast manual untuk satu nomor HP</p>
            </div>
            <button type="button" onclick="document.getElementById('modal-manual-blast').classList.add('hidden')" aria-label="Tutup modal catat blast manual" class="min-w-[44px] min-h-[44px] inline-flex items-center justify-center rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition">
                <i class="fa-solid fa-xmark text-lg" aria-hidden="true"></i>
            </button>
        </div>

        <form action="{{ route('admin.harpa.blast.manual') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="manual_blast_receiver" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Nomor WhatsApp / HP Penerima <span class="text-rose-700">*</span></label>
                <input id="manual_blast_receiver" type="text" name="receiver_number" required placeholder="08xxxxxxxxxx atau 628xxxxxxxxxx" class="w-full min-h-[44px] bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-800 outline-none focus:bg-white focus:ring-2 focus:ring-emerald-600">
                <span class="text-xs text-slate-600 mt-1 block">Otomatis dinormalisasi ke format 628 (11–14 digit).</span>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="manual_blast_type" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Jenis Broadcast</label>
                    <select id="manual_blast_type" name="batch_type" required class="w-full min-h-[44px] bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-700 outline-none focus:border-primary-600">
                        <option value="jumat">Selling Jumat</option>
                        <option value="payday">Payday Bulanan</option>
                        <option value="perkenalan">Perkenalan Donatur Baru</option>
                    </select>
                </div>
                <div>
                    <label for="manual_blast_date" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Tanggal Broadcast</label>
                    <input id="manual_blast_date" type="date" name="batch_date" value="{{ date('Y-m-d') }}" required class="w-full min-h-[44px] bg-slate-50 border border-slate-300 rounded-xl px-4 py-2 text-xs font-bold text-slate-700 outline-none focus:border-primary-600">
                </div>
            </div>

            <div>
                <label for="manual_blast_status" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Status Penerimaan Pesan <span class="text-rose-700">*</span></label>
                <select id="manual_blast_status" name="status" required class="w-full min-h-[44px] bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-800 outline-none focus:border-primary-600">
                    <option value="READ">READ (Dibaca oleh Kontak / Centang 2 Biru)</option>
                    <option value="DELIVERED">DELIVERED (Terkirim Sampai HP / Centang 2 Abu-abu)</option>
                    <option value="SUCCESS">SUCCESS (Terkirim dari Sistem)</option>
                    <option value="FAILED">FAILED (Gagal Terkirim / Nomor Tidak Aktif / Diblokir)</option>
                </select>
            </div>

            <div>
                <label for="manual_blast_catatan" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Catatan / Keterangan</label>
                <input id="manual_blast_catatan" type="text" name="catatan" placeholder="Keterangan singkat..." class="w-full min-h-[44px] bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 outline-none focus:border-primary-600">
            </div>

            <div class="pt-3 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modal-manual-blast').classList.add('hidden')" class="min-h-[44px] px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold uppercase tracking-wider">
                    Batal
                </button>
                <button type="submit" class="min-h-[44px] px-6 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-black uppercase tracking-widest shadow-sm cursor-pointer">
                    Simpan Riwayat Blast
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.getElementById('modal-upload-blast')?.classList.add('hidden');
        document.getElementById('modal-manual-blast')?.classList.add('hidden');
    }
});
</script>
@endsection

