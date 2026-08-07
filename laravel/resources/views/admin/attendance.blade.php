@extends('layouts.app')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar Menu -->
        <div class="lg:col-span-1 space-y-6">
            @include('admin.sidebar', ['active' => 'attendance'])
        </div>

        <!-- Dashboard Content -->
        <div class="lg:col-span-3 space-y-6">
            
            {{-- Header Page Title & Export Button --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="space-y-1">
                    <h1 class="text-2xl sm:text-3xl font-montserrat font-black text-slate-900 uppercase tracking-tight italic">
                        Log Absensi <span class="text-primary-600">GPS Pegawai</span>
                    </h1>
                    <div class="w-12 h-1 bg-primary-500 rounded-full"></div>
                </div>
                
                {{-- Export Button (Excel/CSV) --}}
                <a href="{{ route('admin.attendance.export', request()->all()) }}" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-black uppercase tracking-wider flex items-center justify-center gap-2 shadow-sm transition-all">
                    <i class="fa-solid fa-file-excel text-sm"></i> Export Excel
                </a>
            </div>

            {{-- Filter Bar sesuai Kolom --}}
            <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm space-y-3">
                <form action="{{ route('admin.attendance') }}" method="GET" class="flex flex-wrap items-center gap-3 text-xs font-bold text-slate-700">
                    
                    <div class="flex items-center gap-2">
                        <span>Pegawai:</span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama / Email..." class="border border-slate-250 rounded-xl px-3 py-1.5 text-xs focus:border-primary-500 outline-none w-48">
                    </div>

                    <div class="flex items-center gap-2">
                        <span>Tipe Absen:</span>
                        <select name="type" class="border border-slate-250 rounded-xl px-3 py-1.5 text-xs focus:border-primary-500 outline-none w-36">
                            <option value="">Semua Tipe</option>
                            <option value="check_in" {{ request('type') == 'check_in' ? 'selected' : '' }}>CHECK IN</option>
                            <option value="check_out" {{ request('type') == 'check_out' ? 'selected' : '' }}>CHECK OUT</option>
                            <option value="sakit" {{ request('type') == 'sakit' ? 'selected' : '' }}>SAKIT</option>
                            <option value="izin" {{ request('type') == 'izin' ? 'selected' : '' }}>IZIN</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <span>Dari:</span>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="border border-slate-250 rounded-xl px-3 py-1.5 text-xs focus:border-primary-500 outline-none">
                    </div>

                    <div class="flex items-center gap-2">
                        <span>Sampai:</span>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="border border-slate-250 rounded-xl px-3 py-1.5 text-xs focus:border-primary-500 outline-none">
                    </div>

                    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-1.5 rounded-xl text-xs flex items-center gap-1.5 transition-all">
                        🔍 Filter
                    </button>
                    
                    @if(request()->anyFilled(['search', 'type', 'start_date', 'end_date']))
                        <a href="{{ route('admin.attendance') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-3 py-1.5 rounded-xl text-xs">Reset</a>
                    @endif

                </form>
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
