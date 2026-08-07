@extends('layouts.app')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-12">
    
    <!-- Alerts -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-250 text-emerald-800 rounded-2xl font-semibold text-xs flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button @click="$el.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar Menu -->
        <div class="lg:col-span-1 space-y-6">
            @include('admin.sidebar', ['active' => 'tasks'])
        </div>

        <!-- Dashboard Content -->
        <div class="lg:col-span-3 space-y-6">
            
            {{-- Header Page Title & Export Excel Button --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="space-y-1">
                    <h1 class="text-2xl sm:text-3xl font-montserrat font-black text-slate-900 uppercase tracking-tight italic">
                        Manajemen <span class="text-primary-600">Tugas Lapangan</span>
                    </h1>
                    <div class="w-12 h-1 bg-primary-500 rounded-full"></div>
                </div>

                {{-- Export Button (Excel/CSV) --}}
                <a href="{{ route('admin.tasks.export', request()->all()) }}" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-black uppercase tracking-wider flex items-center justify-center gap-2 shadow-sm transition-all">
                    <i class="fa-solid fa-file-excel text-sm"></i> Export Excel
                </a>
            </div>

            <!-- Two-column content layout -->
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                <!-- Assign Task Form (Left/Top) -->
                <div class="xl:col-span-1">
                    <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 space-y-6 shadow-sm">
                        <div>
                            <h3 class="font-montserrat font-black text-slate-900 uppercase tracking-tight italic text-sm">Berikan <span class="text-primary-600">Tugas Baru</span></h3>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Pilih pegawai dan kirim penugasan</p>
                        </div>
                        <hr class="border-slate-100">

                        <form action="{{ route('admin.tasks.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-[9px] font-black uppercase tracking-wider text-slate-450 mb-1.5 ml-1">Pilih Pegawai Lapangan</label>
                                <select name="user_id" required class="w-full bg-slate-50 border border-slate-150 rounded-2xl px-4 py-3.5 text-xs font-bold text-slate-800 outline-none focus:border-primary-500">
                                    <option value="" disabled selected>Pilih salah satu...</option>
                                    @foreach($workers as $w)
                                        <option value="{{ $w->id }}">{{ $w->name }} ({{ $w->email }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-[9px] font-black uppercase tracking-wider text-slate-450 mb-1.5 ml-1">Judul Tugas</label>
                                <input type="text" name="task" required placeholder="Judul tugas..." class="w-full bg-slate-50 border border-slate-150 rounded-2xl px-4 py-3.5 text-xs font-bold text-slate-800 outline-none focus:border-primary-500 uppercase tracking-wider">
                            </div>

                            <div>
                                <label class="block text-[9px] font-black uppercase tracking-wider text-slate-450 mb-1.5 ml-1">Deskripsi Detail</label>
                                <textarea name="description" rows="3" placeholder="Rincian penugasan lapangan..." class="w-full bg-slate-50 border border-slate-150 rounded-2xl px-4 py-3.5 text-xs font-semibold text-slate-800 outline-none focus:border-primary-500"></textarea>
                            </div>

                            <div>
                                <label class="block text-[9px] font-black uppercase tracking-wider text-slate-450 mb-1.5 ml-1">Tenggat Waktu (Deadline)</label>
                                <input type="datetime-local" name="deadline" class="w-full bg-slate-50 border border-slate-150 rounded-2xl px-4 py-3.5 text-xs font-bold text-slate-800 outline-none focus:border-primary-500">
                            </div>

                            <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-wider py-4 rounded-2xl shadow-lg shadow-primary-600/10 transition duration-300">
                                Berikan Tugas
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Tasks List Log (Right/Bottom) -->
                <div class="xl:col-span-2 space-y-4">
                    
                    {{-- Filter Form Tugas Lapangan --}}
                    <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm space-y-3">
                        <form action="{{ route('admin.tasks') }}" method="GET" class="flex flex-wrap items-center gap-3 text-xs font-bold text-slate-700">
                            
                            <div class="flex items-center gap-2">
                                <span>Cari:</span>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tugas / Deskripsi..." class="border border-slate-250 rounded-xl px-3 py-1.5 text-xs focus:border-primary-500 outline-none w-40">
                            </div>

                            <div class="flex items-center gap-2">
                                <span>Pegawai:</span>
                                <select name="user_id" class="border border-slate-250 rounded-xl px-3 py-1.5 text-xs focus:border-primary-500 outline-none w-36">
                                    <option value="">Semua Staff</option>
                                    @foreach($workers as $w)
                                        <option value="{{ $w->id }}" {{ request('user_id') == $w->id ? 'selected' : '' }}>{{ $w->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex items-center gap-2">
                                <span>Status:</span>
                                <select name="status" class="border border-slate-250 rounded-xl px-3 py-1.5 text-xs focus:border-primary-500 outline-none w-28">
                                    <option value="">Semua</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                                </select>
                            </div>

                            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-1.5 rounded-xl text-xs flex items-center gap-1.5 transition-all">
                                🔍 Filter
                            </button>
                            
                            @if(request()->anyFilled(['search', 'user_id', 'status', 'start_date', 'end_date']))
                                <a href="{{ route('admin.tasks') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-3 py-1.5 rounded-xl text-xs">Reset</a>
                            @endif

                        </form>
                    </div>

                    <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 space-y-6 shadow-sm">
                        <div>
                            <h3 class="font-montserrat font-black text-slate-900 uppercase tracking-tight italic text-sm">Histori <span class="text-primary-600">Semua Tugas</span></h3>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Daftar penugasan staff & laporan selesainya</p>
                        </div>
                        <hr class="border-slate-100">

                        @if($todos->count() > 0)
                            <div class="space-y-4">
                                @foreach($todos as $t)
                                    <div class="p-5 bg-slate-50 border border-slate-100 rounded-2xl space-y-4 hover:border-primary-100 transition-all">
                                        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-3">
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <span class="text-[9px] font-black text-indigo-600 uppercase tracking-wider bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100">
                                                        Untuk: {{ $t->user->name ?? 'Unknown User' }}
                                                    </span>
                                                    @if($t->assigner)
                                                        <span class="text-[8px] text-slate-400 font-bold uppercase tracking-wider">
                                                            Oleh: {{ $t->assigner->name }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <h4 class="font-montserrat font-black text-slate-900 text-xs sm:text-sm uppercase tracking-wide italic mt-1.5">{{ $t->task }}</h4>
                                                @if($t->description)
                                                    <p class="text-xs text-slate-500 font-semibold mt-1 leading-relaxed">{{ $t->description }}</p>
                                                @endif
                                            </div>
                                            <span class="px-3.5 py-1.5 rounded-full text-[8px] font-black uppercase tracking-widest text-center shrink-0 border {{ $t->status === 'completed' ? 'bg-emerald-50 text-emerald-700 border-emerald-200 shadow shadow-emerald-200/20' : 'bg-amber-50 text-amber-700 border-amber-200' }}">
                                                {{ $t->status === 'completed' ? 'SELESAI' : 'PENDING' }}
                                            </span>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4 text-[10px] font-black uppercase tracking-wider text-slate-400 border-t border-slate-200/50 pt-3">
                                            <div>
                                                <span class="block text-[8px] text-slate-400 mb-0.5">Diberikan</span>
                                                <span class="text-slate-700 block text-xs">{{ $t->created_at->format('d M Y H:i') }}</span>
                                            </div>
                                            <div>
                                                <span class="block text-[8px] text-red-400 mb-0.5">Deadline</span>
                                                <span class="text-red-600 block text-xs">{{ $t->deadline ? $t->deadline->format('d M Y H:i') : '-' }}</span>
                                            </div>
                                        </div>

                                        <!-- Reporting Notes & Attachments -->
                                        @if($t->status === 'completed')
                                            <div class="bg-white border border-slate-150 p-4 rounded-xl space-y-3 shadow-sm text-xs">
                                                <div class="flex items-center gap-1.5 text-primary-650 font-black text-[9px] uppercase tracking-wider">
                                                    <i class="fa-solid fa-circle-check"></i> Bukti Penyelesaian Lapangan
                                                </div>
                                                @if($t->result_note)
                                                    <p class="text-slate-600 font-semibold italic bg-slate-50 p-3 rounded-lg border border-slate-100">"{{ $t->result_note }}"</p>
                                                @endif
                                                @if($t->result_attachment)
                                                    <div class="mt-2.5">
                                                        <a href="{{ $t->result_attachment }}" target="_blank" class="w-full sm:w-fit inline-flex items-center justify-center gap-1.5 px-4 py-2.5 bg-slate-900 hover:bg-primary-650 text-white rounded-lg text-[9px] font-black uppercase tracking-widest transition-all">
                                                            <i class="fa-solid fa-image"></i> Lihat Foto Bukti
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            <div class="pt-4">
                                {{ $todos->links() }}
                            </div>
                        @else
                            <div class="py-16 text-center text-slate-400 font-bold uppercase tracking-wider text-xs">
                                <i class="fa-solid fa-clipboard-check text-slate-200 text-4xl mb-3 block"></i>
                                Belum ada daftar tugas lapangan yang dibuat.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection
