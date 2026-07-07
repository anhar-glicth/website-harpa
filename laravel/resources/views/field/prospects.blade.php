@extends('layouts.app')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-20">
    
    <!-- Success Alert -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-250 text-emerald-800 rounded-2xl font-semibold text-xs flex items-center gap-3">
            <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar Menu -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white border border-slate-100 p-8 rounded-3xl space-y-6 shadow-sm">
                <div class="flex items-center space-x-3">
                    @if(Auth::user()->profile_photo)
                        <img src="{{ Auth::user()->profile_photo }}" class="w-12 h-12 rounded-2xl object-cover shadow-sm border border-slate-100" alt="">
                    @else
                        <div class="w-12 h-12 bg-primary-50 text-primary-700 rounded-2xl flex items-center justify-center font-black text-base uppercase border border-slate-100 shadow-sm">
                            {{ substr(Auth::user()->name, 0, 2) }}
                        </div>
                    @endif
                    <div>
                        <h4 class="font-montserrat font-black uppercase text-xs sm:text-sm text-slate-800 leading-tight">{{ Auth::user()->name }}</h4>
                        <span class="text-[9px] text-primary-600 font-bold uppercase tracking-wider block mt-1">Petugas Lapangan</span>
                    </div>
                </div>
                
                <hr class="border-slate-100">

                <div class="flex flex-col space-y-2 font-bold text-[10px] uppercase tracking-wider text-slate-500">
                    <a href="{{ route('field.dashboard') }}" class="flex items-center space-x-3 py-3 px-4 rounded-xl hover:bg-slate-50 hover:text-slate-800 transition-colors">
                        <i class="fa-solid fa-house-laptop"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('field.prospects') }}" class="flex items-center space-x-3 py-3 px-4 rounded-xl bg-primary-50 text-primary-700">
                        <i class="fa-solid fa-users"></i>
                        <span>Kelola Prospek</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Dashboard Content -->
        <div class="lg:col-span-3 space-y-8">
            <div class="space-y-2">
                <h1 class="text-2xl sm:text-3xl font-montserrat font-black text-slate-900 uppercase tracking-tight italic">
                    Kelola <span class="text-primary-600">Calon Donatur (Prospek)</span>
                </h1>
                <div class="w-12 h-1 bg-primary-500 rounded-full"></div>
            </div>

            <!-- Add Prospect Form Card -->
            <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 space-y-6 shadow-sm">
                <div>
                    <h3 class="font-montserrat font-black uppercase text-xs sm:text-sm text-slate-800 leading-tight">Tambah Calon Donatur Baru</h3>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Input data prospek donasi yang Anda hubungi di lapangan</p>
                </div>
                <hr class="border-slate-100">
                <form action="{{ route('field.prospects.store') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="sm:col-span-1">
                            <label class="block text-[9px] font-black uppercase tracking-wider text-slate-450 mb-1.5 ml-1">Nama Lengkap</label>
                            <input type="text" name="name" required placeholder="Nama Prospek" class="w-full bg-slate-50 border border-slate-150 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all">
                        </div>
                        <div class="sm:col-span-1">
                            <label class="block text-[9px] font-black uppercase tracking-wider text-slate-450 mb-1.5 ml-1">No. HP / WA</label>
                            <input type="text" name="phone" placeholder="Contoh: 081234567" class="w-full bg-slate-50 border border-slate-150 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all">
                        </div>
                        <div class="sm:col-span-1">
                            <label class="block text-[9px] font-black uppercase tracking-wider text-slate-450 mb-1.5 ml-1">Alamat Email</label>
                            <input type="email" name="email" placeholder="Email Prospek" class="w-full bg-slate-50 border border-slate-150 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all">
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-wider py-4 rounded-2xl shadow-lg shadow-primary-600/10 transition duration-300">
                            Simpan Prospek <i class="fa-solid fa-user-plus ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Prospects Log table -->
            <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 space-y-6 shadow-sm">
                <div>
                    <h3 class="font-montserrat font-black uppercase text-xs sm:text-sm text-slate-800 tracking-wider">Log Prospek & Follow Up</h3>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Catatan riwayat interaksi dan respon calon donatur</p>
                </div>
                <hr class="border-slate-100">
                
                <div class="space-y-6">
                    @forelse($prospects as $p)
                        <div class="border border-slate-150 p-6 rounded-3xl space-y-4 hover:border-primary-100 transition-all">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                                <div>
                                    <h4 class="font-montserrat font-black uppercase text-xs sm:text-sm text-slate-850 italic">{{ $p->name }}</h4>
                                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider block mt-1.5">HP: {{ $p->phone ?? '-' }} | Email: {{ $p->email ?? '-' }}</span>
                                </div>
                                <span class="text-[8px] text-slate-400 font-black uppercase tracking-wider bg-slate-50 border border-slate-100 px-2 py-0.5 rounded">Terdaftar: {{ $p->created_at->format('d M Y') }}</span>
                            </div>

                            <!-- Add response form -->
                            <form action="{{ route('field.prospects.response', $p->id) }}" method="POST" class="bg-slate-50 border border-slate-150 p-4 rounded-2xl flex flex-col sm:flex-row gap-3 items-end">
                                @csrf
                                <div class="flex-grow w-full">
                                    <label class="block text-[8px] font-black uppercase tracking-wider text-slate-450 mb-1 ml-1">Hasil Hubungi (Follow Up Log)</label>
                                    <input type="text" name="response" required placeholder="Contoh: Berminat donasi awal bulan depan..." class="w-full bg-white border border-slate-150 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 rounded-xl px-4 py-3 text-[10px] font-bold text-slate-850 outline-none transition-all">
                                </div>
                                <div class="w-full sm:w-44">
                                    <label class="block text-[8px] font-black uppercase tracking-wider text-slate-450 mb-1 ml-1">Status Prospek</label>
                                    <select name="status" class="w-full bg-white border border-slate-150 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 rounded-xl px-4 py-3 text-[10px] font-bold text-slate-850 outline-none transition-all">
                                        <option value="Follow Up">Follow Up</option>
                                        <option value="Tertarik">Tertarik</option>
                                        <option value="Tidak Tertarik">Tidak Tertarik</option>
                                    </select>
                                </div>
                                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-black text-[9px] uppercase tracking-wider px-5 py-3.5 rounded-xl shadow transition duration-200 shrink-0">
                                    Log Respon
                                </button>
                            </form>

                            <!-- Responses log list -->
                            @if($p->responses->count() > 0)
                                <div class="pl-4 border-l-2 border-slate-150 space-y-3 pt-2">
                                    <span class="text-[9px] font-black uppercase tracking-widest text-slate-400 block">Riwayat Hubungi:</span>
                                    @foreach($p->responses as $res)
                                        <div class="flex justify-between items-start text-[10px] font-semibold text-slate-650 bg-slate-50/50 p-3.5 rounded-xl border border-slate-50">
                                            <div class="space-y-0.5">
                                                <p class="leading-relaxed">"{{ $res->response }}"</p>
                                                <span class="text-[8px] text-slate-400 font-bold uppercase tracking-wider block mt-1">{{ $res->created_at->diffForHumans() }}</span>
                                            </div>
                                            <span class="text-[8px] font-black uppercase tracking-widest px-2 py-0.5 rounded shrink-0 {{ $res->status === 'Tertarik' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : ($res->status === 'Follow Up' ? 'bg-amber-50 text-amber-700 border border-amber-100' : 'bg-red-50 text-red-700 border border-red-100') }}">
                                                {{ $res->status }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="py-12 text-center text-slate-400 font-bold uppercase tracking-wider text-xs">
                            <i class="fa-solid fa-users text-slate-200 text-3xl mb-2 block"></i>
                            Belum ada calon donatur terdaftar.
                        </div>
                    @endforelse
                </div>

                <div class="pt-4">
                    {{ $prospects->links() }}
                </div>
            </div>

        </div>

    </div>
</section>
@endsection
