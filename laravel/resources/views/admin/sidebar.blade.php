@php
    $active = $active ?? '';
    $menuLinkBase = 'w-full min-h-[44px] flex items-center gap-3.5 px-4 py-2.5 rounded-2xl text-xs font-bold uppercase tracking-wider transition-colors duration-200 group border text-left';
    $menuLinkActive = 'bg-primary-600 text-white border-primary-600 shadow-sm';
    $menuLinkIdle = 'border-transparent text-slate-600 hover:bg-slate-50 hover:text-primary-700 hover:border-slate-200';
    $iconBoxBase = 'w-8 h-8 rounded-xl flex items-center justify-center shrink-0 transition-colors duration-200';
    $iconBoxActive = 'bg-white/20 text-white';
    $iconBoxIdle = 'bg-slate-100 text-slate-600 group-hover:bg-primary-50 group-hover:text-primary-700';
@endphp
<!-- Info Admin Card -->
<div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm">
    <div class="space-y-4 text-center">
        @if(Auth::user()->hasCustomPhoto())
            <img src="{{ Auth::user()->profile_photo_url }}" width="64" height="64" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=063f5c&color=fff';" class="w-16 h-16 rounded-2xl object-cover mx-auto shadow-sm border-2 border-slate-200" alt="Foto Profil {{ Auth::user()->name }}">
        @else
            <div class="w-16 h-16 bg-primary-50 border border-primary-200 text-primary-700 rounded-2xl flex items-center justify-center text-2xl mx-auto shadow-sm">
                <i class="fa-solid fa-user-shield" aria-hidden="true"></i>
            </div>
        @endif
        <div class="space-y-1">
            <p class="text-xs font-black text-slate-600 uppercase tracking-widest leading-none">{{ Auth::user()->role_label }}</p>
            <p class="text-base font-black text-slate-900 tracking-tight truncate">{{ Auth::user()->name }}</p>
        </div>
        <div class="flex gap-2 justify-center pt-1">
            <a href="{{ route('profile.custom') }}" class="min-w-[44px] min-h-[44px] inline-flex items-center justify-center p-2.5 bg-slate-100 text-slate-600 rounded-xl hover:text-primary-700 hover:bg-primary-50 border border-slate-200 transition-colors shadow-sm" title="Edit Profil Saya" aria-label="Edit Profil Saya">
                <i class="fa-solid fa-user-pen" aria-hidden="true"></i>
            </a>
            @if(Auth::user()->isAdmin())
            <a href="{{ route('admin.settings') }}" class="min-w-[44px] min-h-[44px] inline-flex items-center justify-center p-2.5 bg-slate-100 text-slate-600 rounded-xl hover:text-primary-700 hover:bg-primary-50 border border-slate-200 transition-colors shadow-sm" title="Pengaturan Web" aria-label="Pengaturan Web">
                <i class="fa-solid fa-cog" aria-hidden="true"></i>
            </a>
            <a href="{{ route('admin.users') }}" class="min-h-[44px] inline-flex items-center justify-center px-4 py-2.5 bg-primary-600 text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-sm hover:bg-primary-700 transition-colors">
                + Staff
            </a>
            @endif
        </div>

        <!-- Tombol Cepat: Lihat Website & Keluar Akun -->
        <div class="pt-3 border-t border-slate-200 flex items-center justify-between gap-2">
            <a href="{{ route('home') }}" class="flex-1 min-h-[44px] flex items-center justify-center gap-1.5 py-2.5 px-3 rounded-xl text-xs font-black uppercase tracking-wider text-slate-700 bg-slate-100 border border-slate-200 hover:bg-slate-200 hover:text-primary-700 transition-colors" title="Buka Halaman Depan Web">
                <i class="fa-solid fa-globe" aria-hidden="true"></i> Website
            </a>
            <form method="POST" action="{{ route('logout') }}" class="flex-1">
                @csrf
                <button type="submit" class="w-full min-h-[44px] flex items-center justify-center gap-1.5 py-2.5 px-3 rounded-xl text-xs font-black uppercase tracking-wider text-rose-700 bg-rose-50 border border-rose-200 hover:bg-rose-100 hover:text-rose-800 transition-colors" title="Keluar dari Akun">
                    <i class="fa-solid fa-right-from-bracket" aria-hidden="true"></i> Keluar
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Menu Navigasi Admin Accordion Card (Pengaturan Awal Tertutup / Smart Active Open) -->
<nav aria-label="Navigasi Panel Admin" class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden space-y-0.5">

    <!-- 1. MENU UTAMA GROUP -->
    @if(Auth::user()->canAccessMenu('dashboard') || Auth::user()->canAccessMenu('analytics') || Auth::user()->canAccessMenu('campaigns') || Auth::user()->canAccessMenu('donations'))
    <div x-data="{ isOpen: {{ in_array($active, ['dashboard', 'analytics', 'campaigns', 'donations']) ? 'true' : 'false' }} }">
        <button type="button" @click="isOpen = !isOpen" :aria-expanded="isOpen ? 'true' : 'false'" class="w-full min-h-[48px] bg-slate-900 px-6 py-4 flex justify-between items-center text-white cursor-pointer hover:bg-slate-950 transition-colors text-left">
            <span class="text-xs font-bold uppercase tracking-wider">Menu Utama</span>
            <i class="fa-solid fa-chevron-up text-xs transition-transform duration-300" :class="!isOpen ? 'rotate-180' : ''" aria-hidden="true"></i>
        </button>
        <div x-show="isOpen" x-collapse class="p-3 space-y-1.5">
            @if(Auth::user()->canAccessMenu('dashboard'))
            <a href="{{ route('admin.dashboard') }}" class="{{ $menuLinkBase }} {{ $active === 'dashboard' ? $menuLinkActive : $menuLinkIdle }}">
                <div class="{{ $iconBoxBase }} {{ $active === 'dashboard' ? $iconBoxActive : $iconBoxIdle }}">
                    <i class="fa-solid fa-chart-pie text-xs" aria-hidden="true"></i>
                </div>
                <span class="whitespace-nowrap">Dashboard</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('analytics'))
            <a href="{{ route('admin.analytics') }}" class="{{ $menuLinkBase }} {{ $active === 'analytics' ? $menuLinkActive : $menuLinkIdle }}">
                <div class="{{ $iconBoxBase }} {{ $active === 'analytics' ? $iconBoxActive : $iconBoxIdle }}">
                    <i class="fa-solid fa-brain text-xs" aria-hidden="true"></i>
                </div>
                <span class="whitespace-nowrap">Analisa Data &amp; RFM</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('campaigns'))
            <a href="{{ route('admin.campaigns') }}" class="{{ $menuLinkBase }} {{ $active === 'campaigns' ? $menuLinkActive : $menuLinkIdle }}">
                <div class="{{ $iconBoxBase }} {{ $active === 'campaigns' ? $iconBoxActive : $iconBoxIdle }}">
                    <i class="fa-solid fa-gift text-xs" aria-hidden="true"></i>
                </div>
                <span class="whitespace-nowrap">Kelola Kampanye</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('donations'))
            <a href="{{ route('admin.donations') }}" class="{{ $menuLinkBase }} {{ $active === 'donations' ? $menuLinkActive : $menuLinkIdle }}">
                <div class="{{ $iconBoxBase }} {{ $active === 'donations' ? $iconBoxActive : $iconBoxIdle }}">
                    <i class="fa-solid fa-file-invoice-dollar text-xs" aria-hidden="true"></i>
                </div>
                <span class="whitespace-nowrap">Verifikasi Donasi</span>
            </a>
            @endif
        </div>
    </div>
    @endif

    <!-- 2. MONITORING STAFF GROUP -->
    @if(Auth::user()->canAccessMenu('attendance') || Auth::user()->canAccessMenu('tasks') || Auth::user()->canAccessMenu('performance'))
    <div x-data="{ isOpen: {{ in_array($active, ['attendance', 'tasks', 'performance']) ? 'true' : 'false' }} }" class="border-t border-slate-200">
        <button type="button" @click="isOpen = !isOpen" :aria-expanded="isOpen ? 'true' : 'false'" class="w-full min-h-[48px] bg-[#f7ad1a] px-6 py-4 flex justify-between items-center text-[#063f5c] cursor-pointer hover:bg-secondary-400 transition-colors text-left">
            <span class="text-xs font-black uppercase tracking-wider">Monitoring Staff</span>
            <i class="fa-solid fa-chevron-up text-xs transition-transform duration-300" :class="!isOpen ? 'rotate-180' : ''" aria-hidden="true"></i>
        </button>
        <div x-show="isOpen" x-collapse class="p-3 space-y-1.5">
            @if(Auth::user()->canAccessMenu('attendance'))
            <a href="{{ route('admin.attendance') }}" class="{{ $menuLinkBase }} {{ $active === 'attendance' ? $menuLinkActive : $menuLinkIdle }}">
                <div class="{{ $iconBoxBase }} {{ $active === 'attendance' ? $iconBoxActive : $iconBoxIdle }}">
                    <i class="fa-solid fa-user-clock text-xs" aria-hidden="true"></i>
                </div>
                <span class="whitespace-nowrap">Presensi Pegawai</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('tasks'))
            <a href="{{ route('admin.tasks') }}" class="{{ $menuLinkBase }} {{ $active === 'tasks' ? $menuLinkActive : $menuLinkIdle }}">
                <div class="{{ $iconBoxBase }} {{ $active === 'tasks' ? $iconBoxActive : $iconBoxIdle }}">
                    <i class="fa-solid fa-list-check text-xs" aria-hidden="true"></i>
                </div>
                <span class="whitespace-nowrap">Tugas Lapangan</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('performance'))
            <a href="{{ route('admin.performance') }}" class="{{ $menuLinkBase }} {{ $active === 'performance' ? $menuLinkActive : $menuLinkIdle }}">
                <div class="{{ $iconBoxBase }} {{ $active === 'performance' ? $iconBoxActive : $iconBoxIdle }}">
                    <i class="fa-solid fa-chart-line text-xs" aria-hidden="true"></i>
                </div>
                <span class="whitespace-nowrap">Progres Pegawai</span>
            </a>
            @endif
        </div>
    </div>
    @endif

    <!-- 3. ADMIN MANAGEMENT GROUP -->
    @if(Auth::user()->canAccessMenu('users') || Auth::user()->canAccessMenu('offices') || Auth::user()->canAccessMenu('karyawan') || Auth::user()->canAccessMenu('donor_channels') || Auth::user()->canAccessMenu('program_groups') || Auth::user()->canAccessMenu('programs') || Auth::user()->canAccessMenu('payment_via') || Auth::user()->canAccessMenu('prospect_media') || Auth::user()->canAccessMenu('prospect_methods') || Auth::user()->canAccessMenu('prospect_types') || Auth::user()->canAccessMenu('fundraisers') || Auth::user()->canAccessMenu('prospect_responses') || Auth::user()->canAccessMenu('targets') || Auth::user()->canAccessMenu('settings'))
    <div x-data="{ isOpen: {{ in_array($active, ['users', 'offices', 'karyawan', 'donor_channels', 'program_groups', 'programs', 'payment_via', 'prospect_media', 'prospect_methods', 'prospect_types', 'fundraisers', 'prospect_responses', 'targets', 'settings']) ? 'true' : 'false' }} }" class="border-t border-slate-200">
        <button type="button" @click="isOpen = !isOpen" :aria-expanded="isOpen ? 'true' : 'false'" class="w-full min-h-[48px] bg-primary-600 px-6 py-4 flex justify-between items-center text-white cursor-pointer hover:bg-primary-700 transition-colors text-left">
            <span class="text-xs font-bold uppercase tracking-wider">Admin Management</span>
            <i class="fa-solid fa-chevron-up text-xs transition-transform duration-300" :class="!isOpen ? 'rotate-180' : ''" aria-hidden="true"></i>
        </button>
        <div x-show="isOpen" x-collapse class="p-3 space-y-1.5">
            @if(Auth::user()->canAccessMenu('users'))
            <a href="{{ route('admin.users') }}" class="{{ $menuLinkBase }} {{ $active === 'users' ? $menuLinkActive : $menuLinkIdle }}">
                <div class="{{ $iconBoxBase }} {{ $active === 'users' ? $iconBoxActive : $iconBoxIdle }}">
                    <i class="fa-solid fa-users text-xs" aria-hidden="true"></i>
                </div>
                <span class="whitespace-nowrap">Kelola Pengguna</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('offices'))
            <a href="{{ route('admin.offices') }}" class="{{ $menuLinkBase }} {{ $active === 'offices' ? $menuLinkActive : $menuLinkIdle }}">
                <div class="{{ $iconBoxBase }} {{ $active === 'offices' ? $iconBoxActive : $iconBoxIdle }}">
                    <i class="fa-solid fa-building text-xs" aria-hidden="true"></i>
                </div>
                <span class="whitespace-nowrap">Master Kantor</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('karyawan'))
            <a href="{{ route('admin.karyawan') }}" class="{{ $menuLinkBase }} {{ $active === 'karyawan' ? $menuLinkActive : $menuLinkIdle }}">
                <div class="{{ $iconBoxBase }} {{ $active === 'karyawan' ? $iconBoxActive : $iconBoxIdle }}">
                    <i class="fa-solid fa-id-card text-xs" aria-hidden="true"></i>
                </div>
                <span class="whitespace-nowrap">Master Karyawan</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('donor_channels'))
            <a href="{{ route('admin.donor_channels') }}" class="{{ $menuLinkBase }} {{ $active === 'donor_channels' ? $menuLinkActive : $menuLinkIdle }}">
                <div class="{{ $iconBoxBase }} {{ $active === 'donor_channels' ? $iconBoxActive : $iconBoxIdle }}">
                    <i class="fa-solid fa-layer-group text-xs" aria-hidden="true"></i>
                </div>
                <span class="whitespace-nowrap">Channel Donasi</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('program_groups'))
            <a href="{{ route('admin.program_groups') }}" class="{{ $menuLinkBase }} {{ $active === 'program_groups' ? $menuLinkActive : $menuLinkIdle }}">
                <div class="{{ $iconBoxBase }} {{ $active === 'program_groups' ? $iconBoxActive : $iconBoxIdle }}">
                    <i class="fa-solid fa-sitemap text-xs" aria-hidden="true"></i>
                </div>
                <span class="whitespace-nowrap">Rumpun Program</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('programs'))
            <a href="{{ route('admin.programs') }}" class="{{ $menuLinkBase }} {{ $active === 'programs' ? $menuLinkActive : $menuLinkIdle }}">
                <div class="{{ $iconBoxBase }} {{ $active === 'programs' ? $iconBoxActive : $iconBoxIdle }}">
                    <i class="fa-solid fa-clipboard-list text-xs" aria-hidden="true"></i>
                </div>
                <span class="whitespace-nowrap">Master Program</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('payment_via'))
            <a href="{{ route('admin.payment_via') }}" class="{{ $menuLinkBase }} {{ $active === 'payment_via' ? $menuLinkActive : $menuLinkIdle }}">
                <div class="{{ $iconBoxBase }} {{ $active === 'payment_via' ? $iconBoxActive : $iconBoxIdle }}">
                    <i class="fa-solid fa-credit-card text-xs" aria-hidden="true"></i>
                </div>
                <span class="whitespace-nowrap">Via Bayar</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('prospect_media'))
            <a href="{{ route('admin.prospect_media') }}" class="{{ $menuLinkBase }} {{ $active === 'prospect_media' ? $menuLinkActive : $menuLinkIdle }}">
                <div class="{{ $iconBoxBase }} {{ $active === 'prospect_media' ? $iconBoxActive : $iconBoxIdle }}">
                    <i class="fa-solid fa-bullhorn text-xs" aria-hidden="true"></i>
                </div>
                <span class="whitespace-nowrap">Media Prospek</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('prospect_methods'))
            <a href="{{ route('admin.prospect_methods') }}" class="{{ $menuLinkBase }} {{ $active === 'prospect_methods' ? $menuLinkActive : $menuLinkIdle }}">
                <div class="{{ $iconBoxBase }} {{ $active === 'prospect_methods' ? $iconBoxActive : $iconBoxIdle }}">
                    <i class="fa-solid fa-handshake-angle text-xs" aria-hidden="true"></i>
                </div>
                <span class="whitespace-nowrap">Metode Prospek</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('prospect_types'))
            <a href="{{ route('admin.prospect_types') }}" class="{{ $menuLinkBase }} {{ $active === 'prospect_types' ? $menuLinkActive : $menuLinkIdle }}">
                <div class="{{ $iconBoxBase }} {{ $active === 'prospect_types' ? $iconBoxActive : $iconBoxIdle }}">
                    <i class="fa-solid fa-tags text-xs" aria-hidden="true"></i>
                </div>
                <span class="whitespace-nowrap">Jenis Prospek</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('fundraisers'))
            <a href="{{ route('admin.fundraisers') }}" class="{{ $menuLinkBase }} {{ $active === 'fundraisers' ? $menuLinkActive : $menuLinkIdle }}">
                <div class="{{ $iconBoxBase }} {{ $active === 'fundraisers' ? $iconBoxActive : $iconBoxIdle }}">
                    <i class="fa-solid fa-user-group text-xs" aria-hidden="true"></i>
                </div>
                <span class="whitespace-nowrap">Master Fundraiser</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('prospect_responses'))
            <a href="{{ route('admin.prospect_responses') }}" class="{{ $menuLinkBase }} {{ $active === 'prospect_responses' ? $menuLinkActive : $menuLinkIdle }}">
                <div class="{{ $iconBoxBase }} {{ $active === 'prospect_responses' ? $iconBoxActive : $iconBoxIdle }}">
                    <i class="fa-solid fa-reply-all text-xs" aria-hidden="true"></i>
                </div>
                <span class="whitespace-nowrap">Respon Prospek</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('targets'))
            <a href="{{ route('admin.targets') }}" class="{{ $menuLinkBase }} {{ $active === 'targets' ? $menuLinkActive : $menuLinkIdle }}">
                <div class="{{ $iconBoxBase }} {{ $active === 'targets' ? $iconBoxActive : $iconBoxIdle }}">
                    <i class="fa-solid fa-bullseye text-xs" aria-hidden="true"></i>
                </div>
                <span class="whitespace-nowrap">Master Target</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('settings'))
            <a href="{{ route('admin.settings') }}" class="{{ $menuLinkBase }} {{ $active === 'settings' ? $menuLinkActive : $menuLinkIdle }}">
                <div class="{{ $iconBoxBase }} {{ $active === 'settings' ? $iconBoxActive : $iconBoxIdle }}">
                    <i class="fa-solid fa-gears text-xs" aria-hidden="true"></i>
                </div>
                <span class="whitespace-nowrap">Pengaturan Web</span>
            </a>
            @endif
        </div>
    </div>
    @endif

    <!-- 4. DISTRIBUSI MANAGEMENT GROUP -->
    @if(Auth::user()->canAccessMenu('distribution_dashboard') || Auth::user()->canAccessMenu('distributions'))
    <div x-data="{ isOpen: {{ in_array($active, ['distribution_dashboard', 'distributions']) ? 'true' : 'false' }} }" class="border-t border-slate-200">
        <button type="button" @click="isOpen = !isOpen" :aria-expanded="isOpen ? 'true' : 'false'" class="w-full min-h-[48px] bg-emerald-700 px-6 py-4 flex justify-between items-center text-white cursor-pointer hover:bg-emerald-800 transition-colors text-left">
            <span class="text-xs font-bold uppercase tracking-wider">Distribusi Management</span>
            <i class="fa-solid fa-chevron-up text-xs transition-transform duration-300" :class="!isOpen ? 'rotate-180' : ''" aria-hidden="true"></i>
        </button>
        <div x-show="isOpen" x-collapse class="p-3 space-y-1.5">
            @if(Auth::user()->canAccessMenu('distribution_dashboard'))
            <a href="{{ route('admin.distribution_dashboard') }}" class="{{ $menuLinkBase }} {{ $active === 'distribution_dashboard' ? $menuLinkActive : $menuLinkIdle }}">
                <div class="{{ $iconBoxBase }} {{ $active === 'distribution_dashboard' ? $iconBoxActive : $iconBoxIdle }}">
                    <i class="fa-solid fa-chart-line text-xs" aria-hidden="true"></i>
                </div>
                <span class="whitespace-nowrap">Dashboard Penyaluran</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('distributions'))
            <a href="{{ route('admin.distributions') }}" class="{{ $menuLinkBase }} {{ $active === 'distributions' ? $menuLinkActive : $menuLinkIdle }}">
                <div class="{{ $iconBoxBase }} {{ $active === 'distributions' ? $iconBoxActive : $iconBoxIdle }}">
                    <i class="fa-solid fa-truck-ramp-box text-xs" aria-hidden="true"></i>
                </div>
                <span class="whitespace-nowrap">List Penyaluran</span>
            </a>
            @endif
        </div>
    </div>
    @endif

    <!-- 4.5. SISTEM DATA HARPA GROUP (Alur A - F) -->
    <div x-data="{ isOpen: {{ str_starts_with($active, 'harpa_') ? 'true' : 'false' }} }" class="border-t border-slate-200">
        <button type="button" @click="isOpen = !isOpen" :aria-expanded="isOpen ? 'true' : 'false'" class="w-full min-h-[48px] bg-[#063f5c] px-6 py-4 flex justify-between items-center text-white cursor-pointer hover:bg-[#04324a] transition-colors text-left">
            <span class="text-xs font-bold uppercase tracking-wider flex items-center gap-2">
                <i class="fa-solid fa-database text-[#f7ad1a]" aria-hidden="true"></i> Sistem Data Harpa
            </span>
            <i class="fa-solid fa-chevron-up text-xs transition-transform duration-300" :class="!isOpen ? 'rotate-180' : ''" aria-hidden="true"></i>
        </button>
        <div x-show="isOpen" x-collapse class="p-3 space-y-1.5">
            <a href="{{ route('admin.harpa.papan_kerja') }}" class="{{ $menuLinkBase }} {{ $active === 'harpa_papan_kerja' ? $menuLinkActive : $menuLinkIdle }}">
                <div class="{{ $iconBoxBase }} {{ $active === 'harpa_papan_kerja' ? $iconBoxActive : $iconBoxIdle }}">
                    <i class="fa-solid fa-clipboard-check text-xs" aria-hidden="true"></i>
                </div>
                <span class="whitespace-nowrap">Papan Kerja</span>
            </a>

            <a href="{{ route('admin.harpa.import_donasi') }}" class="{{ $menuLinkBase }} {{ $active === 'harpa_import_donasi' ? $menuLinkActive : $menuLinkIdle }}">
                <div class="{{ $iconBoxBase }} {{ $active === 'harpa_import_donasi' ? $iconBoxActive : $iconBoxIdle }}">
                    <i class="fa-solid fa-file-import text-xs" aria-hidden="true"></i>
                </div>
                <span class="whitespace-nowrap">Impor Donasi (Alur A)</span>
            </a>

            <a href="{{ route('admin.harpa.blast_history') }}" class="{{ $menuLinkBase }} {{ $active === 'harpa_blast' ? $menuLinkActive : $menuLinkIdle }}">
                <div class="{{ $iconBoxBase }} {{ $active === 'harpa_blast' ? $iconBoxActive : $iconBoxIdle }}">
                    <i class="fa-solid fa-paper-plane text-xs" aria-hidden="true"></i>
                </div>
                <span class="whitespace-nowrap">Riwayat Blast &amp; Diagnosa</span>
            </a>

            <a href="{{ route('admin.harpa.wbs') }}" class="{{ $menuLinkBase }} {{ $active === 'harpa_wbs' ? $menuLinkActive : $menuLinkIdle }}">
                <div class="{{ $iconBoxBase }} {{ $active === 'harpa_wbs' ? $iconBoxActive : $iconBoxIdle }}">
                    <i class="fa-solid fa-phone-slash text-xs" aria-hidden="true"></i>
                </div>
                <span class="whitespace-nowrap">Nomor Mati (WBS Pro)</span>
            </a>

            <a href="{{ route('admin.harpa.rekap') }}" class="{{ $menuLinkBase }} {{ $active === 'harpa_rekap' ? $menuLinkActive : $menuLinkIdle }}">
                <div class="{{ $iconBoxBase }} {{ $active === 'harpa_rekap' ? $iconBoxActive : $iconBoxIdle }}">
                    <i class="fa-solid fa-filter-circle-dollar text-xs" aria-hidden="true"></i>
                </div>
                <span class="whitespace-nowrap">Rekap &amp; Waterfall (Alur C)</span>
            </a>

            <a href="{{ route('admin.harpa.daftar_kirim') }}" class="{{ $menuLinkBase }} {{ $active === 'harpa_daftar_kirim' ? $menuLinkActive : $menuLinkIdle }}">
                <div class="{{ $iconBoxBase }} {{ $active === 'harpa_daftar_kirim' ? $iconBoxActive : $iconBoxIdle }}">
                    <i class="fa-solid fa-list-check text-xs" aria-hidden="true"></i>
                </div>
                <span class="whitespace-nowrap">Daftar Kirim (Alur D)</span>
            </a>

            <a href="{{ route('admin.harpa.laporan') }}" class="{{ $menuLinkBase }} {{ $active === 'harpa_laporan' ? $menuLinkActive : $menuLinkIdle }}">
                <div class="{{ $iconBoxBase }} {{ $active === 'harpa_laporan' ? $iconBoxActive : $iconBoxIdle }}">
                    <i class="fa-solid fa-chart-pie text-xs" aria-hidden="true"></i>
                </div>
                <span class="whitespace-nowrap">Laporan Analitik (Alur F)</span>
            </a>

            <a href="{{ route('admin.harpa.settings') }}" class="{{ $menuLinkBase }} {{ $active === 'harpa_settings' ? $menuLinkActive : $menuLinkIdle }}">
                <div class="{{ $iconBoxBase }} {{ $active === 'harpa_settings' ? $iconBoxActive : $iconBoxIdle }}">
                    <i class="fa-solid fa-sliders text-xs" aria-hidden="true"></i>
                </div>
                <span class="whitespace-nowrap">Pengaturan Ambang</span>
            </a>
        </div>
    </div>

    <!-- 4.6. MARKETING & META ADS GROUP -->
    @if(Auth::user()->canAccessMenu('marketing_dashboard') || Auth::user()->canAccessMenu('marketing_input'))
    <div x-data="{ isOpen: {{ str_starts_with($active, 'marketing_') ? 'true' : 'false' }} }" class="border-t border-slate-200">
        <button type="button" @click="isOpen = !isOpen" :aria-expanded="isOpen ? 'true' : 'false'" class="w-full min-h-[48px] bg-blue-700 px-6 py-4 flex justify-between items-center text-white cursor-pointer hover:bg-blue-800 transition-colors text-left">
            <span class="text-xs font-bold uppercase tracking-wider flex items-center gap-2">
                <i class="fa-brands fa-meta text-blue-100" aria-hidden="true"></i> Marketing &amp; Meta Ads
            </span>
            <i class="fa-solid fa-chevron-up text-xs transition-transform duration-300" :class="!isOpen ? 'rotate-180' : ''" aria-hidden="true"></i>
        </button>
        <div x-show="isOpen" x-collapse class="p-3 space-y-1.5">
            @if(Auth::user()->canAccessMenu('marketing_dashboard'))
            <a href="{{ route('admin.marketing.dashboard') }}" class="{{ $menuLinkBase }} {{ $active === 'marketing_dashboard' ? $menuLinkActive : $menuLinkIdle }}">
                <div class="{{ $iconBoxBase }} {{ $active === 'marketing_dashboard' ? $iconBoxActive : $iconBoxIdle }}">
                    <i class="fa-solid fa-chart-line text-xs" aria-hidden="true"></i>
                </div>
                <span class="whitespace-nowrap">Dashboard &amp; Analisa</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('marketing_input'))
            <a href="{{ route('admin.marketing.index') }}" class="{{ $menuLinkBase }} {{ in_array($active, ['marketing_index', 'marketing_create', 'marketing_edit']) ? $menuLinkActive : $menuLinkIdle }}">
                <div class="{{ $iconBoxBase }} {{ in_array($active, ['marketing_index', 'marketing_create', 'marketing_edit']) ? $iconBoxActive : $iconBoxIdle }}">
                    <i class="fa-brands fa-meta text-xs" aria-hidden="true"></i>
                </div>
                <span class="whitespace-nowrap">Data Iklan &amp; Input</span>
            </a>
            @endif
        </div>
    </div>
    @endif

    <!-- 5. TRANSACTION MANAGEMENT GROUP -->
    @if(Auth::user()->canAccessMenu('donors_list') || Auth::user()->canAccessMenu('transactions_list') || Auth::user()->canAccessMenu('prospects_list') || Auth::user()->canAccessMenu('potential_donors') || Auth::user()->canAccessMenu('transactions_dashboard') || Auth::user()->canAccessMenu('donors_dashboard') || Auth::user()->canAccessMenu('prospects_dashboard') || Auth::user()->canAccessMenu('target_vs_achievement') || Auth::user()->canAccessMenu('rfm_dashboard'))
    <div x-data="{ isOpen: {{ in_array($active, ['donors_list', 'transactions_list', 'prospects_list', 'potential_donors', 'transactions_dashboard', 'donors_dashboard', 'prospects_dashboard', 'target_vs_achievement', 'rfm_dashboard']) ? 'true' : 'false' }} }" class="border-t border-slate-200">
        <button type="button" @click="isOpen = !isOpen" :aria-expanded="isOpen ? 'true' : 'false'" class="w-full min-h-[48px] bg-rose-700 px-6 py-4 flex justify-between items-center text-white cursor-pointer hover:bg-rose-800 transition-colors text-left">
            <span class="text-xs font-bold uppercase tracking-wider">Transaction Management</span>
            <i class="fa-solid fa-chevron-up text-xs transition-transform duration-300" :class="!isOpen ? 'rotate-180' : ''" aria-hidden="true"></i>
        </button>
        <div x-show="isOpen" x-collapse class="p-3 space-y-1.5">
            @if(Auth::user()->canAccessMenu('donors_list'))
            <a href="{{ route('admin.donors_list') }}" class="{{ $menuLinkBase }} {{ $active === 'donors_list' ? $menuLinkActive : $menuLinkIdle }}">
                <div class="{{ $iconBoxBase }} {{ $active === 'donors_list' ? $iconBoxActive : $iconBoxIdle }}">
                    <i class="fa-solid fa-users text-xs" aria-hidden="true"></i>
                </div>
                <span class="whitespace-nowrap">List Donatur</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('transactions_list'))
            <a href="{{ route('admin.transactions_list') }}" class="{{ $menuLinkBase }} {{ $active === 'transactions_list' ? $menuLinkActive : $menuLinkIdle }}">
                <div class="{{ $iconBoxBase }} {{ $active === 'transactions_list' ? $iconBoxActive : $iconBoxIdle }}">
                    <i class="fa-solid fa-receipt text-xs" aria-hidden="true"></i>
                </div>
                <span class="whitespace-nowrap">List Transaksi</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('prospects_list'))
            <a href="{{ route('admin.prospects_list') }}" class="{{ $menuLinkBase }} {{ $active === 'prospects_list' ? $menuLinkActive : $menuLinkIdle }}">
                <div class="{{ $iconBoxBase }} {{ $active === 'prospects_list' ? $iconBoxActive : $iconBoxIdle }}">
                    <i class="fa-solid fa-address-book text-xs" aria-hidden="true"></i>
                </div>
                <span class="whitespace-nowrap">List Prospek</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('potential_donors'))
            <a href="{{ route('admin.potential_donors') }}" class="{{ $menuLinkBase }} {{ $active === 'potential_donors' ? $menuLinkActive : $menuLinkIdle }}">
                <div class="{{ $iconBoxBase }} {{ $active === 'potential_donors' ? $iconBoxActive : $iconBoxIdle }}">
                    <i class="fa-solid fa-user-plus text-xs" aria-hidden="true"></i>
                </div>
                <span class="whitespace-nowrap">List Calon Donatur</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('transactions_dashboard'))
            <a href="{{ route('admin.transactions_dashboard') }}" class="{{ $menuLinkBase }} {{ $active === 'transactions_dashboard' ? $menuLinkActive : $menuLinkIdle }}">
                <div class="{{ $iconBoxBase }} {{ $active === 'transactions_dashboard' ? $iconBoxActive : $iconBoxIdle }}">
                    <i class="fa-solid fa-chart-pie text-xs" aria-hidden="true"></i>
                </div>
                <span class="whitespace-nowrap">Dashboard Transaksi</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('donors_dashboard'))
            <a href="{{ route('admin.donors_dashboard') }}" class="{{ $menuLinkBase }} {{ $active === 'donors_dashboard' ? $menuLinkActive : $menuLinkIdle }}">
                <div class="{{ $iconBoxBase }} {{ $active === 'donors_dashboard' ? $iconBoxActive : $iconBoxIdle }}">
                    <i class="fa-solid fa-chart-column text-xs" aria-hidden="true"></i>
                </div>
                <span class="whitespace-nowrap">Dashboard Donatur</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('prospects_dashboard'))
            <a href="{{ route('admin.prospects_dashboard') }}" class="{{ $menuLinkBase }} {{ $active === 'prospects_dashboard' ? $menuLinkActive : $menuLinkIdle }}">
                <div class="{{ $iconBoxBase }} {{ $active === 'prospects_dashboard' ? $iconBoxActive : $iconBoxIdle }}">
                    <i class="fa-solid fa-chart-line text-xs" aria-hidden="true"></i>
                </div>
                <span class="whitespace-nowrap">Dashboard Prospek</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('target_vs_achievement'))
            <a href="{{ route('admin.target_vs_achievement') }}" class="{{ $menuLinkBase }} {{ $active === 'target_vs_achievement' ? $menuLinkActive : $menuLinkIdle }}">
                <div class="{{ $iconBoxBase }} {{ $active === 'target_vs_achievement' ? $iconBoxActive : $iconBoxIdle }}">
                    <i class="fa-solid fa-bullseye text-xs" aria-hidden="true"></i>
                </div>
                <span class="whitespace-nowrap">Dashboard Target Vs Capaian</span>
            </a>
            @endif

            @if(Auth::user()->canAccessMenu('rfm_dashboard'))
            <a href="{{ route('admin.rfm_dashboard') }}" class="{{ $menuLinkBase }} {{ $active === 'rfm_dashboard' ? $menuLinkActive : $menuLinkIdle }}">
                <div class="{{ $iconBoxBase }} {{ $active === 'rfm_dashboard' ? $iconBoxActive : $iconBoxIdle }}">
                    <i class="fa-solid fa-layer-group text-xs" aria-hidden="true"></i>
                </div>
                <span class="whitespace-nowrap">Dashboard RFM</span>
            </a>
            @endif
        </div>
    </div>
    @endif

</nav>
