@extends('layouts.app')

@section('content')
<section x-data="{ 
    activeTab: 'todo', 
    modalNews: false, 
    modalCampaign: false, 
    modalDistribution: false, 
    modalUpdate: false, 
    activeCampaignId: null,
    activeCampaignTitle: '',
    expandedTodoId: null,
    modalTodoResult: false,
    activeTodoId: null,
    activeTodoTask: ''
}" class="max-w-7xl mx-auto px-4 sm:px-6 py-12">
    
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- SIDEBAR NAVIGATION MENU -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Profile Info Box -->
            <div class="bg-white border border-slate-100 p-6 rounded-[2rem] shadow-sm space-y-6">
                <div class="flex items-center space-x-3">
                    @if(Auth::user()->hasCustomPhoto())
                        <img src="{{ Auth::user()->profile_photo_url }}" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=063f5c&color=fff';" class="w-12 h-12 rounded-2xl object-cover" alt="">
                    @else
                        <div class="w-12 h-12 bg-primary-100 text-primary-700 rounded-2xl flex items-center justify-center font-bold text-base uppercase shrink-0">
                            {{ substr(Auth::user()->name, 0, 2) }}
                        </div>
                    @endif
                    <div class="min-w-0">
                        <h4 class="font-montserrat font-black uppercase text-xs sm:text-sm text-slate-800 leading-tight truncate">{{ Auth::user()->name }}</h4>
                        <span class="text-[9px] font-bold text-primary-600 uppercase block tracking-wider mt-1">Petugas Lapangan</span>
                    </div>
                </div>
                
                <hr class="border-slate-100">

                <!-- Vertical Tab List (Desktop) -->
                <div class="flex flex-col space-y-2 font-bold text-xs sm:text-sm uppercase tracking-wider text-slate-500">
                    <button @click="activeTab = 'campaigns'" :class="activeTab === 'campaigns' ? 'bg-primary-50 text-primary-700' : 'hover:bg-slate-50 hover:text-slate-800'" class="w-full flex items-center space-x-3 py-3.5 px-5 rounded-xl text-left transition-all outline-none whitespace-nowrap">
                        <i class="fa-solid fa-folder-open text-xs w-4"></i>
                        <span>Proyek Saya</span>
                    </button>
                    
                    <button @click="activeTab = 'news'" :class="activeTab === 'news' ? 'bg-primary-50 text-primary-700' : 'hover:bg-slate-50 hover:text-slate-800'" class="w-full flex items-center space-x-3 py-3.5 px-5 rounded-xl text-left transition-all outline-none whitespace-nowrap">
                        <i class="fa-solid fa-newspaper text-xs w-4"></i>
                        <span>Arsip Berita</span>
                    </button>

                    <button @click="activeTab = 'todo'" :class="activeTab === 'todo' ? 'bg-primary-50 text-primary-700' : 'hover:bg-slate-50 hover:text-slate-800'" class="w-full flex items-center space-x-3 py-3.5 px-5 rounded-xl text-left transition-all outline-none whitespace-nowrap">
                        <i class="fa-solid fa-list-check text-xs w-4"></i>
                        <span>To-Do List</span>
                    </button>

                    <button @click="activeTab = 'distributions'" :class="activeTab === 'distributions' ? 'bg-primary-50 text-primary-700' : 'hover:bg-slate-50 hover:text-slate-800'" class="w-full flex items-center space-x-3 py-3.5 px-5 rounded-xl text-left transition-all outline-none whitespace-nowrap">
                        <i class="fa-solid fa-truck-ramp-box text-xs w-4"></i>
                        <span>Laporan Saya</span>
                    </button>

                    <button @click="activeTab = 'attendance'" :class="activeTab === 'attendance' ? 'bg-primary-50 text-primary-700' : 'hover:bg-slate-50 hover:text-slate-800'" class="w-full flex items-center space-x-3 py-3.5 px-5 rounded-xl text-left transition-all outline-none whitespace-nowrap">
                        <i class="fa-solid fa-location-dot text-xs w-4"></i>
                        <span>Absensi GPS</span>
                    </button>

                    <button @click="activeTab = 'stats'" :class="activeTab === 'stats' ? 'bg-primary-50 text-primary-700' : 'hover:bg-slate-50 hover:text-slate-800'" class="w-full flex items-center space-x-3 py-3.5 px-5 rounded-xl text-left transition-all outline-none whitespace-nowrap">
                        <i class="fa-solid fa-chart-pie text-xs w-4"></i>
                        <span>Statistik</span>
                    </button>

                    <!-- Tombol Cepat: Lihat Website & Keluar Akun -->
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                        <a href="{{ route('home') }}" class="flex-1 flex items-center justify-center gap-1.5 py-2.5 px-3 rounded-xl text-[10px] font-black uppercase tracking-wider text-slate-600 bg-slate-50 hover:bg-slate-100 hover:text-primary-600 transition-all" title="Buka Halaman Depan Web">
                            <i class="fa-solid fa-globe"></i> Website
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center gap-1.5 py-2.5 px-3 rounded-xl text-[10px] font-black uppercase tracking-wider text-rose-600 bg-rose-50 hover:bg-rose-100 hover:text-rose-700 transition-all" title="Keluar dari Akun">
                                <i class="fa-solid fa-right-from-bracket"></i> Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- MAIN DASHBOARD CONTENT AREA -->
        <div class="lg:col-span-3 space-y-8">
            
            <!-- HEADER PANEL STAFF -->
            <header class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 bg-white p-6 sm:p-8 rounded-[2rem] border border-slate-200 shadow-sm relative overflow-hidden">
                <div class="space-y-1.5 relative z-10">
                    <div class="flex items-center gap-3">
                        <h1 class="text-xl sm:text-2xl font-montserrat font-black text-slate-900 tracking-tight leading-none uppercase italic">Panel <span class="text-primary-600">Staff Lapangan</span></h1>
                        <span class="bg-primary-600 text-white text-xs font-black uppercase px-3 py-1 rounded-full shadow-sm">Live</span>
                    </div>
                    <p class="text-xs text-slate-600 font-bold uppercase tracking-widest">Halo, {{ Auth::user()->name }} | Manajemen Aktivitas Lapangan</p>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-2.5 sm:gap-3 z-10 shrink-0">
                    <button type="button" @click="modalNews = true" class="min-h-[44px] px-5 py-3 bg-primary-50 hover:bg-primary-100 text-primary-700 rounded-xl font-black uppercase text-xs sm:text-sm tracking-wider transition-colors border border-primary-200 whitespace-nowrap">Tulis Berita</button>
                    <button type="button" @click="modalDistribution = true" class="min-h-[44px] px-5 py-3 bg-primary-50 hover:bg-primary-100 text-primary-700 rounded-xl font-black uppercase text-xs sm:text-sm tracking-wider transition-colors border border-primary-200 whitespace-nowrap">Lapor Penyaluran</button>
                    <button type="button" @click="modalCampaign = true" class="min-h-[44px] px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-black uppercase text-xs sm:text-sm tracking-wider transition-colors shadow-sm whitespace-nowrap">Kampanye Baru</button>
                </div>
            </header>

            <!-- TAB CONTENTS -->
            
            <!-- 1. CAMPAIGNS TAB -->
            <div x-show="activeTab === 'campaigns'" style="display: none;" class="space-y-6">
                @if($myCampaigns->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @foreach($myCampaigns as $c)
                            <div class="group bg-white rounded-[2rem] border border-slate-200 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300">
                                <div class="relative h-48 bg-slate-100 overflow-hidden">
                                    <img src="{{ $c->foto ?? 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=600' }}" width="600" height="320" loading="lazy" decoding="async" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" alt="{{ $c->title }}">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent" aria-hidden="true"></div>
                                    <span class="absolute top-4 left-4 px-3 py-1 bg-slate-900/90 text-white text-xs font-black uppercase rounded-full border border-white/15">{{ $c->category ?? 'Umum' }}</span>
                                </div>
                                <div class="p-6 sm:p-8 space-y-4">
                                    <h2 class="font-montserrat font-black text-slate-800 uppercase italic text-base group-hover:text-primary-600 transition-colors">{{ $c->title }}</h2>
                                    <div class="flex justify-between items-end border-t border-slate-200 pt-4">
                                        <div class="space-y-1">
                                            <p class="text-xs font-black text-slate-600 uppercase tracking-widest">Status</p>
                                            @if($c->status === 'approved')
                                                <span class="text-xs font-black uppercase px-2.5 py-1 rounded bg-emerald-50 text-emerald-800 tracking-wider">AKTIF</span>
                                            @else
                                                <span class="text-xs font-black uppercase px-2.5 py-1 rounded bg-amber-50 text-amber-800 tracking-wider">PROSES VERIFIKASI</span>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xs font-black text-slate-600 uppercase tracking-widest mb-0.5">Terkumpul</p>
                                            <p class="font-montserrat font-black text-primary-700 text-base italic">Rp {{ number_format($c->current_amount, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                    <button @click="activeCampaignId = {{ $c->id }}; activeCampaignTitle = '{{ addslashes($c->title) }}'; modalUpdate = true" class="w-full mt-2 py-4 bg-slate-900 hover:bg-primary-600 text-white rounded-xl text-xs font-black uppercase tracking-wider transition-all">Kirim Kabar Terbaru</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-16 text-center bg-white rounded-[2rem] border border-slate-100">
                        <i class="fa-solid fa-folder-open text-slate-200 text-4xl mb-3 block"></i>
                        <p class="text-slate-400 font-bold italic uppercase tracking-wider text-xs">Belum ada proyek yang Anda buat.</p>
                    </div>
                @endif
            </div>

            <!-- 2. NEWS ARCHIVE TAB -->
            <div x-show="activeTab === 'news'" style="display: none;" class="space-y-6">
                @if($myNews->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($myNews as $n)
                            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-all group space-y-4">
                                <div class="relative h-40 rounded-2xl overflow-hidden bg-slate-50">
                                    <img src="{{ $n->image ?? 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=600' }}" class="w-full h-full object-cover" alt="">
                                </div>
                                <div>
                                    <p class="text-[8px] font-black text-primary-600 uppercase tracking-[0.2em] mb-1">{{ $n->created_at->format('d M Y') }}</p>
                                    <h4 class="font-montserrat font-black text-slate-800 uppercase italic text-sm line-clamp-1">{{ $n->title }}</h4>
                                </div>
                                <p class="text-[10px] text-slate-400 font-semibold line-clamp-3 leading-relaxed">{{ $n->content }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-16 text-center bg-white rounded-[2rem] border border-slate-100">
                        <i class="fa-solid fa-newspaper text-slate-200 text-4xl mb-3 block"></i>
                        <p class="text-slate-400 font-bold italic uppercase tracking-wider text-xs">Belum ada berita yang Anda terbitkan.</p>
                    </div>
                @endif
            </div>

            <!-- 3. TO-DO LIST TAB (TRELLO KANBAN BOARD) -->
            <div x-show="activeTab === 'todo'" class="space-y-6">
                <x-trello-board 
                    :todos="$todos" 
                    :canAssign="false" 
                    :storeRoute="route('field.todo.store')" 
                    :showViewToggle="true" 
                />
            </div>

            <!-- 4. DISTRIBUTION REPORTS TAB -->
            <div x-show="activeTab === 'distributions'" style="display: none;" class="space-y-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="font-montserrat font-black text-slate-900 uppercase tracking-tighter italic text-base">Laporan <span class="text-primary-600">Penyaluran Saya</span></h3>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">{{ $myDistributions->count() }} laporan tercatat</p>
                    </div>
                    <button @click="modalDistribution = true" class="flex items-center gap-2 px-6 py-3.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-xs sm:text-sm font-black uppercase tracking-wider shadow-lg shadow-primary-600/10">
                        <i class="fa-solid fa-plus"></i> Lapor Baru
                    </button>
                </div>

                @if($myDistributions->count() > 0)
                    <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 shadow-sm">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-slate-100 text-[10px] font-black uppercase tracking-wider text-slate-400">
                                        <th class="pb-4">Judul Penyaluran</th>
                                        <th class="pb-4">Penerima</th>
                                        <th class="pb-4">Nominal</th>
                                        <th class="pb-4">Lokasi</th>
                                        <th class="pb-4">Tanggal</th>
                                        <th class="pb-4 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                                    @foreach($myDistributions as $d)
                                        <tr class="border-b border-slate-50 last:border-0">
                                            <td class="py-4 pr-4">
                                                <span class="block text-slate-800">{{ $d->title }}</span>
                                                <span class="block text-[9px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">Program: {{ $d->campaign->title ?? 'Umum' }}</span>
                                            </td>
                                            <td class="py-4">{{ $d->recipient_name }}</td>
                                            <td class="py-4 text-primary-700">Rp {{ number_format($d->amount, 0, ',', '.') }}</td>
                                            <td class="py-4">{{ $d->location_name ?? '-' }}</td>
                                            <td class="py-4">{{ $d->distribution_date ? $d->distribution_date->format('d M Y') : '-' }}</td>
                                            <td class="py-4 text-right">
                                                @if($d->photo_url)
                                                    <a href="{{ $d->photo_url }}" target="_blank" class="text-primary-600 hover:text-primary-800 text-xs font-black uppercase tracking-wider">Lihat Foto</a>
                                                @else
                                                    <span class="text-slate-400 text-xs font-bold uppercase tracking-wider">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="py-16 text-center bg-white rounded-[2rem] border border-slate-100">
                        <i class="fa-solid fa-truck-ramp-box text-slate-200 text-4xl mb-3 block"></i>
                        <p class="text-slate-400 font-bold italic uppercase tracking-wider text-xs">Belum ada laporan penyaluran yang Anda kirimkan.</p>
                    </div>
                @endif
            </div>

            <!-- 5. ATTENDANCE TAB -->
            <div x-show="activeTab === 'attendance'" style="display: none;" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- GPS widget -->
                <div class="lg:col-span-2 space-y-6">
                    <x-live-clock variant="card" />

                    <div class="bg-white border border-slate-100 p-8 rounded-[2rem] shadow-xl space-y-6">
                        <h3 class="font-montserrat font-black uppercase text-sm text-slate-800 tracking-wider">Absensi GPS Harian</h3>
                        
                        <form action="{{ route('field.attendance.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <input type="hidden" name="latitude" id="lat">
                        <input type="hidden" name="longitude" id="lng">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Tipe Absen</label>
                                <select name="type" required class="w-full bg-slate-50 border border-slate-150 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all">
                                    <option value="check_in">Check-In Masuk</option>
                                    <option value="check_out">Check-Out Pulang</option>
                                    <option value="izin">Izin</option>
                                    <option value="sakit">Sakit</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Foto Bukti / Selfi (Opsional)</label>
                                <input type="file" name="attachment" class="w-full bg-slate-50 border border-slate-150 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Lokasi / Keterangan Tambahan</label>
                            <input type="text" name="location" id="field-loc" placeholder="Contoh: Kantor Cabang / Alamat Penyaluran" class="w-full bg-slate-50 border border-slate-150 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all">
                        </div>

                        <x-gps-detector latId="lat" lngId="lng" statusId="field-gps-status" locationId="field-loc" />

                        <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-black text-sm uppercase tracking-wider py-4.5 rounded-2xl shadow-lg transition duration-300">
                            Kirim Absensi <i class="fa-solid fa-stamp ml-2"></i>
                        </button>
                    </form>

                    <!-- Today Attendance Logs -->
                    <div class="pt-6 border-t border-slate-100 space-y-4">
                        <h4 class="font-montserrat font-black uppercase text-xs text-slate-800 tracking-wider">Absen Hari Ini</h4>
                        <div class="space-y-3">
                            @forelse($todayAttendance as $att)
                                <div class="flex items-center justify-between text-xs font-bold text-slate-600 bg-slate-50 border border-slate-100/50 p-4 rounded-xl">
                                    <span class="uppercase tracking-wider">
                                        @if($att->type === 'check_in') Check In @elseif($att->type === 'check_out') Check Out @else {{ $att->type }} @endif
                                    </span>
                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">{{ $att->created_at->format('H:i') }} WITA</span>
                                </div>
                            @empty
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider text-center py-2">Anda belum melakukan absen hari ini.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Target sidebar -->
                <div class="bg-white border border-slate-100 p-8 rounded-[2rem] shadow-sm space-y-6 flex flex-col justify-center">
                    <h3 class="font-montserrat font-black uppercase text-sm text-slate-800 tracking-wider text-center">Target Bulan Ini</h3>
                    <div class="space-y-4 text-center py-6">
                        @if($target)
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Target Alokasi Donasi</span>
                            <h2 class="text-xl sm:text-2xl font-montserrat font-black text-slate-900 leading-none mt-1">
                                Rp {{ number_format($target->target_amount, 0, ',', '.') }}
                            </h2>
                            <p class="text-[9px] text-slate-500 font-bold uppercase tracking-wider mt-2">Periode: {{ $target->month_period->format('F Y') }}</p>
                        @else
                            <i class="fa-solid fa-circle-nodes text-slate-200 text-4xl block mb-2 mx-auto"></i>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Belum ada target bulanan dari admin.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- 6. STATISTICS TAB -->
            <div x-show="activeTab === 'stats'" style="display: none;" class="space-y-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm text-center space-y-1">
                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block">Proyek Kampanye</span>
                        <h3 class="text-xl font-montserrat font-black text-slate-800 leading-none pt-1">{{ $stats['totalCampaigns'] }}</h3>
                    </div>
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm text-center space-y-1">
                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block">Berita Diterbit</span>
                        <h3 class="text-xl font-montserrat font-black text-slate-800 leading-none pt-1">{{ $stats['totalContent'] }}</h3>
                    </div>
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm text-center space-y-1">
                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block">Donasi Terkumpul</span>
                        <h3 class="text-xl font-montserrat font-black text-primary-650 leading-none pt-1">Rp {{ number_format($stats['totalFunds'], 0, ',', '.') }}</h3>
                    </div>
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm text-center space-y-1">
                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block">Kehadiran (Check-In)</span>
                        <h3 class="text-xl font-montserrat font-black text-slate-800 leading-none pt-1">{{ $stats['presentDays'] }} Hari</h3>
                    </div>
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm text-center space-y-1">
                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block">Efektivitas Tugas</span>
                        <h3 class="text-xl font-montserrat font-black text-slate-800 leading-none pt-1">{{ $stats['effectiveness'] }}%</h3>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- Staff Action Modals -->

    <!-- A. NEW CAMPAIGN MODAL -->
    <div x-show="modalCampaign" @keydown.escape.window="modalCampaign = false" role="dialog" aria-modal="true" aria-labelledby="modal-field-campaign-title" style="display: none;" class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-950/70">
        <div @click.away="modalCampaign = false" class="bg-white border border-slate-200 w-full max-w-lg rounded-3xl p-8 shadow-2xl relative space-y-6 flex flex-col max-h-[90vh]">
            <button type="button" @click="modalCampaign = false" aria-label="Tutup modal kampanye baru" class="min-w-[44px] min-h-[44px] inline-flex items-center justify-center absolute top-4 right-4 text-slate-600 hover:text-slate-900"><i class="fa-solid fa-xmark text-lg" aria-hidden="true"></i></button>
            <div class="space-y-1">
                <h2 id="modal-field-campaign-title" class="text-lg font-montserrat font-black uppercase text-slate-800 italic">Ajukan <span class="text-primary-600">Kampanye Baru</span></h2>
                <p class="text-xs text-slate-600 font-bold uppercase tracking-wider">Usulkan program bantuan kemanusiaan baru</p>
            </div>
            <div class="overflow-y-auto pr-2 space-y-4 max-h-[60vh] no-scrollbar">
                <form action="{{ route('field.campaigns.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 pr-1">
                    @csrf
                    <div>
                        <label for="fc_title" class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Judul Program</label>
                        <input id="fc_title" type="text" name="title" required placeholder="Contoh: Wakaf Quran Pelosok" class="w-full min-h-[44px] bg-slate-50 border border-slate-300 rounded-2xl px-5 py-3.5 text-xs font-bold text-slate-800 outline-none transition-colors focus:border-primary-600">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="fc_category" class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Kategori</label>
                            <select id="fc_category" name="category" required class="w-full min-h-[44px] bg-slate-50 border border-slate-300 rounded-2xl px-5 py-3.5 text-xs font-bold text-slate-800 outline-none focus:border-primary-600">
                                <option value="Kesehatan">Kesehatan</option>
                                <option value="Pendidikan">Pendidikan</option>
                                <option value="Dakwah">Dakwah</option>
                                <option value="Kemanusiaan">Kemanusiaan</option>
                            </select>
                        </div>
                        <div>
                            <label for="fc_target_amount" class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Target Dana (Rupiah)</label>
                            <input id="fc_target_amount" type="number" name="target_amount" required placeholder="Contoh: 15000000" class="w-full min-h-[44px] bg-slate-50 border border-slate-300 rounded-2xl px-5 py-3.5 text-xs font-bold text-slate-800 outline-none focus:border-primary-600">
                        </div>
                    </div>
                    <div>
                        <label for="fc_description" class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Deskripsi Lengkap</label>
                        <textarea id="fc_description" name="description" required rows="4" placeholder="Tuliskan cerita, latar belakang, dan urgensi program bantuan ini..." class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-5 py-3.5 text-xs font-bold text-slate-800 outline-none focus:border-primary-600"></textarea>
                    </div>
                    <div>
                        <label for="fc_foto" class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Foto Sampul Kampanye</label>
                        <input id="fc_foto" type="file" name="foto" required class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-5 py-3.5 text-xs font-bold text-slate-800 outline-none focus:border-primary-600">
                    </div>
                    <button type="submit" class="w-full min-h-[44px] bg-primary-600 hover:bg-primary-700 text-white font-black text-sm uppercase tracking-wider py-4 rounded-2xl shadow-md transition-colors">Ajukan Kampanye <i class="fa-solid fa-paper-plane ml-2" aria-hidden="true"></i></button>
                </form>
            </div>
        </div>
    </div>

    <!-- B. NEW STORY/NEWS ARTICLE MODAL -->
    <div x-show="modalNews" @keydown.escape.window="modalNews = false" role="dialog" aria-modal="true" aria-labelledby="modal-field-news-title" style="display: none;" class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-950/70">
        <div @click.away="modalNews = false" class="bg-white border border-slate-200 w-full max-w-lg rounded-3xl p-8 shadow-2xl relative space-y-6 flex flex-col max-h-[90vh]">
            <button type="button" @click="modalNews = false" aria-label="Tutup modal berita kebaikan" class="min-w-[44px] min-h-[44px] inline-flex items-center justify-center absolute top-4 right-4 text-slate-600 hover:text-slate-900"><i class="fa-solid fa-xmark text-lg" aria-hidden="true"></i></button>
            <div class="space-y-1">
                <h2 id="modal-field-news-title" class="text-lg font-montserrat font-black uppercase text-slate-800 italic">Terbitkan <span class="text-primary-600">Berita Kebaikan</span></h2>
                <p class="text-xs text-slate-600 font-bold uppercase tracking-wider">Bagikan cerita inspiratif penyaluran bantuan langsung dari lapangan</p>
            </div>
            <div class="overflow-y-auto pr-2 space-y-4 max-h-[60vh] no-scrollbar">
                <form action="{{ route('field.news.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 pr-1">
                    @csrf
                    <div>
                        <label for="fn_title" class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Judul Berita</label>
                        <input id="fn_title" type="text" name="title" required placeholder="Contoh: Senyum Kebahagiaan Anak-anak Yatim NTT" class="w-full min-h-[44px] bg-slate-50 border border-slate-300 rounded-2xl px-5 py-3.5 text-xs font-bold text-slate-800 outline-none focus:border-primary-600">
                    </div>
                    <div>
                        <label for="fn_tag" class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Tag / Kategori Berita</label>
                        <input id="fn_tag" type="text" name="tag" required placeholder="Contoh: Penyaluran / Inspirasi / Dampak" class="w-full min-h-[44px] bg-slate-50 border border-slate-300 rounded-2xl px-5 py-3.5 text-xs font-bold text-slate-800 outline-none focus:border-primary-600">
                    </div>
                    <div>
                        <label for="fn_content" class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Konten / Isi Berita</label>
                        <textarea id="fn_content" name="content" required rows="5" placeholder="Tulis rincian cerita penyaluran berita..." class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-5 py-3.5 text-xs font-bold text-slate-800 outline-none focus:border-primary-600"></textarea>
                    </div>
                    <div>
                        <label for="fn_image" class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Foto Dokumentasi</label>
                        <input id="fn_image" type="file" name="image" required class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-5 py-3.5 text-xs font-bold text-slate-800 outline-none focus:border-primary-600">
                    </div>
                    <button type="submit" class="w-full min-h-[44px] bg-primary-600 hover:bg-primary-700 text-white font-black text-sm uppercase tracking-wider py-4 rounded-2xl shadow-md transition-colors">Terbitkan Berita <i class="fa-solid fa-cloud-arrow-up ml-2" aria-hidden="true"></i></button>
                </form>
            </div>
        </div>
    </div>

    <!-- C. REPORT DISTRIBUTION MODAL -->
    <div x-show="modalDistribution" @keydown.escape.window="modalDistribution = false" role="dialog" aria-modal="true" aria-labelledby="modal-field-dist-title" style="display: none;" class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-950/70">
        <div @click.away="modalDistribution = false" class="bg-white border border-slate-200 w-full max-w-lg rounded-3xl p-8 shadow-2xl relative space-y-6 flex flex-col max-h-[90vh]">
            <button type="button" @click="modalDistribution = false" aria-label="Tutup modal laporan penyaluran" class="min-w-[44px] min-h-[44px] inline-flex items-center justify-center absolute top-4 right-4 text-slate-600 hover:text-slate-900"><i class="fa-solid fa-xmark text-lg" aria-hidden="true"></i></button>
            <div class="space-y-1">
                <h2 id="modal-field-dist-title" class="text-lg font-montserrat font-black uppercase text-slate-800 italic">Kirim Laporan <span class="text-primary-600">Penyaluran Donasi</span></h2>
                <p class="text-xs text-slate-600 font-bold uppercase tracking-wider">Kirimkan rincian bukti alokasi bantuan untuk transparansi donatur</p>
            </div>
            <div class="overflow-y-auto pr-2 space-y-4 max-h-[60vh] no-scrollbar">
                <form action="{{ route('field.distributions.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 pr-1">
                    @csrf
                    <div>
                        <label for="fd_campaign" class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Program Kampanye Terkait</label>
                        <select id="fd_campaign" name="campaign_id" required class="w-full min-h-[44px] bg-slate-50 border border-slate-300 rounded-2xl px-5 py-3.5 text-xs font-bold text-slate-800 outline-none focus:border-primary-600">
                            @foreach($allCampaigns as $ac)
                                <option value="{{ $ac->id }}">{{ $ac->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="fd_title" class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Nama Laporan Penyaluran</label>
                            <input id="fd_title" type="text" name="title" required placeholder="Contoh: Berbagi beras Cicadas" class="w-full min-h-[44px] bg-slate-50 border border-slate-300 rounded-2xl px-5 py-3.5 text-xs font-bold text-slate-800 outline-none focus:border-primary-600">
                        </div>
                        <div>
                            <label for="fd_recipient" class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Penerima Manfaat / Kelompok</label>
                            <input id="fd_recipient" type="text" name="recipient_name" required placeholder="Contoh: Warga RT 02 Cicadas" class="w-full min-h-[44px] bg-slate-50 border border-slate-300 rounded-2xl px-5 py-3.5 text-xs font-bold text-slate-800 outline-none focus:border-primary-600">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="fd_amount" class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Nominal Dana Disalurkan (Rupiah)</label>
                            <input id="fd_amount" type="number" name="amount" required placeholder="Contoh: 1500000" class="w-full min-h-[44px] bg-slate-50 border border-slate-300 rounded-2xl px-5 py-3.5 text-xs font-bold text-slate-800 outline-none focus:border-primary-600">
                        </div>
                        <div>
                            <label for="fd_items" class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Rincian Barang yang Disalurkan</label>
                            <input id="fd_items" type="text" name="items_distributed" required placeholder="Contoh: 50 Kantong Beras @ 5kg" class="w-full min-h-[44px] bg-slate-50 border border-slate-300 rounded-2xl px-5 py-3.5 text-xs font-bold text-slate-800 outline-none focus:border-primary-600">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="fd_location" class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Nama Lokasi Penyaluran</label>
                            <input id="fd_location" type="text" name="location_name" placeholder="Contoh: Masjid Jami Al-Ikhlas" class="w-full min-h-[44px] bg-slate-50 border border-slate-300 rounded-2xl px-5 py-3.5 text-xs font-bold text-slate-800 outline-none focus:border-primary-600">
                        </div>
                        <div>
                            <label for="fd_maps" class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Link Google Maps (Opsional)</label>
                            <input id="fd_maps" type="url" name="maps_link" placeholder="https://maps.google.com/..." class="w-full min-h-[44px] bg-slate-50 border border-slate-300 rounded-2xl px-5 py-3.5 text-xs font-bold text-slate-800 outline-none focus:border-primary-600">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="dist_lat" class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Latitude GPS (Opsional)</label>
                            <input type="text" name="latitude" id="dist_lat" placeholder="Koordinat Lintang" class="w-full min-h-[44px] bg-slate-50 border border-slate-300 rounded-2xl px-5 py-3.5 text-xs font-bold text-slate-800 outline-none focus:border-primary-600">
                        </div>
                        <div>
                            <label for="dist_lng" class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Longitude GPS (Opsional)</label>
                            <input type="text" name="longitude" id="dist_lng" placeholder="Koordinat Bujur" class="w-full min-h-[44px] bg-slate-50 border border-slate-300 rounded-2xl px-5 py-3.5 text-xs font-bold text-slate-800 outline-none focus:border-primary-600">
                        </div>
                    </div>
                    <div>
                        <label for="fd_desc" class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Deskripsi / Keterangan Penyaluran</label>
                        <textarea id="fd_desc" name="description" required rows="3" placeholder="Tuliskan catatan kondisi atau detail proses alokasi bantuan kemanusiaan..." class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-5 py-3.5 text-xs font-bold text-slate-800 outline-none focus:border-primary-600"></textarea>
                    </div>
                    <div>
                        <label for="fd_photo" class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Foto Bukti Penyaluran Lapangan</label>
                        <input id="fd_photo" type="file" name="photo" class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-5 py-3.5 text-xs font-bold text-slate-800 outline-none focus:border-primary-600">
                    </div>
                    <button type="submit" class="w-full min-h-[44px] bg-primary-600 hover:bg-primary-700 text-white font-black text-sm uppercase tracking-wider py-4 rounded-2xl shadow-md transition-colors">Simpan Laporan <i class="fa-solid fa-shield-halved ml-2" aria-hidden="true"></i></button>
                </form>
            </div>
        </div>
    </div>

    <!-- D. SEND CAMPAIGN UPDATE (KABAR TERBARU) MODAL -->
    <div x-show="modalUpdate" @keydown.escape.window="modalUpdate = false" role="dialog" aria-modal="true" aria-labelledby="modal-field-update-title" style="display: none;" class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-950/70">
        <div @click.away="modalUpdate = false" class="bg-white border border-slate-200 w-full max-w-lg rounded-3xl p-8 shadow-2xl relative space-y-6 flex flex-col max-h-[90vh]">
            <button type="button" @click="modalUpdate = false" aria-label="Tutup modal kabar terbaru" class="min-w-[44px] min-h-[44px] inline-flex items-center justify-center absolute top-4 right-4 text-slate-600 hover:text-slate-900"><i class="fa-solid fa-xmark text-lg" aria-hidden="true"></i></button>
            <div class="space-y-1">
                <h2 id="modal-field-update-title" class="text-lg font-montserrat font-black uppercase text-slate-800 italic">Kirim <span class="text-primary-600">Kabar Terbaru</span></h2>
                <p class="text-xs text-slate-600 font-bold uppercase tracking-wider">Kirim ke: <span class="text-slate-900 font-bold" x-text="activeCampaignTitle"></span></p>
            </div>
            <div class="overflow-y-auto pr-2 space-y-4 max-h-[60vh] no-scrollbar">
                <form :action="'{{ url('/field/campaigns') }}/' + activeCampaignId + '/update'" method="POST" enctype="multipart/form-data" class="space-y-4 pr-1">
                    @csrf
                    <div>
                        <label for="fu_title" class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Judul Kabar Utama</label>
                        <input id="fu_title" type="text" name="title" required placeholder="Contoh: Pembelian Sembako Tahap 1" class="w-full min-h-[44px] bg-slate-50 border border-slate-300 rounded-2xl px-5 py-3.5 text-xs font-bold text-slate-800 outline-none focus:border-primary-600">
                    </div>
                    <div>
                        <label for="fu_content" class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Rincian Perkembangan Program</label>
                        <textarea id="fu_content" name="content" required rows="4" placeholder="Tuliskan update, progres pembelian barang, rencana penyaluran berikutnya..." class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-5 py-3.5 text-xs font-bold text-slate-800 outline-none focus:border-primary-600"></textarea>
                    </div>
                    <div>
                        <label for="fu_image" class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Foto Dokumentasi Pendukung</label>
                        <input id="fu_image" type="file" name="image" class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-5 py-3.5 text-xs font-bold text-slate-800 outline-none focus:border-primary-600">
                    </div>
                    <button type="submit" class="w-full min-h-[44px] bg-primary-600 hover:bg-primary-700 text-white font-black text-sm uppercase tracking-wider py-4 rounded-2xl shadow-md transition-colors">Kirim Kabar Perkembangan <i class="fa-solid fa-bullhorn ml-2" aria-hidden="true"></i></button>
                </form>
            </div>
        </div>
    </div>

    <!-- E. MARK TODO COMPLETED & UPLOAD PROOF MODAL -->
    <div x-show="modalTodoResult" @keydown.escape.window="modalTodoResult = false" role="dialog" aria-modal="true" aria-labelledby="modal-field-todo-title" style="display: none;" class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-950/70">
        <div @click.away="modalTodoResult = false" class="bg-white border border-slate-200 w-full max-w-lg rounded-3xl p-8 shadow-2xl relative space-y-6 flex flex-col max-h-[90vh]">
            <button type="button" @click="modalTodoResult = false" aria-label="Tutup modal selesaikan tugas" class="min-w-[44px] min-h-[44px] inline-flex items-center justify-center absolute top-4 right-4 text-slate-600 hover:text-slate-900"><i class="fa-solid fa-xmark text-lg" aria-hidden="true"></i></button>
            <div class="space-y-1">
                <h2 id="modal-field-todo-title" class="text-lg font-montserrat font-black uppercase text-slate-800 italic">Selesaikan <span class="text-primary-600">Tugas Lapangan</span></h2>
                <p class="text-xs text-slate-600 font-bold uppercase tracking-wider">Tugas: <span class="text-slate-900 font-bold" x-text="activeTodoTask"></span></p>
            </div>
            <div class="overflow-y-auto pr-2 space-y-4 max-h-[60vh] no-scrollbar">
                <form :action="'{{ url('/field/todo') }}/' + activeTodoId + '/update'" method="POST" enctype="multipart/form-data" class="space-y-4 pr-1">
                    @csrf
                    <input type="hidden" name="status" value="completed">
                    <div>
                        <label for="ft_note" class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Laporan Hasil Pengerjaan</label>
                        <textarea id="ft_note" name="result_note" required rows="3" placeholder="Tuliskan keterangan penyelesaian tugas lapangan..." class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-5 py-3.5 text-xs font-bold text-slate-800 outline-none focus:border-primary-600"></textarea>
                    </div>
                    <div>
                        <label for="ft_attachment" class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Foto Bukti Hasil Kerja (Selfie/Penyaluran)</label>
                        <input id="ft_attachment" type="file" name="result_attachment" required class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-5 py-3.5 text-xs font-bold text-slate-800 outline-none focus:border-primary-600">
                    </div>
                    <button type="submit" class="w-full min-h-[44px] bg-primary-600 hover:bg-primary-700 text-white font-black text-sm uppercase tracking-wider py-4 rounded-2xl shadow-md transition-colors">Kirim Bukti &amp; Selesaikan Tugas <i class="fa-solid fa-upload ml-2" aria-hidden="true"></i></button>
                </form>
            </div>
        </div>
    </div>

</section>
@endsection
