@extends('layouts.app')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-20">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar Menu -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white border border-slate-100 p-8 rounded-3xl space-y-6 shadow-sm">
                <div class="flex items-center space-x-3">
                    @if(Auth::user()->profile_photo)
                        <img src="{{ Auth::user()->profile_photo }}" class="w-12 h-12 rounded-2xl object-cover" alt="">
                    @else
                        <div class="w-12 h-12 bg-primary-100 text-primary-700 rounded-2xl flex items-center justify-center font-bold text-base uppercase">
                            {{ substr(Auth::user()->name, 0, 2) }}
                        </div>
                    @endif
                    <div>
                        <h4 class="font-montserrat font-black uppercase text-xs sm:text-sm text-slate-800 leading-tight">{{ Auth::user()->name }}</h4>
                        <span class="text-[9px] font-bold text-primary-600 uppercase block tracking-wider mt-1">Petugas Lapangan</span>
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
            <div class="bg-white border border-slate-100 rounded-3xl p-8 sm:p-10 space-y-6 shadow-sm">
                <h3 class="font-montserrat font-black uppercase text-base text-slate-900 leading-tight">Tambah Calon Donatur Baru</h3>
                <form action="{{ route('field.prospects.store') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="sm:col-span-1">
                            <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Nama Lengkap</label>
                            <input type="text" name="name" required placeholder="Nama Prospek" class="w-full bg-slate-50 border border-slate-100 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all">
                        </div>
                        <div class="sm:col-span-1">
                            <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">No. HP / WA</label>
                            <input type="text" name="phone" placeholder="Contoh: 081234567" class="w-full bg-slate-50 border border-slate-100 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all">
                        </div>
                        <div class="sm:col-span-1">
                            <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Alamat Email</label>
                            <input type="email" name="email" placeholder="Email Prospek" class="w-full bg-slate-50 border border-slate-100 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all">
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-widest py-4.5 rounded-2xl shadow-lg transition duration-300">
                            Simpan Prospek <i class="fa-solid fa-user-plus ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Prospects Log table -->
            <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 space-y-6 shadow-sm">
                <h3 class="font-montserrat font-black uppercase text-sm text-slate-800 tracking-wider">Log Prospek & Follow Up</h3>
                
                <div class="space-y-6">
                    @forelse($prospects as $p)
                        <div class="border border-slate-100 p-6 rounded-3xl space-y-4">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                                <div>
                                    <h4 class="font-montserrat font-black uppercase text-xs sm:text-sm text-slate-800">{{ $p->name }}</h4>
                                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider block mt-1">HP: {{ $p->phone ?? '-' }} | Email: {{ $p->email ?? '-' }}</span>
                                </div>
                                <span class="text-[8px] text-slate-400 font-bold uppercase tracking-wider">Terdaftar: {{ $p->created_at->format('d M Y') }}</span>
                            </div>

                            <!-- Add response form -->
                            <form action="{{ route('field.prospects.response', $p->id) }}" method="POST" class="bg-slate-50/50 border border-slate-100/50 p-4 rounded-2xl flex flex-col sm:flex-row gap-3 items-end">
                                @csrf
                                <div class="flex-grow w-full">
                                    <label class="block text-[8px] font-black uppercase tracking-wider text-slate-400 mb-1">Hasil Hubungi (Follow Up Log)</label>
                                    <input type="text" name="response" required placeholder="Contoh: Berminat donasi awal bulan depan..." class="w-full bg-white border border-slate-100 focus:border-primary-500 rounded-xl px-4 py-2.5 text-[10px] font-bold text-slate-800 outline-none transition-all">
                                </div>
                                <div class="w-full sm:w-44">
                                    <label class="block text-[8px] font-black uppercase tracking-wider text-slate-400 mb-1">Status Prospek</label>
                                    <select name="status" class="w-full bg-white border border-slate-100 focus:border-primary-500 rounded-xl px-4 py-2.5 text-[10px] font-bold text-slate-800 outline-none transition-all">
                                        <option value="Follow Up">Follow Up</option>
                                        <option value="Tertarik">Tertarik</option>
                                        <option value="Tidak Tertarik">Tidak Tertarik</option>
                                    </select>
                                </div>
                                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-black text-[9px] uppercase tracking-wider px-4 py-3 rounded-xl shadow transition duration-200">
                                    Log
                                </button>
                            </form>

                            <!-- Responses log list -->
                            @if($p->responses->count() > 0)
                                <div class="pl-4 border-l-2 border-slate-100 space-y-2 pt-2">
                                    <span class="text-[9px] font-black uppercase tracking-widest text-slate-400 block mb-1">Riwayat Follow Up:</span>
                                    @foreach($p->responses as $res)
                                        <div class="flex justify-between items-start text-[10px] font-bold text-slate-600">
                                            <div class="space-y-0.5">
                                                <p class="leading-relaxed">"{{ $res->response }}"</p>
                                                <span class="text-[8px] text-slate-400 font-bold uppercase tracking-wider">{{ $res->created_at->diffForHumans() }}</span>
                                            </div>
                                            <span class="bg-primary-50 text-primary-700 text-[8px] font-black uppercase tracking-widest px-2 py-0.5 rounded shrink-0">
                                                {{ $res->status }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider text-center py-8">Belum ada calon donatur terdaftar.</p>
                    @endforelse
                </div>

                <div>
                    {{ $prospects->links() }}
                </div>
            </div>

        </div>

    </div>
</section>
@endsection
