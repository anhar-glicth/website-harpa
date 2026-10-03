<!-- Info Admin Card -->
<div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm relative overflow-hidden group">
    <div class="absolute -right-4 -top-4 w-24 h-24 bg-primary-650/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
    <div class="relative z-10 space-y-4 text-center">
        @if(Auth::user()->hasCustomPhoto())
            <img src="{{ Auth::user()->profile_photo_url }}" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=ea580c&color=fff';" class="w-16 h-16 rounded-2xl object-cover mx-auto shadow-sm border-2 border-slate-100" alt="Foto Profil">
        @else
            <div class="w-16 h-16 bg-primary-50 text-primary-650 rounded-2xl flex items-center justify-center text-2xl mx-auto shadow-sm">
                <i class="fa-solid fa-user-shield"></i>
            </div>
        @endif
        <div class="space-y-1">
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none">{{ Auth::user()->role_label }}</p>
            <p class="text-base font-black text-slate-900 tracking-tighter truncate">{{ Auth::user()->name }}</p>
        </div>
        <div class="flex gap-2 justify-center pt-2">
            <a href="{{ route('profile.custom') }}" class="p-2.5 bg-slate-50 text-slate-400 rounded-xl hover:text-primary-600 hover:bg-primary-50 transition-all shadow-sm" title="Edit Profil Saya">
                <i class="fa-solid fa-user-pen"></i>
            </a>
            @if(Auth::user()->isAdmin())
            <a href="{{ route('admin.settings') }}" class="p-2.5 bg-slate-50 text-slate-400 rounded-xl hover:text-primary-600 hover:bg-primary-50 transition-all shadow-sm" title="Pengaturan Web">
                <i class="fa-solid fa-cog"></i>
            </a>
            <a href="{{ route('admin.users') }}" class="px-4 py-2.5 bg-primary-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-primary-100 hover:bg-primary-700 transition-all">
                + Staff
            </a>
            @endif
        </div>
    </div>
</div>

<!-- Menu Navigasi Admin Accordion Card (Pengaturan Awal Tertutup / Smart Active Open) -->
<nav class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden space-y-0.5">
    
    <!-- 1. MENU UTAMA GROUP -->
    @if(Auth::user()->canAccessMenu('dashboard') || Auth::user()->canAccessMenu('analytics') || Auth::user()->canAccessMenu('campaigns') || Auth::user()->canAccessMenu('donations'))
    <div x-data="{ isOpen: {{ in_array($active ?? '', ['dashboard', 'analytics', 'campaigns', 'donations']) ? 'true' : 'false' }} }">
        <div @click="isOpen = !isOpen" class="bg-slate-900 px-6 py-4.5 flex justify-between items-center text-white cursor-pointer hover:bg-slate-950 transition-colors">
            <span class="text-[11px] font-bold uppercase tracking-wider">Menu Utama</span>
            <i class="fa-solid fa-chevron-up text-[10px] transition-transform duration-300" :class="!isOpen ? 'rotate-180' : ''"></i>
        </div>
        <div x-show="isOpen" x-collapse class="p-3 space-y-1.5 animate-in slide-in-from-top-2 duration-300">
            @if(Auth::user()->canAccessMenu('dashboard'))
            <!-- Tab: Dashboard -->
            <a href="{{ route('admin.dashboard') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'dashboard' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'dashboard' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-chart-pie text-xs"></i>
                </div>
                <span class="whitespace-nowrap">Dashboard</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('analytics'))
            <!-- Tab: Analisa Data & RFM -->
            <a href="{{ route('admin.analytics') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'analytics' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'analytics' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-brain text-xs"></i>
                </div>
                <span class="whitespace-nowrap">Analisa Data & RFM</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('campaigns'))
            <!-- Tab: Kelola Kampanye -->
            <a href="{{ route('admin.campaigns') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'campaigns' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'campaigns' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-gift text-xs"></i>
                </div>
                <span class="whitespace-nowrap">Kelola Kampanye</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('donations'))
            <!-- Tab: Verifikasi Donasi -->
            <a href="{{ route('admin.donations') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'donations' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'donations' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-file-invoice-dollar text-xs"></i>
                </div>
                <span class="whitespace-nowrap">Verifikasi Donasi</span>
            </a>
            @endif
        </div>
    </div>
    @endif

    <!-- 2. MONITORING STAFF GROUP -->
    @if(Auth::user()->canAccessMenu('attendance') || Auth::user()->canAccessMenu('tasks') || Auth::user()->canAccessMenu('performance'))
    <div x-data="{ isOpen: {{ in_array($active ?? '', ['attendance', 'tasks', 'performance']) ? 'true' : 'false' }} }" class="border-t border-slate-100">
        <div @click="isOpen = !isOpen" class="bg-secondary-600 px-6 py-4.5 flex justify-between items-center text-white cursor-pointer hover:bg-secondary-700 transition-colors">
            <span class="text-[11px] font-bold uppercase tracking-wider">Monitoring Staff</span>
            <i class="fa-solid fa-chevron-up text-[10px] transition-transform duration-300" :class="!isOpen ? 'rotate-180' : ''"></i>
        </div>
        <div x-show="isOpen" x-collapse class="p-3 space-y-1.5 animate-in slide-in-from-top-2 duration-300">
            @if(Auth::user()->canAccessMenu('attendance'))
            <!-- Tab: Presensi Pegawai -->
            <a href="{{ route('admin.attendance') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'attendance' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'attendance' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-user-clock text-xs"></i>
                </div>
                <span class="whitespace-nowrap">Presensi Pegawai</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('tasks'))
            <!-- Tab: Tugas Lapangan -->
            <a href="{{ route('admin.tasks') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'tasks' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'tasks' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-list-check text-xs"></i>
                </div>
                <span class="whitespace-nowrap">Tugas Lapangan</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('performance'))
            <!-- Tab: Progres Pegawai -->
            <a href="{{ route('admin.performance') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'performance' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'performance' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-chart-line text-xs"></i>
                </div>
                <span class="whitespace-nowrap">Progres Pegawai</span>
            </a>
            @endif
        </div>
    </div>
    @endif

    <!-- 3. ADMIN MANAGEMENT GROUP -->
    @if(Auth::user()->canAccessMenu('users') || Auth::user()->canAccessMenu('offices') || Auth::user()->canAccessMenu('karyawan') || Auth::user()->canAccessMenu('donor_channels') || Auth::user()->canAccessMenu('program_groups') || Auth::user()->canAccessMenu('programs') || Auth::user()->canAccessMenu('payment_via') || Auth::user()->canAccessMenu('prospect_media') || Auth::user()->canAccessMenu('prospect_methods') || Auth::user()->canAccessMenu('prospect_types') || Auth::user()->canAccessMenu('fundraisers') || Auth::user()->canAccessMenu('prospect_responses') || Auth::user()->canAccessMenu('targets') || Auth::user()->canAccessMenu('settings'))
    <div x-data="{ isOpen: {{ in_array($active ?? '', ['users', 'offices', 'karyawan', 'donor_channels', 'program_groups', 'programs', 'payment_via', 'prospect_media', 'prospect_methods', 'prospect_types', 'fundraisers', 'prospect_responses', 'targets', 'settings']) ? 'true' : 'false' }} }" class="border-t border-slate-100">
        <div @click="isOpen = !isOpen" class="bg-primary-600 px-6 py-4.5 flex justify-between items-center text-white cursor-pointer hover:bg-primary-750 transition-colors">
            <span class="text-[11px] font-bold uppercase tracking-wider">Admin Management</span>
            <i class="fa-solid fa-chevron-up text-[10px] transition-transform duration-300" :class="!isOpen ? 'rotate-180' : ''"></i>
        </div>
        <div x-show="isOpen" x-collapse class="p-3 space-y-1.5 animate-in slide-in-from-top-2 duration-300">
            @if(Auth::user()->canAccessMenu('users'))
            <!-- Tab: Kelola Pengguna -->
            <a href="{{ route('admin.users') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'users' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'users' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-users text-xs"></i>
                </div>
                <span class="whitespace-nowrap">Kelola Pengguna</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('offices'))
            <!-- Tab: Master Kantor -->
            <a href="{{ route('admin.offices') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'offices' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'offices' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-building text-xs"></i>
                </div>
                <span class="whitespace-nowrap">Master Kantor</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('karyawan'))
            <!-- Tab: Master Karyawan -->
            <a href="{{ route('admin.karyawan') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'karyawan' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'karyawan' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-id-card text-xs"></i>
                </div>
                <span class="whitespace-nowrap">Master Karyawan</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('donor_channels'))
            <!-- Tab: Channel Donasi -->
            <a href="{{ route('admin.donor_channels') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'donor_channels' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'donor_channels' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-layer-group text-xs"></i>
                </div>
                <span class="whitespace-nowrap">Channel Donasi</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('program_groups'))
            <!-- Tab: Rumpun Program -->
            <a href="{{ route('admin.program_groups') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'program_groups' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'program_groups' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-sitemap text-xs"></i>
                </div>
                <span class="whitespace-nowrap">Rumpun Program</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('programs'))
            <!-- Tab: Master Program -->
            <a href="{{ route('admin.programs') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'programs' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'programs' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-clipboard-list text-xs"></i>
                </div>
                <span class="whitespace-nowrap">Master Program</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('payment_via'))
            <!-- Tab: Via Bayar -->
            <a href="{{ route('admin.payment_via') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'payment_via' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'payment_via' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-credit-card text-xs"></i>
                </div>
                <span class="whitespace-nowrap">Via Bayar</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('prospect_media'))
            <!-- Tab: Media Prospek -->
            <a href="{{ route('admin.prospect_media') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'prospect_media' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'prospect_media' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-bullhorn text-xs"></i>
                </div>
                <span class="whitespace-nowrap">Media Prospek</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('prospect_methods'))
            <!-- Tab: Metode Prospek -->
            <a href="{{ route('admin.prospect_methods') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'prospect_methods' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'prospect_methods' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-handshake-angle text-xs"></i>
                </div>
                <span class="whitespace-nowrap">Metode Prospek</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('prospect_types'))
            <!-- Tab: Jenis Prospek -->
            <a href="{{ route('admin.prospect_types') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'prospect_types' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'prospect_types' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-tags text-xs"></i>
                </div>
                <span class="whitespace-nowrap">Jenis Prospek</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('fundraisers'))
            <!-- Tab: Master Fundraiser -->
            <a href="{{ route('admin.fundraisers') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'fundraisers' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'fundraisers' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-user-group text-xs"></i>
                </div>
                <span class="whitespace-nowrap">Master Fundraiser</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('prospect_responses'))
            <!-- Tab: Respon Prospek -->
            <a href="{{ route('admin.prospect_responses') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'prospect_responses' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'prospect_responses' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-reply-all text-xs"></i>
                </div>
                <span class="whitespace-nowrap">Respon Prospek</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('targets'))
            <!-- Tab: Master Target -->
            <a href="{{ route('admin.targets') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'targets' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'targets' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-bullseye text-xs"></i>
                </div>
                <span class="whitespace-nowrap">Master Target</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('settings'))
            <!-- Tab: Pengaturan Web -->
            <a href="{{ route('admin.settings') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'settings' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'settings' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-gears text-xs"></i>
                </div>
                <span class="whitespace-nowrap">Pengaturan Web</span>
            </a>
            @endif
        </div>
    </div>
    @endif

    <!-- 4. DISTRIBUSI MANAGEMENT GROUP -->
    @if(Auth::user()->canAccessMenu('distribution_dashboard') || Auth::user()->canAccessMenu('distributions'))
    <div x-data="{ isOpen: {{ in_array($active ?? '', ['distribution_dashboard', 'distributions']) ? 'true' : 'false' }} }" class="border-t border-slate-100">
        <div @click="isOpen = !isOpen" class="bg-emerald-700 px-6 py-4.5 flex justify-between items-center text-white cursor-pointer hover:bg-emerald-800 transition-colors">
            <span class="text-[11px] font-bold uppercase tracking-wider">Distribusi Management</span>
            <i class="fa-solid fa-chevron-up text-[10px] transition-transform duration-300" :class="!isOpen ? 'rotate-180' : ''"></i>
        </div>
        <div x-show="isOpen" x-collapse class="p-3 space-y-1.5 animate-in slide-in-from-top-2 duration-300">
            @if(Auth::user()->canAccessMenu('distribution_dashboard'))
            <!-- Tab: Dashboard Penyaluran -->
            <a href="{{ route('admin.distribution_dashboard') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'distribution_dashboard' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'distribution_dashboard' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-chart-line text-xs"></i>
                </div>
                <span class="whitespace-nowrap">Dashboard Penyaluran</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('distributions'))
            <!-- Tab: List Penyaluran -->
            <a href="{{ route('admin.distributions') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'distributions' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'distributions' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-truck-ramp-box text-xs"></i>
                </div>
                <span class="whitespace-nowrap">List Penyaluran</span>
            </a>
            @endif
        </div>
    </div>
    @endif

    <!-- 5. TRANSACTION MANAGEMENT GROUP -->
    @if(Auth::user()->canAccessMenu('donors_list') || Auth::user()->canAccessMenu('transactions_list') || Auth::user()->canAccessMenu('prospects_list') || Auth::user()->canAccessMenu('potential_donors') || Auth::user()->canAccessMenu('transactions_dashboard') || Auth::user()->canAccessMenu('donors_dashboard') || Auth::user()->canAccessMenu('prospects_dashboard') || Auth::user()->canAccessMenu('target_vs_achievement') || Auth::user()->canAccessMenu('rfm_dashboard'))
    <div x-data="{ isOpen: {{ in_array($active ?? '', ['donors_list', 'transactions_list', 'prospects_list', 'potential_donors', 'transactions_dashboard', 'donors_dashboard', 'prospects_dashboard', 'target_vs_achievement', 'rfm_dashboard']) ? 'true' : 'false' }} }" class="border-t border-slate-100">
        <div @click="isOpen = !isOpen" class="bg-rose-600 px-6 py-4.5 flex justify-between items-center text-white cursor-pointer hover:bg-rose-700 transition-colors">
            <span class="text-[11px] font-bold uppercase tracking-wider">Transaction Management</span>
            <i class="fa-solid fa-chevron-up text-[10px] transition-transform duration-300" :class="!isOpen ? 'rotate-180' : ''"></i>
        </div>
        <div x-show="isOpen" x-collapse class="p-3 space-y-1.5 animate-in slide-in-from-top-2 duration-300">
            
            @if(Auth::user()->canAccessMenu('donors_list'))
            <!-- 1. List Donatur -->
            <a href="{{ route('admin.donors_list') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'donors_list' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'donors_list' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-users text-xs"></i>
                </div>
                <span class="whitespace-nowrap">List Donatur</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('transactions_list'))
            <!-- 2. List Transaksi -->
            <a href="{{ route('admin.transactions_list') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'transactions_list' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'transactions_list' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-receipt text-xs"></i>
                </div>
                <span class="whitespace-nowrap">List Transaksi</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('prospects_list'))
            <!-- 3. List Prospek -->
            <a href="{{ route('admin.prospects_list') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'prospects_list' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'prospects_list' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-address-book text-xs"></i>
                </div>
                <span class="whitespace-nowrap">List Prospek</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('potential_donors'))
            <!-- 4. List Calon Donatur -->
            <a href="{{ route('admin.potential_donors') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'potential_donors' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'potential_donors' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-user-plus text-xs"></i>
                </div>
                <span class="whitespace-nowrap">List Calon Donatur</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('transactions_dashboard'))
            <!-- 5. Dashboard Transaksi -->
            <a href="{{ route('admin.transactions_dashboard') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'transactions_dashboard' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'transactions_dashboard' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-chart-pie text-xs"></i>
                </div>
                <span class="whitespace-nowrap">Dashboard Transaksi</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('donors_dashboard'))
            <!-- 6. Dashboard Donatur -->
            <a href="{{ route('admin.donors_dashboard') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'donors_dashboard' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'donors_dashboard' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-chart-column text-xs"></i>
                </div>
                <span class="whitespace-nowrap">Dashboard Donatur</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('prospects_dashboard'))
            <!-- 7. Dashboard Prospek -->
            <a href="{{ route('admin.prospects_dashboard') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'prospects_dashboard' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'prospects_dashboard' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-chart-line text-xs"></i>
                </div>
                <span class="whitespace-nowrap">Dashboard Prospek</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('target_vs_achievement'))
            <!-- 8. Dashboard Target Vs Capaian -->
            <a href="{{ route('admin.target_vs_achievement') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'target_vs_achievement' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'target_vs_achievement' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-bullseye text-xs"></i>
                </div>
                <span class="whitespace-nowrap">Dashboard Target Vs Capaian</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('rfm_dashboard'))
            <!-- 9. Dashboard RFM -->
            <a href="{{ route('admin.rfm_dashboard') }}" 
               class="w-full flex items-center gap-4 px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-wider transition-all duration-300 group border border-transparent text-left outline-none {{ $active === 'rfm_dashboard' ? 'bg-primary-600 text-white shadow-xl shadow-primary-200/50 scale-[1.02]' : 'text-slate-400 hover:bg-white hover:text-primary-600 hover:border-primary-100 hover:shadow-lg hover:shadow-slate-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $active === 'rfm_dashboard' ? 'bg-white/20 text-white rotate-12' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 group-hover:rotate-12' }}">
                    <i class="fa-solid fa-layer-group text-xs"></i>
                </div>
                <span class="whitespace-nowrap">Dashboard RFM</span>
            </a>
            @endif

        </div>
    </div>
    @endif

</nav>
