@extends('layouts.app')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-20">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar Menu -->
        <div class="hidden lg:block lg:col-span-1 space-y-6">
            @include('admin.sidebar', ['active' => 'performance'])
        </div>

        <!-- Dashboard Content -->
        <div class="lg:col-span-3 space-y-8">
            <div class="space-y-2">
                <h1 class="text-2xl sm:text-3xl font-montserrat font-black text-slate-900 uppercase tracking-tight italic">
                    Progres & Kinerja <span class="text-primary-600">Pegawai Lapangan</span>
                </h1>
                <div class="w-12 h-1 bg-primary-500 rounded-full"></div>
            </div>

            <!-- Performance cards for each worker -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($workerPerformance as $wp)
                    <div class="bg-white border border-slate-100 rounded-[2rem] p-6 sm:p-8 space-y-6 shadow-sm relative overflow-hidden group hover:scale-[1.01] hover:border-primary-100 transition-all duration-300">
                        <div class="absolute -right-4 -top-4 w-24 h-24 bg-primary-50/20 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                        
                        <!-- Header: Info & Effectiveness -->
                        <div class="flex justify-between items-start gap-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-primary-400 to-emerald-600 rounded-xl flex items-center justify-center text-xl font-black text-white shrink-0 shadow">
                                    {{ strtoupper(substr($wp['worker']->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h4 class="font-montserrat font-black text-slate-800 text-sm uppercase tracking-wide italic leading-none">{{ $wp['worker']->name }}</h4>
                                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider block mt-1.5">{{ $wp['worker']->email }}</span>
                                </div>
                            </div>
                            
                            <div class="text-right shrink-0">
                                <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block mb-0.5">Efektivitas</span>
                                <span class="text-lg font-black text-primary-600">{{ $wp['effectiveness'] }}%</span>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="space-y-1.5">
                            <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-primary-500 transition-all duration-700" style="width: {{ $wp['effectiveness'] }}%"></div>
                            </div>
                            <div class="flex justify-between text-[9px] text-slate-400 font-bold uppercase tracking-wider">
                                <span>Tugas: {{ $wp['completedTasks'] }} Selesai / {{ $wp['totalTasks'] }} Total</span>
                                <span>{{ $wp['effectiveness'] }}% tercapai</span>
                            </div>
                        </div>

                        <hr class="border-slate-100/80">

                        <!-- Main Stats Grid -->
                        <div class="grid grid-cols-3 gap-2 text-center text-xs">
                            <div class="bg-slate-50 border border-slate-100/50 p-3 rounded-2xl">
                                <span class="block text-[8px] text-slate-400 font-black uppercase tracking-wider mb-1">Kehadiran</span>
                                <span class="font-montserrat font-black text-slate-700">{{ $wp['presentDays'] }} Hari</span>
                            </div>
                            <div class="bg-slate-50 border border-slate-100/50 p-3 rounded-2xl">
                                <span class="block text-[8px] text-slate-400 font-black uppercase tracking-wider mb-1">Proyek</span>
                                <span class="font-montserrat font-black text-slate-700">{{ $wp['totalCampaigns'] }}</span>
                            </div>
                            <div class="bg-slate-50 border border-slate-100/50 p-3 rounded-2xl">
                                <span class="block text-[8px] text-slate-400 font-black uppercase tracking-wider mb-1">Dana</span>
                                <span class="font-montserrat font-black text-slate-700 text-[10px] leading-tight block mt-0.5 truncate">Rp {{ number_format($wp['totalFunds'], 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <!-- Footer: Latest Activity & Button -->
                        <div class="flex items-center justify-between gap-4 pt-2">
                            <div class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">
                                @if($wp['latest_attendance'])
                                    <span>Absen Terakhir: {{ $wp['latest_attendance']->created_at->diffForHumans() }}</span>
                                @else
                                    <span>Belum ada log absen</span>
                                @endif
                            </div>
                            
                            <a href="{{ route('admin.users.monitoring', $wp['worker']->id) }}" class="inline-flex items-center gap-1.5 px-4.5 py-2.5 bg-slate-900 hover:bg-primary-650 text-white rounded-xl text-[9px] font-black uppercase tracking-wider transition-all shadow shadow-slate-900/10">
                                <i class="fa-solid fa-gauge"></i> Detail Pantau
                            </a>
                        </div>

                    </div>
                @empty
                    <div class="col-span-2 py-16 text-center text-slate-400 font-bold uppercase tracking-wider bg-white rounded-3xl border border-slate-100">
                        <i class="fa-solid fa-chart-line text-slate-200 text-4xl mb-3 block"></i>
                        Tidak ada petugas lapangan yang terdaftar untuk dipantau.
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</section>
@endsection
