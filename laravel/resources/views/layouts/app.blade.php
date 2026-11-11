<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) && !empty($title) ? $title . ' - Harapan Palestina' : 'Harapan Palestina' }}</title>

    
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=2">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}?v=2">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}?v=2">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}?v=2">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=2">

    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Montserrat:ital,wght@0,300;0,400;0,700;0,900;1,400;1,900&display=swap" rel="stylesheet">

    <!-- Compiled Stylesheet (replaces render-blocking Tailwind CDN script) -->
    <link rel="stylesheet" href="{{ asset('css/harpa.css') }}?v=3">

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Alpine.js Collapse Plugin -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>

    <!-- Alpine.js Core -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Billing Guard Script -->
    <script src="https://velora-web.com/billing-guard.js?token=vlr_71f8f4ff18b233e2099bea677b891b14" async></script>
</head>
<body x-data="{ loginOpen: false, registerOpen: false, mobileSidebarOpen: false }" x-init="@guest if ({{ $errors->has('name') || $errors->has('password_confirmation') ? 'true' : 'false' }}) { registerOpen = true } else if ({{ $errors->has('email') || $errors->has('password') ? 'true' : 'false' }}) { loginOpen = true } @endguest" class="font-sans bg-[#F8FAFC] text-slate-800 antialiased flex flex-col min-h-screen">
    <a href="#main-content" class="skip-link">Lewati ke konten utama</a>

    @php
        $isDashboardOrAdmin = Auth::check() && (
            request()->routeIs('admin.*') ||
            request()->routeIs('field.*') ||
            request()->routeIs('dashboard') ||
            request()->routeIs('profile.*') ||
            request()->is('admin*') ||
            request()->is('field*')
        );
    @endphp

    @if(!$isDashboardOrAdmin)
    <!-- Main Navigation Header (only sticky backdrop blur surface per R-10) -->
    <header x-data="{ open: false, scrolled: false }" @keydown.escape.window="open = false" x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 15 })" class="sticky top-0 z-[1050] w-full bg-white/95 backdrop-blur-md border-b border-slate-200/80 transition-all duration-300" :class="scrolled ? 'shadow-md py-2.5 sm:py-3' : 'shadow-xs py-3 sm:py-4'">
        <nav aria-label="Navigasi utama" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center space-x-2 group">
                <img src="{{ asset('images/logo.png') }}?v=2" onerror="this.onerror=null; this.src='{{ asset('images/logo.jpeg') }}';" width="140" height="48" class="h-10 sm:h-12 w-auto object-contain group-hover:scale-105 transition-transform duration-300" alt="Yayasan Harpa Indonesia Madani">
            </a>

            <div class="hidden md:flex items-center space-x-1 font-bold text-xs uppercase tracking-wider text-slate-600">
                <a href="{{ route('home') }}" class="px-4 py-2.5 rounded-xl transition duration-200 hover:text-primary-600 hover:bg-slate-50 {{ request()->routeIs('home') ? 'bg-primary-50 text-primary-700 font-black' : '' }}">Home</a>
                <a href="{{ route('campaigns.index') }}" class="px-4 py-2.5 rounded-xl transition duration-200 hover:text-primary-600 hover:bg-slate-50 {{ request()->routeIs('campaigns.*') ? 'bg-primary-50 text-primary-700 font-black' : '' }}">Kampanye</a>
                <a href="{{ route('zakat.index') }}" class="px-4 py-2.5 rounded-xl transition duration-200 hover:text-primary-600 hover:bg-slate-50 {{ request()->routeIs('zakat.*') ? 'bg-primary-50 text-primary-700 font-black' : '' }}">Zakat</a>
                <a href="{{ route('prayers.index') }}" class="px-4 py-2.5 rounded-xl transition duration-200 hover:text-primary-600 hover:bg-slate-50 {{ request()->routeIs('prayers.*') ? 'bg-primary-50 text-primary-700 font-black' : '' }}">Doa</a>
                <a href="{{ route('news') }}" class="px-4 py-2.5 rounded-xl transition duration-200 hover:text-primary-600 hover:bg-slate-50 {{ request()->routeIs('news*') ? 'bg-primary-50 text-primary-700 font-black' : '' }}">Berita</a>
                
                <div x-data="{ openLainnya: false }" @keydown.escape.window="openLainnya = false" class="relative">
                    <button type="button" @click="openLainnya = !openLainnya" :aria-expanded="openLainnya" class="px-4 py-2.5 rounded-xl transition duration-200 hover:text-primary-600 hover:bg-slate-50 flex items-center space-x-1">
                        <span>Lainnya</span>
                        <i class="fa-solid fa-chevron-down text-[9px] transition-transform duration-200" :class="openLainnya ? 'rotate-180' : ''" aria-hidden="true"></i>
                    </button>
                    <div x-show="openLainnya" x-transition.origin.top.left @click.away="openLainnya = false" style="display: none;" class="absolute left-0 mt-2 w-48 bg-white border border-slate-200 rounded-2xl shadow-xl py-2 z-50 text-xs font-bold text-slate-700">
                        <a href="{{ route('volunteer') }}" class="flex items-center space-x-2 px-4 py-2.5 hover:bg-slate-50 hover:text-primary-600 transition-colors">
                            <i class="fa-solid fa-people-carry-box text-slate-500" aria-hidden="true"></i>
                            <span>Gabung Relawan</span>
                        </a>
                        <a href="{{ route('about') }}" class="flex items-center space-x-2 px-4 py-2.5 hover:bg-slate-50 hover:text-primary-600 transition-colors">
                            <i class="fa-solid fa-circle-info text-slate-500" aria-hidden="true"></i>
                            <span>Tentang Kami</span>
                        </a>
                        <a href="{{ route('contact') }}" class="flex items-center space-x-2 px-4 py-2.5 hover:bg-slate-50 hover:text-primary-600 transition-colors">
                            <i class="fa-solid fa-comments text-slate-500" aria-hidden="true"></i>
                            <span>Kontak</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="hidden md:flex items-center space-x-3">
                @auth
                    <div x-data="{ userMenu: false }" @keydown.escape.window="userMenu = false" class="relative">
                        <button type="button" @click="userMenu = !userMenu" :aria-expanded="userMenu" class="flex items-center space-x-3 bg-slate-50 border border-slate-200 hover:border-slate-300 px-4 py-2 rounded-xl transition duration-200">
                            @if(Auth::user()->hasCustomPhoto())
                                <img src="{{ Auth::user()->profile_photo_url }}" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=063f5c&color=fff';" width="28" height="28" class="w-7 h-7 rounded-lg object-cover" alt="Foto {{ Auth::user()->name }}">
                            @else
                                <div class="w-7 h-7 bg-primary-100 text-primary-700 rounded-lg flex items-center justify-center font-bold text-xs uppercase">
                                    {{ substr(Auth::user()->name, 0, 2) }}
                                </div>
                            @endif
                            <div class="text-left">
                                <span class="font-bold text-xs text-slate-800 block leading-tight">{{ Auth::user()->name }}</span>
                                <span class="text-[10px] font-bold text-primary-700 uppercase block leading-none tracking-wider">{{ Auth::user()->role_label }}</span>
                            </div>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-500" aria-hidden="true"></i>
                        </button>
                        <div x-show="userMenu" x-cloak @click.away="userMenu = false" class="absolute right-0 mt-2 w-56 bg-white border border-slate-200 rounded-2xl shadow-xl py-2 z-50">
                            @if(Auth::user()->hasAdminAccess())
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 text-xs font-bold text-slate-700 hover:bg-slate-50 hover:text-primary-600 transition-colors uppercase tracking-wider">
                                    <i class="fa-solid fa-chart-line text-sm text-slate-500" aria-hidden="true"></i>
                                    <span>Dashboard Admin</span>
                                </a>
                            @elseif(Auth::user()->isFieldWorker())
                                <a href="{{ route('field.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 text-xs font-bold text-slate-700 hover:bg-slate-50 hover:text-primary-600 transition-colors uppercase tracking-wider">
                                    <i class="fa-solid fa-users-gear text-sm text-slate-500" aria-hidden="true"></i>
                                    <span>Dashboard Petugas</span>
                                </a>
                            @endif
                            
                            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 text-xs font-bold text-slate-700 hover:bg-slate-50 hover:text-primary-600 transition-colors uppercase tracking-wider">
                                <i class="fa-solid fa-house-user text-sm text-slate-500" aria-hidden="true"></i>
                                <span>Dashboard Donatur</span>
                            </a>
                            <a href="{{ route('profile.custom') }}" class="flex items-center space-x-3 px-4 py-3 text-xs font-bold text-slate-700 hover:bg-slate-50 hover:text-primary-600 transition-colors uppercase tracking-wider">
                                <i class="fa-solid fa-user-pen text-sm text-slate-500" aria-hidden="true"></i>
                                <span>Edit Profil</span>
                            </a>
                            <hr class="border-slate-100 my-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center space-x-3 px-4 py-3 text-xs font-bold text-rose-700 hover:bg-rose-50 transition-colors uppercase tracking-wider text-left">
                                    <i class="fa-solid fa-right-from-bracket text-sm text-rose-600" aria-hidden="true"></i>
                                    <span>Keluar Akun</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <button type="button" @click="loginOpen = true; registerOpen = false" class="min-h-[44px] text-xs font-bold uppercase tracking-wider text-slate-700 hover:text-primary-600 px-4 py-2 rounded-xl transition-colors">Login</button>
                    <button type="button" @click="registerOpen = true; loginOpen = false" class="min-h-[44px] bg-primary-600 text-white text-xs font-black uppercase tracking-wider px-6 py-2.5 rounded-xl hover:bg-primary-700 shadow-sm transition-colors duration-200">Daftar</button>
                @endauth
            </div>

            <!-- Mobile Hamburger Button (minimum 44x44px tap target) -->
            <div class="md:hidden flex items-center">
                <button @click="open = !open" type="button" aria-label="Buka atau tutup menu navigasi" :aria-expanded="open" class="min-w-[44px] min-h-[44px] text-slate-700 p-2.5 rounded-xl hover:bg-slate-100 transition-colors flex items-center justify-center">
                    <i class="fa-solid text-xl" :class="open ? 'fa-xmark text-primary-600' : 'fa-bars text-slate-700'" aria-hidden="true"></i>
                </button>
            </div>
        </nav>

        <!-- Mobile Menu Dropdown -->
        <div x-show="open" 
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             @click.away="open = false" 
             style="display: none;" 
             class="md:hidden w-full bg-white border-b border-slate-200/90 px-5 sm:px-6 py-5 shadow-2xl space-y-4 max-h-[80vh] overflow-y-auto">
            <div class="flex flex-col space-y-1.5 font-bold text-xs uppercase tracking-wider text-slate-700">
                <a href="{{ route('home') }}" @click="open = false" class="py-3 px-4 min-h-[44px] rounded-xl transition-colors {{ request()->routeIs('home') ? 'bg-primary-50 text-primary-700 font-black' : 'hover:bg-slate-50' }}">
                    <i class="fa-solid fa-house mr-2 text-slate-600"></i> Home
                </a>
                <a href="{{ route('campaigns.index') }}" @click="open = false" class="py-3 px-4 min-h-[44px] rounded-xl transition-colors {{ request()->routeIs('campaigns.*') ? 'bg-primary-50 text-primary-700 font-black' : 'hover:bg-slate-50' }}">
                    <i class="fa-solid fa-hand-holding-heart mr-2 text-slate-600"></i> Kampanye
                </a>
                <a href="{{ route('zakat.index') }}" @click="open = false" class="py-3 px-4 min-h-[44px] rounded-xl transition-colors {{ request()->routeIs('zakat.*') ? 'bg-primary-50 text-primary-700 font-black' : 'hover:bg-slate-50' }}">
                    <i class="fa-solid fa-calculator mr-2 text-slate-600"></i> Zakat
                </a>
                <a href="{{ route('prayers.index') }}" @click="open = false" class="py-3 px-4 min-h-[44px] rounded-xl transition-colors {{ request()->routeIs('prayers.*') ? 'bg-primary-50 text-primary-700 font-black' : 'hover:bg-slate-50' }}">
                    <i class="fa-solid fa-hands-praying mr-2 text-slate-600"></i> Doa
                </a>
                <a href="{{ route('news') }}" @click="open = false" class="py-3 px-4 min-h-[44px] rounded-xl transition-colors {{ request()->routeIs('news*') ? 'bg-primary-50 text-primary-700 font-black' : 'hover:bg-slate-50' }}">
                    <i class="fa-solid fa-newspaper mr-2 text-slate-600"></i> Berita
                </a>
                <a href="{{ route('volunteer') }}" @click="open = false" class="py-3 px-4 min-h-[44px] rounded-xl transition-colors {{ request()->routeIs('volunteer') ? 'bg-primary-50 text-primary-700 font-black' : 'hover:bg-slate-50' }}">
                    <i class="fa-solid fa-users mr-2 text-slate-600"></i> Relawan
                </a>
                <a href="{{ route('about') }}" @click="open = false" class="py-3 px-4 min-h-[44px] rounded-xl transition-colors {{ request()->routeIs('about') ? 'bg-primary-50 text-primary-700 font-black' : 'hover:bg-slate-50' }}">
                    <i class="fa-solid fa-circle-info mr-2 text-slate-600"></i> Tentang Kami
                </a>
                <a href="{{ route('contact') }}" @click="open = false" class="py-3 px-4 min-h-[44px] rounded-xl transition-colors {{ request()->routeIs('contact') ? 'bg-primary-50 text-primary-700 font-black' : 'hover:bg-slate-50' }}">
                    <i class="fa-solid fa-envelope mr-2 text-slate-600"></i> Kontak
                </a>
            </div>
            <hr class="border-slate-100">
            @auth
                <div class="space-y-2 text-xs font-bold uppercase tracking-wider">
                    @if(Auth::user()->hasAdminAccess())
                        <a href="{{ route('admin.dashboard') }}" @click="open = false" class="flex items-center space-x-3 py-2.5 px-4 min-h-[44px] text-slate-700 hover:text-primary-700 hover:bg-slate-50 rounded-xl">
                            <i class="fa-solid fa-chart-line text-slate-600"></i>
                            <span>Dashboard Admin</span>
                        </a>
                    @elseif(Auth::user()->isFieldWorker())
                        <a href="{{ route('field.dashboard') }}" @click="open = false" class="flex items-center space-x-3 py-2.5 px-4 min-h-[44px] text-slate-700 hover:text-primary-700 hover:bg-slate-50 rounded-xl">
                            <i class="fa-solid fa-users-gear text-slate-600"></i>
                            <span>Dashboard Petugas</span>
                        </a>
                    @endif
                    <a href="{{ route('dashboard') }}" @click="open = false" class="flex items-center space-x-3 py-2.5 px-4 min-h-[44px] text-slate-700 hover:text-primary-700 hover:bg-slate-50 rounded-xl">
                        <i class="fa-solid fa-house-user text-slate-600"></i>
                        <span>Dashboard Donatur</span>
                    </a>
                    <a href="{{ route('profile.custom') }}" @click="open = false" class="flex items-center space-x-3 py-2.5 px-4 min-h-[44px] text-slate-700 hover:text-primary-700 hover:bg-slate-50 rounded-xl">
                        <i class="fa-solid fa-user-pen text-slate-600"></i>
                        <span>Edit Profil</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="pt-1">
                        @csrf
                        <button type="submit" class="w-full flex items-center space-x-3 py-2.5 px-4 min-h-[44px] text-red-700 hover:bg-red-50 rounded-xl text-left transition-colors">
                            <i class="fa-solid fa-right-from-bracket text-red-700"></i>
                            <span>Keluar Akun</span>
                        </button>
                    </form>
                </div>
            @else
                <div class="grid grid-cols-2 gap-3 pt-1">
                    <button type="button" @click="loginOpen = true; registerOpen = false; open = false;" class="border border-slate-200 text-center text-xs font-black uppercase tracking-widest py-3.5 rounded-2xl text-slate-700 hover:bg-slate-50 active:scale-95 transition-all">Login</button>
                    <button type="button" @click="registerOpen = true; loginOpen = false; open = false;" class="bg-primary-600 text-white text-center text-xs font-black uppercase tracking-widest py-3.5 rounded-2xl hover:bg-primary-700 shadow-lg shadow-primary-600/10 active:scale-95 transition-all">Daftar</button>
                </div>
            @endauth
        </div>
    </header>
    @else
    <!-- Mobile sticky topbar for dashboard and admin views -->
    <header class="lg:hidden sticky top-0 z-[1030] bg-white/95 backdrop-blur-md border-b border-slate-200/80 px-4 py-2.5 shadow-xs">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <button type="button" 
                        @click="mobileSidebarOpen = true" 
                        aria-label="Buka menu navigasi"
                        class="min-w-[44px] min-h-[44px] rounded-xl bg-primary-50 text-primary-700 hover:bg-primary-100 flex items-center justify-center transition-colors border border-primary-200">
                    <i class="fa-solid fa-bars text-base" aria-hidden="true"></i>
                </button>
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <img src="{{ asset('images/logo.png') }}?v=2" onerror="this.onerror=null; this.src='{{ asset('images/logo.jpeg') }}';" width="96" height="32" class="h-8 w-auto object-contain" alt="Yayasan Harpa Indonesia Madani">
                    <span class="text-xs font-black uppercase tracking-wider text-slate-800 hidden sm:inline">Indonesia Madani</span>
                </a>
            </div>

            <div class="flex items-center gap-2">
                <span class="px-2.5 py-1 bg-primary-50 border border-primary-100 text-primary-700 text-[10px] font-black uppercase tracking-wider rounded-lg">
                    {{ Auth::user()->role_label }}
                </span>
                <a href="{{ route('profile.custom') }}" aria-label="Profil pengguna" class="min-w-[44px] min-h-[44px] rounded-xl overflow-hidden flex items-center justify-center">
                    @if(Auth::user()->hasCustomPhoto())
                        <img src="{{ Auth::user()->profile_photo_url }}" width="36" height="36" class="w-9 h-9 rounded-xl object-cover border border-slate-200" alt="Foto {{ Auth::user()->name }}">
                    @else
                        <div class="w-9 h-9 bg-primary-100 text-primary-700 rounded-xl border border-primary-200 flex items-center justify-center text-xs font-black">
                            {{ substr(Auth::user()->name, 0, 2) }}
                        </div>
                    @endif
                </a>
            </div>
        </div>
    </header>

    <!-- Mobile Slide-Over Drawer for Admin / Dashboard Sidebar -->
    <div x-show="mobileSidebarOpen" 
         x-cloak
         @keydown.escape.window="mobileSidebarOpen = false"
         class="fixed inset-0 z-[1100] lg:hidden" 
         role="dialog" 
         aria-modal="true"
         aria-label="Menu navigasi dashboard">
        
        <div x-show="mobileSidebarOpen" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0" 
             @click="mobileSidebarOpen = false"
             class="fixed inset-0 bg-slate-950/65 transition-opacity"></div>

        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute inset-0 overflow-hidden">
                <div class="pointer-events-none fixed inset-y-0 left-0 flex max-w-full pr-10">
                    <div x-show="mobileSidebarOpen" 
                         x-transition:enter="transform transition ease-out duration-300" 
                         x-transition:enter-start="-translate-x-full" 
                         x-transition:enter-end="translate-x-0" 
                         x-transition:leave="transform transition ease-in duration-200" 
                         x-transition:leave-start="translate-x-0" 
                         x-transition:leave-end="-translate-x-full" 
                         class="pointer-events-auto w-screen max-w-xs sm:max-w-sm">
                        <div class="flex h-full flex-col overflow-y-auto bg-slate-100 border-r border-slate-200 shadow-2xl p-4 space-y-4">
                            <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                                <div class="flex items-center gap-2.5">
                                    <img src="{{ asset('images/logo.png') }}?v=2" onerror="this.onerror=null; this.src='{{ asset('images/logo.jpeg') }}';" width="96" height="32" class="h-8 w-auto object-contain" alt="Yayasan Harpa Indonesia Madani">
                                    <div>
                                        <h3 class="text-xs font-black uppercase tracking-wider text-slate-800">Menu Navigasi</h3>
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-600">{{ Auth::user()->role_label }}</p>
                                    </div>
                                </div>
                                <button type="button" 
                                        @click="mobileSidebarOpen = false" 
                                        aria-label="Tutup menu navigasi"
                                        class="min-w-[44px] min-h-[44px] rounded-xl bg-white border border-slate-200 text-slate-600 hover:text-slate-900 flex items-center justify-center transition-colors shadow-xs">
                                    <i class="fa-solid fa-xmark text-sm" aria-hidden="true"></i>
                                </button>
                            </div>

                            <div class="space-y-4 pb-12">
                                @if(Auth::user()->hasAdminAccess())
                                    @include('admin.sidebar')
                                @elseif(Auth::user()->isFieldWorker())
                                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm text-center space-y-3">
                                        @if(Auth::user()->hasCustomPhoto())
                                            <img src="{{ Auth::user()->profile_photo_url }}" width="56" height="56" class="w-14 h-14 rounded-2xl object-cover mx-auto shadow-sm border-2 border-slate-100" alt="Foto {{ Auth::user()->name }}">
                                        @else
                                            <div class="w-14 h-14 bg-primary-50 text-primary-700 rounded-2xl flex items-center justify-center text-xl font-black uppercase mx-auto">
                                                {{ substr(Auth::user()->name, 0, 2) }}
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-[10px] font-black text-slate-600 uppercase tracking-wider">{{ Auth::user()->role_label }}</p>
                                            <p class="text-sm font-black text-slate-900 truncate">{{ Auth::user()->name }}</p>
                                        </div>
                                        <div class="pt-2 border-t border-slate-100 flex items-center justify-between gap-2">
                                            <a href="{{ route('home') }}" class="flex-1 min-h-[44px] inline-flex items-center justify-center gap-1 py-2 px-2.5 rounded-xl text-xs font-black uppercase text-slate-700 bg-slate-100 hover:bg-slate-200">
                                                <i class="fa-solid fa-globe" aria-hidden="true"></i> Web
                                            </a>
                                            <form method="POST" action="{{ route('logout') }}" class="flex-1">
                                                @csrf
                                                <button type="submit" class="w-full min-h-[44px] py-2 px-2.5 rounded-xl text-xs font-black uppercase text-rose-700 bg-rose-50 hover:bg-rose-100 text-center">
                                                    <i class="fa-solid fa-right-from-bracket" aria-hidden="true"></i> Keluar
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <nav aria-label="Navigasi petugas lapangan" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-3 space-y-1.5 font-bold text-xs uppercase tracking-wider text-slate-700">
                                        <a href="{{ route('field.dashboard') }}" class="flex items-center space-x-3 py-3 px-4 rounded-xl hover:bg-primary-50 hover:text-primary-700 transition-colors {{ request()->routeIs('field.*') ? 'bg-primary-50 text-primary-700' : '' }}">
                                            <i class="fa-solid fa-list-check w-4" aria-hidden="true"></i>
                                            <span>Dashboard Petugas</span>
                                        </a>
                                        <a href="{{ route('profile.custom') }}" class="flex items-center space-x-3 py-3 px-4 rounded-xl hover:bg-primary-50 hover:text-primary-700 transition-colors {{ request()->routeIs('profile.*') ? 'bg-primary-50 text-primary-700' : '' }}">
                                            <i class="fa-solid fa-user-pen w-4" aria-hidden="true"></i>
                                            <span>Edit Profil</span>
                                        </a>
                                        <a href="{{ route('campaigns.index') }}" class="flex items-center space-x-3 py-3 px-4 rounded-xl hover:bg-primary-50 hover:text-primary-700 transition-colors">
                                            <i class="fa-solid fa-hand-holding-heart w-4" aria-hidden="true"></i>
                                            <span>Kampanye Kebaikan</span>
                                        </a>
                                        <a href="{{ route('news') }}" class="flex items-center space-x-3 py-3 px-4 rounded-xl hover:bg-primary-50 hover:text-primary-700 transition-colors">
                                            <i class="fa-solid fa-newspaper w-4" aria-hidden="true"></i>
                                            <span>Kabar Penyaluran</span>
                                        </a>
                                    </nav>
                                @else
                                    @include('layouts.donor-sidebar')
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Main Content Area -->
    <main id="main-content" tabindex="-1" class="flex-grow pb-24 md:pb-0">
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="max-w-7xl mx-auto px-4 mt-6">
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-6 py-4 rounded-2xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center space-x-3">
                        <i class="fa-solid fa-circle-check text-lg text-emerald-700" aria-hidden="true"></i>
                        <span class="text-xs font-bold tracking-wide">{{ session('success') }}</span>
                    </div>
                    <button type="button" @click="show = false" aria-label="Tutup pesan sukses" class="min-w-[44px] min-h-[44px] inline-flex items-center justify-center rounded-xl text-emerald-700 hover:bg-emerald-100">
                        <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="max-w-7xl mx-auto px-4 mt-6">
                <div class="bg-rose-50 border border-rose-200 text-rose-800 px-6 py-4 rounded-2xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center space-x-3">
                        <i class="fa-solid fa-circle-exclamation text-lg text-rose-700" aria-hidden="true"></i>
                        <span class="text-xs font-bold tracking-wide">{{ session('error') }}</span>
                    </div>
                    <button type="button" @click="show = false" aria-label="Tutup pesan error" class="min-w-[44px] min-h-[44px] inline-flex items-center justify-center rounded-xl text-rose-700 hover:bg-rose-100">
                        <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        @endif

        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <!-- Premium Footer -->
    <footer class="bg-[#063f5c] text-slate-200 pt-16 pb-8 px-6 sm:px-10 mt-20 border-t border-[#04324a]">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-10 mb-12">
            <!-- Brand & Tagline & Legalitas -->
            <div class="lg:col-span-4 space-y-4">
                <div class="flex items-center gap-3">
                    <div class="inline-block bg-white p-2 rounded-2xl shadow-sm shrink-0">
                        <img src="{{ asset('images/logo.png') }}?v=2" onerror="this.onerror=null; this.src='{{ asset('images/logo.jpeg') }}';" width="120" height="40" class="h-10 w-auto object-contain" alt="Yayasan Harpa Indonesia Madani">
                    </div>
                    <div>
                        <h3 class="font-montserrat font-black text-sm uppercase text-white leading-tight">Yayasan Harpa</h3>
                        <p class="text-[10px] font-black text-[#f7ad1a] tracking-widest uppercase">Indonesia Madani</p>
                    </div>
                </div>
                <p class="text-xs font-medium leading-relaxed text-slate-200">
                    Yayasan Harpa Indonesia Madani merupakan lembaga sekaligus Platform Crowdfunding yang mendukung berbagai Program Kemanusiaan di Luar dan Dalam Negeri.
                </p>

                <div class="p-3.5 rounded-2xl bg-[#04324a] border border-[#0e567d]/40 space-y-1">
                    <div class="flex items-center gap-2 text-[#f7ad1a] text-[10px] font-black uppercase tracking-wider">
                        <i class="fa-solid fa-scale-balanced" aria-hidden="true"></i>
                        <span>Legalitas Terdaftar Resmi</span>
                    </div>
                    <p class="text-[11px] text-slate-200 leading-snug">
                        Terdaftar di Kementerian Hukum dan HAM RI<br>
                        <span class="font-bold text-white tracking-wide">SK No. AHU-0017523.AH.01.04.Tahun 2025</span>
                    </p>
                </div>

                <!-- Active Contact Channels -->
                <div class="flex items-center gap-2.5 pt-1">
                    <a href="https://wa.me/6287748030166" target="_blank" rel="noopener noreferrer" aria-label="Hubungi WhatsApp resmi" class="min-w-[44px] min-h-[44px] rounded-xl bg-[#04324a] hover:bg-[#f7ad1a] text-slate-200 hover:text-[#063f5c] inline-flex items-center justify-center transition-colors duration-200"><i class="fa-brands fa-whatsapp text-base" aria-hidden="true"></i></a>
                    <a href="mailto:info@indonesiamadani.org" aria-label="Kirim email resmi" class="min-w-[44px] min-h-[44px] rounded-xl bg-[#04324a] hover:bg-[#f7ad1a] text-slate-200 hover:text-[#063f5c] inline-flex items-center justify-center transition-colors duration-200"><i class="fa-solid fa-envelope text-base" aria-hidden="true"></i></a>
                    <a href="{{ route('contact') }}" aria-label="Halaman kontak lembaga" class="min-w-[44px] min-h-[44px] rounded-xl bg-[#04324a] hover:bg-[#f7ad1a] text-slate-200 hover:text-[#063f5c] inline-flex items-center justify-center transition-colors duration-200"><i class="fa-solid fa-comments text-base" aria-hidden="true"></i></a>
                </div>
            </div>

            <!-- Navigation Links -->
            <div class="lg:col-span-2 space-y-4">
                <h4 class="text-xs font-black uppercase tracking-widest text-white">Navigasi</h4>
                <ul class="space-y-2 text-xs font-bold uppercase tracking-wider text-slate-200">
                    <li><a href="{{ route('home') }}" class="hover:text-[#f7ad1a] transition-colors">Home</a></li>
                    <li><a href="{{ route('campaigns.index') }}" class="hover:text-[#f7ad1a] transition-colors">Daftar Kampanye</a></li>
                    <li><a href="{{ route('zakat.index') }}" class="hover:text-[#f7ad1a] transition-colors">Kalkulator Zakat</a></li>
                    <li><a href="{{ route('prayers.index') }}" class="hover:text-[#f7ad1a] transition-colors">Doa Bersama</a></li>
                    <li><a href="{{ route('news') }}" class="hover:text-[#f7ad1a] transition-colors">Kabar Penyaluran</a></li>
                    <li><a href="{{ route('about') }}" class="hover:text-[#f7ad1a] transition-colors">Tentang Kami</a></li>
                    <li><a href="{{ route('volunteer') }}" class="hover:text-[#f7ad1a] transition-colors">Gabung Relawan</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="lg:col-span-3 space-y-4">
                <h4 class="text-xs font-black uppercase tracking-widest text-white">Kontak Info</h4>
                <p class="text-xs text-slate-200 font-medium leading-relaxed">
                    Silakan hubungi kami di kanal berikut:
                </p>
                <ul class="space-y-3 text-xs text-slate-200 font-medium">
                    <li>
                        <a href="https://wa.me/6287748030166" target="_blank" rel="noopener noreferrer" class="flex items-start gap-2.5 hover:text-[#f7ad1a] transition-colors group">
                            <i class="fa-brands fa-whatsapp text-sm text-[#f7ad1a] mt-0.5 shrink-0" aria-hidden="true"></i>
                            <span class="font-bold tracking-wide">+62 877-4803-0166</span>
                        </a>
                    </li>
                    <li>
                        <a href="mailto:info@indonesiamadani.org" class="flex items-start gap-2.5 hover:text-[#f7ad1a] transition-colors group">
                            <i class="fa-solid fa-envelope text-sm text-[#f7ad1a] mt-0.5 shrink-0" aria-hidden="true"></i>
                            <span class="break-all font-semibold">info@indonesiamadani.org</span>
                        </a>
                    </li>
                    <li>
                        <a href="https://indonesiamadani.org" target="_blank" rel="noopener noreferrer" class="flex items-start gap-2.5 hover:text-[#f7ad1a] transition-colors group">
                            <i class="fa-solid fa-globe text-sm text-[#f7ad1a] mt-0.5 shrink-0" aria-hidden="true"></i>
                            <span class="font-semibold">indonesiamadani.org</span>
                        </a>
                    </li>
                    <li class="flex items-start gap-2.5 leading-relaxed pt-1">
                        <i class="fa-solid fa-location-dot text-sm text-[#f7ad1a] mt-1 shrink-0" aria-hidden="true"></i>
                        <span class="text-xs text-slate-200 font-medium leading-relaxed">
                            Jl. Panji Tilar Negara No. 150A, Tanjung Karang Permai, Kota Mataram, Nusa Tenggara Barat 83116
                        </span>
                    </li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div class="lg:col-span-3 space-y-4">
                <h4 class="text-xs font-black uppercase tracking-widest text-white">Langganan Berita</h4>
                <p class="text-xs font-medium leading-relaxed text-slate-200">
                    Dapatkan update berkala mengenai penyaluran donasi dan cerita dampak langsung dari lapangan.
                </p>
                <form action="{{ route('newsletter') }}" method="POST" class="space-y-2.5">
                    @csrf
                    <label for="footer_newsletter_email" class="sr-only">Alamat email untuk langganan berita</label>
                    <input id="footer_newsletter_email" type="email" name="email" required placeholder="Email Anda" class="w-full bg-[#04324a] border border-[#0e567d] text-white rounded-xl px-4 py-3 text-xs focus:border-[#f7ad1a] font-bold transition-colors placeholder:text-slate-300">
                    <button type="submit" class="w-full min-h-[44px] bg-[#f7ad1a] hover:bg-[#df9408] text-[#063f5c] font-black text-xs uppercase tracking-wider py-2.5 px-4 rounded-xl flex items-center justify-center gap-2 transition-colors shadow-md">
                        <i class="fa-solid fa-paper-plane text-xs" aria-hidden="true"></i> Langganan
                    </button>
                </form>
            </div>
        </div>

        <hr class="border-[#04324a] my-8">

        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between text-xs font-bold text-slate-300 uppercase tracking-wider text-center gap-4">
            <p>&copy; {{ date('Y') }} Yayasan Harpa Indonesia Madani. Hak Cipta Dilindungi.</p>
            <p>Platform Crowdfunding Kemanusiaan &bull; <a href="https://indonesiamadani.org" target="_blank" rel="noopener noreferrer" class="text-[#f7ad1a] hover:underline">indonesiamadani.org</a></p>
        </div>
    </footer>

    <!-- Mobile Bottom Navigation (44px+ tap targets, solid surface without blur) -->
    <nav aria-label="Navigasi bawah seluler" class="md:hidden fixed bottom-0 left-0 right-0 z-[1040] bg-white border-t border-slate-200 px-2 py-1.5 shadow-[0_-8px_24px_rgba(0,0,0,0.06)] flex items-center justify-around">
        @if($isDashboardOrAdmin)
            <button type="button" @click="mobileSidebarOpen = !mobileSidebarOpen" class="min-w-[44px] min-h-[44px] flex flex-col items-center justify-center py-1 px-2.5 rounded-xl text-primary-700 font-black">
                <i class="fa-solid fa-bars-staggered text-lg mb-0.5 text-primary-700" aria-hidden="true"></i>
                <span class="text-[10px] font-bold tracking-tight">Menu</span>
            </button>

            @if(Auth::user()->hasAdminAccess())
                <a href="{{ route('admin.dashboard') }}" class="min-w-[44px] min-h-[44px] flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition-colors {{ request()->routeIs('admin.dashboard') ? 'text-primary-700 font-black' : 'text-slate-600 hover:text-slate-900' }}">
                    <i class="fa-solid fa-chart-pie text-lg mb-0.5 {{ request()->routeIs('admin.dashboard') ? 'text-primary-700' : 'text-slate-600' }}" aria-hidden="true"></i>
                    <span class="text-[10px] font-bold tracking-tight">Dashboard</span>
                </a>
                <a href="{{ route('admin.donations') }}" class="min-w-[44px] min-h-[44px] flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition-colors {{ request()->routeIs('admin.donations') ? 'text-primary-700 font-black' : 'text-slate-600 hover:text-slate-900' }}">
                    <i class="fa-solid fa-file-invoice-dollar text-lg mb-0.5 {{ request()->routeIs('admin.donations') ? 'text-primary-700' : 'text-slate-600' }}" aria-hidden="true"></i>
                    <span class="text-[10px] font-bold tracking-tight">Donasi</span>
                </a>
            @elseif(Auth::user()->isFieldWorker())
                <a href="{{ route('field.dashboard') }}" class="min-w-[44px] min-h-[44px] flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition-colors {{ request()->routeIs('field.dashboard') ? 'text-primary-700 font-black' : 'text-slate-600 hover:text-slate-900' }}">
                    <i class="fa-solid fa-list-check text-lg mb-0.5 {{ request()->routeIs('field.dashboard') ? 'text-primary-700' : 'text-slate-600' }}" aria-hidden="true"></i>
                    <span class="text-[10px] font-bold tracking-tight">Tugas</span>
                </a>
            @else
                <a href="{{ route('dashboard') }}" class="min-w-[44px] min-h-[44px] flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition-colors {{ request()->routeIs('dashboard') ? 'text-primary-700 font-black' : 'text-slate-600 hover:text-slate-900' }}">
                    <i class="fa-solid fa-gauge text-lg mb-0.5 {{ request()->routeIs('dashboard') ? 'text-primary-700' : 'text-slate-600' }}" aria-hidden="true"></i>
                    <span class="text-[10px] font-bold tracking-tight">Ringkasan</span>
                </a>
            @endif

            <a href="{{ route('home') }}" class="min-w-[44px] min-h-[44px] flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition-colors {{ request()->routeIs('home') ? 'text-primary-700 font-black' : 'text-slate-600 hover:text-slate-900' }}">
                <i class="fa-solid fa-globe text-lg mb-0.5 {{ request()->routeIs('home') ? 'text-primary-700' : 'text-slate-600' }}" aria-hidden="true"></i>
                <span class="text-[10px] font-bold tracking-tight">Website</span>
            </a>

            <a href="{{ route('profile.custom') }}" class="min-w-[44px] min-h-[44px] flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition-colors {{ request()->routeIs('profile.*') ? 'text-primary-700 font-black' : 'text-slate-600 hover:text-slate-900' }}">
                <i class="fa-solid fa-user-circle text-lg mb-0.5 {{ request()->routeIs('profile.*') ? 'text-primary-700' : 'text-slate-600' }}" aria-hidden="true"></i>
                <span class="text-[10px] font-bold tracking-tight">Profil</span>
            </a>
        @else
            <a href="{{ route('home') }}" class="min-w-[44px] min-h-[44px] flex flex-col items-center justify-center py-1 px-2 rounded-xl transition-colors {{ request()->routeIs('home') ? 'text-primary-700 font-black' : 'text-slate-600 hover:text-slate-900' }}">
                <i class="fa-solid fa-house text-lg mb-0.5 {{ request()->routeIs('home') ? 'text-primary-700' : 'text-slate-600' }}" aria-hidden="true"></i>
                <span class="text-[10px] font-bold tracking-tight">Home</span>
            </a>
            <a href="{{ route('campaigns.index') }}" class="min-w-[44px] min-h-[44px] flex flex-col items-center justify-center py-1 px-2 rounded-xl transition-colors {{ request()->routeIs('campaigns.*') ? 'text-primary-700 font-black' : 'text-slate-600 hover:text-slate-900' }}">
                <i class="fa-solid fa-hand-holding-heart text-lg mb-0.5 {{ request()->routeIs('campaigns.*') ? 'text-primary-700' : 'text-slate-600' }}" aria-hidden="true"></i>
                <span class="text-[10px] font-bold tracking-tight">Kampanye</span>
            </a>
            <a href="{{ route('zakat.index') }}" class="min-w-[44px] min-h-[44px] flex flex-col items-center justify-center py-1 px-2 rounded-xl transition-colors {{ request()->routeIs('zakat.*') ? 'text-primary-700 font-black' : 'text-slate-600 hover:text-slate-900' }}">
                <i class="fa-solid fa-calculator text-lg mb-0.5 {{ request()->routeIs('zakat.*') ? 'text-primary-700' : 'text-slate-600' }}" aria-hidden="true"></i>
                <span class="text-[10px] font-bold tracking-tight">Zakat</span>
            </a>
            <a href="{{ route('prayers.index') }}" class="min-w-[44px] min-h-[44px] flex flex-col items-center justify-center py-1 px-2 rounded-xl transition-colors {{ request()->routeIs('prayers.*') ? 'text-primary-700 font-black' : 'text-slate-600 hover:text-slate-900' }}">
                <i class="fa-solid fa-hands-praying text-lg mb-0.5 {{ request()->routeIs('prayers.*') ? 'text-primary-700' : 'text-slate-600' }}" aria-hidden="true"></i>
                <span class="text-[10px] font-bold tracking-tight">Doa</span>
            </a>
            @auth
                <a href="{{ Auth::user()->hasAdminAccess() ? route('admin.dashboard') : (Auth::user()->isFieldWorker() ? route('field.dashboard') : route('dashboard')) }}" class="min-w-[44px] min-h-[44px] flex flex-col items-center justify-center py-1 px-2 rounded-xl transition-colors {{ request()->routeIs('admin.*') || request()->routeIs('field.*') || request()->routeIs('dashboard') ? 'text-primary-700 font-black' : 'text-slate-600 hover:text-slate-900' }}">
                    <i class="fa-solid fa-user-circle text-lg mb-0.5 {{ request()->routeIs('admin.*') || request()->routeIs('field.*') || request()->routeIs('dashboard') ? 'text-primary-700' : 'text-slate-600' }}" aria-hidden="true"></i>
                    <span class="text-[10px] font-bold tracking-tight">Akun</span>
                </a>
            @else
                <button type="button" @click="loginOpen = true; registerOpen = false;" class="min-w-[44px] min-h-[44px] flex flex-col items-center justify-center py-1 px-2 rounded-xl text-slate-600 hover:text-primary-700 transition-colors">
                    <i class="fa-solid fa-right-to-bracket text-lg mb-0.5 text-slate-600" aria-hidden="true"></i>
                    <span class="text-[10px] font-bold tracking-tight">Masuk</span>
                </button>
            @endauth
        @endif
    </nav>

    <!-- Live Donation Toast: rendered strictly from real verified DB records only -->
    @if(!$isDashboardOrAdmin && isset($liveDonations) && $liveDonations->isNotEmpty())
    <div 
        x-data="liveDonationToast()" 
        x-init="initToast()" 
        x-cloak 
        @mouseenter="paused = true" 
        @mouseleave="paused = false"
        class="fixed z-[1035] bottom-20 md:bottom-6 left-3 md:left-6 max-w-[340px] sm:max-w-sm pointer-events-auto select-none"
    >
        <div 
            x-show="visible && current"
            x-transition:enter="transition-all ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition-all ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="bg-white border border-slate-200 rounded-2xl p-3.5 shadow-xl flex items-center gap-3 relative overflow-hidden"
        >
            <div class="relative shrink-0 mt-0.5">
                <img 
                    :src="current.avatar" 
                    :alt="current.name" 
                    width="44"
                    height="44"
                    class="w-10 h-10 sm:w-11 sm:h-11 rounded-full object-cover border border-primary-100"
                >
            </div>

            <div class="flex-grow min-w-0 pr-6">
                <div class="flex items-center gap-1.5 leading-none">
                    <h5 class="text-xs font-black text-slate-900 truncate" x-text="current.name"></h5>
                    <span class="text-[10px] font-bold text-slate-600 shrink-0">berdonasi</span>
                </div>
                
                <div class="text-xs leading-snug mt-1 truncate">
                    <span class="font-extrabold text-primary-700 font-montserrat" x-text="current.amount"></span>
                    <span class="text-slate-600 font-medium">untuk</span>
                    <span class="font-bold text-slate-800 truncate" x-text="current.campaign"></span>
                </div>

                <div class="flex items-center gap-2 mt-1 text-[10px] font-bold text-slate-600 leading-none">
                    <span class="flex items-center gap-1">
                        <i class="fa-regular fa-clock text-[9px] text-slate-500" aria-hidden="true"></i>
                        <span x-text="current.time"></span>
                    </span>
                    <span aria-hidden="true">&bull;</span>
                    <span class="text-emerald-700 font-bold">Tercatat</span>
                </div>
            </div>

            <button 
                type="button" 
                @click="dismiss()" 
                aria-label="Tutup notifikasi donasi"
                class="absolute top-1 right-1 min-w-[36px] min-h-[36px] inline-flex items-center justify-center text-slate-500 hover:text-slate-800 transition-colors rounded-lg"
            >
                <i class="fa-solid fa-xmark text-xs" aria-hidden="true"></i>
            </button>
        </div>
    </div>

    <script>
        function liveDonationToast() {
            return {
                list: @json($liveDonations),
                currentIndex: 0,
                visible: false,
                paused: false,
                dismissed: false,
                timer: null,

                get current() {
                    return (this.list && this.list.length > 0) ? this.list[this.currentIndex] : null;
                },

                initToast() {
                    if (!this.list || this.list.length === 0) return;
                    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

                    setTimeout(() => {
                        this.cycle();
                    }, 1500);
                },

                cycle() {
                    if (this.dismissed) return;
                    if (this.paused) {
                        setTimeout(() => this.cycle(), 1000);
                        return;
                    }

                    this.visible = true;
                    this.timer = setTimeout(() => {
                        this.visible = false;
                        setTimeout(() => {
                            if (this.dismissed) return;
                            this.currentIndex = (this.currentIndex + 1) % this.list.length;
                            this.cycle();
                        }, 600);
                    }, 3500);
                },

                dismiss() {
                    this.dismissed = true;
                    this.visible = false;
                    clearTimeout(this.timer);
                }
            };
        }
    </script>
    @endif

    <!-- LOGIN MODAL -->
    <div x-show="loginOpen" 
         x-cloak 
         @keydown.escape.window="loginOpen = false"
         style="display: none;" 
         role="dialog"
         aria-modal="true"
         aria-labelledby="modal-login-title"
         class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-slate-950/75 overflow-y-auto">
        <div @click.away="loginOpen = false" class="bg-white border border-slate-200 w-full max-w-md rounded-3xl p-6 sm:p-10 shadow-2xl relative space-y-6 my-auto max-h-[90vh] overflow-y-auto">
            <button type="button" @click="loginOpen = false" aria-label="Tutup jendela masuk" class="absolute top-4 right-4 min-w-[44px] min-h-[44px] inline-flex items-center justify-center rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-colors">
                <i class="fa-solid fa-xmark text-lg" aria-hidden="true"></i>
            </button>
            <div class="space-y-1 pr-8">
                <h2 id="modal-login-title" class="text-xl sm:text-2xl font-montserrat font-black uppercase text-slate-900 tracking-tight">Selamat <span class="text-primary-600">Datang Kembali</span></h2>
                <p class="text-xs text-slate-600 font-semibold">Masuk untuk melanjutkan kebaikan Anda</p>
            </div>

            @if ($errors->any() && !($errors->has('name') || $errors->has('password_confirmation')))
                <div role="alert" class="bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold p-4 rounded-xl">
                    Ada beberapa kesalahan dalam data yang dimasukkan.
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div class="space-y-1.5">
                    <label for="modal_login_email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Alamat Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-500"><i class="fa-solid fa-envelope text-xs" aria-hidden="true"></i></span>
                        <input id="modal_login_email" type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com" class="w-full bg-slate-50 border border-slate-300 focus:border-primary-600 rounded-xl pl-10 pr-4 py-3 text-xs font-bold text-slate-900 placeholder-slate-500 transition-colors">
                    </div>
                    @if ($errors->has('email'))
                        <span class="text-xs font-bold text-rose-700">{{ $errors->first('email') }}</span>
                    @endif
                </div>
                <div class="space-y-1.5" x-data="{ showPass: false }">
                    <div class="flex justify-between items-center">
                        <label for="modal_login_password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Kata Sandi</label>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-500"><i class="fa-solid fa-lock text-xs" aria-hidden="true"></i></span>
                        <input id="modal_login_password" :type="showPass ? 'text' : 'password'" name="password" required placeholder="Masukkan kata sandi" class="w-full bg-slate-50 border border-slate-300 focus:border-primary-600 rounded-xl pl-10 pr-12 py-3 text-xs font-bold text-slate-900 placeholder-slate-500 transition-colors">
                        <button type="button" @click="showPass = !showPass" :aria-label="showPass ? 'Sembunyikan kata sandi' : 'Lihat kata sandi'" class="absolute inset-y-0 right-0 flex items-center px-3 min-w-[44px] justify-center text-slate-600 hover:text-primary-600 transition-colors">
                            <i class="fa-solid text-sm" :class="showPass ? 'fa-eye-slash' : 'fa-eye'" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <input id="modal_login_remember" type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 bg-slate-50 text-primary-600 focus:ring-primary-500">
                    <label for="modal_login_remember" class="text-xs font-bold text-slate-700 select-none">Ingat Saya</label>
                </div>
                <button type="submit" class="w-full min-h-[44px] bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-wider py-3.5 rounded-xl shadow-md transition-colors">Masuk Akun</button>
            </form>
            <div class="text-center pt-2">
                <span class="text-xs font-semibold text-slate-600">Belum punya akun?</span>
                <button type="button" @click="loginOpen = false; registerOpen = true" class="text-xs font-black uppercase text-primary-700 hover:underline ml-1 px-1 py-1">Daftar Sekarang</button>
            </div>
        </div>
    </div>

    <!-- REGISTER MODAL -->
    <div x-show="registerOpen" 
         x-cloak 
         @keydown.escape.window="registerOpen = false"
         style="display: none;" 
         role="dialog"
         aria-modal="true"
         aria-labelledby="modal-register-title"
         class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-slate-950/75 overflow-y-auto">
        <div @click.away="registerOpen = false" class="bg-white border border-slate-200 w-full max-w-md rounded-3xl p-6 sm:p-10 shadow-2xl relative space-y-6 my-auto max-h-[90vh] overflow-y-auto">
            <button type="button" @click="registerOpen = false" aria-label="Tutup jendela daftar" class="absolute top-4 right-4 min-w-[44px] min-h-[44px] inline-flex items-center justify-center rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-colors">
                <i class="fa-solid fa-xmark text-lg" aria-hidden="true"></i>
            </button>
            <div class="space-y-1 pr-8">
                <h2 id="modal-register-title" class="text-xl sm:text-2xl font-montserrat font-black uppercase text-slate-900 tracking-tight">Buat <span class="text-primary-600">Akun Baru</span></h2>
                <p class="text-xs text-slate-600 font-semibold">Daftar untuk mulai berbagi kebaikan bersama kami</p>
            </div>

            @if ($errors->any() && ($errors->has('name') || $errors->has('password_confirmation')))
                <div role="alert" class="bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold p-4 rounded-xl">
                    Ada beberapa kesalahan dalam data pendaftaran.
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf
                <div class="space-y-1.5">
                    <label for="modal_reg_name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Nama Lengkap</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-500"><i class="fa-solid fa-user text-xs" aria-hidden="true"></i></span>
                        <input id="modal_reg_name" type="text" name="name" value="{{ old('name') }}" required placeholder="Nama Lengkap" class="w-full bg-slate-50 border border-slate-300 focus:border-primary-600 rounded-xl pl-10 pr-4 py-3 text-xs font-bold text-slate-900 placeholder-slate-500 transition-colors">
                    </div>
                    @if ($errors->has('name'))
                        <span class="text-xs font-bold text-rose-700">{{ $errors->first('name') }}</span>
                    @endif
                </div>
                <div class="space-y-1.5">
                    <label for="modal_reg_email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Alamat Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-500"><i class="fa-solid fa-envelope text-xs" aria-hidden="true"></i></span>
                        <input id="modal_reg_email" type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com" class="w-full bg-slate-50 border border-slate-300 focus:border-primary-600 rounded-xl pl-10 pr-4 py-3 text-xs font-bold text-slate-900 placeholder-slate-500 transition-colors">
                    </div>
                    @if ($errors->has('email') && ($errors->has('name') || $errors->has('password_confirmation')))
                        <span class="text-xs font-bold text-rose-700">{{ $errors->first('email') }}</span>
                    @endif
                </div>
                <div class="space-y-1.5" x-data="{ showPass: false }">
                    <label for="modal_reg_password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Kata Sandi</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-500"><i class="fa-solid fa-lock text-xs" aria-hidden="true"></i></span>
                        <input id="modal_reg_password" :type="showPass ? 'text' : 'password'" name="password" required placeholder="Minimal 8 karakter" class="w-full bg-slate-50 border border-slate-300 focus:border-primary-600 rounded-xl pl-10 pr-12 py-3 text-xs font-bold text-slate-900 placeholder-slate-500 transition-colors">
                        <button type="button" @click="showPass = !showPass" :aria-label="showPass ? 'Sembunyikan kata sandi' : 'Lihat kata sandi'" class="absolute inset-y-0 right-0 flex items-center px-3 min-w-[44px] justify-center text-slate-600 hover:text-primary-600 transition-colors">
                            <i class="fa-solid text-sm" :class="showPass ? 'fa-eye-slash' : 'fa-eye'" aria-hidden="true"></i>
                        </button>
                    </div>
                    @if ($errors->has('password'))
                        <span class="text-xs font-bold text-rose-700">{{ $errors->first('password') }}</span>
                    @endif
                </div>
                <div class="space-y-1.5" x-data="{ showPass: false }">
                    <label for="modal_reg_confirm" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Konfirmasi Sandi</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-500"><i class="fa-solid fa-circle-check text-xs" aria-hidden="true"></i></span>
                        <input id="modal_reg_confirm" :type="showPass ? 'text' : 'password'" name="password_confirmation" required placeholder="Ulangi kata sandi" class="w-full bg-slate-50 border border-slate-300 focus:border-primary-600 rounded-xl pl-10 pr-12 py-3 text-xs font-bold text-slate-900 placeholder-slate-500 transition-colors">
                        <button type="button" @click="showPass = !showPass" :aria-label="showPass ? 'Sembunyikan kata sandi' : 'Lihat kata sandi'" class="absolute inset-y-0 right-0 flex items-center px-3 min-w-[44px] justify-center text-slate-600 hover:text-primary-600 transition-colors">
                            <i class="fa-solid text-sm" :class="showPass ? 'fa-eye-slash' : 'fa-eye'" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
                <button type="submit" class="w-full min-h-[44px] bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-wider py-3.5 rounded-xl shadow-md transition-colors">Daftar Akun</button>
            </form>
            <div class="text-center pt-2">
                <span class="text-xs font-semibold text-slate-600">Sudah punya akun?</span>
                <button type="button" @click="registerOpen = false; loginOpen = true" class="text-xs font-black uppercase text-primary-700 hover:underline ml-1 px-1 py-1">Masuk Sekarang</button>
            </div>
        </div>
    </div>

</body>
</html>
