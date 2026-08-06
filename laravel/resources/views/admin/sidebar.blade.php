<!-- Info Admin Card -->
<div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm relative overflow-hidden group">
    <div class="absolute -right-4 -top-4 w-24 h-24 bg-primary-650/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
    <div class="relative z-10 space-y-4 text-center">
        <div class="w-16 h-16 bg-primary-50 text-primary-650 rounded-2xl flex items-center justify-center text-2xl mx-auto shadow-sm">
            <i class="fa-solid fa-user-shield"></i>
        </div>
        <div class="space-y-1">
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none">Super Admin</p>
            <p class="text-base font-black text-slate-900 tracking-tighter truncate">{{ Auth::user()->name }}</p>
        </div>
        <div class="flex gap-2 justify-center pt-2">
            <a href="{{ route('admin.settings') }}" class="p-2.5 bg-slate-50 text-slate-400 rounded-xl hover:text-primary-600 hover:bg-primary-50 transition-all shadow-sm">
                <i class="fa-solid fa-cog"></i>
            </a>
            <a href="{{ route('admin.users') }}" class="px-4 py-2.5 bg-primary-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-primary-100 hover:bg-primary-700 transition-all">
                + Staff
            </a>
        </div>
    </div>
</div>

<!-- Menu Navigasi Admin Accordion Card -->
<nav class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden space-y-0.5">
    
    <!-- 1. MENU UTAMA GROUP -->
    <div x-data="{ isOpen: true }">
        <div @click="isOpen = !isOpen" class="bg-slate-900 px-6 py-4.5 flex justify-between items-center text-white cursor-pointer hover:bg-slate-950 transition-colors">
            <span class="text-[11px] font-bold uppercase tracking-wider">Menu Utama</span>
            <i class="fa-solid fa-chevron-up text-[10px] transition-transform duration-300" :class="!isOpen ? 'rotate-180' : ''"></i>
        </div>
        <div x-show="isOpen" x-collapse class="p-3 space-y-1.5 animate-in slide-in-from-top-2 duration-300">
            <!-- Tab: Dashboard -->
            <a href="{{ route('admin.dashboard') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'dashboard' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'dashboard' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-chart-pie text-xs"></i>
                </div>
                <span class="whitespace-nowrap">Dashboard</span>
            </a>

            <!-- Tab: Analisa Data & RFM -->
            <a href="{{ route('admin.analytics') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'analytics' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'analytics' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-brain text-xs"></i>
                </div>
                <span class="whitespace-nowrap">Analisa Data & RFM</span>
            </a>

            <!-- Tab: Kelola Kampanye -->
            <a href="{{ route('admin.campaigns') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'campaigns' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'campaigns' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-gift text-xs"></i>
                </div>
                <span class="whitespace-nowrap">Kelola Kampanye</span>
            </a>

            <!-- Tab: Verifikasi Donasi -->
            <a href="{{ route('admin.donations') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'donations' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'donations' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-file-invoice-dollar text-xs"></i>
                </div>
                <span class="whitespace-nowrap">Verifikasi Donasi</span>
            </a>

            <!-- Tab: Distribusi -->
            <a href="{{ route('admin.distributions') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'distributions' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'distributions' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-truck-ramp-box text-xs"></i>
                </div>
                <span class="whitespace-nowrap">Distribusi</span>
            </a>
        </div>
    </div>

    <!-- 2. MONITORING STAFF GROUP -->
    <div x-data="{ isOpen: true }" class="border-t border-slate-100">
        <div @click="isOpen = !isOpen" class="bg-secondary-600 px-6 py-4.5 flex justify-between items-center text-white cursor-pointer hover:bg-secondary-700 transition-colors">
            <span class="text-[11px] font-bold uppercase tracking-wider">Monitoring Staff</span>
            <i class="fa-solid fa-chevron-up text-[10px] transition-transform duration-300" :class="!isOpen ? 'rotate-180' : ''"></i>
        </div>
        <div x-show="isOpen" x-collapse class="p-3 space-y-1.5 animate-in slide-in-from-top-2 duration-300">
            <!-- Tab: Presensi Pegawai -->
            <a href="{{ route('admin.attendance') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'attendance' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'attendance' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-user-clock text-xs"></i>
                </div>
                <span class="whitespace-nowrap">Presensi Pegawai</span>
            </a>

            <!-- Tab: Tugas Lapangan -->
            <a href="{{ route('admin.tasks') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'tasks' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'tasks' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-list-check text-xs"></i>
                </div>
                <span class="whitespace-nowrap">Tugas Lapangan</span>
            </a>

            <!-- Tab: Progres Pegawai -->
            <a href="{{ route('admin.performance') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'performance' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'performance' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-chart-line text-xs"></i>
                </div>
                <span class="whitespace-nowrap">Progres Pegawai</span>
            </a>
        </div>
    </div>

    <!-- 3. ADMIN MANAGEMENT GROUP -->
    <div x-data="{ isOpen: true }" class="border-t border-slate-100">
        <div @click="isOpen = !isOpen" class="bg-primary-600 px-6 py-4.5 flex justify-between items-center text-white cursor-pointer hover:bg-primary-750 transition-colors">
            <span class="text-[11px] font-bold uppercase tracking-wider">Admin Management</span>
            <i class="fa-solid fa-chevron-up text-[10px] transition-transform duration-300" :class="!isOpen ? 'rotate-180' : ''"></i>
        </div>
        <div x-show="isOpen" x-collapse class="p-3 space-y-1.5 animate-in slide-in-from-top-2 duration-300">
            <!-- Tab: Kelola Pengguna -->
            <a href="{{ route('admin.users') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'users' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'users' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-users text-xs"></i>
                </div>
                <span class="whitespace-nowrap">Kelola Pengguna</span>
            </a>

            <!-- Tab: Master Karyawan -->
            <a href="{{ route('admin.karyawan') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'karyawan' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'karyawan' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-id-card text-xs"></i>
                </div>
                <span class="whitespace-nowrap">Master Karyawan</span>
            </a>

            <!-- Tab: Channel Donasi -->
            <a href="{{ route('admin.donor_channels') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'donor_channels' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'donor_channels' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-layer-group text-xs"></i>
                </div>
                <span class="whitespace-nowrap">Channel Donasi</span>
            </a>

            <!-- Tab: Rumpun Program -->
            <a href="{{ route('admin.program_groups') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'program_groups' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'program_groups' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-sitemap text-xs"></i>
                </div>
                <span class="whitespace-nowrap">Rumpun Program</span>
            </a>

            <!-- Tab: Master Program -->
            <a href="{{ route('admin.programs') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'programs' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'programs' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-clipboard-list text-xs"></i>
                </div>
                <span class="whitespace-nowrap">Master Program</span>
            </a>

            <!-- Tab: Via Bayar -->
            <a href="{{ route('admin.payment_via') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'payment_via' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'payment_via' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-credit-card text-xs"></i>
                </div>
                <span class="whitespace-nowrap">Via Bayar</span>
            </a>

            <!-- Tab: Media Prospek -->
            <a href="{{ route('admin.prospect_media') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'prospect_media' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'prospect_media' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-bullhorn text-xs"></i>
                </div>
                <span class="whitespace-nowrap">Media Prospek</span>
            </a>

            <!-- Tab: Metode Prospek -->
            <a href="{{ route('admin.prospect_methods') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'prospect_methods' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'prospect_methods' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-handshake-angle text-xs"></i>
                </div>
                <span class="whitespace-nowrap">Metode Prospek</span>
            </a>

            <!-- Tab: Jenis Prospek -->
            <a href="{{ route('admin.prospect_types') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'prospect_types' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'prospect_types' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-tags text-xs"></i>
                </div>
                <span class="whitespace-nowrap">Jenis Prospek</span>
            </a>






            <!-- Tab: Pengaturan Web -->
            <a href="{{ route('admin.settings') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'settings' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'settings' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-gears text-xs"></i>
                </div>
                <span class="whitespace-nowrap">Pengaturan Web</span>
            </a>
        </div>
    </div>

</nav>
