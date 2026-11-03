@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50/60 pb-20">
    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-8 items-start min-w-0">
            <!-- Sidebar Menu -->
            <aside class="hidden lg:block space-y-6 shrink-0 w-[280px]">
                @include('admin.sidebar', ['active' => 'harpa_settings'])
            </aside>

            <!-- Main Content Area -->
            <div class="space-y-6 min-w-0">
                <!-- Top Header Bar -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-widest">
                            <span>Sistem Data Harpa</span>
                            <span>/</span>
                            <span class="text-primary-600 font-black">Pengaturan Ambang Batas</span>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-montserrat font-black text-slate-900 tracking-tight mt-1 flex items-center gap-2">
                            <i class="fa-solid fa-sliders text-[#f7ad1a]"></i> Parameter Sistem Harpa
                        </h1>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.harpa.rekap') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs uppercase tracking-wider transition-all">
                            <i class="fa-solid fa-arrow-left"></i>
                            Kembali ke Rekap
                        </a>
                    </div>
                </div>

                <div class="max-w-4xl space-y-6">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center gap-3 shadow-sm">
                <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <form action="{{ route('admin.harpa.settings.update') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Card 1: Ambang Batas Waterfall Blast -->
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-4">
                <div class="pb-3 border-b border-slate-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-primary-50 text-primary-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-filter"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-montserrat font-black uppercase tracking-wider text-slate-800">1. Ambang Batas Waterfall Blast</h3>
                        <p class="text-xs text-slate-400 font-medium">Aturan penentuan segmen ARSIP Bermasalah, Tidak Pernah Baca, dan Berhenti Baca.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Ambang Gagal Beruntun (Pesan)</label>
                        <input type="number" name="threshold_failed_beruntun" value="{{ \App\Models\Harpa\HarpaSetting::get('threshold_failed_beruntun', 2) }}" min="1" max="10" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-800 outline-none">
                        <span class="text-[10px] text-slate-400 mt-1 block">Bawaan: 2x (masuk ARSIP Bermasalah)</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Ambang Total Blast Read=0</label>
                        <input type="number" name="threshold_total_blast_read0" value="{{ \App\Models\Harpa\HarpaSetting::get('threshold_total_blast_read0', 3) }}" min="1" max="10" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-800 outline-none">
                        <span class="text-[10px] text-slate-400 mt-1 block">Bawaan: 3x (masuk ARSIP Tidak Pernah Baca)</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Ambang Blast Sejak Read</label>
                        <input type="number" name="threshold_blast_sejak_read" value="{{ \App\Models\Harpa\HarpaSetting::get('threshold_blast_sejak_read', 3) }}" min="1" max="10" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-800 outline-none">
                        <span class="text-[10px] text-slate-400 mt-1 block">Bawaan: 3x (masuk ARSIP Berhenti Baca)</span>
                    </div>
                </div>
            </div>

            <!-- Card 2: Kalender & Masa Tenggang Broadcast -->
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-4">
                <div class="pb-3 border-b border-slate-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-calendar-days"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-montserrat font-black uppercase tracking-wider text-slate-800">2. Kalender & Masa Tenggang Broadcast</h3>
                        <p class="text-xs text-slate-400 font-medium">Jadwal pengiriman Payday dan masa transisi donatur perkenalan.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Tanggal Mulai Payday</label>
                        <input type="number" name="payday_start_day" value="{{ \App\Models\Harpa\HarpaSetting::get('payday_start_day', 1) }}" min="1" max="31" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-800 outline-none">
                        <span class="text-[10px] text-slate-400 mt-1 block">Bawaan: Tgl 1</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Tanggal Selesai Payday</label>
                        <input type="number" name="payday_end_day" value="{{ \App\Models\Harpa\HarpaSetting::get('payday_end_day', 5) }}" min="1" max="31" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-800 outline-none">
                        <span class="text-[10px] text-slate-400 mt-1 block">Bawaan: Tgl 5</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Masa Jumat Baru (Hari)</label>
                        <input type="number" name="perkenalan_grace_days" value="{{ \App\Models\Harpa\HarpaSetting::get('perkenalan_grace_days', 28) }}" min="7" max="90" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-800 outline-none">
                        <span class="text-[10px] text-slate-400 mt-1 block">Bawaan: 28 hari (masuk KIRIM_JUMAT_BARU)</span>
                    </div>
                </div>
            </div>

            <!-- Card 3: Ambang Batas RFM Analitik -->
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-4">
                <div class="pb-3 border-b border-slate-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-brain"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-montserrat font-black uppercase tracking-wider text-slate-800">3. Ambang Batas RFM Donatur</h3>
                        <p class="text-xs text-slate-400 font-medium">Parameter penentuan segmen CHAMPION, FRESH, POTENTIAL, dan DORMANT.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Minimal Frekuensi CHAMPION</label>
                        <input type="number" name="threshold_champion_min_freq" value="{{ \App\Models\Harpa\HarpaSetting::get('threshold_champion_min_freq', 3) }}" min="1" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-800 outline-none">
                        <span class="text-[10px] text-slate-400 mt-1 block">Bawaan: 3 kali</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Minimal Nominal CHAMPION (Rp)</label>
                        <input type="number" name="threshold_champion_min_amount" value="{{ \App\Models\Harpa\HarpaSetting::get('threshold_champion_min_amount', 1000000) }}" min="10000" step="10000" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-800 outline-none">
                        <span class="text-[10px] text-slate-400 mt-1 block">Bawaan: Rp 1.000.000</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Batas Hari CHAMPION vs AT RISK</label>
                        <input type="number" name="threshold_days_champion_vs_at_risk" value="{{ \App\Models\Harpa\HarpaSetting::get('threshold_days_champion_vs_at_risk', 90) }}" min="1" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-800 outline-none">
                        <span class="text-[10px] text-slate-400 mt-1 block">Bawaan: 90 hari</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Batas Hari FRESH (Donatur Baru)</label>
                        <input type="number" name="threshold_days_fresh" value="{{ \App\Models\Harpa\HarpaSetting::get('threshold_days_fresh', 30) }}" min="1" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-800 outline-none">
                        <span class="text-[10px] text-slate-400 mt-1 block">Bawaan: 30 hari</span>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-widest px-8 py-3.5 rounded-xl shadow-lg shadow-primary-600/20 hover:shadow-primary-600/30 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
            </div>
        </div>
    </div>
</div>
@endsection

