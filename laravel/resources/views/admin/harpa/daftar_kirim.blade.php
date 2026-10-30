@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50/60 pb-20">
    <!-- Top Header Bar -->
    <div class="bg-white border-b border-slate-200/80 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-widest">
                    <span>Sistem Data Harpa</span>
                    <span>/</span>
                    <span class="text-primary-600 font-black">Alur D: Daftar Kirim untuk CRM</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-montserrat font-black text-slate-900 tracking-tight mt-1 flex items-center gap-2">
                    <i class="fa-solid fa-list-check text-primary-600"></i> Daftar Siap Kirim Broadcast
                </h1>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.harpa.daftar_kirim.export', ['type' => $tab, 'bulan' => $bulan, 'sumber' => $sumber]) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs uppercase tracking-wider shadow-md transition-all">
                    <i class="fa-solid fa-file-csv"></i>
                    Unduh CSV untuk CRM
                </a>
                <a href="{{ route('admin.harpa.daftar_kirim.export', ['type' => $tab, 'bulan' => $bulan, 'sumber' => $sumber, 'mask' => 1]) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs uppercase tracking-wider transition-all" title="Ekspor Aman Tanpa Nomor Lengkap untuk Laporan Pihak Ketiga">
                    <i class="fa-solid fa-user-shield"></i>
                    Ekspor Aman
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 space-y-6">
        <!-- TABS NAVIGASI 5 DAFTAR KIRIM -->
        <div class="flex items-center gap-2 overflow-x-auto pb-2 border-b border-slate-200">
            <a href="{{ route('admin.harpa.daftar_kirim', ['tab' => 'jumat']) }}" class="px-5 py-3 rounded-2xl text-xs font-black uppercase tracking-wider transition-all whitespace-nowrap {{ $tab === 'jumat' ? 'bg-[#063f5c] text-white shadow-lg' : 'bg-white text-slate-600 hover:bg-slate-100' }}">
                <i class="fa-solid fa-calendar-week mr-1.5 {{ $tab === 'jumat' ? 'text-[#f7ad1a]' : 'text-slate-400' }}"></i>
                1. KIRIM_JUMAT (Donatur)
            </a>

            <a href="{{ route('admin.harpa.daftar_kirim', ['tab' => 'payday']) }}" class="px-5 py-3 rounded-2xl text-xs font-black uppercase tracking-wider transition-all whitespace-nowrap {{ $tab === 'payday' ? 'bg-[#063f5c] text-white shadow-lg' : 'bg-white text-slate-600 hover:bg-slate-100' }}">
                <i class="fa-solid fa-money-check-dollar mr-1.5 {{ $tab === 'payday' ? 'text-[#f7ad1a]' : 'text-slate-400' }}"></i>
                2. KIRIM_PAYDAY (Tgl 1–5)
            </a>

            <a href="{{ route('admin.harpa.daftar_kirim', ['tab' => 'perkenalan']) }}" class="px-5 py-3 rounded-2xl text-xs font-black uppercase tracking-wider transition-all whitespace-nowrap {{ $tab === 'perkenalan' ? 'bg-[#063f5c] text-white shadow-lg' : 'bg-white text-slate-600 hover:bg-slate-100' }}">
                <i class="fa-solid fa-handshake mr-1.5 {{ $tab === 'perkenalan' ? 'text-[#f7ad1a]' : 'text-slate-400' }}"></i>
                3. KIRIM_PERKENALAN (Web Baru)
            </a>

            <a href="{{ route('admin.harpa.daftar_kirim', ['tab' => 'jumat_baru']) }}" class="px-5 py-3 rounded-2xl text-xs font-black uppercase tracking-wider transition-all whitespace-nowrap {{ $tab === 'jumat_baru' ? 'bg-[#063f5c] text-white shadow-lg' : 'bg-white text-slate-600 hover:bg-slate-100' }}">
                <i class="fa-solid fa-user-tag mr-1.5 {{ $tab === 'jumat_baru' ? 'text-[#f7ad1a]' : 'text-slate-400' }}"></i>
                4. KIRIM_JUMAT_BARU (28 Hari)
            </a>

            <a href="{{ route('admin.harpa.daftar_kirim', ['tab' => 'nomor_mati']) }}" class="px-5 py-3 rounded-2xl text-xs font-black uppercase tracking-wider transition-all whitespace-nowrap {{ $tab === 'nomor_mati' ? 'bg-rose-600 text-white shadow-lg' : 'bg-white text-slate-600 hover:bg-slate-100' }}">
                <i class="fa-solid fa-triangle-exclamation mr-1.5 text-white"></i>
                5. ALERT Nomor Mati
            </a>
        </div>

        <!-- INFO DAFTAR & FILTER KHUSUS PERKENALAN -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h3 class="text-sm font-montserrat font-black uppercase tracking-wider text-slate-800">
                    @if($tab === 'jumat')
                        KIRIM_JUMAT &bull; Segmen AKTIF Donatur (Nomor Hidup)
                    @elseif($tab === 'payday')
                        KIRIM_PAYDAY &bull; Segmen AKTIF Pembaca & PANTAU (Tanggal 1–5)
                    @elseif($tab === 'perkenalan')
                        KIRIM_PERKENALAN &bull; Donatur Web Baru yang Belum Pernah Di-Blast
                    @elseif($tab === 'jumat_baru')
                        KIRIM_JUMAT_BARU &bull; Nomor Dikenalkan dalam 28 Hari Terakhir
                    @else
                        ALERT NOMOR DONATUR MATI &bull; Ditindaklanjuti Tim CRM untuk Minta Nomor Baru
                    @endif
                </h3>
                <p class="text-xs text-slate-400 font-medium mt-0.5">
                    Total baris siap kirim: <strong class="text-slate-800">{{ count($data) }} nomor</strong> (hasil kalkulasi real-time sistem)
                </p>
            </div>

            @if($tab === 'perkenalan')
                <form method="GET" action="{{ route('admin.harpa.daftar_kirim') }}" class="flex items-center gap-2">
                    <input type="hidden" name="tab" value="perkenalan">
                    <input type="month" name="bulan" value="{{ $bulan }}" class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 outline-none">
                    <select name="sumber" class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 outline-none">
                        <option value="">Semua Sumber</option>
                        <option value="IM" {{ $sumber === 'IM' ? 'selected' : '' }}>Indonesia Madani (IM)</option>
                        <option value="FH" {{ $sumber === 'FH' ? 'selected' : '' }}>ForHumanity (FH)</option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-slate-800 transition">
                        Filter
                    </button>
                </form>
            @endif
        </div>

        <!-- TABEL DATA DAFTAR KIRIM -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm">
            @if(empty($data))
                <div class="text-center py-12 text-slate-400">
                    <i class="fa-regular fa-clipboard text-3xl mb-2"></i>
                    <p class="text-xs font-bold">Daftar kirim ini saat ini kosong.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        @if($tab === 'nomor_mati')
                            <thead class="bg-slate-50 text-[10px] font-black uppercase tracking-wider text-slate-500 border-b border-slate-100">
                                <tr>
                                    <th class="py-3 px-4">Nomor HP Lama</th>
                                    <th class="py-3 px-4">Donatur ID</th>
                                    <th class="py-3 px-4">Nama Lengkap</th>
                                    <th class="py-3 px-4">Total Donasi</th>
                                    <th class="py-3 px-4">Donasi Terakhir</th>
                                    <th class="py-3 px-4">PIC CRM</th>
                                    <th class="py-3 px-4">Tindak Lanjut CRM</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($data as $r)
                                    <tr class="hover:bg-slate-50/50">
                                        <td class="py-3 px-4 font-mono font-bold text-rose-600">{{ $r['no_hp_lama'] }}</td>
                                        <td class="py-3 px-4 font-mono text-primary-600 font-bold">{{ $r['donatur_id'] }}</td>
                                        <td class="py-3 px-4 font-semibold text-slate-800">{{ $r['nama_lengkap'] }}</td>
                                        <td class="py-3 px-4 font-bold text-emerald-700">Rp {{ number_format($r['total_donasi'], 0, ',', '.') }}</td>
                                        <td class="py-3 px-4 text-slate-500">{{ $r['donasi_terakhir'] }}</td>
                                        <td class="py-3 px-4 text-slate-600">{{ $r['pic_crm'] }}</td>
                                        <td class="py-3 px-4 text-slate-500 text-[11px]">{{ $r['catatan'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        @elseif($tab === 'perkenalan')
                            <thead class="bg-slate-50 text-[10px] font-black uppercase tracking-wider text-slate-500 border-b border-slate-100">
                                <tr>
                                    <th class="py-3 px-4">Nomor HP</th>
                                    <th class="py-3 px-4">Total Donasi</th>
                                    <th class="py-3 px-4">Frekuensi</th>
                                    <th class="py-3 px-4">Donasi Terakhir</th>
                                    <th class="py-3 px-4">Donatur ID / Nama</th>
                                    <th class="py-3 px-4">Sumber / Bulan</th>
                                    <th class="py-3 px-4">Program Terakhir</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($data as $r)
                                    <tr class="hover:bg-slate-50/50">
                                        <td class="py-3 px-4 font-mono font-bold text-slate-900">{{ $r['no_hp'] }}</td>
                                        <td class="py-3 px-4 font-bold text-emerald-700">Rp {{ number_format($r['total_donasi'], 0, ',', '.') }}</td>
                                        <td class="py-3 px-4 text-slate-700 font-semibold">{{ $r['frekuensi'] }}x donasi</td>
                                        <td class="py-3 px-4 text-slate-500">{{ $r['tgl_donasi_terakhir'] }}</td>
                                        <td class="py-3 px-4">
                                            <span class="font-mono text-[10px] text-primary-600 font-bold block">{{ $r['donatur_id'] }}</span>
                                            <span class="text-slate-800 font-semibold truncate max-w-[180px] block">{{ $r['nama_lengkap'] }}</span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-wider {{ $r['sumber'] === 'IM' ? 'bg-primary-50 text-primary-700' : 'bg-amber-50 text-amber-700' }}">
                                                {{ $r['sumber'] }} &bull; {{ $r['bulan_donasi'] }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-slate-600 truncate max-w-xs">{{ $r['program_terakhir'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        @elseif($tab === 'jumat_baru')
                            <thead class="bg-slate-50 text-[10px] font-black uppercase tracking-wider text-slate-500 border-b border-slate-100">
                                <tr>
                                    <th class="py-3 px-4">Nomor HP</th>
                                    <th class="py-3 px-4">Donatur ID</th>
                                    <th class="py-3 px-4">Nama Lengkap</th>
                                    <th class="py-3 px-4">Tgl Dikenalkan</th>
                                    <th class="py-3 px-4">Sisa Masa Baru</th>
                                    <th class="py-3 px-4">Catatan Sistem</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($data as $r)
                                    <tr class="hover:bg-slate-50/50">
                                        <td class="py-3 px-4 font-mono font-bold text-slate-900">{{ $r['no_hp'] }}</td>
                                        <td class="py-3 px-4 font-mono text-primary-600 font-bold">{{ $r['donatur_id'] }}</td>
                                        <td class="py-3 px-4 font-semibold text-slate-800">{{ $r['nama_lengkap'] }}</td>
                                        <td class="py-3 px-4 text-slate-600">{{ $r['tgl_dikenalkan'] }}</td>
                                        <td class="py-3 px-4">
                                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-black bg-amber-100 text-amber-800">
                                                {{ $r['sisa_hari_baru'] }} Hari Lagi
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-slate-500 text-[11px]">{{ $r['catatan'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        @else
                            <thead class="bg-slate-50 text-[10px] font-black uppercase tracking-wider text-slate-500 border-b border-slate-100">
                                <tr>
                                    <th class="py-3 px-4">Nomor HP</th>
                                    <th class="py-3 px-4">Donatur ID / Nama</th>
                                    <th class="py-3 px-4">Segmen</th>
                                    <th class="py-3 px-4">Jadwal Kirim</th>
                                    <th class="py-3 px-4">Total Donasi</th>
                                    <th class="py-3 px-4">Terakhir Dibaca</th>
                                    <th class="py-3 px-4 min-w-[200px]">Alasan Waterfall</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($data as $r)
                                    <tr class="hover:bg-slate-50/50">
                                        <td class="py-3 px-4 font-mono font-bold text-slate-900">{{ $r['no_hp'] }}</td>
                                        <td class="py-3 px-4">
                                            <span class="font-mono text-[10px] text-primary-600 font-bold block">{{ $r['donatur_id'] }}</span>
                                            <span class="text-slate-800 font-semibold truncate max-w-[150px] block">{{ $r['nama'] }}</span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider bg-primary-100 text-primary-800">
                                                {{ $r['segmen'] }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 font-bold text-[10px] {{ str_contains($r['jadwal'], 'CEK') ? 'text-amber-600' : 'text-emerald-700' }}">
                                            {{ $r['jadwal'] }}
                                        </td>
                                        <td class="py-3 px-4 font-bold text-emerald-700 text-[10px]">
                                            Rp {{ number_format($r['total_donasi'], 0, ',', '.') }}
                                        </td>
                                        <td class="py-3 px-4 text-slate-400 text-[10px]">
                                            {{ $r['read_terakhir'] ? $r['read_terakhir']->format('d M Y') : 'Belum Pernah' }}
                                        </td>
                                        <td class="py-3 px-4 text-slate-600 text-[11px] leading-snug">
                                            {{ $r['alasan'] }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        @endif
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

