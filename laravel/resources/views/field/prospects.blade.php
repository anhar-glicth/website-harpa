@extends('layouts.app')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-20">
    
    <!-- Success Alert -->
    @if(session('success'))
        <div role="status" class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl font-semibold text-xs flex items-center gap-3">
            <i class="fa-solid fa-circle-check text-emerald-700 text-base" aria-hidden="true"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar Menu -->
        <div class="hidden lg:block lg:col-span-1 space-y-6">
            <div class="bg-white border border-slate-200 p-8 rounded-3xl space-y-6 shadow-sm">
                <div class="flex items-center space-x-3">
                    @if(Auth::user()->hasCustomPhoto())
                        <img src="{{ Auth::user()->profile_photo_url }}" width="48" height="48" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=063f5c&color=fff';" class="w-12 h-12 rounded-2xl object-cover shadow-sm border border-slate-200" alt="{{ Auth::user()->name }}">
                    @else
                        <div class="w-12 h-12 bg-primary-50 text-primary-700 rounded-2xl flex items-center justify-center font-black text-base uppercase border border-slate-200 shadow-sm">
                            {{ substr(Auth::user()->name, 0, 2) }}
                        </div>
                    @endif
                    <div>
                        <p class="font-montserrat font-black uppercase text-xs sm:text-sm text-slate-800 leading-tight">{{ Auth::user()->name }}</p>
                        <span class="text-xs text-primary-700 font-bold uppercase tracking-wider block mt-1">Petugas Lapangan</span>
                    </div>
                </div>
                
                <hr class="border-slate-200">

                <div class="flex flex-col space-y-2 font-bold text-xs uppercase tracking-wider text-slate-600">
                    <a href="{{ route('field.dashboard') }}" class="flex items-center space-x-3 min-h-[44px] py-3 px-4 rounded-xl hover:bg-slate-50 hover:text-slate-800 transition-colors">
                        <i class="fa-solid fa-house-laptop" aria-hidden="true"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('field.prospects') }}" class="flex items-center space-x-3 min-h-[44px] py-3 px-4 rounded-xl bg-primary-50 text-primary-700">
                        <i class="fa-solid fa-users" aria-hidden="true"></i>
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
            <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 space-y-6 shadow-sm">
                <div>
                    <h2 class="font-montserrat font-black uppercase text-xs sm:text-sm text-slate-800 leading-tight">Tambah Calon Donatur Baru</h2>
                    <p class="text-xs text-slate-600 font-bold uppercase tracking-widest mt-0.5">Input data prospek donasi yang Anda hubungi di lapangan</p>
                </div>
                <hr class="border-slate-200">
                <form action="{{ route('field.prospects.store') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="sm:col-span-1">
                            <label for="prospect_name" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5 ml-1">Nama Lengkap</label>
                            <input id="prospect_name" type="text" name="name" required placeholder="Nama Prospek" class="w-full min-h-[44px] bg-slate-50 border border-slate-300 focus:border-primary-600 focus:ring-4 focus:ring-primary-500/10 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-800 transition-all">
                        </div>
                        <div class="sm:col-span-1">
                            <label for="prospect_phone" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5 ml-1">No. HP / WA</label>
                            <input id="prospect_phone" type="text" name="phone" placeholder="Contoh: 081234567" class="w-full min-h-[44px] bg-slate-50 border border-slate-300 focus:border-primary-600 focus:ring-4 focus:ring-primary-500/10 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-800 transition-all">
                        </div>
                        <div class="sm:col-span-1">
                            <label for="prospect_email" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5 ml-1">Alamat Email</label>
                            <input id="prospect_email" type="email" name="email" placeholder="Email Prospek" class="w-full min-h-[44px] bg-slate-50 border border-slate-300 focus:border-primary-600 focus:ring-4 focus:ring-primary-500/10 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-800 transition-all">
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full min-h-[48px] bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-wider py-4 rounded-2xl shadow-lg shadow-primary-600/10 transition duration-300">
                            Simpan Prospek <i class="fa-solid fa-user-plus ml-2" aria-hidden="true"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Prospects Log table -->
            <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 space-y-6 shadow-sm">
                <div>
                    <h2 class="font-montserrat font-black uppercase text-xs sm:text-sm text-slate-800 tracking-wider">Log Prospek &amp; Follow Up</h2>
                    <p class="text-xs text-slate-600 font-bold uppercase tracking-widest mt-0.5">Catatan riwayat interaksi dan respon calon donatur</p>
                </div>
                <hr class="border-slate-200">
                
                <div class="space-y-6">
                    @forelse($prospects as $p)
                        <div class="border border-slate-200 p-6 rounded-3xl space-y-4 hover:border-primary-300 transition-all">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                                <div>
                                    <h3 class="font-montserrat font-black uppercase text-xs sm:text-sm text-slate-900 italic">{{ $p->name }}</h3>
                                    <span class="text-xs text-slate-600 font-bold uppercase tracking-wider block mt-1.5">HP: {{ $p->phone ?? '-' }} | Email: {{ $p->email ?? '-' }}</span>
                                </div>
                                <span class="text-xs text-slate-600 font-black uppercase tracking-wider bg-slate-50 border border-slate-200 px-2.5 py-1 rounded">Terdaftar: {{ $p->created_at->format('d M Y') }}</span>
                            </div>

                            <!-- Add response form -->
                            <form action="{{ route('field.prospects.response', $p->id) }}" method="POST" class="bg-slate-50 border border-slate-200 p-4 rounded-2xl flex flex-col sm:flex-row gap-3 items-end">
                                @csrf
                                <div class="flex-grow w-full">
                                    <label for="prospect_resp_{{ $p->id }}" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1 ml-1">Hasil Hubungi (Follow Up Log)</label>
                                    <input id="prospect_resp_{{ $p->id }}" type="text" name="response" required placeholder="Contoh: Berminat donasi awal bulan depan..." class="w-full min-h-[44px] bg-white border border-slate-300 focus:border-primary-600 focus:ring-4 focus:ring-primary-500/10 rounded-xl px-4 py-2.5 text-xs font-semibold text-slate-900 transition-all">
                                </div>
                                <div class="w-full sm:w-44">
                                    <label for="prospect_status_{{ $p->id }}" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1 ml-1">Status Prospek</label>
                                    <select id="prospect_status_{{ $p->id }}" name="status" class="w-full min-h-[44px] bg-white border border-slate-300 focus:border-primary-600 focus:ring-4 focus:ring-primary-500/10 rounded-xl px-4 py-2.5 text-xs font-semibold text-slate-900 transition-all">
                                        <option value="Follow Up">Follow Up</option>
                                        <option value="Tertarik">Tertarik</option>
                                        <option value="Tidak Tertarik">Tidak Tertarik</option>
                                    </select>
                                </div>
                                <button type="submit" class="min-h-[44px] bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-wider px-5 py-3 rounded-xl shadow transition duration-200 shrink-0">
                                    Log Respon
                                </button>
                            </form>

                            <!-- Responses log list -->
                            @if($p->responses->count() > 0)
                                <div class="pl-4 border-l-2 border-slate-200 space-y-3 pt-2">
                                    <span class="text-xs font-black uppercase tracking-widest text-slate-600 block">Riwayat Hubungi:</span>
                                    @foreach($p->responses as $res)
                                        <div class="flex justify-between items-start text-xs font-semibold text-slate-700 bg-slate-50 p-3.5 rounded-xl border border-slate-200">
                                            <div class="space-y-0.5">
                                                <p class="leading-relaxed">"{{ $res->response }}"</p>
                                                <span class="text-xs text-slate-600 font-bold uppercase tracking-wider block mt-1">{{ $res->created_at->diffForHumans() }}</span>
                                            </div>
                                            <span class="text-xs font-black uppercase tracking-widest px-2.5 py-0.5 rounded shrink-0 {{ $res->status === 'Tertarik' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($res->status === 'Follow Up' ? 'bg-amber-50 text-amber-800 border border-amber-200' : 'bg-red-50 text-red-700 border border-red-200') }}">
                                                {{ $res->status }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="py-12 text-center text-slate-600 font-bold uppercase tracking-wider text-xs">
                            <i class="fa-solid fa-users text-slate-400 text-3xl mb-2 block" aria-hidden="true"></i>
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
