@php $active = $active ?? ''; @endphp
<!-- Info Donatur Card -->
<div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm relative overflow-hidden group">
    <div class="relative z-10 space-y-4 text-center">
        @if(Auth::user()->hasCustomPhoto())
            <img src="{{ Auth::user()->profile_photo_url }}" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=063f5c&color=fff';" class="w-16 h-16 rounded-2xl object-cover mx-auto shadow-sm border-2 border-slate-100" alt="Foto Profil {{ Auth::user()->name }}" width="64" height="64">
        @else
            <div class="w-16 h-16 bg-primary-50 text-primary-700 rounded-2xl flex items-center justify-center text-xl font-black uppercase mx-auto shadow-sm border-2 border-slate-100">
                {{ substr(Auth::user()->name, 0, 2) }}
            </div>
        @endif
        
        <div class="space-y-1">
            @if(Auth::user()->role === 'admin')
                <p class="text-xs font-black text-slate-600 uppercase tracking-widest leading-none">Administrator</p>
            @elseif(Auth::user()->role === 'field_worker')
                <p class="text-xs font-black text-slate-600 uppercase tracking-widest leading-none">Petugas Lapangan</p>
            @else
                <p class="text-xs font-black text-slate-600 uppercase tracking-widest leading-none">Donatur Kehormatan</p>
            @endif
            <p class="text-base font-black text-slate-900 tracking-tighter truncate">{{ Auth::user()->name }}</p>
            <span class="text-xs font-bold text-slate-600 block tracking-wider truncate">{{ Auth::user()->email }}</span>
        </div>
        <div class="flex gap-2 justify-center pt-2">
            <a href="{{ route('profile.custom') }}" class="px-5 py-2.5 min-h-[44px] inline-flex items-center bg-slate-100 hover:bg-primary-50 text-slate-700 hover:text-primary-700 rounded-xl text-xs font-black uppercase tracking-widest transition-colors shadow-sm">
                <i class="fa-solid fa-user-pen mr-1"></i> Edit Profil
            </a>
        </div>

        <!-- Tombol Cepat: Lihat Website & Keluar Akun -->
        <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
            <a href="{{ route('home') }}" class="flex-1 flex items-center justify-center gap-1.5 py-2.5 px-3 min-h-[44px] rounded-xl text-xs font-black uppercase tracking-wider text-slate-700 bg-slate-50 hover:bg-slate-100 hover:text-primary-700 transition-colors" title="Buka Halaman Depan Web">
                <i class="fa-solid fa-globe"></i> Website
            </a>
            <form method="POST" action="{{ route('logout') }}" class="flex-1">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-1.5 py-2.5 px-3 min-h-[44px] rounded-xl text-xs font-black uppercase tracking-wider text-rose-700 bg-rose-50 hover:bg-rose-100 hover:text-rose-800 transition-colors" title="Keluar dari Akun">
                    <i class="fa-solid fa-right-from-bracket"></i> Keluar
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Menu Navigasi Donatur Card -->
<nav aria-label="Navigasi Donatur" class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden p-3 space-y-1.5">
    <!-- Tab: Ringkasan -->
    <a href="{{ route('dashboard') }}" 
       class="w-full flex items-center gap-4 px-5 py-3 min-h-[44px] rounded-2xl text-xs font-black uppercase tracking-wider transition-colors group border border-transparent text-left {{ ($active ?? '') === 'dashboard' ? 'bg-primary-600 text-white shadow-sm' : 'text-slate-700 hover:bg-slate-50 hover:text-primary-700 hover:border-primary-100' }}">
        <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-colors {{ ($active ?? '') === 'dashboard' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600 group-hover:bg-primary-50 group-hover:text-primary-700' }}">
            <i class="fa-solid fa-gauge text-xs"></i>
        </div>
        <span class="whitespace-nowrap">Ringkasan Aktivitas</span>
    </a>

    <!-- Tab: Kampanye Kebaikan -->
    <a href="{{ route('campaigns.index') }}" 
       class="w-full flex items-center gap-4 px-5 py-3 min-h-[44px] rounded-2xl text-xs font-black uppercase tracking-wider transition-colors group border border-transparent text-left {{ ($active ?? '') === 'campaigns' ? 'bg-primary-600 text-white shadow-sm' : 'text-slate-700 hover:bg-slate-50 hover:text-primary-700 hover:border-primary-100' }}">
        <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-colors {{ ($active ?? '') === 'campaigns' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600 group-hover:bg-primary-50 group-hover:text-primary-700' }}">
            <i class="fa-solid fa-hand-holding-heart text-xs"></i>
        </div>
        <span class="whitespace-nowrap">Kampanye Kebaikan</span>
    </a>

    <!-- Tab: Kabar & Penyaluran -->
    <a href="{{ route('news') }}" 
       class="w-full flex items-center gap-4 px-5 py-3 min-h-[44px] rounded-2xl text-xs font-black uppercase tracking-wider transition-colors group border border-transparent text-left {{ ($active ?? '') === 'news' ? 'bg-primary-600 text-white shadow-sm' : 'text-slate-700 hover:bg-slate-50 hover:text-primary-700 hover:border-primary-100' }}">
        <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-colors {{ ($active ?? '') === 'news' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600 group-hover:bg-primary-50 group-hover:text-primary-700' }}">
            <i class="fa-solid fa-newspaper text-xs"></i>
        </div>
        <span class="whitespace-nowrap">Kabar Penyaluran</span>
    </a>

    <!-- Tab: Bayar Zakat -->
    <a href="{{ route('zakat.index') }}" 
       class="w-full flex items-center gap-4 px-5 py-3 min-h-[44px] rounded-2xl text-xs font-black uppercase tracking-wider transition-colors group border border-transparent text-left {{ $active === 'zakat' ? 'bg-primary-600 text-white shadow-sm' : 'text-slate-700 hover:bg-slate-50 hover:text-primary-700 hover:border-primary-100' }}">
        <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-colors {{ $active === 'zakat' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600 group-hover:bg-primary-50 group-hover:text-primary-700' }}">
            <i class="fa-solid fa-calculator text-xs"></i>
        </div>
        <span class="whitespace-nowrap">Kalkulator &amp; Bayar Zakat</span>
    </a>

    <!-- Tab: Papan Doa -->
    <a href="{{ route('prayers.index') }}" 
       class="w-full flex items-center gap-4 px-5 py-3 min-h-[44px] rounded-2xl text-xs font-black uppercase tracking-wider transition-colors group border border-transparent text-left {{ $active === 'prayers' ? 'bg-primary-600 text-white shadow-sm' : 'text-slate-700 hover:bg-slate-50 hover:text-primary-700 hover:border-primary-100' }}">
        <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-colors {{ $active === 'prayers' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600 group-hover:bg-primary-50 group-hover:text-primary-700' }}">
            <i class="fa-solid fa-hands-praying text-xs"></i>
        </div>
        <span class="whitespace-nowrap">Papan Doa Bersama</span>
    </a>

    <!-- Tab: Edit Profil -->
    <a href="{{ route('profile.custom') }}" 
       class="w-full flex items-center gap-4 px-5 py-3 min-h-[44px] rounded-2xl text-xs font-black uppercase tracking-wider transition-colors group border border-transparent text-left {{ $active === 'profile' ? 'bg-primary-600 text-white shadow-sm' : 'text-slate-700 hover:bg-slate-50 hover:text-primary-700 hover:border-primary-100' }}">
        <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-colors {{ $active === 'profile' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600 group-hover:bg-primary-50 group-hover:text-primary-700' }}">
            <i class="fa-solid fa-user-pen text-xs"></i>
        </div>
        <span class="whitespace-nowrap">Pengaturan Akun</span>
    </a>
</nav>
