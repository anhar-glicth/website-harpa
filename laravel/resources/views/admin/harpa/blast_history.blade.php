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
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-widest">
                            <span>Sistem Data Harpa</span>
                            <span>/</span>
                            <span class="text-primary-600 font-black">Alur B: Riwayat Blast Everpro</span>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-montserrat font-black text-slate-900 tracking-tight mt-1 flex items-center gap-2">
                            <i class="fa-solid fa-paper-plane text-primary-600"></i> Riwayat Blast & Diagnosa
                        </h1>
                    </div>
                    <div class="flex items-center gap-2">
                        <button onclick="document.getElementById('modal-upload-blast').classList.remove('hidden')" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-wider shadow-md transition-all">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                            Unggah Ekspor Everpro
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

        <!-- 6 CEK DIAGNOSA INTEGRITAS (Sheet DIAGNOSA) -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h2 class="text-sm font-montserrat font-black uppercase tracking-wider text-slate-800 flex items-center gap-2">
                        <i class="fa-solid fa-stethoscope text-primary-600"></i>
                        6 Pemeriksa Wajib Diagnosa Integritas (Sheet DIAGNOSA)
                    </h2>
                    <p class="text-xs text-slate-400 font-medium">Daftar kirim tidak boleh dipakai selama ada pemeriksaan yang gagal.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($diagnosa as $key => $check)
                    <div class="p-4 rounded-2xl border {{ $check['passed'] ? 'bg-emerald-50/50 border-emerald-200/80' : 'bg-rose-50 border-rose-200' }} flex items-start gap-3">
                        <div class="w-8 h-8 rounded-xl {{ $check['passed'] ? 'bg-emerald-600 text-white' : 'bg-rose-600 text-white' }} flex items-center justify-center text-sm shrink-0 mt-0.5">
                            <i class="fa-solid {{ $check['passed'] ? 'fa-check' : 'fa-xmark' }}"></i>
                        </div>
                        <div class="space-y-1">
                            <h4 class="text-xs font-bold text-slate-800 leading-snug">{{ $check['name'] }}</h4>
                            <p class="text-[10px] font-mono font-bold {{ $check['passed'] ? 'text-emerald-700' : 'text-rose-700' }}">
                                Hasil: {{ $check['value'] }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- TABEL DATA BLAST & FILTER -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-100">
                <div>
                    <h3 class="text-sm font-montserrat font-black uppercase tracking-wider text-slate-800">
                        Daftar Pesan Riwayat Blast
                    </h3>
                    <p class="text-xs text-slate-400 font-medium">Total riwayat tersimpan: {{ number_format($blasts->total()) }} pesan</p>
                </div>

                <!-- Filters -->
                <form method="GET" action="{{ route('admin.harpa.blast_history') }}" class="flex flex-wrap items-center gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No HP / Broadcast ID..." class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-medium text-slate-700 outline-none focus:bg-white focus:ring-2 focus:ring-primary-500">
                    <select name="status" class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 outline-none">
                        <option value="">Semua Status</option>
                        <option value="READ" {{ request('status') === 'READ' ? 'selected' : '' }}>READ</option>
                        <option value="DELIVERED" {{ request('status') === 'DELIVERED' ? 'selected' : '' }}>DELIVERED</option>
                        <option value="SUCCESS" {{ request('status') === 'SUCCESS' ? 'selected' : '' }}>SUCCESS</option>
                        <option value="FAILED" {{ request('status') === 'FAILED' ? 'selected' : '' }}>FAILED</option>
                        <option value="LAINNYA" {{ request('status') === 'LAINNYA' ? 'selected' : '' }}>LAINNYA</option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-slate-800 transition">
                        Filter
                    </button>
                </form>
            </div>

            @if($blasts->isEmpty())
                <div class="text-center py-12 text-slate-400">
                    <i class="fa-regular fa-paper-plane text-3xl mb-2"></i>
                    <p class="text-xs font-bold">Belum ada data blast Everpro yang diunggah.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-[10px] font-black uppercase tracking-wider text-slate-500 border-b border-slate-100">
                            <tr>
                                <th class="py-3 px-4">Receiver Number</th>
                                <th class="py-3 px-4">Broadcast ID</th>
                                <th class="py-3 px-4">Batch Label</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4">Waktu Blast</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($blasts as $b)
                                <tr class="hover:bg-slate-50/50">
                                    <td class="py-3 px-4 font-mono font-bold text-slate-800">{{ $b->receiver_number }}</td>
                                    <td class="py-3 px-4 font-mono text-slate-500">{{ $b->broadcast_id }}</td>
                                    <td class="py-3 px-4 font-semibold text-slate-600">{{ $b->batch_tarik }}</td>
                                    <td class="py-3 px-4">
                                        <span class="px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider 
                                            {{ $b->status === 'READ' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                            {{ $b->status === 'DELIVERED' ? 'bg-sky-100 text-sky-800' : '' }}
                                            {{ $b->status === 'SUCCESS' ? 'bg-primary-100 text-primary-800' : '' }}
                                            {{ $b->status === 'FAILED' ? 'bg-rose-100 text-rose-800' : '' }}
                                            {{ $b->status === 'LAINNYA' ? 'bg-amber-100 text-amber-800' : '' }}
                                        ">
                                            {{ $b->status }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-slate-400">{{ $b->timestamp_blast ? $b->timestamp_blast->format('d M Y H:i') : '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pt-4 border-t border-slate-100">
                    {{ $blasts->links() }}
                </div>
            @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Upload Everpro Blast -->
<div id="modal-upload-blast" class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 space-y-6 shadow-2xl">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 class="text-base font-montserrat font-black text-slate-800">Unggah Ekspor Everpro</h3>
            <button onclick="document.getElementById('modal-upload-blast').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form action="{{ route('admin.harpa.blast.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Jenis Broadcast (Batch Label)</label>
                <select name="batch_type" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs font-bold text-slate-700 outline-none">
                    <option value="jumat">Selling Jumat (jumat-YYYY-MM-DD)</option>
                    <option value="payday">Payday Bulanan (payday-YYYY-MM-DD)</option>
                    <option value="perkenalan">Perkenalan Donatur Baru (perkenalan-YYYY-MM-DD)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Tanggal Broadcast</label>
                <input type="date" name="batch_date" value="{{ date('Y-m-d') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-700 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">File Ekspor Everpro (.xlsx / .csv)</label>
                <input type="file" name="file" accept=".xlsx,.xls,.csv,.txt" required class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:bg-primary-600 file:text-white cursor-pointer">
                <p class="text-[10px] text-slate-400 mt-1">Kunci unik: nomor HP + BROADCAST ID. Duplikat akan otomatis ditolak.</p>
            </div>

            <div class="pt-4 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modal-upload-blast').classList.add('hidden')" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold uppercase tracking-wider">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-700 text-white text-xs font-black uppercase tracking-widest shadow-md">
                    Unggah & Periksa
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

