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
                <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-600 uppercase tracking-widest">
                            <span>Sistem Data Harpa</span>
                            <span aria-hidden="true">/</span>
                            <span class="text-primary-600 font-black">Gerbang Berhenti</span>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-montserrat font-black text-slate-900 tracking-tight mt-1 flex items-center gap-2">
                            <i class="fa-solid fa-clipboard-check text-[#f7ad1a]" aria-hidden="true"></i> Papan Kerja & Persetujuan
                        </h1>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.harpa.import_donasi') }}" class="inline-flex items-center min-h-[44px] gap-2 px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs uppercase tracking-wider transition-colors">
                            <i class="fa-solid fa-arrow-left text-sm" aria-hidden="true"></i>
                            Kembali ke Impor
                        </a>
                    </div>
                </div>
        <!-- Flash Messages -->
        @if(session('success'))
            <div role="status" class="p-4 rounded-2xl bg-emerald-50 border border-emerald-300 text-emerald-800 text-xs font-bold flex items-center gap-3 shadow-sm">
                <i class="fa-solid fa-circle-check text-emerald-700 text-base" aria-hidden="true"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('info'))
            <div role="status" class="p-4 rounded-2xl bg-sky-50 border border-sky-300 text-sky-900 text-xs font-bold flex items-center gap-3 shadow-sm">
                <i class="fa-solid fa-circle-info text-sky-700 text-base" aria-hidden="true"></i>
                <span>{{ session('info') }}</span>
            </div>
        @endif
        @if(session('warning'))
            <div role="alert" class="p-4 rounded-2xl bg-amber-50 border border-amber-300 text-amber-900 text-xs font-bold flex items-center gap-3 shadow-sm">
                <i class="fa-solid fa-triangle-exclamation text-amber-700 text-base" aria-hidden="true"></i>
                <span>{{ session('warning') }}</span>
            </div>
        @endif

        @if(!empty($pendingData))
            <!-- STATUS BANNER GERBANG BERHENTI -->
            <div class="p-6 sm:p-8 rounded-3xl bg-white border border-slate-200 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-start gap-3">
                        <i class="fa-solid {{ $pendingData['status'] === 'STOP' ? 'fa-hand text-rose-700' : 'fa-circle-check text-emerald-700' }} text-xl mt-0.5 shrink-0" aria-hidden="true"></i>
                        <div>
                            <span class="text-xs font-black uppercase tracking-widest {{ $pendingData['status'] === 'STOP' ? 'text-rose-700' : 'text-emerald-700' }}">
                                STATUS: {{ $pendingData['status'] }}
                            </span>
                            <h2 class="text-base sm:text-lg font-montserrat font-black text-slate-900 mt-1">
                                Tarikan {{ $pendingData['sumber'] }} Periode {{ $pendingData['periode'] }} ({{ number_format($pendingData['total_rows']) }} Baris)
                            </h2>
                            <p class="text-xs text-slate-600 font-medium mt-0.5">
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
                        <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Nomor Belum Terdaftar</span>
                        <span class="text-sm font-black tabular-nums {{ count($pendingData['unregistered_numbers'] ?? []) > 0 ? 'text-amber-800' : 'text-emerald-700' }}">
                            {{ count($pendingData['unregistered_numbers'] ?? []) }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-600 font-medium">Nomor valid yang belum memiliki donatur_id.</p>
                </div>

                <!-- Check 2: Judul Campaign Belum Dipetakan -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Campaign Belum Dipetakan</span>
                        <span class="text-sm font-black tabular-nums {{ count($pendingData['unmapped_campaigns'] ?? []) > 0 ? 'text-rose-700' : 'text-emerald-700' }}">
                            {{ count($pendingData['unmapped_campaigns'] ?? []) }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-600 font-medium">Judul ejaan baru yang belum ada di ref_campaign_alias.</p>
                </div>

                <!-- Check 3: Tanggal Gagal Dibaca -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Tanggal Gagal Dibaca</span>
                        <span class="text-sm font-black tabular-nums {{ count($pendingData['date_errors'] ?? []) > 0 ? 'text-rose-700' : 'text-emerald-700' }}">
                            {{ count($pendingData['date_errors'] ?? []) }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-600 font-medium">Format tanggal rusak yang menghentikan proses.</p>
                </div>
            </div>

            <!-- RESOLVE: NOMOR BELUM TERDAFTAR -->
            @if(!empty($pendingData['unregistered_numbers']))
                <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                        <div>
                            <h2 class="text-sm font-montserrat font-black uppercase tracking-wider text-slate-800">
                                1. Setujui Pendaftaran Donatur Baru
                            </h2>
                            <p class="text-xs text-slate-600 font-medium mt-0.5">Setiap nomor unik akan otomatis dibuatkan donatur_id baru (DNRxxxxxx).</p>
                        </div>
                        <span class="text-xs font-bold text-primary-700">{{ count($pendingData['unregistered_numbers']) }} Nomor</span>
                    </div>

                    <div class="max-h-80 overflow-y-auto space-y-2 pr-2">
                        @foreach($pendingData['unregistered_numbers'] as $phone => $name)
                            <div class="flex items-center justify-between gap-3 p-3.5 bg-slate-50 rounded-2xl border border-slate-200">
                                <div>
                                    <span class="font-mono text-xs font-bold text-slate-800">{{ $phone }}</span>
                                    <span class="text-slate-500 text-xs mx-2" aria-hidden="true">&bull;</span>
                                    <span class="text-xs font-semibold text-slate-700">{{ $name }}</span>
                                </div>
                                <form action="{{ route('admin.harpa.papan_kerja.approve') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="action" value="register_donor">
                                    <input type="hidden" name="phone" value="{{ $phone }}">
                                    <input type="hidden" name="name" value="{{ $name }}">
                                    <button type="submit" class="min-h-[40px] px-4 py-2 rounded-xl bg-primary-600 hover:bg-primary-700 text-white text-xs font-bold uppercase tracking-wider shadow-sm transition-colors">
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
                <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                        <div>
                            <h2 class="text-sm font-montserrat font-black uppercase tracking-wider text-slate-800">
                                2. Petakan Judul Campaign Baru ke Master Campaign
                            </h2>
                            <p class="text-xs text-slate-600 font-medium mt-0.5">Ejaan judul ekspor tidak diubah; cukup daftarkan ejaannya sebagai alias.</p>
                        </div>
                        <span class="text-xs font-bold text-rose-700">{{ count($pendingData['unmapped_campaigns']) }} Judul</span>
                    </div>

                    <div class="space-y-3">
                        @foreach($pendingData['unmapped_campaigns'] as $rawTitle)
                            <form action="{{ route('admin.harpa.papan_kerja.approve') }}" method="POST" class="flex flex-col sm:flex-row sm:items-center gap-3 p-4 bg-slate-50 rounded-2xl border border-slate-200">
                                @csrf
                                <input type="hidden" name="action" value="map_alias">
                                <input type="hidden" name="teks_asli" value="{{ $rawTitle }}">
                                <div class="flex-1">
                                    <span class="text-[11px] font-bold text-slate-600 uppercase tracking-widest block">Teks Asli di File Ekspor:</span>
                                    <span class="text-xs font-bold text-slate-800">"{{ $rawTitle }}"</span>
                                </div>
                                <div class="flex-1">
                                    <select name="campaign_id" aria-label="Pilih campaign resmi untuk {{ $rawTitle }}" required class="w-full min-h-[44px] bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-primary-600 outline-none">
                                        <option value="">-- Hubungkan ke Campaign Resmi --</option>
                                        @foreach($campaigns as $camp)
                                            <option value="{{ $camp->id }}">{{ $camp->title }} (ID: {{ $camp->id }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <button type="submit" class="w-full sm:w-auto min-h-[44px] px-5 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-black uppercase tracking-wider shadow-sm transition-colors">
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
            <div class="bg-white rounded-3xl border border-slate-200 p-8 text-center space-y-4 shadow-sm">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 border border-slate-200 text-[#063f5c] flex items-center justify-center text-2xl mx-auto">
                    <i class="fa-solid fa-check-double" aria-hidden="true"></i>
                </div>
                <div class="max-w-md mx-auto space-y-1">
                    <h2 class="text-base font-montserrat font-black text-slate-800">Papan Kerja Bersih (Tidak Ada Antrean)</h2>
                    <p class="text-xs text-slate-600 font-medium">Semua data donasi yang telah diimpor sebelumnya telah lolos verifikasi dan tersimpan rapi di database.</p>
                </div>
                <div class="pt-2">
                    <a href="{{ route('admin.harpa.import_donasi') }}" class="inline-flex items-center min-h-[44px] gap-2 px-6 py-3 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-widest shadow-md transition-colors">
                        <i class="fa-solid fa-cloud-arrow-up" aria-hidden="true"></i>
                        Unggah Data Donasi Baru
                    </a>
                </div>
            </div>
        @endif

        <!-- DAFTAR TRANSAKSI TANPA-NOMOR (BISA DIPERBAIKI) -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                <div>
                    <h2 class="text-sm font-montserrat font-black uppercase tracking-wider text-slate-800 flex items-center gap-2">
                        <i class="fa-solid fa-triangle-exclamation text-amber-700" aria-hidden="true"></i>
                        Daftar Transaksi "TANPA-NOMOR" (Bisa Diperbaiki)
                    </h2>
                    <p class="text-xs text-slate-600 font-medium mt-0.5">Sesuai aturan SOP, transaksi tanpa nomor tetap disimpan dan tidak boleh digugurkan.</p>
                </div>
                <span class="text-xs font-bold text-slate-700">{{ $tanpaNomorTransactions->count() }} Transaksi</span>
            </div>

            @if($tanpaNomorTransactions->isEmpty())
                <p class="text-xs text-slate-600 py-4 text-center font-medium">Tidak ada transaksi yang berstatus TANPA-NOMOR.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-xs font-black uppercase tracking-wider text-slate-600 border-b border-slate-200">
                            <tr>
                                <th class="py-3 px-4">Donasi ID</th>
                                <th class="py-3 px-4">Tanggal</th>
                                <th class="py-3 px-4">Nominal</th>
                                <th class="py-3 px-4">Campaign</th>
                                <th class="py-3 px-4">Nomor Input Asli</th>
                                <th class="py-3 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($tanpaNomorTransactions as $tx)
                                <tr class="hover:bg-slate-50">
                                    <td class="py-3 px-4 font-mono font-bold text-primary-700">{{ $tx->donasi_id }}</td>
                                    <td class="py-3 px-4 text-slate-600">{{ $tx->tanggal->format('d M Y') }}</td>
                                    <td class="py-3 px-4 font-bold text-slate-800">Rp {{ number_format($tx->total, 0, ',', '.') }}</td>
                                    <td class="py-3 px-4 text-slate-700 truncate max-w-xs">{{ $tx->campaign_title_raw ?: '-' }}</td>
                                    <td class="py-3 px-4 font-mono text-rose-700 font-semibold">{{ $tx->no_hp_input ?: '(kosong)' }}</td>
                                    <td class="py-3 px-4 text-right">
                                        <button type="button" onclick="promptFixNumber('{{ $tx->donasi_id }}', '{{ $tx->no_hp_input }}')" class="min-h-[36px] px-3 py-1.5 bg-white border border-slate-300 hover:border-[#063f5c] text-slate-800 hover:text-[#063f5c] rounded-lg text-xs font-bold uppercase tracking-wider transition-colors">
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

