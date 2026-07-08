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
                    @if(Auth::user()->profile_photo)
                        <img src="{{ Auth::user()->profile_photo }}" class="w-12 h-12 rounded-2xl object-cover" alt="">
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
                </div>
            </div>
        </div>

        <!-- MAIN DASHBOARD CONTENT AREA -->
        <div class="lg:col-span-3 space-y-8">
            
            <!-- HEADER PANEL STAFF -->
            <header class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 bg-white p-6 sm:p-8 rounded-[2rem] border border-slate-100 shadow-sm relative overflow-hidden">
                <div class="space-y-1.5 relative z-10">
                    <div class="flex items-center gap-3">
                        <h1 class="text-xl sm:text-2xl font-montserrat font-black text-slate-900 tracking-tight leading-none uppercase italic">Panel <span class="text-primary-600">Staff Lapangan</span></h1>
                        <span class="bg-primary-600 text-white text-[9px] font-black uppercase px-3 py-1 rounded-full shadow-lg shadow-primary-600/10">Live</span>
                    </div>
                    <p class="text-[9px] sm:text-[10px] text-slate-400 font-bold uppercase tracking-widest">Halo, {{ Auth::user()->name }} — Manajemen Aktivitas Lapangan</p>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-2.5 sm:gap-3 z-10 shrink-0">
                    <button @click="modalNews = true" class="px-6 py-3.5 bg-primary-50 hover:bg-primary-100 text-primary-700 rounded-xl font-black uppercase text-xs sm:text-sm tracking-wider transition-all border border-primary-100 whitespace-nowrap">Tulis Berita</button>
                    <button @click="modalDistribution = true" class="px-6 py-3.5 bg-primary-50 hover:bg-primary-100 text-primary-700 rounded-xl font-black uppercase text-xs sm:text-sm tracking-wider transition-all border border-primary-100 whitespace-nowrap">Lapor Penyaluran</button>
                    <button @click="modalCampaign = true" class="px-7 py-3.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-black uppercase text-xs sm:text-sm tracking-wider transition-all shadow-lg shadow-primary-600/15 whitespace-nowrap">Kampanye Baru</button>
                </div>
            </header>

            <!-- TAB CONTENTS -->
            
            <!-- 1. CAMPAIGNS TAB -->
            <div x-show="activeTab === 'campaigns'" style="display: none;" class="space-y-6">
                @if($myCampaigns->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @foreach($myCampaigns as $c)
                            <div class="group bg-white rounded-[2rem] border border-slate-100 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-305">
                                <div class="relative h-48 bg-slate-100 overflow-hidden">
                                    <img src="{{ $c->foto ?? 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=600' }}" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105" alt="">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                                    <span class="absolute top-6 left-6 px-3 py-1 bg-white/20 backdrop-blur-md text-white text-[9px] font-black uppercase rounded-full border border-white/20">{{ $c->category ?? 'Umum' }}</span>
                                </div>
                                <div class="p-6 sm:p-8 space-y-4">
                                    <h3 class="font-montserrat font-black text-slate-800 uppercase italic text-base group-hover:text-primary-600 transition-colors">{{ $c->title }}</h3>
                                    <div class="flex justify-between items-end border-t border-slate-50 pt-4">
                                        <div class="space-y-1">
                                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Status</p>
                                            @if($c->status === 'approved')
                                                <span class="text-[9px] font-black uppercase px-2.5 py-1 rounded bg-emerald-50 text-emerald-700 tracking-wider">AKTIF</span>
                                            @else
                                                <span class="text-[9px] font-black uppercase px-2.5 py-1 rounded bg-amber-50 text-amber-700 tracking-wider">PROSES VERIFIKASI</span>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Terkumpul</p>
                                            <p class="font-montserrat font-black text-primary-600 text-base italic">Rp {{ number_format($c->current_amount, 0, ',', '.') }}</p>
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

            <!-- 3. TO-DO LIST TAB -->
            <div x-show="activeTab === 'todo'" class="space-y-6">
                <div class="bg-white p-6 sm:p-8 rounded-[2rem] border border-slate-100 shadow-sm space-y-6">
                    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                        <h3 class="font-montserrat font-black text-slate-900 uppercase tracking-tighter italic text-base">Daftar <span class="text-primary-600">Tugas Lapangan</span></h3>
                        <div class="flex items-center gap-3">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ $todos->where('status', 'completed')->count() }}/{{ $todos->count() }} Selesai</span>
                            <div class="w-20 h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-primary-600 rounded-full transition-all duration-500" style="width: {{ $todos->count() ? ($todos->where('status', 'completed')->count() / $todos->count()) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Tambah Tugas Internal Staff -->
                    <div class="bg-slate-50 rounded-3xl p-6 space-y-4 border border-slate-100">
                        <h4 class="text-[10px] font-black uppercase tracking-wider text-slate-400">Buat Tugas Lapangan Mandiri</h4>
                        <form action="{{ route('field.todo.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="text" name="task" required placeholder="Judul tugas lapangan..." class="w-full p-4 bg-white border border-slate-150 rounded-2xl outline-none focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 font-bold text-xs transition-all uppercase">
                            <textarea name="description" placeholder="Catatan tambahan / rincian pengerjaan tugas..." class="w-full p-4 bg-white border border-slate-150 rounded-2xl outline-none focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 font-semibold text-xs transition-all h-20 resize-none"></textarea>
                            <div class="flex flex-col sm:flex-row gap-3 items-end">
                                <div class="flex-grow w-full">
                                    <label class="text-[9px] font-black uppercase tracking-wider text-slate-400 mb-1.5 block ml-1">Tenggat Waktu (Deadline)</label>
                                    <input name="deadline" type="datetime-local" class="w-full p-3.5 bg-white border border-slate-150 rounded-2xl outline-none focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 font-bold text-xs transition-all">
                                </div>
                                <button type="submit" class="w-full sm:w-auto px-8 py-4 bg-primary-600 hover:bg-primary-700 text-white rounded-2xl font-black uppercase text-xs tracking-wider shrink-0">Simpan Tugas</button>
                            </div>
                        </form>
                    </div>

                    <!-- Todos list container -->
                    @if($todos->count() > 0)
                        <div class="space-y-3">
                            @foreach($todos as $t)
                                <div @click="expandedTodoId = (expandedTodoId === {{ $t->id }} ? null : {{ $t->id }})" :class="expandedTodoId === {{ $t->id }} ? 'border-primary-200 bg-primary-50/5' : 'border-slate-100 bg-slate-50/20'" class="p-5 rounded-2xl border transition-all duration-205 cursor-pointer space-y-4 hover:border-primary-100">
                                    <div class="flex justify-between items-center gap-4">
                                        <div class="flex items-center space-x-3.5">
                                            <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 border {{ $t->status === 'completed' ? 'bg-primary-100 text-primary-700 border-primary-100' : 'bg-white text-slate-400 border-slate-100' }}">
                                                <i class="fa-solid {{ $t->status === 'completed' ? 'fa-circle-check' : 'fa-clipboard-list' }}"></i>
                                            </div>
                                            <div>
                                                <span class="block text-xs font-bold text-slate-800 uppercase {{ $t->status === 'completed' ? 'line-through text-slate-400' : '' }}">{{ $t->task }}</span>
                                                @if($t->deadline)
                                                    <span class="text-[9px] font-bold uppercase tracking-wider text-amber-600"><i class="fa-regular fa-clock mr-1"></i> {{ $t->deadline->format('d M Y H:i') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <i class="fa-solid fa-chevron-down text-[9px] text-slate-400 transition-transform duration-200" :class="expandedTodoId === {{ $t->id }} ? 'rotate-180 text-primary-600' : ''"></i>
                                    </div>

                                    <!-- Expanded block -->
                                    <div x-show="expandedTodoId === {{ $t->id }}" @click.stop style="display: none;" class="border-t border-slate-100 pt-4 space-y-4">
                                        @if($t->description)
                                            <div>
                                                <span class="text-[9px] font-black uppercase tracking-wider text-slate-400 block mb-1">Rincian Tugas:</span>
                                                <p class="text-xs text-slate-600 font-semibold leading-relaxed bg-white border border-slate-100 p-4 rounded-xl">{{ $t->description }}</p>
                                            </div>
                                        @endif

                                        @if($t->result_note)
                                            <div>
                                                <span class="text-[9px] font-black uppercase tracking-wider text-emerald-600 block mb-1">Catatan Hasil Selesai:</span>
                                                <p class="text-xs text-slate-600 font-semibold leading-relaxed bg-emerald-50/20 border border-emerald-100/50 p-4 rounded-xl font-medium">"{{ $t->result_note }}"</p>
                                            </div>
                                        @endif

                                        <div class="flex items-center justify-between gap-4 flex-wrap pt-2">
                                            <div class="flex items-center gap-2">
                                                @if($t->status === 'pending')
                                                    <button @click="activeTodoId = {{ $t->id }}; activeTodoTask = '{{ addslashes($t->task) }}'; modalTodoResult = true" class="bg-primary-600 hover:bg-primary-700 text-white font-black text-[11px] uppercase tracking-wider px-5 py-3 rounded-xl shadow">Tandai Selesai & Upload Bukti</button>
                                                @else
                                                    <form action="{{ route('field.todo.update', $t->id) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="status" value="pending">
                                                        <button type="submit" class="bg-slate-100 hover:bg-amber-50 hover:text-amber-700 text-slate-500 font-black text-[11px] uppercase tracking-wider px-5 py-3 rounded-xl border border-slate-200/50">Batalkan Status Selesai</button>
                                                    </form>
                                                @endif

                                                @if($t->result_attachment)
                                                    <a href="{{ $t->result_attachment }}" target="_blank" class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-black text-[11px] uppercase tracking-wider px-5 py-3 rounded-xl border border-slate-200/50 flex items-center gap-1"><i class="fa-solid fa-image"></i> Lihat Foto Bukti</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-12 text-center">
                            <i class="fa-solid fa-check-double text-slate-200 text-4xl mb-3 block"></i>
                            <p class="text-slate-450 font-bold italic uppercase tracking-wider text-[10px] text-slate-400">Belum ada tugas lapangan yang direncanakan.</p>
                        </div>
                    @endif
                </div>
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
                <div class="lg:col-span-2 bg-white border border-slate-100 p-8 rounded-[2rem] shadow-xl space-y-6">
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
                            <input type="text" name="location" placeholder="Contoh: Kantor Cabang / Alamat Penyaluran" class="w-full bg-slate-50 border border-slate-150 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all">
                        </div>

                        <div id="gps-status" class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">
                            <i class="fa-solid fa-location-crosshairs animate-pulse mr-1"></i> Mendapatkan koordinat GPS Anda...
                        </div>

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
                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">{{ $att->created_at->format('H:i') }} WIB</span>
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
    <!-- ================= ALPINE MODALS (STAFF ACTIONS) ================= -->

    <!-- A. NEW CAMPAIGN MODAL -->
    <div x-show="modalCampaign" style="display: none;" class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm">
        <div @click.away="modalCampaign = false" class="bg-white border border-slate-100 w-full max-w-lg rounded-3xl p-8 shadow-2xl relative space-y-6 flex flex-col max-h-[90vh]">
            <button @click="modalCampaign = false" class="absolute top-6 right-6 text-slate-400 hover:text-slate-655"><i class="fa-solid fa-xmark text-lg"></i></button>
            <div class="space-y-1">
                <h3 class="text-lg font-montserrat font-black uppercase text-slate-800 italic">Ajukan <span class="text-primary-600">Kampanye Baru</span></h3>
                <p class="text-[11px] text-slate-400 font-bold uppercase tracking-wider">Usulkan program bantuan kemanusiaan baru</p>
            </div>
            <div class="overflow-y-auto pr-2 space-y-4 max-h-[60vh] no-scrollbar">
                <form action="{{ route('field.campaigns.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 pr-1">
                    @csrf
                    <div>
                        <label class="block text-[11px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Judul Program</label>
                        <input type="text" name="title" required placeholder="Contoh: Wakaf Quran Pelosok" class="w-full bg-slate-50 border border-slate-150 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all focus:border-primary-500">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Kategori</label>
                            <select name="category" required class="w-full bg-slate-50 border border-slate-150 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none focus:border-primary-500">
                                <option value="Kesehatan">Kesehatan</option>
                                <option value="Pendidikan">Pendidikan</option>
                                <option value="Dakwah">Dakwah</option>
                                <option value="Kemanusiaan">Kemanusiaan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Target Dana (Rupiah)</label>
                            <input type="number" name="target_amount" required placeholder="Contoh: 15000000" class="w-full bg-slate-50 border border-slate-150 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none focus:border-primary-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Deskripsi Lengkap</label>
                        <textarea name="description" required rows="4" placeholder="Tuliskan cerita, latar belakang, dan urgensi program bantuan ini..." class="w-full bg-slate-50 border border-slate-150 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none focus:border-primary-500"></textarea>
                    </div>
                    <div>
                        <label class="block text-[11px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Foto Sampul Kampanye</label>
                        <input type="file" name="foto" required class="w-full bg-slate-50 border border-slate-150 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none focus:border-primary-500">
                    </div>
                    <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-black text-sm uppercase tracking-wider py-4.5 rounded-2xl shadow-lg transition duration-300">Ajukan Kampanye <i class="fa-solid fa-paper-plane ml-2"></i></button>
                </form>
            </div>
        </div>
    </div>

    <!-- B. NEW STORY/NEWS ARTICLE MODAL -->
    <div x-show="modalNews" style="display: none;" class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm">
        <div @click.away="modalNews = false" class="bg-white border border-slate-100 w-full max-w-lg rounded-3xl p-8 shadow-2xl relative space-y-6 flex flex-col max-h-[90vh]">
            <button @click="modalNews = false" class="absolute top-6 right-6 text-slate-400 hover:text-slate-655"><i class="fa-solid fa-xmark text-lg"></i></button>
            <div class="space-y-1">
                <h3 class="text-lg font-montserrat font-black uppercase text-slate-800 italic">Terbitkan <span class="text-primary-600">Berita Kebaikan</span></h3>
                <p class="text-[11px] text-slate-400 font-bold uppercase tracking-wider">Bagikan cerita inspiratif penyaluran bantuan langsung dari lapangan</p>
            </div>
            <div class="overflow-y-auto pr-2 space-y-4 max-h-[60vh] no-scrollbar">
                <form action="{{ route('field.news.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 pr-1">
                    @csrf
                    <div>
                        <label class="block text-[11px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Judul Berita</label>
                        <input type="text" name="title" required placeholder="Contoh: Senyum Kebahagiaan Anak-anak Yatim NTT" class="w-full bg-slate-50 border border-slate-150 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none focus:border-primary-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Tag / Kategori Berita</label>
                        <input type="text" name="tag" required placeholder="Contoh: Penyaluran / Inspirasi / Dampak" class="w-full bg-slate-50 border border-slate-150 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none focus:border-primary-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Konten / Isi Berita</label>
                        <textarea name="content" required rows="5" placeholder="Tulis rincian cerita penyaluran berita..." class="w-full bg-slate-50 border border-slate-150 rounded-2xl px-5 py-4 text-xs font-bold text-slate-850 outline-none focus:border-primary-500"></textarea>
                    </div>
                    <div>
                        <label class="block text-[11px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Foto Dokumentasi</label>
                        <input type="file" name="image" required class="w-full bg-slate-50 border border-slate-150 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none focus:border-primary-500">
                    </div>
                    <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-black text-sm uppercase tracking-wider py-4.5 rounded-2xl shadow-lg transition duration-300">Terbitkan Berita <i class="fa-solid fa-cloud-arrow-up ml-2"></i></button>
                </form>
            </div>
        </div>
    </div>

    <!-- C. REPORT DISTRIBUTION MODAL -->
    <div x-show="modalDistribution" style="display: none;" class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm">
        <div @click.away="modalDistribution = false" class="bg-white border border-slate-100 w-full max-w-lg rounded-3xl p-8 shadow-2xl relative space-y-6 flex flex-col max-h-[90vh]">
            <button @click="modalDistribution = false" class="absolute top-6 right-6 text-slate-400 hover:text-slate-655"><i class="fa-solid fa-xmark text-lg"></i></button>
            <div class="space-y-1">
                <h3 class="text-lg font-montserrat font-black uppercase text-slate-800 italic">Kirim Laporan <span class="text-primary-600">Penyaluran Donasi</span></h3>
                <p class="text-[11px] text-slate-400 font-bold uppercase tracking-wider">Kirimkan rincian bukti alokasi bantuan untuk transparansi donatur</p>
            </div>
            <div class="overflow-y-auto pr-2 space-y-4 max-h-[60vh] no-scrollbar">
                <form action="{{ route('field.distributions.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 pr-1">
                    @csrf
                    <div>
                        <label class="block text-[11px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Program Kampanye Terkait</label>
                        <select name="campaign_id" required class="w-full bg-slate-50 border border-slate-150 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none focus:border-primary-500">
                            @foreach($allCampaigns as $ac)
                                <option value="{{ $ac->id }}">{{ $ac->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Nama Laporan Penyaluran</label>
                            <input type="text" name="title" required placeholder="Contoh: Berbagi beras Cicadas" class="w-full bg-slate-50 border border-slate-150 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none focus:border-primary-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Penerima Manfaat / Kelompok</label>
                            <input type="text" name="recipient_name" required placeholder="Contoh: Warga RT 02 Cicadas" class="w-full bg-slate-50 border border-slate-150 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none focus:border-primary-500">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Nominal Dana Disalurkan (Rupiah)</label>
                            <input type="number" name="amount" required placeholder="Contoh: 1500000" class="w-full bg-slate-50 border border-slate-150 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none focus:border-primary-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Rincian Barang yang Disalurkan</label>
                            <input type="text" name="items_distributed" required placeholder="Contoh: 50 Kantong Beras @ 5kg" class="w-full bg-slate-50 border border-slate-150 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none focus:border-primary-500">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Nama Lokasi Penyaluran</label>
                            <input type="text" name="location_name" placeholder="Contoh: Masjid Jami Al-Ikhlas" class="w-full bg-slate-50 border border-slate-150 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none focus:border-primary-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Link Google Maps (Opsional)</label>
                            <input type="url" name="maps_link" placeholder="https://maps.google.com/..." class="w-full bg-slate-50 border border-slate-150 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none focus:border-primary-500">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Latitude GPS (Opsional)</label>
                            <input type="text" name="latitude" id="dist_lat" placeholder="Koordinat Lintang" class="w-full bg-slate-50 border border-slate-150 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none focus:border-primary-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Longitude GPS (Opsional)</label>
                            <input type="text" name="longitude" id="dist_lng" placeholder="Koordinat Bujur" class="w-full bg-slate-50 border border-slate-150 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none focus:border-primary-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Deskripsi / Keterangan Penyaluran</label>
                        <textarea name="description" required rows="3" placeholder="Tuliskan catatan kondisi atau detail proses alokasi bantuan kemanusiaan..." class="w-full bg-slate-50 border border-slate-150 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none focus:border-primary-500"></textarea>
                    </div>
                    <div>
                        <label class="block text-[11px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Foto Bukti Penyaluran Lapangan</label>
                        <input type="file" name="photo" class="w-full bg-slate-50 border border-slate-150 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none focus:border-primary-500">
                    </div>
                    <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-black text-sm uppercase tracking-wider py-4.5 rounded-2xl shadow-lg transition duration-300">Simpan Laporan <i class="fa-solid fa-shield-halved ml-2"></i></button>
                </form>
            </div>
        </div>
    </div>

    <!-- D. SEND CAMPAIGN UPDATE (KABAR TERBARU) MODAL -->
    <div x-show="modalUpdate" style="display: none;" class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm">
        <div @click.away="modalUpdate = false" class="bg-white border border-slate-100 w-full max-w-lg rounded-3xl p-8 shadow-2xl relative space-y-6 flex flex-col max-h-[90vh]">
            <button @click="modalUpdate = false" class="absolute top-6 right-6 text-slate-400 hover:text-slate-655"><i class="fa-solid fa-xmark text-lg"></i></button>
            <div class="space-y-1">
                <h3 class="text-lg font-montserrat font-black uppercase text-slate-800 italic">Kirim <span class="text-primary-600">Kabar Terbaru</span></h3>
                <p class="text-[11px] text-slate-400 font-bold uppercase tracking-wider">Kirim ke: <span class="text-slate-800 font-bold" x-text="activeCampaignTitle"></span></p>
            </div>
            <div class="overflow-y-auto pr-2 space-y-4 max-h-[60vh] no-scrollbar">
                <form :action="'{{ url('/field/campaigns') }}/' + activeCampaignId + '/update'" method="POST" enctype="multipart/form-data" class="space-y-4 pr-1">
                    @csrf
                    <div>
                        <label class="block text-[11px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Judul Kabar Utama</label>
                        <input type="text" name="title" required placeholder="Contoh: Pembelian Sembako Tahap 1" class="w-full bg-slate-50 border border-slate-150 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none focus:border-primary-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Rincian Perkembangan Program</label>
                        <textarea name="content" required rows="4" placeholder="Tuliskan update, progres pembelian barang, rencana penyaluran berikutnya..." class="w-full bg-slate-50 border border-slate-150 rounded-2xl px-5 py-4 text-xs font-bold text-slate-850 outline-none focus:border-primary-500"></textarea>
                    </div>
                    <div>
                        <label class="block text-[11px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Foto Dokumentasi Pendukung</label>
                        <input type="file" name="image" class="w-full bg-slate-50 border border-slate-150 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none focus:border-primary-500">
                    </div>
                    <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-black text-sm uppercase tracking-wider py-4.5 rounded-2xl shadow-lg transition duration-300">Kirim Kabar Perkembangan <i class="fa-solid fa-bullhorn ml-2"></i></button>
                </form>
            </div>
        </div>
    </div>

    <!-- E. MARK TODO COMPLETED & UPLOAD PROOF MODAL -->
    <div x-show="modalTodoResult" style="display: none;" class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm">
        <div @click.away="modalTodoResult = false" class="bg-white border border-slate-100 w-full max-w-lg rounded-3xl p-8 shadow-2xl relative space-y-6 flex flex-col max-h-[90vh]">
            <button @click="modalTodoResult = false" class="absolute top-6 right-6 text-slate-400 hover:text-slate-655"><i class="fa-solid fa-xmark text-lg"></i></button>
            <div class="space-y-1">
                <h3 class="text-lg font-montserrat font-black uppercase text-slate-800 italic">Selesaikan <span class="text-primary-600">Tugas Lapangan</span></h3>
                <p class="text-[11px] text-slate-400 font-bold uppercase tracking-wider">Tugas: <span class="text-slate-850 font-bold" x-text="activeTodoTask"></span></p>
            </div>
            <div class="overflow-y-auto pr-2 space-y-4 max-h-[60vh] no-scrollbar">
                <form :action="'{{ url('/field/todo') }}/' + activeTodoId + '/update'" method="POST" enctype="multipart/form-data" class="space-y-4 pr-1">
                    @csrf
                    <input type="hidden" name="status" value="completed">
                    <div>
                        <label class="block text-[11px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Laporan Hasil Pengerjaan</label>
                        <textarea name="result_note" required rows="3" placeholder="Tuliskan keterangan penyelesaian tugas lapangan..." class="w-full bg-slate-50 border border-slate-150 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none focus:border-primary-500"></textarea>
                    </div>
                    <div>
                        <label class="block text-[11px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Foto Bukti Hasil Kerja (Selfie/Penyaluran)</label>
                        <input type="file" name="result_attachment" required class="w-full bg-slate-50 border border-slate-150 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none focus:border-primary-500">
                    </div>
                    <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-black text-sm uppercase tracking-wider py-4.5 rounded-2xl shadow-lg transition duration-300">Kirim Bukti & Selesaikan Tugas <i class="fa-solid fa-upload ml-2"></i></button>
                </form>
            </div>
        </div>
    </div>

</section>

<!-- GPS Geolocation Script -->
<script>
    function requestGPSLocation() {
        const gpsStatus = document.getElementById('gps-status');
        gpsStatus.innerHTML = '<i class="fa-solid fa-location-crosshairs animate-pulse mr-1"></i> Mendapatkan koordinat GPS Anda...';
        
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    
                    document.getElementById('lat').value = lat;
                    document.getElementById('lng').value = lng;
                    
                    if(document.getElementById('dist_lat')) {
                        document.getElementById('dist_lat').value = lat;
                    }
                    if(document.getElementById('dist_lng')) {
                        document.getElementById('dist_lng').value = lng;
                    }
                    
                    gpsStatus.innerHTML = '<i class="fa-solid fa-location-dot text-emerald-500 mr-1"></i> KOORDINAT GPS BERHASIL DIDAPATKAN (' + lat.toFixed(6) + ', ' + lng.toFixed(6) + ')';
                },
                function(error) {
                    gpsStatus.innerHTML = '<span class="text-rose-500"><i class="fa-solid fa-location-dot mr-1"></i> GAGAL MENDAPATKAN LOKASI GPS (IZINKAN AKSES LOKASI)</span> <button type="button" onclick="requestGPSLocation()" class="ml-2 px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-[8px] font-black uppercase tracking-wider transition-all inline-flex items-center gap-1"><i class="fa-solid fa-arrows-rotate animate-spin"></i> Coba Lagi</button>';
                }
            );
        } else {
            gpsStatus.innerHTML = '<i class="fa-solid fa-location-dot text-rose-500 mr-1"></i> BROWSER TIDAK MENDUKUNG GPS';
        }
    }

    document.addEventListener("DOMContentLoaded", requestGPSLocation);
</script>
@endsection
