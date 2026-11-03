@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50/60 pb-20">
    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-8 items-start min-w-0">
            <!-- Sidebar Menu -->
            <aside class="hidden lg:block space-y-6 shrink-0 w-[280px]">
                @include('admin.sidebar', ['active' => 'harpa_papan_kerja'])
            </aside>

            <!-- Main Content Area -->
            <div class="space-y-6 min-w-0">
                <!-- Top Header Bar -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-widest">
                            <span>Sistem Data Harpa</span>
                            <span>/</span>
                            <span class="text-primary-600 font-black">Gerbang Berhenti</span>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-montserrat font-black text-slate-900 tracking-tight mt-1 flex items-center gap-2">
                            <i class="fa-solid fa-clipboard-check text-[#f7ad1a]"></i> Papan Kerja & Persetujuan
                        </h1>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.harpa.import_donasi') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs uppercase tracking-wider transition-all">
                            <i class="fa-solid fa-arrow-left text-sm"></i>
                            Kembali ke Impor
                        </a>
                    </div>
                </div>
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center gap-3 shadow-sm">
                <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('info'))
            <div class="p-4 rounded-2xl bg-sky-50 border border-sky-200 text-sky-800 text-xs font-bold flex items-center gap-3 shadow-sm">
                <i class="fa-solid fa-circle-info text-sky-600 text-base"></i>
                <span>{{ session('info') }}</span>
            </div>
        @endif
        @if(session('warning'))
            <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 text-xs font-bold flex items-center gap-3 shadow-sm">
                <i class="fa-solid fa-triangle-exclamation text-amber-600 text-base"></i>
                <span>{{ session('warning') }}</span>
            </div>
        @endif

        @if(!empty($pendingData))
            <!-- STATUS BANNER GERBANG BERHENTI -->
            <div class="p-6 sm:p-8 rounded-3xl border {{ $pendingData['status'] === 'STOP' ? 'bg-rose-50 border-rose-200' : 'bg-emerald-50 border-emerald-200' }} shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl {{ $pendingData['status'] === 'STOP' ? 'bg-rose-600' : 'bg-emerald-600' }} text-white flex items-center justify-center text-xl shrink-0">
                            <i class="fa-solid {{ $pendingData['status'] === 'STOP' ? 'fa-hand' : 'fa-circle-check' }}"></i>
                        </div>
                        <div>
                            <span class="inline-block px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest {{ $pendingData['status'] === 'STOP' ? 'bg-rose-100 text-rose-800' : 'bg-emerald-100 text-emerald-800' }}">
                                STATUS: {{ $pendingData['status'] }}
                            </span>
                            <h2 class="text-base sm:text-lg font-montserrat font-black text-slate-800 mt-1">
                                Tarikan {{ $pendingData['sumber'] }} Periode {{ $pendingData['periode'] }} ({{ number_format($pendingData['total_rows']) }} Baris)
                            </h2>
                            <p class="text-xs text-slate-500 font-medium mt-0.5">
                                {{ $pendingData['status'] === 'STOP' ? 'Pemeriksaan menemukan kendala yang harus diselesaikan sebelum data masuk ke transaksi_donasi.' : 'Semua pemeriksaan lolos! Data siap disimpan permanen.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TABEL PEMERIKSAAN GERBANG BERHENTI -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Check 1: Nomor Baru -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Nomor Belum Terdaftar</span>
                        <span class="px-2 py-0.5 rounded-md text-[10px] font-black {{ count($pendingData['unregistered_numbers'] ?? []) > 0 ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' }}">
                            {{ count($pendingData['unregistered_numbers'] ?? []) }}
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-400 font-medium">Nomor valid yang belum memiliki donatur_id.</p>
                </div>

                <!-- Check 2: Judul Campaign Belum Dipetakan -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Campaign Belum Dipetakan</span>
                        <span class="px-2 py-0.5 rounded-md text-[10px] font-black {{ count($pendingData['unmapped_campaigns'] ?? []) > 0 ? 'bg-rose-100 text-rose-800' : 'bg-emerald-100 text-emerald-800' }}">
                            {{ count($pendingData['unmapped_campaigns'] ?? []) }}
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-400 font-medium">Judul ejaan baru yang belum ada di ref_campaign_alias.</p>
                </div>

                <!-- Check 3: Tanggal Gagal Dibaca -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal Gagal Dibaca</span>
                        <span class="px-2 py-0.5 rounded-md text-[10px] font-black {{ count($pendingData['date_errors'] ?? []) > 0 ? 'bg-rose-100 text-rose-800' : 'bg-emerald-100 text-emerald-800' }}">
                            {{ count($pendingData['date_errors'] ?? []) }}
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-400 font-medium">Format tanggal rusak yang menghentikan proses.</p>
                </div>
            </div>

            <!-- RESOLVE: NOMOR BELUM TERDAFTAR -->
            @if(!empty($pendingData['unregistered_numbers']))
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div>
                            <h3 class="text-sm font-montserrat font-black uppercase tracking-wider text-slate-800">
                                1. Setujui Pendaftaran Donatur Baru
                            </h3>
                            <p class="text-xs text-slate-400 font-medium">Setiap nomor unik akan otomatis dibuatkan donatur_id baru (DNRxxxxxx).</p>
                        </div>
                        <span class="text-xs font-bold text-primary-600">{{ count($pendingData['unregistered_numbers']) }} Nomor</span>
                    </div>

                    <div class="max-h-80 overflow-y-auto space-y-2 pr-2">
                        @foreach($pendingData['unregistered_numbers'] as $phone => $name)
                            <div class="flex items-center justify-between p-3.5 bg-slate-50 rounded-2xl border border-slate-100">
                                <div>
                                    <span class="font-mono text-xs font-bold text-slate-800">{{ $phone }}</span>
                                    <span class="text-slate-400 text-xs mx-2">&bull;</span>
                                    <span class="text-xs font-semibold text-slate-600">{{ $name }}</span>
                                </div>
                                <form action="{{ route('admin.harpa.papan_kerja.approve') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="action" value="register_donor">
                                    <input type="hidden" name="phone" value="{{ $phone }}">
                                    <input type="hidden" name="name" value="{{ $name }}">
                                    <button type="submit" class="px-4 py-1.5 rounded-xl bg-primary-600 hover:bg-primary-700 text-white text-[11px] font-bold uppercase tracking-wider shadow-sm transition">
                                        Daftarkan
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- RESOLVE: CAMPAIGN BELUM DIPETAKAN -->
            @if(!empty($pendingData['unmapped_campaigns']))
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div>
                            <h3 class="text-sm font-montserrat font-black uppercase tracking-wider text-slate-800">
                                2. Petakan Judul Campaign Baru ke Master Campaign
                            </h3>
                            <p class="text-xs text-slate-400 font-medium">Ejaan judul ekspor tidak diubah; cukup daftarkan ejaannya sebagai alias.</p>
                        </div>
                        <span class="text-xs font-bold text-rose-600">{{ count($pendingData['unmapped_campaigns']) }} Judul</span>
                    </div>

                    <div class="space-y-3">
                        @foreach($pendingData['unmapped_campaigns'] as $rawTitle)
                            <form action="{{ route('admin.harpa.papan_kerja.approve') }}" method="POST" class="flex flex-col sm:flex-row sm:items-center gap-3 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                @csrf
                                <input type="hidden" name="action" value="map_alias">
                                <input type="hidden" name="teks_asli" value="{{ $rawTitle }}">
                                <div class="flex-1">
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Teks Asli di File Ekspor:</span>
                                    <span class="text-xs font-bold text-slate-800">"{{ $rawTitle }}"</span>
                                </div>
                                <div class="flex-1">
                                    <select name="campaign_id" required class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-primary-500 outline-none">
                                        <option value="">-- Hubungkan ke Campaign Resmi --</option>
                                        @foreach($campaigns as $camp)
                                            <option value="{{ $camp->id }}">{{ $camp->title }} (ID: {{ $camp->id }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <button type="submit" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black uppercase tracking-wider shadow-sm transition">
                                        Simpan Alias
                                    </button>
                                </div>
                            </form>
                        @endforeach
                    </div>
                </div>
            @endif
        @else
            <!-- KONDISI TENANG: TIDAK ADA ANTREAN AKTIF -->
            <div class="bg-white rounded-3xl border border-slate-200/80 p-8 text-center space-y-4 shadow-sm">
                <div class="w-16 h-16 rounded-3xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl mx-auto">
                    <i class="fa-solid fa-check-double"></i>
                </div>
                <div class="max-w-md mx-auto space-y-1">
                    <h3 class="text-base font-montserrat font-black text-slate-800">Papan Kerja Bersih (Tidak Ada Antrean)</h3>
                    <p class="text-xs text-slate-400 font-medium">Semua data donasi yang telah diimpor sebelumnya telah lolos verifikasi dan tersimpan rapi di database.</p>
                </div>
                <div class="pt-2">
                    <a href="{{ route('admin.harpa.import_donasi') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-widest shadow-md transition">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        Unggah Data Donasi Baru
                    </a>
                </div>
            </div>
        @endif

        <!-- DAFTAR TRANSAKSI TANPA-NOMOR (BISA DIPERBAIKI) -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-sm font-montserrat font-black uppercase tracking-wider text-slate-800 flex items-center gap-2">
                        <i class="fa-solid fa-triangle-exclamation text-amber-500"></i>
                        Daftar Transaksi "TANPA-NOMOR" (Bisa Diperbaiki)
                    </h3>
                    <p class="text-xs text-slate-400 font-medium">Sesuai aturan SOP, transaksi tanpa nomor tetap disimpan dan tidak boleh digugurkan.</p>
                </div>
                <span class="text-xs font-bold text-slate-500">{{ $tanpaNomorTransactions->count() }} Transaksi</span>
            </div>

            @if($tanpaNomorTransactions->isEmpty())
                <p class="text-xs text-slate-400 py-4 text-center">Tidak ada transaksi yang berstatus TANPA-NOMOR.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-[10px] font-black uppercase tracking-wider text-slate-500 border-b border-slate-100">
                            <tr>
                                <th class="py-3 px-4">Donasi ID</th>
                                <th class="py-3 px-4">Tanggal</th>
                                <th class="py-3 px-4">Nominal</th>
                                <th class="py-3 px-4">Campaign</th>
                                <th class="py-3 px-4">Nomor Input Asli</th>
                                <th class="py-3 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($tanpaNomorTransactions as $tx)
                                <tr class="hover:bg-slate-50/50">
                                    <td class="py-3 px-4 font-mono font-bold text-primary-600">{{ $tx->donasi_id }}</td>
                                    <td class="py-3 px-4 text-slate-500">{{ $tx->tanggal->format('d M Y') }}</td>
                                    <td class="py-3 px-4 font-bold text-slate-800">Rp {{ number_format($tx->total, 0, ',', '.') }}</td>
                                    <td class="py-3 px-4 text-slate-600 truncate max-w-xs">{{ $tx->campaign_title_raw ?: '-' }}</td>
                                    <td class="py-3 px-4 font-mono text-rose-500">{{ $tx->no_hp_input ?: '(kosong)' }}</td>
                                    <td class="py-3 px-4 text-right">
                                        <button onclick="promptFixNumber('{{ $tx->donasi_id }}', '{{ $tx->no_hp_input }}')" class="px-3 py-1 bg-amber-50 hover:bg-amber-100 text-amber-700 rounded-lg text-[10px] font-bold uppercase tracking-wider transition">
                                            Perbaiki Nomor
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            </div>
        </div>
    </div>
</div>

<script>
    function promptFixNumber(donasiId, oldPhone) {
        const newPhone = prompt("Masukkan nomor HP yang valid untuk donasi " + donasiId + " (format 628...):", oldPhone || "");
        if (newPhone) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route('admin.harpa.papan_kerja.approve') }}";
            
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = "{{ csrf_token() }}";
            form.appendChild(csrf);

            const act = document.createElement('input');
            act.type = 'hidden';
            act.name = 'action';
            act.value = 'register_donor';
            form.appendChild(act);

            const pInput = document.createElement('input');
            pInput.type = 'hidden';
            pInput.name = 'phone';
            pInput.value = newPhone;
            form.appendChild(pInput);

            const nInput = document.createElement('input');
            nInput.type = 'hidden';
            nInput.name = 'name';
            nInput.value = "Donatur " + donasiId;
            form.appendChild(nInput);

            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endsection

