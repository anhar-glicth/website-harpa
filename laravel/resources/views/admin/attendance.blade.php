@extends('layouts.app')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar Menu -->
        <div class="hidden lg:block lg:col-span-1 space-y-6">
            @include('admin.sidebar', ['active' => 'attendance'])
        </div>

        <!-- Dashboard Content -->
        <div class="lg:col-span-3 space-y-6">

            {{-- Real-time WITA Clock Widget --}}
            <x-live-clock variant="card" />

            {{-- Personal Absensi GPS Card --}}
            <div x-data="{ showAbsenForm: false }" class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm space-y-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="font-montserrat font-black text-slate-900 uppercase tracking-tight italic text-sm">
                            Absensi <span class="text-primary-600">GPS Saya Hari Ini</span>
                        </h2>
                        <p class="text-xs text-slate-600 font-bold uppercase tracking-widest mt-0.5">{{ now(\App\Models\Attendance::WITA_TIMEZONE)->locale('id')->translatedFormat('l, d F Y') }}</p>
                    </div>
                    <button type="button" @click="showAbsenForm = !showAbsenForm" :aria-expanded="showAbsenForm ? 'true' : 'false'"
                        class="min-h-[44px] px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-xs font-black uppercase tracking-wider flex items-center gap-2 shadow-sm transition-colors">
                        <i class="fa-solid fa-stamp" aria-hidden="true"></i> Catat Absensi
                    </button>
                </div>

                {{-- Status hari ini --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    @php
                        $hasCheckIn  = $todayAttendance->where('type', 'check_in')->isNotEmpty();
                        $hasCheckOut = $todayAttendance->where('type', 'check_out')->isNotEmpty();
                        $hasSakit    = $todayAttendance->where('type', 'sakit')->isNotEmpty();
                        $hasIzin     = $todayAttendance->where('type', 'izin')->isNotEmpty();
                    @endphp
                    <div class="flex items-center gap-2.5 p-3 rounded-2xl border {{ $hasCheckIn ? 'bg-white border-slate-300 shadow-xs' : 'bg-slate-50 border-slate-200' }}">
                        <i class="fa-solid fa-right-to-bracket text-sm {{ $hasCheckIn ? 'text-emerald-700' : 'text-slate-500' }}" aria-hidden="true"></i>
                        <div>
                            <p class="text-[11px] font-black uppercase tracking-wider {{ $hasCheckIn ? 'text-emerald-700' : 'text-slate-600' }}">Check In</p>
                            <p class="text-xs font-bold text-slate-800">{{ $hasCheckIn ? $todayAttendance->where('type','check_in')->first()->formatWita('H:i') . ' WITA' : '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2.5 p-3 rounded-2xl border {{ $hasCheckOut ? 'bg-white border-slate-300 shadow-xs' : 'bg-slate-50 border-slate-200' }}">
                        <i class="fa-solid fa-right-from-bracket text-sm {{ $hasCheckOut ? 'text-[#063f5c]' : 'text-slate-500' }}" aria-hidden="true"></i>
                        <div>
                            <p class="text-[11px] font-black uppercase tracking-wider {{ $hasCheckOut ? 'text-[#063f5c]' : 'text-slate-600' }}">Check Out</p>
                            <p class="text-xs font-bold text-slate-800">{{ $hasCheckOut ? $todayAttendance->where('type','check_out')->first()->formatWita('H:i') . ' WITA' : '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2.5 p-3 rounded-2xl border {{ $hasSakit ? 'bg-white border-slate-300 shadow-xs' : 'bg-slate-50 border-slate-200' }}">
                        <i class="fa-solid fa-kit-medical text-sm {{ $hasSakit ? 'text-amber-800' : 'text-slate-500' }}" aria-hidden="true"></i>
                        <div>
                            <p class="text-[11px] font-black uppercase tracking-wider {{ $hasSakit ? 'text-amber-800' : 'text-slate-600' }}">Sakit</p>
                            <p class="text-xs font-bold text-slate-800">{{ $hasSakit ? 'Tercatat' : '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2.5 p-3 rounded-2xl border {{ $hasIzin ? 'bg-white border-slate-300 shadow-xs' : 'bg-slate-50 border-slate-200' }}">
                        <i class="fa-solid fa-file-circle-exclamation text-sm {{ $hasIzin ? 'text-rose-700' : 'text-slate-500' }}" aria-hidden="true"></i>
                        <div>
                            <p class="text-[11px] font-black uppercase tracking-wider {{ $hasIzin ? 'text-rose-700' : 'text-slate-600' }}">Izin</p>
                            <p class="text-xs font-bold text-slate-800">{{ $hasIzin ? 'Tercatat' : '-' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Form Catat Absen --}}
                <div x-show="showAbsenForm" x-transition style="display:none;" class="border-t border-slate-200 pt-5">
                    <form action="{{ route('attendance.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @csrf
                        <input type="hidden" name="latitude" id="admin_lat">
                        <input type="hidden" name="longitude" id="admin_lng">

                        <div>
                            <label for="admin_att_type" class="block text-xs font-black uppercase tracking-wider text-slate-600 mb-1.5">Tipe Absen</label>
                            <select id="admin_att_type" name="type" required class="w-full min-h-[44px] bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3 text-xs font-bold text-slate-800 outline-none focus:border-primary-600">
                                <option value="check_in">Check In (Masuk)</option>
                                <option value="check_out">Check Out (Pulang)</option>
                                <option value="sakit">Sakit</option>
                                <option value="izin">Izin</option>
                            </select>
                        </div>
                        <div>
                            <label for="admin_loc" class="block text-xs font-black uppercase tracking-wider text-slate-600 mb-1.5">Keterangan Lokasi</label>
                            <input type="text" name="location" id="admin_loc" placeholder="Nama kantor / lokasi..." class="w-full min-h-[44px] bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3 text-xs font-bold text-slate-800 outline-none focus:border-primary-600">
                        </div>

                        <div class="sm:col-span-2">
                            <x-gps-detector latId="admin_lat" lngId="admin_lng" statusId="admin-gps-status" locationId="admin_loc" />
                        </div>

                        <div>
                            <label for="admin_att_attachment" class="block text-xs font-black uppercase tracking-wider text-slate-600 mb-1.5">Foto Selfie Bukti (opsional)</label>
                            <input id="admin_att_attachment" type="file" name="attachment" accept="image/*" class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-2.5 text-xs font-bold text-slate-800 outline-none focus:border-primary-600">
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full min-h-[44px] bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-wider py-3.5 rounded-2xl shadow-md transition-colors flex items-center justify-center gap-2">
                                <i class="fa-solid fa-stamp" aria-hidden="true"></i> Kirim Absensi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            {{-- Header Page Title & Export Button --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="space-y-1">
                    <h1 class="text-2xl sm:text-3xl font-montserrat font-black text-slate-900 uppercase tracking-tight italic">
                        Log Absensi <span class="text-primary-600">GPS Pegawai</span>
                    </h1>
                    <div class="w-12 h-1 bg-primary-500 rounded-full"></div>
                </div>
                
                {{-- Export Button (Excel/CSV) --}}
                <a href="{{ route('admin.attendance.export', request()->all()) }}" class="min-h-[44px] px-5 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl text-xs font-black uppercase tracking-wider flex items-center justify-center gap-2 shadow-sm transition-colors">
                    <i class="fa-solid fa-file-excel text-sm" aria-hidden="true"></i> Export Excel
                </a>
            </div>

            {{-- Filter Bar sesuai Kolom --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm space-y-3">
                <form action="{{ route('admin.attendance') }}" method="GET" class="flex flex-wrap items-center gap-3 text-xs font-bold text-slate-700">
                    
                    <div class="flex items-center gap-2">
                        <label for="att_search">Pegawai:</label>
                        <input id="att_search" type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama / Email..." class="min-h-[40px] border border-slate-300 rounded-xl px-3 py-1.5 text-xs focus:border-primary-600 outline-none w-48">
                    </div>

                    <div class="flex items-center gap-2">
                        <label for="att_type">Tipe Absen:</label>
                        <select id="att_type" name="type" class="min-h-[40px] border border-slate-300 rounded-xl px-3 py-1.5 text-xs focus:border-primary-600 outline-none w-36">
                            <option value="">Semua Tipe</option>
                            <option value="check_in" {{ request('type') == 'check_in' ? 'selected' : '' }}>CHECK IN</option>
                            <option value="check_out" {{ request('type') == 'check_out' ? 'selected' : '' }}>CHECK OUT</option>
                            <option value="sakit" {{ request('type') == 'sakit' ? 'selected' : '' }}>SAKIT</option>
                            <option value="izin" {{ request('type') == 'izin' ? 'selected' : '' }}>IZIN</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <label for="att_start">Dari:</label>
                        <input id="att_start" type="date" name="start_date" value="{{ request('start_date') }}" class="min-h-[40px] border border-slate-300 rounded-xl px-3 py-1.5 text-xs focus:border-primary-600 outline-none">
                    </div>

                    <div class="flex items-center gap-2">
                        <label for="att_end">Sampai:</label>
                        <input id="att_end" type="date" name="end_date" value="{{ request('end_date') }}" class="min-h-[40px] border border-slate-300 rounded-xl px-3 py-1.5 text-xs focus:border-primary-600 outline-none">
                    </div>

                    <button type="submit" class="min-h-[40px] bg-primary-600 hover:bg-primary-700 text-white px-4 py-1.5 rounded-xl text-xs flex items-center gap-1.5 transition-colors">
                        <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i> Filter
                    </button>
                    
                    @if(request()->anyFilled(['search', 'type', 'start_date', 'end_date']))
                        <a href="{{ route('admin.attendance') }}" class="min-h-[40px] inline-flex items-center bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-1.5 rounded-xl text-xs">Reset</a>
                    @endif

                </form>
            </div>

            <!-- Table of Attendance Logs -->
            <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 space-y-6 shadow-sm">
                @if($attendances->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-200 text-xs font-black uppercase tracking-wider text-slate-600">
                                    <th class="pb-4">Pegawai</th>
                                    <th class="pb-4">Tipe Absen</th>
                                    <th class="pb-4">Tanggal &amp; Waktu</th>
                                    <th class="pb-4">Lokasi / Keterangan</th>
                                    <th class="pb-4">Koordinat GPS</th>
                                    <th class="pb-4 text-right">Selfie Bukti</th>
                                </tr>
                            </thead>
                            <tbody class="text-xs font-semibold text-slate-700 uppercase tracking-wide">
                                @foreach($attendances as $att)
                                    <tr class="border-b border-slate-100 last:border-0 hover:bg-slate-50 transition-colors">
                                        <td class="py-4 pr-4">
                                            <span class="block text-slate-800 font-bold">{{ $att->user->name ?? 'Unknown User' }}</span>
                                            <span class="block text-xs text-slate-600 font-bold uppercase tracking-wider mt-0.5">{{ $att->user->email ?? '-' }}</span>
                                        </td>
                                        <td class="py-4 pr-4 whitespace-nowrap">
                                            @if($att->type === 'check_in')
                                                <span class="inline-block whitespace-nowrap text-xs font-black uppercase tracking-wider text-emerald-700">Check In</span>
                                            @elseif($att->type === 'check_out')
                                                <span class="inline-block whitespace-nowrap text-xs font-black uppercase tracking-wider text-[#063f5c]">Check Out</span>
                                            @elseif($att->type === 'sakit')
                                                <span class="inline-block whitespace-nowrap text-xs font-black uppercase tracking-wider text-amber-800">Sakit</span>
                                            @else
                                                <span class="inline-block whitespace-nowrap text-xs font-black uppercase tracking-wider text-rose-700">Izin</span>
                                            @endif
                                        </td>
                                        <td class="py-4 pr-4 whitespace-nowrap text-slate-800 font-mono">{{ $att->formatWita('d M Y, H:i') }} <span class="text-xs font-sans font-bold text-primary-700">WITA</span></td>
                                        <td class="py-4 text-slate-700">{{ $att->location ?? '-' }}</td>
                                        <td class="py-4 text-slate-600 font-mono text-xs">
                                            @if($att->latitude && $att->longitude)
                                                <a href="https://www.google.com/maps/search/?api=1&query={{ $att->latitude }},{{ $att->longitude }}" target="_blank" rel="noopener noreferrer" class="text-primary-700 hover:underline inline-flex items-center gap-1">
                                                    <i class="fa-solid fa-map-location-dot" aria-hidden="true"></i> {{ $att->latitude }}, {{ $att->longitude }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="py-4 text-right">
                                            @if($att->attachment)
                                                <a href="{{ $att->attachment }}" target="_blank" rel="noopener noreferrer" class="min-h-[38px] bg-slate-100 hover:bg-slate-200 text-slate-700 font-black text-xs uppercase tracking-wider px-3.5 py-2 rounded-xl border border-slate-300 inline-flex items-center justify-center gap-1.5 w-fit ml-auto">
                                                    <i class="fa-solid fa-image" aria-hidden="true"></i> Lihat Foto
                                                </a>
                                            @else
                                                <span class="text-slate-600 font-bold">-</span>
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
                    <div class="py-16 text-center text-slate-600 font-bold uppercase tracking-wider">
                        <i class="fa-solid fa-user-clock text-slate-500 text-4xl mb-3 block" aria-hidden="true"></i>
                        Belum ada data absensi pegawai lapangan yang tercatat.
                    </div>
                @endif
            </div>
        </div>

    </div>
</section>
@endsection
