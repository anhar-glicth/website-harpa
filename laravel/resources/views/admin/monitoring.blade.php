@extends('layouts.app')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-20">
    
    <!-- Toast Notifications -->
    @if(session('success'))
        <div role="status" class="mb-6 p-4 bg-emerald-50 border border-emerald-300 text-emerald-800 rounded-2xl font-semibold text-xs flex items-center gap-3">
            <i class="fa-solid fa-circle-check text-emerald-700 text-base" aria-hidden="true"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div role="alert" class="mb-6 p-4 bg-red-50 border border-red-300 text-red-900 rounded-2xl font-semibold text-xs flex items-center gap-3">
            <i class="fa-solid fa-circle-xmark text-red-700 text-base" aria-hidden="true"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar Menu -->
        <div class="hidden lg:block lg:col-span-1 space-y-6">
            @include('admin.sidebar', ['active' => 'users'])
        </div>

        <!-- Dashboard Content -->
        <div class="lg:col-span-3 space-y-8">
            
            <!-- Back button & title -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="space-y-2">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.users') }}" aria-label="Kembali ke daftar pengguna" class="min-w-[44px] min-h-[44px] bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl flex items-center justify-center transition-colors">
                            <i class="fa-solid fa-arrow-left text-xs" aria-hidden="true"></i>
                        </a>
                        <h1 class="text-2xl sm:text-3xl font-montserrat font-black text-slate-900 uppercase tracking-tight italic">
                            Monitoring <span class="text-primary-600">Pegawai Lapangan</span>
                        </h1>
                    </div>
                    <div class="w-12 h-1 bg-primary-500 rounded-full"></div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 bg-emerald-700 rounded-full" aria-hidden="true"></span>
                    <span class="text-xs font-black text-slate-600 uppercase tracking-widest">Live Worker Analytics</span>
                </div>
            </div>

            <!-- Profile & Effectiveness Header -->
            <div class="bg-[#063f5c] rounded-[2.5rem] p-8 sm:p-10 text-white relative overflow-hidden shadow-xl">
                <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
                    <!-- Left: Profile Info -->
                    <div class="flex flex-col sm:flex-row items-center gap-6 text-center sm:text-left">
                        <div class="w-20 h-20 bg-primary-700 rounded-[2rem] flex items-center justify-center text-3xl font-black text-[#f7ad1a] shadow-lg border-4 border-white/15 shrink-0">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="space-y-1.5">
                            <span class="px-3 py-1 bg-slate-900/60 border border-[#f7ad1a]/40 text-[#f7ad1a] rounded-full text-xs font-black uppercase tracking-widest block w-fit mx-auto sm:mx-0">
                                {{ str_replace('_', ' ', $user->role) }}
                            </span>
                            <h2 class="text-2xl font-black font-montserrat tracking-tight leading-none uppercase italic">{{ $user->name }}</h2>
                            <p class="text-xs font-bold text-slate-200 tracking-wider">{{ $user->email }} | {{ $user->phone ?? 'Tidak ada nomor telepon' }}</p>
                        </div>
                    </div>
                    
                    <!-- Right: Quick Performance Stat -->
                    <div class="flex flex-col items-center md:items-end gap-2 shrink-0 w-full md:w-auto">
                        <div class="text-center md:text-right">
                            <p class="text-xs font-black text-slate-200 uppercase tracking-widest mb-1">Efektivitas Tugas</p>
                            <div class="text-4xl md:text-5xl font-black text-[#f7ad1a] tracking-tighter leading-none">
                                {{ $stats['effectiveness'] }}%
                            </div>
                        </div>
                        <div class="w-40 h-2 bg-white/15 rounded-full overflow-hidden mt-1">
                            <div class="h-full bg-[#f7ad1a] transition-all duration-700 ease-out" style="width: {{ $stats['effectiveness'] }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Mini Cards Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-6">
                <!-- Total Tasks -->
                <div class="bg-white border border-slate-200 p-6 rounded-3xl shadow-sm flex items-center space-x-4">
                    <div class="bg-slate-100 border border-slate-200 text-[#063f5c] w-11 h-11 rounded-xl flex items-center justify-center text-lg shrink-0">
                        <i class="fa-solid fa-list-check" aria-hidden="true"></i>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-slate-600 uppercase tracking-wider block leading-none">Total Tugas</span>
                        <h3 class="text-lg font-montserrat font-black text-slate-800 mt-1">{{ $stats['totalTasks'] }}</h3>
                    </div>
                </div>

                <!-- Completed Tasks -->
                <div class="bg-white border border-slate-200 p-6 rounded-3xl shadow-sm flex items-center space-x-4">
                    <div class="bg-slate-100 border border-slate-200 text-emerald-700 w-11 h-11 rounded-xl flex items-center justify-center text-lg shrink-0">
                        <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-slate-600 uppercase tracking-wider block leading-none">Tugas Selesai</span>
                        <h3 class="text-lg font-montserrat font-black text-emerald-700 mt-1">{{ $stats['completedTasks'] }}</h3>
                    </div>
                </div>

                <!-- Present Days -->
                <div class="bg-white border border-slate-200 p-6 rounded-3xl shadow-sm flex items-center space-x-4">
                    <div class="bg-slate-100 border border-slate-200 text-[#063f5c] w-11 h-11 rounded-xl flex items-center justify-center text-lg shrink-0">
                        <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-slate-600 uppercase tracking-widest block leading-none">Kehadiran</span>
                        <p class="text-lg font-montserrat font-black text-[#063f5c] mt-1">{{ $stats['presentDays'] }} Hari</p>
                    </div>
                </div>

                <!-- Total Campaigns -->
                <div class="bg-white border border-slate-200 p-6 rounded-3xl shadow-sm flex items-center space-x-4">
                    <div class="bg-slate-100 border border-slate-200 text-[#063f5c] w-11 h-11 rounded-xl flex items-center justify-center text-lg shrink-0">
                        <i class="fa-solid fa-folder-open" aria-hidden="true"></i>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-slate-600 uppercase tracking-widest block leading-none">Proyek</span>
                        <p class="text-lg font-montserrat font-black text-slate-900 mt-1">{{ $stats['totalCampaigns'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Task Assignment Form & Tasks List -->
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                <!-- Assign Task Form (Left/Top) -->
                <div class="xl:col-span-1 space-y-6">
                    <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 space-y-6 shadow-sm">
                        <div>
                            <h2 class="font-montserrat font-black text-slate-900 uppercase tracking-tight italic text-sm">Berikan <span class="text-primary-600">Tugas Baru</span></h2>
                            <p class="text-xs text-slate-600 font-bold uppercase tracking-widest mt-0.5">Tugas akan masuk ke todo list petugas</p>
                        </div>
                        <hr class="border-slate-200">
                        
                        <form action="{{ route('admin.users.assign_task', $user->id) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label for="mon_task_title" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5 ml-1">Judul Tugas</label>
                                <input id="mon_task_title" type="text" name="task" required placeholder="Judul tugas..." class="w-full min-h-[44px] bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3 text-xs font-bold text-slate-800 focus:border-primary-600 uppercase tracking-wider">
                            </div>
                            
                            <div>
                                <label for="mon_task_desc" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5 ml-1">Deskripsi Detail</label>
                                <textarea id="mon_task_desc" name="description" rows="3" placeholder="Tuliskan catatan rincian tugas lapangan..." class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3 text-xs font-semibold text-slate-800 focus:border-primary-600"></textarea>
                            </div>
                            
                            <div>
                                <label for="mon_task_deadline" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5 ml-1">Tenggat Waktu (Deadline)</label>
                                <input id="mon_task_deadline" type="datetime-local" name="deadline" class="w-full min-h-[44px] bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3 text-xs font-bold text-slate-800 focus:border-primary-600">
                            </div>
                            
                            <button type="submit" class="w-full min-h-[48px] bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-wider py-4 rounded-2xl shadow-lg shadow-primary-600/10 transition duration-300">
                                Berikan Tugas
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Tasks Log List (Right/Bottom) -->
                <div class="xl:col-span-2 space-y-6">
                    <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 space-y-6 shadow-sm">
                        <div>
                            <h2 class="font-montserrat font-black text-slate-900 uppercase tracking-tight italic text-sm">Histori <span class="text-primary-600">Tugas Lapangan</span></h2>
                            <p class="text-xs text-slate-600 font-bold uppercase tracking-widest mt-0.5">Tugas yang diberikan &amp; progresnya</p>
                        </div>
                        <hr class="border-slate-200">

                        @if($todos->count() > 0)
                            <div class="space-y-4">
                                @foreach($todos as $t)
                                    <div class="p-5 bg-slate-50 border border-slate-200 rounded-2xl space-y-4 hover:border-primary-200 transition-colors">
                                        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-3">
                                            <div>
                                                <h3 class="font-montserrat font-black text-slate-900 text-xs sm:text-sm uppercase tracking-wide italic">{{ $t->task }}</h3>
                                                @if($t->description)
                                                    <p class="text-xs text-slate-600 font-semibold mt-1 leading-relaxed">{{ $t->description }}</p>
                                                @endif
                                            </div>
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold uppercase tracking-wider shrink-0 border border-slate-200 bg-slate-50 text-slate-800">
                                                <span class="w-1.5 h-1.5 rounded-full {{ $t->status === 'completed' ? 'bg-emerald-700' : 'bg-[#f7ad1a]' }}" aria-hidden="true"></span>
                                                {{ $t->status === 'completed' ? 'SELESAI' : 'PENDING' }}
                                            </span>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4 text-xs font-black uppercase tracking-wider text-slate-600 border-t border-slate-200 pt-3">
                                            <div>
                                                <span class="block text-xs text-slate-600 mb-0.5">Diberikan</span>
                                                <span class="text-slate-800 block text-xs">{{ $t->created_at->format('d M Y H:i') }}</span>
                                            </div>
                                            <div>
                                                <span class="block text-xs text-rose-700 mb-0.5">Deadline</span>
                                                <span class="text-rose-700 block text-xs">{{ $t->deadline ? $t->deadline->format('d M Y H:i') : '-' }}</span>
                                            </div>
                                        </div>

                                        <!-- Reporting Notes & Attachments -->
                                        @if($t->status === 'completed')
                                            <div class="bg-white border border-slate-200 p-4 rounded-xl space-y-3 shadow-sm text-xs">
                                                <div class="flex items-center gap-1.5 text-primary-700 font-black text-xs uppercase tracking-wider">
                                                    <i class="fa-solid fa-circle-check" aria-hidden="true"></i> Bukti Penyelesaian
                                                </div>
                                                @if($t->result_note)
                                                    <p class="text-slate-700 font-semibold italic bg-slate-50 p-3 rounded-lg border border-slate-200">"{{ $t->result_note }}"</p>
                                                @endif
                                                @if($t->result_attachment)
                                                    <div class="mt-2.5">
                                                        <a href="{{ $t->result_attachment }}" target="_blank" rel="noopener noreferrer" class="w-full sm:w-fit min-h-[44px] inline-flex items-center justify-center gap-1.5 px-4 py-2.5 bg-slate-900 hover:bg-primary-700 text-white rounded-lg text-xs font-black uppercase tracking-widest transition-colors">
                                                            <i class="fa-solid fa-image" aria-hidden="true"></i> Lihat Foto Bukti
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            <div class="pt-4">
                                {{ $todos->appends(request()->except('todo_page'))->links() }}
                            </div>
                        @else
                            <div class="py-12 text-center text-slate-600 font-bold uppercase tracking-wider text-xs">
                                <i class="fa-solid fa-clipboard-check text-slate-500 text-3xl mb-2 block" aria-hidden="true"></i>
                                Belum ada tugas lapangan yang diberikan.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- GPS Attendance Logs -->
            <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 space-y-6 shadow-sm">
                <div>
                    <h2 class="font-montserrat font-black text-slate-900 uppercase tracking-tight italic text-sm">Histori <span class="text-primary-600">Absensi GPS</span></h2>
                    <p class="text-xs text-slate-600 font-bold uppercase tracking-widest mt-0.5">Catatan check-in/out harian dengan koordinat</p>
                </div>
                <hr class="border-slate-200">

                @if($attendances->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-200 text-xs font-black uppercase tracking-wider text-slate-600">
                                    <th class="pb-4">Tipe Absen</th>
                                    <th class="pb-4">Tanggal &amp; Waktu</th>
                                    <th class="pb-4">Lokasi / Keterangan</th>
                                    <th class="pb-4">Koordinat GPS</th>
                                    <th class="pb-4 text-right">Selfie Bukti</th>
                                </tr>
                            </thead>
                            <tbody class="text-xs font-semibold text-slate-700 uppercase tracking-wide">
                                @foreach($attendances as $att)
                                    <tr class="border-b border-slate-100 last:border-0">
                                        <td class="py-4">
                                            @if($att->type === 'check_in')
                                                <span class="inline-flex items-center gap-1.5 bg-slate-50 text-slate-800 border border-slate-200 text-xs font-bold uppercase tracking-wider px-2.5 py-1 rounded-md"><span class="w-1.5 h-1.5 rounded-full bg-emerald-700" aria-hidden="true"></span>CHECK IN</span>
                                            @elseif($att->type === 'check_out')
                                                <span class="inline-flex items-center gap-1.5 bg-slate-50 text-slate-800 border border-slate-200 text-xs font-bold uppercase tracking-wider px-2.5 py-1 rounded-md"><span class="w-1.5 h-1.5 rounded-full bg-[#063f5c]" aria-hidden="true"></span>CHECK OUT</span>
                                            @elseif($att->type === 'sakit')
                                                <span class="inline-flex items-center gap-1.5 bg-slate-50 text-slate-800 border border-slate-200 text-xs font-bold uppercase tracking-wider px-2.5 py-1 rounded-md"><span class="w-1.5 h-1.5 rounded-full bg-[#f7ad1a]" aria-hidden="true"></span>SAKIT</span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 bg-slate-50 text-slate-800 border border-slate-200 text-xs font-bold uppercase tracking-wider px-2.5 py-1 rounded-md"><span class="w-1.5 h-1.5 rounded-full bg-rose-700" aria-hidden="true"></span>IZIN</span>
                                            @endif
                                        </td>
                                        <td class="py-4 text-slate-800">{{ $att->created_at->format('d M Y, H:i') }}</td>
                                        <td class="py-4 text-slate-700">{{ $att->location ?? '-' }}</td>
                                        <td class="py-4 text-slate-600 font-mono text-xs">
                                            @if($att->latitude && $att->longitude)
                                                <a href="https://www.google.com/maps/search/?api=1&query={{ $att->latitude }},{{ $att->longitude }}" target="_blank" rel="noopener noreferrer" class="text-primary-700 hover:underline inline-flex items-center min-h-[44px]">
                                                    <i class="fa-solid fa-map-location-dot mr-1" aria-hidden="true"></i> {{ $att->latitude }}, {{ $att->longitude }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="py-4 text-right">
                                            @if($att->attachment)
                                                <a href="{{ $att->attachment }}" target="_blank" rel="noopener noreferrer" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-black text-xs uppercase tracking-wider px-3.5 py-2 min-h-[44px] rounded-xl border border-slate-300 inline-flex items-center justify-center gap-1 ml-auto">
                                                    <i class="fa-solid fa-image" aria-hidden="true"></i> Lihat Foto
                                                </a>
                                            @else
                                                <span class="text-slate-600">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="pt-4">
                        {{ $attendances->appends(request()->except('attendance_page'))->links() }}
                    </div>
                @else
                    <div class="py-12 text-center text-slate-600 font-bold uppercase tracking-wider text-xs">
                        <i class="fa-solid fa-user-clock text-slate-500 text-3xl mb-2 block" aria-hidden="true"></i>
                        Belum ada data absensi untuk petugas ini.
                    </div>
                @endif
            </div>

            <!-- Worker Development (Campaigns & Distributions) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Campaigns Created -->
                <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 space-y-6 shadow-sm">
                    <div>
                        <h2 class="font-montserrat font-black text-slate-900 uppercase tracking-tight italic text-sm">Proyek Kampanye <span class="text-primary-600">Diajukan</span></h2>
                        <p class="text-xs text-slate-600 font-bold uppercase tracking-widest mt-0.5">Program penggalangan dana yang ditulis petugas</p>
                    </div>
                    <hr class="border-slate-200">

                    @if($myCampaigns->count() > 0)
                        <div class="space-y-4">
                            @foreach($myCampaigns as $c)
                                <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl flex items-center justify-between gap-4">
                                    <div>
                                        <h3 class="font-montserrat font-black text-slate-800 text-xs uppercase tracking-wide truncate max-w-[200px]">{{ $c->title }}</h3>
                                        <p class="text-xs font-bold text-slate-600 uppercase tracking-wider mt-0.5">Target: Rp {{ number_format($c->target_amount, 0, ',', '.') }}</p>
                                    </div>
                                    <span class="px-2.5 py-1 rounded text-xs font-black uppercase tracking-widest {{ $c->status === 'approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($c->status === 'pending' ? 'bg-amber-50 text-amber-800 border border-amber-200' : 'bg-red-50 text-red-700 border border-red-200') }}">
                                        {{ $c->status }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-12 text-center text-slate-600 font-bold uppercase tracking-wider text-xs">
                            Belum ada proyek penggalangan dana yang dibuat.
                        </div>
                    @endif
                </div>

                <!-- Distributions Reported -->
                <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 space-y-6 shadow-sm">
                    <div>
                        <h2 class="font-montserrat font-black text-slate-900 uppercase tracking-tight italic text-sm">Laporan Penyaluran <span class="text-primary-600">Dilaporkan</span></h2>
                        <p class="text-xs text-slate-600 font-bold uppercase tracking-widest mt-0.5">Penyaluran bantuan yang didistribusikan langsung</p>
                    </div>
                    <hr class="border-slate-200">

                    @if($myDistributions->count() > 0)
                        <div class="space-y-4">
                            @foreach($myDistributions as $d)
                                <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl space-y-2">
                                    <div class="flex justify-between items-center gap-3">
                                        <h3 class="font-montserrat font-black text-slate-800 text-xs uppercase tracking-wide truncate max-w-[200px]">{{ $d->title }}</h3>
                                        <span class="text-primary-700 font-montserrat font-black text-xs">Rp {{ number_format($d->amount, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between items-center text-xs text-slate-600 font-bold uppercase tracking-wider">
                                        <span>Penerima: {{ $d->recipient_name }}</span>
                                        <span>{{ $d->distribution_date ? $d->distribution_date->format('d M Y') : '-' }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-12 text-center text-slate-600 font-bold uppercase tracking-wider text-xs">
                            Belum ada laporan penyaluran bantuan.
                        </div>
                    @endif
                </div>
            </div>

        </div>

    </div>
</section>
@endsection
