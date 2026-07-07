@extends('layouts.app')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-20">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar Menu -->
        <div class="lg:col-span-1 space-y-6">
            @include('admin.sidebar', ['active' => 'attendance'])
        </div>

        <!-- Dashboard Content -->
        <div class="lg:col-span-3 space-y-8">
            <div class="space-y-2">
                <h1 class="text-2xl sm:text-3xl font-montserrat font-black text-slate-900 uppercase tracking-tight italic">
                    Log Absensi <span class="text-primary-600">GPS Pegawai</span>
                </h1>
                <div class="w-12 h-1 bg-primary-500 rounded-full"></div>
            </div>

            <!-- Table of Attendance Logs -->
            <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 space-y-6 shadow-sm">
                @if($attendances->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-100 text-[10px] font-black uppercase tracking-wider text-slate-400">
                                    <th class="pb-4">Pegawai</th>
                                    <th class="pb-4">Tipe Absen</th>
                                    <th class="pb-4">Tanggal & Waktu</th>
                                    <th class="pb-4">Lokasi / Keterangan</th>
                                    <th class="pb-4">Koordinat GPS</th>
                                    <th class="pb-4 text-right">Selfie Bukti</th>
                                </tr>
                            </thead>
                            <tbody class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                                @foreach($attendances as $att)
                                    <tr class="border-b border-slate-50 last:border-0 hover:bg-slate-50/40 transition-colors">
                                        <td class="py-4 pr-4">
                                            <span class="block text-slate-800 font-bold">{{ $att->user->name ?? 'Unknown User' }}</span>
                                            <span class="block text-[9px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">{{ $att->user->email ?? '-' }}</span>
                                        </td>
                                        <td class="py-4">
                                            @if($att->type === 'check_in')
                                                <span class="bg-emerald-50 text-emerald-700 text-[8px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md">CHECK IN</span>
                                            @elseif($att->type === 'check_out')
                                                <span class="bg-blue-50 text-blue-700 text-[8px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md">CHECK OUT</span>
                                            @elseif($att->type === 'sakit')
                                                <span class="bg-amber-50 text-amber-700 text-[8px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md">SAKIT</span>
                                            @else
                                                <span class="bg-red-50 text-red-700 text-[8px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md">IZIN</span>
                                            @endif
                                        </td>
                                        <td class="py-4 text-slate-800">{{ $att->created_at->format('d M Y, H:i') }}</td>
                                        <td class="py-4 text-slate-600">{{ $att->location ?? '-' }}</td>
                                        <td class="py-4 text-slate-500 font-mono text-[11px]">
                                            @if($att->latitude && $att->longitude)
                                                <a href="https://www.google.com/maps/search/?api=1&query={{ $att->latitude }},{{ $att->longitude }}" target="_blank" class="text-primary-600 hover:underline inline-flex items-center gap-1">
                                                    <i class="fa-solid fa-map-location-dot"></i> {{ $att->latitude }}, {{ $att->longitude }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="py-4 text-right">
                                            @if($att->attachment)
                                                <a href="{{ $att->attachment }}" target="_blank" class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-black text-[9px] uppercase tracking-wider px-3.5 py-2 rounded-xl border border-slate-250/30 inline-flex items-center justify-center gap-1 w-fit ml-auto">
                                                    <i class="fa-solid fa-image"></i> Lihat Foto
                                                </a>
                                            @else
                                                <span class="text-slate-400 font-bold">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="pt-4">
                        {{ $attendances->links() }}
                    </div>
                @else
                    <div class="py-16 text-center text-slate-400 font-bold uppercase tracking-wider">
                        <i class="fa-solid fa-user-clock text-slate-200 text-4xl mb-3 block"></i>
                        Belum ada data absensi pegawai lapangan yang tercatat.
                    </div>
                @endif
            </div>
        </div>

    </div>
</section>
@endsection
