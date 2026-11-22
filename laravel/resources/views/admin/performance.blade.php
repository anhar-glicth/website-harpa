@extends('layouts.app')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-12 sm:py-16">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar Menu -->
        <div class="hidden lg:block lg:col-span-1 space-y-6">
            @include('admin.sidebar', ['active' => 'performance'])
        </div>

        <!-- Dashboard Content -->
        <div class="lg:col-span-3 space-y-6">
            
            <!-- Header Page Title -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="space-y-1">
                    <h1 class="text-2xl sm:text-3xl font-montserrat font-black text-slate-900 uppercase tracking-tight italic">
                        Progres & Kinerja <span class="text-primary-600">Pegawai & Staf</span>
                    </h1>
                    <div class="w-12 h-1 bg-primary-500 rounded-full"></div>
                </div>

                <a href="{{ route('admin.users') }}" class="min-h-[44px] inline-flex items-center justify-center px-4 py-2.5 bg-primary-600 text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-xs hover:bg-primary-700 transition-colors">
                    <i class="fa-solid fa-user-plus mr-2" aria-hidden="true"></i> + Kelola Staf
                </a>
            </div>

            <!-- Filter & Search Toolbar -->
            <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 shadow-xs space-y-4">
                
                <!-- Search Form -->
                <form action="{{ route('admin.performance') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                    <input type="hidden" name="role" value="{{ $selectedRole }}">
                    
                    <div class="relative flex-1">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs" aria-hidden="true"></i>
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}" 
                            placeholder="Cari nama pegawai atau email..." 
                            class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-hidden focus:ring-2 focus:ring-primary-500 focus:bg-white transition-all min-h-[44px]"
                        >
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="submit" class="min-h-[44px] px-5 py-2.5 bg-[#063f5c] hover:bg-slate-900 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-colors shrink-0">
                            Cari
                        </button>
                        @if(request('search') || $selectedRole !== 'all')
                            <a href="{{ route('admin.performance') }}" class="min-h-[44px] px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold uppercase tracking-wider rounded-xl transition-colors inline-flex items-center justify-center shrink-0">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>

                <!-- Role Filter Tabs (Horizontal Scroll / Wrap) -->
                <div class="flex items-center gap-2 overflow-x-auto pb-1 pt-1 scrollbar-none">
                    @php
                        $tabItems = [
                            'all' => 'Semua Divisi',
                            'field_worker' => 'Petugas Lapangan',
                            'marketing' => 'Marketing',
                            'crm_staff' => 'CRM & WA',
                            'finance' => 'Keuangan',
                            'program_manager' => 'Manager Program',
                            'operator' => 'Data Entry',
                            'admin' => 'Super Admin',
                        ];
                    @endphp

                    @foreach($tabItems as $roleKey => $roleTitle)
                        @php
                            $isActive = ($selectedRole === $roleKey);
                            $count = $roleCounts[$roleKey] ?? 0;
                            $filterUrl = route('admin.performance', array_merge(request()->except(['page', 'role']), ['role' => $roleKey]));
                        @endphp
                        <a 
                            href="{{ $filterUrl }}" 
                            class="min-h-[38px] px-3.5 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition-all inline-flex items-center gap-1.5 shrink-0 {{ $isActive ? 'bg-[#063f5c] text-white shadow-xs' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200' }}"
                        >
                            <span>{{ $roleTitle }}</span>
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] font-black {{ $isActive ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-800' }}">
                                {{ $count }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Performance cards grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($workerPerformance as $wp)
                    @php
                        $roleBadgeColor = match($wp['worker']->role) {
                            'field_worker' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                            'marketing' => 'bg-amber-50 text-amber-800 border-amber-200',
                            'crm_staff' => 'bg-sky-50 text-sky-800 border-sky-200',
                            'finance' => 'bg-indigo-50 text-indigo-800 border-indigo-200',
                            'program_manager' => 'bg-purple-50 text-purple-800 border-purple-200',
                            'operator' => 'bg-teal-50 text-teal-800 border-teal-200',
                            'admin' => 'bg-rose-50 text-rose-800 border-rose-200',
                            default => 'bg-slate-50 text-slate-800 border-slate-200'
                        };
                    @endphp

                    <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-5 shadow-xs relative overflow-hidden hover:border-primary-300 transition-colors duration-200">
                        
                        <!-- Header: Info & Effectiveness -->
                        <div class="flex justify-between items-start gap-4">
                            <div class="flex items-center gap-3.5 min-w-0">
                                <div class="w-12 h-12 bg-[#063f5c] rounded-xl flex items-center justify-center text-lg font-black text-white shrink-0 shadow-xs">
                                    {{ strtoupper(substr($wp['worker']->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <h2 class="font-montserrat font-black text-slate-800 text-sm uppercase tracking-wide italic leading-snug truncate">
                                        {{ $wp['worker']->name }}
                                    </h2>
                                    <div class="flex items-center gap-2 mt-1 flex-wrap">
                                        <span class="inline-block px-2.5 py-0.5 rounded-md text-[11px] font-bold uppercase tracking-wider border {{ $roleBadgeColor }}">
                                            {{ $wp['worker']->role_label }}
                                        </span>
                                    </div>
                                    <span class="text-xs text-slate-600 font-medium block truncate mt-1">
                                        {{ $wp['worker']->email }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="text-right shrink-0">
                                <span class="text-[11px] font-bold text-slate-600 uppercase tracking-wider block mb-0.5">Efektivitas</span>
                                <span class="text-lg font-black text-primary-700">{{ $wp['effectiveness'] }}%</span>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="space-y-1.5">
                            <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-primary-600 transition-all duration-700" style="width: {{ $wp['effectiveness'] }}%"></div>
                            </div>
                            <div class="flex justify-between text-xs text-slate-600 font-medium">
                                <span>Tugas: <strong class="text-slate-800">{{ $wp['completedTasks'] }}</strong> Selesai / {{ $wp['totalTasks'] }} Total</span>
                                <span class="font-bold text-slate-700">{{ $wp['effectiveness'] }}% tercapai</span>
                            </div>
                        </div>

                        <hr class="border-slate-100">

                        <!-- Main Stats Grid -->
                        <div class="grid grid-cols-3 gap-2 text-center text-xs">
                            <div class="bg-slate-50 border border-slate-200 p-3 rounded-xl">
                                <span class="block text-[11px] text-slate-600 font-bold uppercase tracking-wider mb-0.5">Kehadiran</span>
                                <span class="font-montserrat font-black text-slate-800">{{ $wp['presentDays'] }} Hari</span>
                            </div>
                            <div class="bg-slate-50 border border-slate-200 p-3 rounded-xl">
                                <span class="block text-[11px] text-slate-600 font-bold uppercase tracking-wider mb-0.5">Proyek</span>
                                <span class="font-montserrat font-black text-slate-800">{{ $wp['totalCampaigns'] }}</span>
                            </div>
                            <div class="bg-slate-50 border border-slate-200 p-3 rounded-xl">
                                <span class="block text-[11px] text-slate-600 font-bold uppercase tracking-wider mb-0.5">Dana</span>
                                <span class="font-montserrat font-black text-slate-800 text-xs leading-tight block truncate">
                                    Rp {{ number_format($wp['totalFunds'], 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <!-- Footer: Latest Activity & Button -->
                        <div class="flex items-center justify-between gap-4 pt-1">
                            <div class="text-xs text-slate-600 font-medium">
                                @if($wp['latest_attendance'])
                                    <span>Absen: <strong class="text-slate-800">{{ $wp['latest_attendance']->created_at->diffForHumans() }}</strong></span>
                                @else
                                    <span class="text-slate-400 italic">Belum ada log absen</span>
                                @endif
                            </div>
                            
                            <a href="{{ route('admin.users.monitoring', $wp['worker']->id) }}" class="inline-flex items-center gap-1.5 min-h-[44px] px-4 py-2 bg-slate-900 hover:bg-primary-700 text-white rounded-xl text-xs font-bold uppercase tracking-wider transition-colors shadow-xs">
                                <i class="fa-solid fa-gauge" aria-hidden="true"></i> Detail Pantau
                            </a>
                        </div>

                    </div>
                @empty
                    <div class="col-span-1 md:col-span-2 py-16 text-center text-slate-600 font-medium bg-white rounded-2xl border border-slate-200 shadow-xs">
                        <i class="fa-solid fa-users-slash text-slate-400 text-4xl mb-3 block" aria-hidden="true"></i>
                        <p class="font-bold text-slate-800 text-sm">Tidak ada staf yang ditemukan pada filter ini.</p>
                        <p class="text-xs text-slate-600 mt-1">Silakan pilih divisi lain atau klik tombol reset.</p>
                        <div class="mt-4">
                            <a href="{{ route('admin.performance') }}" class="inline-flex items-center min-h-[44px] px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold uppercase tracking-wider rounded-xl transition-colors">
                                Tampilkan Semua Divisi
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</section>
@endsection
