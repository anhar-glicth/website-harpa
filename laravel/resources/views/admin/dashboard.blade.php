@extends('layouts.app')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-20">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar Menu -->
        <div class="hidden lg:block lg:col-span-1 space-y-6">
            @include('admin.sidebar', ['active' => 'dashboard'])
        </div>

        <!-- Dashboard Content -->
        <div class="lg:col-span-3 space-y-8">
            <div class="space-y-2">
                <h1 class="text-2xl sm:text-3xl font-montserrat font-black text-slate-900 uppercase tracking-tight italic">
                    Dashboard <span class="text-primary-600">Manajemen Pusat</span>
                </h1>
                <div class="w-12 h-1 bg-primary-500 rounded-full"></div>
            </div>

            {{-- ✅ Personal Absensi & Todo Widget --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Personal Absensi GPS Card --}}
                <div x-data="{ showAbsenForm: false }" class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-montserrat font-black text-slate-900 uppercase tracking-tight italic text-sm">
                                Absensi <span class="text-primary-600">Hari Ini</span>
                            </h3>
                            <div class="mt-1">
                                <x-live-clock variant="inline" />
                            </div>
                        </div>
                        <button @click="showAbsenForm = !showAbsenForm"
                            class="px-3 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-[9px] font-black uppercase tracking-wider flex items-center gap-1.5 shadow-sm transition-all">
                            <i class="fa-solid fa-stamp"></i> Catat
                        </button>
                    </div>
                    @php
                        $hasCI = $todayAttendance->where('type','check_in')->isNotEmpty();
                        $hasCO = $todayAttendance->where('type','check_out')->isNotEmpty();
                        $hasSK = $todayAttendance->where('type','sakit')->isNotEmpty();
                        $hasIZ = $todayAttendance->where('type','izin')->isNotEmpty();
                    @endphp
                    <div class="grid grid-cols-2 gap-2">
                        <div class="flex items-center gap-2 p-2.5 rounded-xl border {{ $hasCI ? 'bg-emerald-50 border-emerald-200' : 'bg-slate-50 border-slate-100' }}">
                            <i class="fa-solid fa-right-to-bracket text-xs {{ $hasCI ? 'text-emerald-600' : 'text-slate-300' }}"></i>
                            <div>
                                <p class="text-[8px] font-black uppercase tracking-wider {{ $hasCI ? 'text-emerald-700' : 'text-slate-400' }}">Check In</p>
                                <p class="text-[9px] font-bold text-slate-500">{{ $hasCI ? $todayAttendance->where('type','check_in')->first()->created_at->format('H:i') . ' WITA' : '—' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 p-2.5 rounded-xl border {{ $hasCO ? 'bg-blue-50 border-blue-200' : 'bg-slate-50 border-slate-100' }}">
                            <i class="fa-solid fa-right-from-bracket text-xs {{ $hasCO ? 'text-blue-600' : 'text-slate-300' }}"></i>
                            <div>
                                <p class="text-[8px] font-black uppercase tracking-wider {{ $hasCO ? 'text-blue-700' : 'text-slate-400' }}">Check Out</p>
                                <p class="text-[9px] font-bold text-slate-500">{{ $hasCO ? $todayAttendance->where('type','check_out')->first()->created_at->format('H:i') . ' WITA' : '—' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 p-2.5 rounded-xl border {{ $hasSK ? 'bg-amber-50 border-amber-200' : 'bg-slate-50 border-slate-100' }}">
                            <i class="fa-solid fa-kit-medical text-xs {{ $hasSK ? 'text-amber-600' : 'text-slate-300' }}"></i>
                            <div>
                                <p class="text-[8px] font-black uppercase tracking-wider {{ $hasSK ? 'text-amber-700' : 'text-slate-400' }}">Sakit</p>
                                <p class="text-[9px] font-bold text-slate-500">{{ $hasSK ? '✓ Tercatat' : '—' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 p-2.5 rounded-xl border {{ $hasIZ ? 'bg-red-50 border-red-200' : 'bg-slate-50 border-slate-100' }}">
                            <i class="fa-solid fa-file-circle-exclamation text-xs {{ $hasIZ ? 'text-red-500' : 'text-slate-300' }}"></i>
                            <div>
                                <p class="text-[8px] font-black uppercase tracking-wider {{ $hasIZ ? 'text-red-600' : 'text-slate-400' }}">Izin</p>
                                <p class="text-[9px] font-bold text-slate-500">{{ $hasIZ ? '✓ Tercatat' : '—' }}</p>
                            </div>
                        </div>
                    </div>
                    <div x-show="showAbsenForm" x-transition style="display:none;" class="border-t border-slate-100 pt-4">
                        <form action="{{ route('attendance.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                            @csrf
                            <input type="hidden" name="latitude" id="adm_dash_lat">
                            <input type="hidden" name="longitude" id="adm_dash_lng">

                            <select name="type" required class="w-full bg-slate-50 border border-slate-150 rounded-2xl px-4 py-3 text-xs font-bold text-slate-800 outline-none focus:border-primary-500">
                                <option value="check_in">Check In (Masuk)</option>
                                <option value="check_out">Check Out (Pulang)</option>
                                <option value="sakit">Sakit</option>
                                <option value="izin">Izin</option>
                            </select>
                            <input type="text" name="location" id="adm_dash_loc" placeholder="Nama lokasi / kantor..." class="w-full bg-slate-50 border border-slate-150 rounded-2xl px-4 py-3 text-xs font-bold text-slate-800 outline-none focus:border-primary-500">
                            
                            <x-gps-detector latId="adm_dash_lat" lngId="adm_dash_lng" statusId="adm-dash-gps-status" locationId="adm_dash_loc" />

                            <input type="file" name="attachment" accept="image/*" class="w-full bg-slate-50 border border-slate-150 rounded-2xl px-4 py-2.5 text-xs">
                            <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-wider py-3 rounded-2xl transition-all flex items-center justify-center gap-2">
                                <i class="fa-solid fa-stamp"></i> Kirim Absensi
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Personal To-Do List Card --}}
                <div x-data="{ showAddForm: false, expandedId: null, modalOpen: false, activeTodoId: null, activeTodoTask: '' }"
                     class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-montserrat font-black text-slate-900 uppercase tracking-tight italic text-sm">
                                To-Do <span class="text-primary-600">Saya</span>
                            </h3>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">
                                {{ $todos->where('status','completed')->count() }}/{{ $todos->count() }} Selesai
                            </p>
                        </div>
                        <button @click="showAddForm = !showAddForm"
                            class="px-3 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-[9px] font-black uppercase tracking-wider flex items-center gap-1.5 shadow-sm transition-all">
                            <i class="fa-solid fa-plus"></i> Tambah
                        </button>
                    </div>

                    {{-- Progress bar --}}
                    <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-primary-600 rounded-full transition-all duration-500"
                             style="width: {{ $todos->count() ? ($todos->where('status','completed')->count() / $todos->count()) * 100 : 0 }}%">
                        </div>
                    </div>

                    {{-- Add Form --}}
                    <div x-show="showAddForm" x-transition style="display:none;" class="border-t border-slate-100 pt-3">
                        <form action="{{ route('todo.store') }}" method="POST" class="space-y-2.5">
                            @csrf
                            <input type="text" name="task" required placeholder="Judul tugas baru..." class="w-full bg-slate-50 border border-slate-150 rounded-2xl px-4 py-3 text-xs font-bold text-slate-800 outline-none focus:border-primary-500">
                            <textarea name="description" rows="2" placeholder="Deskripsi..." class="w-full bg-slate-50 border border-slate-150 rounded-2xl px-4 py-2.5 text-xs font-semibold text-slate-800 outline-none focus:border-primary-500"></textarea>
                            <input type="datetime-local" name="deadline" class="w-full bg-slate-50 border border-slate-150 rounded-2xl px-4 py-2.5 text-xs font-bold text-slate-800 outline-none focus:border-primary-500">
                            <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-wider py-3 rounded-2xl transition-all">
                                Tambah Tugas
                            </button>
                        </form>
                    </div>

                    {{-- Todo Items --}}
                    <div class="space-y-2 max-h-56 overflow-y-auto pr-1">
                        @forelse($todos as $t)
                            <div @click="expandedId = (expandedId === {{ $t->id }} ? null : {{ $t->id }})"
                                 :class="expandedId === {{ $t->id }} ? 'border-primary-200 bg-primary-50/20' : 'border-slate-100 bg-slate-50/30'"
                                 class="p-3 rounded-2xl border cursor-pointer space-y-2 transition-all hover:border-primary-100">
                                <div class="flex items-center justify-between gap-2">
                                    <div class="flex items-center gap-2 flex-1 min-w-0">
                                        <span class="shrink-0 w-4 h-4 rounded-full border-2 flex items-center justify-center {{ $t->status === 'completed' ? 'bg-emerald-500 border-emerald-500' : 'border-slate-300' }}">
                                            @if($t->status === 'completed')<i class="fa-solid fa-check text-white text-[7px]"></i>@endif
                                        </span>
                                        <p class="text-[10px] font-black text-slate-800 uppercase tracking-wide truncate {{ $t->status === 'completed' ? 'line-through text-slate-400' : '' }}">{{ $t->task }}</p>
                                    </div>
                                    <i class="fa-solid fa-chevron-down text-[8px] text-slate-400 shrink-0 transition-transform duration-200"
                                       :class="expandedId === {{ $t->id }} ? 'rotate-180 text-primary-600' : ''"></i>
                                </div>
                                <div x-show="expandedId === {{ $t->id }}" @click.stop style="display:none;" class="border-t border-slate-100 pt-2 space-y-2">
                                    @if($t->deadline)<p class="text-[9px] font-bold text-red-500">⏰ {{ $t->deadline->format('d M Y H:i') }}</p>@endif
                                    @if($t->status === 'pending')
                                        <button @click.stop="activeTodoId = {{ $t->id }}; activeTodoTask = '{{ addslashes($t->task) }}'; modalOpen = true"
                                            class="w-full bg-primary-600 hover:bg-primary-700 text-white font-black text-[9px] uppercase tracking-wider px-3 py-2 rounded-xl">
                                            Tandai Selesai & Upload Bukti
                                        </button>
                                    @else
                                        <span class="text-emerald-700 text-[9px] font-black uppercase tracking-wider"><i class="fa-solid fa-circle-check mr-1"></i>Selesai {{ $t->completed_at?->format('d M Y') }}</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-[10px] text-slate-400 font-bold uppercase tracking-wider py-4">Belum ada tugas.</p>
                        @endforelse
                    </div>

                    {{-- Modal Bukti Selesai --}}
                    <div x-show="modalOpen" style="display:none;" class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm">
                        <div @click.away="modalOpen = false" class="bg-white border border-slate-100 w-full max-w-md rounded-3xl p-8 shadow-2xl space-y-5 relative">
                            <button @click="modalOpen = false" class="absolute top-5 right-5 text-slate-400 hover:text-slate-700"><i class="fa-solid fa-xmark text-lg"></i></button>
                            <div>
                                <h4 class="font-montserrat font-black text-slate-900 uppercase tracking-tight text-sm">Upload <span class="text-primary-600">Bukti Selesai</span></h4>
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1">Tugas: <span class="text-slate-700" x-text="activeTodoTask"></span></p>
                            </div>
                            <form :action="'{{ url('todo') }}/' + activeTodoId + '/update'" method="POST" enctype="multipart/form-data" class="space-y-3">
                                @csrf
                                <input type="hidden" name="status" value="completed">
                                <textarea name="result_note" rows="3" placeholder="Catatan hasil kerja..." class="w-full bg-slate-50 border border-slate-150 rounded-2xl px-4 py-3 text-xs font-semibold text-slate-800 outline-none focus:border-primary-500"></textarea>
                                <input type="file" name="result_attachment" accept="image/*" class="w-full bg-slate-50 border border-slate-150 rounded-2xl px-4 py-2.5 text-xs">
                                <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-wider py-3.5 rounded-2xl shadow-lg transition-all">
                                    Kirim Bukti & Tandai Selesai
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>{{-- end personal widget row --}}

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <!-- Total Donations -->
                <div class="bg-white border border-slate-100 p-6 rounded-3xl shadow-sm flex items-center space-x-4 hover:shadow-lg hover:shadow-primary-500/5 hover:-translate-y-0.5 transition-all duration-300">
                    <div class="bg-emerald-50 text-emerald-600 w-12 h-12 rounded-xl flex items-center justify-center text-xl shrink-0">
                        <i class="fa-solid fa-hand-holding-dollar"></i>
                    </div>
                    <div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block leading-none">Total Donasi Terkumpul</span>
                        <h3 class="text-base sm:text-lg font-montserrat font-black text-slate-800 mt-1">
                            Rp {{ number_format($totalDonationAmount, 0, ',', '.') }}
                        </h3>
                    </div>
                </div>

                <!-- Total Zakat -->
                <div class="bg-white border border-slate-100 p-6 rounded-3xl shadow-sm flex items-center space-x-4 hover:shadow-lg hover:shadow-primary-500/5 hover:-translate-y-0.5 transition-all duration-300">
                    <div class="bg-primary-50 text-primary-600 w-12 h-12 rounded-xl flex items-center justify-center text-xl shrink-0">
                        <i class="fa-solid fa-handshake"></i>
                    </div>
                    <div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block leading-none">Total Zakat Terkumpul</span>
                        <h3 class="text-base sm:text-lg font-montserrat font-black text-slate-800 mt-1">
                            Rp {{ number_format($totalZakatAmount, 0, ',', '.') }}
                        </h3>
                    </div>
                </div>

                <!-- Pending Proof Verifications -->
                <div class="bg-white border border-slate-100 p-6 rounded-3xl shadow-sm flex items-center space-x-4 hover:shadow-lg hover:shadow-primary-500/5 hover:-translate-y-0.5 transition-all duration-300">
                    <div class="bg-amber-50 text-amber-600 w-12 h-12 rounded-xl flex items-center justify-center text-xl shrink-0">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                    <div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block leading-none">Menunggu Verifikasi</span>
                        <h3 class="text-base sm:text-lg font-montserrat font-black text-slate-800 mt-1">
                            {{ $pendingDonations }} Bukti
                        </h3>
                    </div>
                </div>
            </div>

            <!-- Charts Section (Chart.js via CDN) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Bar Chart (Monthly) -->
                <div class="bg-white border border-slate-100 p-6 rounded-3xl shadow-sm flex flex-col justify-between">
                    <h3 class="font-montserrat font-black uppercase text-xs text-slate-800 tracking-wider mb-4">Donasi Bulanan</h3>
                    <div class="relative h-[220px]">
                        <canvas id="monthlyChart"></canvas>
                    </div>
                </div>

                <!-- Pie Chart (Categories) -->
                <div class="bg-white border border-slate-100 p-6 rounded-3xl shadow-sm flex flex-col justify-between">
                    <h3 class="font-montserrat font-black uppercase text-xs text-slate-800 tracking-wider mb-4">Donasi Per Kategori</h3>
                    <div class="relative h-[220px] flex items-center justify-center">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>

                <!-- Bar Chart (Staff Performance) -->
                <div class="bg-white border border-slate-100 p-6 rounded-3xl shadow-sm flex flex-col justify-between">
                    <h3 class="font-montserrat font-black uppercase text-xs text-slate-800 tracking-wider mb-4">Kinerja Pegawai (% Selesai)</h3>
                    <div class="relative h-[220px]">
                        <canvas id="staffChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Analisa Operasional Section -->
            <div class="space-y-4">
                <h3 class="font-montserrat font-black uppercase text-xs sm:text-sm text-slate-800 tracking-wider italic">
                    Analisa <span class="text-primary-500">Operasional & Kinerja</span>
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <!-- 1. Analisa Pegawai -->
                    <div class="bg-white border border-slate-100 p-6 rounded-3xl shadow-sm space-y-4 hover:shadow-lg hover:shadow-primary-500/5 hover:-translate-y-0.5 transition-all duration-300">
                        <div class="flex items-center justify-between">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">Analisa Pegawai</span>
                            <span class="w-8 h-8 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center"><i class="fa-solid fa-users-gear text-sm"></i></span>
                        </div>
                        <div class="space-y-1">
                            <span class="text-2xl font-montserrat font-black text-slate-800">{{ $totalStaff }} Orang</span>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wide block mt-1">Total Pegawai</span>
                        </div>
                        <div class="pt-2 border-t border-slate-50 flex items-center justify-between text-[9px] font-black uppercase tracking-wider text-slate-500">
                            <span>Hadir Hari Ini:</span>
                            <span class="text-secondary-600 font-bold bg-secondary-50 px-2 py-0.5 rounded">{{ $presentToday }} Orang</span>
                        </div>
                    </div>

                    <!-- 2. Analisa Kegiatan Pegawai -->
                    <div class="bg-white border border-slate-100 p-6 rounded-3xl shadow-sm space-y-4 hover:shadow-lg hover:shadow-primary-500/5 hover:-translate-y-0.5 transition-all duration-300">
                        <div class="flex items-center justify-between">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">Analisa Kegiatan</span>
                            <span class="w-8 h-8 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center"><i class="fa-solid fa-clipboard-check text-sm"></i></span>
                        </div>
                        <div class="space-y-1">
                            <span class="text-2xl font-montserrat font-black text-slate-800">{{ $totalTasks }} Tugas</span>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wide block mt-1">Total Penugasan</span>
                        </div>
                        <div class="pt-2 border-t border-slate-50 flex items-center justify-between text-[9px] font-black uppercase tracking-wider text-slate-500">
                            <span>Selesai Kerja:</span>
                            <span class="text-secondary-600 font-bold bg-secondary-50 px-2 py-0.5 rounded">{{ $completedTasks }} Selesai</span>
                        </div>
                    </div>

                    <!-- 3. Analisa Berita -->
                    <div class="bg-white border border-slate-100 p-6 rounded-3xl shadow-sm space-y-4 hover:shadow-lg hover:shadow-primary-500/5 hover:-translate-y-0.5 transition-all duration-300">
                        <div class="flex items-center justify-between">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">Analisa Berita</span>
                            <span class="w-8 h-8 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center"><i class="fa-solid fa-newspaper text-sm"></i></span>
                        </div>
                        <div class="space-y-1">
                            <span class="text-2xl font-montserrat font-black text-slate-800">{{ $totalNews }} Post</span>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wide block mt-1">Dampak Terbit</span>
                        </div>
                        <div class="pt-2 border-t border-slate-50 flex items-center justify-between text-[9px] font-black uppercase tracking-wider text-slate-500">
                            <span>Kabar Terbit:</span>
                            <span class="text-indigo-600 font-bold bg-indigo-50 px-2 py-0.5 rounded">Berita Dampak</span>
                        </div>
                    </div>

                    <!-- 4. Analisa Penyaluran -->
                    <div class="bg-white border border-slate-100 p-6 rounded-3xl shadow-sm space-y-4 hover:shadow-lg hover:shadow-primary-500/5 hover:-translate-y-0.5 transition-all duration-300">
                        <div class="flex items-center justify-between">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">Analisa Penyaluran</span>
                            <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-550 flex items-center justify-center"><i class="fa-solid fa-truck-ramp-box text-sm"></i></span>
                        </div>
                        <div class="space-y-1">
                            <span class="text-base font-montserrat font-black text-slate-800 block truncate">Rp {{ number_format($totalFundsDistributed, 0, ',', '.') }}</span>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wide block mt-1">Total Penyaluran</span>
                        </div>
                        <div class="pt-2 border-t border-slate-50 flex items-center justify-between text-[9px] font-black uppercase tracking-wider text-slate-500">
                            <span>Frekuensi Alokasi:</span>
                            <span class="text-secondary-600 font-bold bg-secondary-50 px-2 py-0.5 rounded">{{ $totalDistributionsCount }} Kali</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Verified Transactions -->
            <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 space-y-6 shadow-sm">
                <h3 class="font-montserrat font-black uppercase text-sm text-slate-800 tracking-wider">Aktivitas Donasi Terbaru</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-[10px] font-black uppercase tracking-wider text-slate-400">
                                <th class="pb-4">Donatur</th>
                                <th class="pb-4">Kampanye</th>
                                <th class="pb-4">Jumlah</th>
                                <th class="pb-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                            @forelse($recentTransactions as $r)
                                <tr class="border-b border-slate-50 last:border-0">
                                    <td class="py-4">{{ $r->donor_name }}</td>
                                    <td class="py-4 pr-4 max-w-[200px] line-clamp-1 mt-3">{{ $r->campaign->title }}</td>
                                    <td class="py-4">Rp {{ number_format($r->amount, 0, ',', '.') }}</td>
                                    <td class="py-4">
                                        @if($r->payment_status === 'Success')
                                            <span class="bg-emerald-50 text-emerald-700 text-[8px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md">Success</span>
                                        @elseif($r->payment_status === 'Pending')
                                            <span class="bg-amber-50 text-amber-700 text-[8px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md">Pending</span>
                                        @else
                                            <span class="bg-rose-50 text-rose-700 text-[8px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md">Failed</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-slate-400 font-bold uppercase tracking-wider">Belum ada donasi terdaftar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- ChartJS Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 1. Monthly Bar Chart
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    const monthlyTotals = Array(12).fill(0);
    @foreach($monthlyData as $data)
        monthlyTotals[{{ $data->month - 1 }}] = {{ $data->total }};
    @endforeach

    new Chart(document.getElementById('monthlyChart'), {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Total Donasi (Rp)',
                data: monthlyTotals,
                backgroundColor: '#f97316',
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // 2. Category Pie Chart
    const categories = [];
    const categoryTotals = [];
    @foreach($categoryData as $data)
        categories.push('{{ $data->category }}');
        categoryTotals.push({{ $data->total }});
    @endforeach

    new Chart(document.getElementById('categoryChart'), {
        type: 'pie',
        data: {
            labels: categories,
            datasets: [{
                data: categoryTotals,
                backgroundColor: ['#f97316', '#29abe2', '#ea580c', '#0284c7', '#fed7aa', '#bae6fd']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // 3. Staff Performance Chart (Horizontal Bar Chart)
    const staffNames = [];
    const staffEffectiveness = [];
    @foreach($staffChartData as $data)
        staffNames.push('{{ $data["name"] }}');
        staffEffectiveness.push({{ $data["effectiveness"] }});
    @endforeach

    new Chart(document.getElementById('staffChart'), {
        type: 'bar',
        data: {
            labels: staffNames,
            datasets: [{
                label: 'Efektivitas Kerja (%)',
                data: staffEffectiveness,
                backgroundColor: '#29abe2',
                borderRadius: 8
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: { 
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
