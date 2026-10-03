<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Amal Abadi - Platform Donasi & Zakat' }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Montserrat:ital,wght@0,300;0,400;0,700;0,900;1,400;1,900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (CDN for ultimate stability and speed on local host) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        montserrat: ['Montserrat', 'sans-serif'],
                    },
                    spacing: {
                        '4.5': '1.125rem',
                    },
                    colors: {
                        primary: {
                            50: '#fff7ed',
                            100: '#ffedd5',
                            200: '#fed7aa',
                            500: '#f97316',
                            600: '#ea580c',
                            700: '#c2410c',
                            800: '#9a3412',
                        },
                        secondary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            500: '#29abe2',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                        },
                        emerald: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#29abe2',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                        }
                    }
                }
            }
        }
    </script>

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Alpine.js Collapse Plugin -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>

    <!-- Alpine.js Core -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }
        html {
            scroll-behavior: smooth;
            -webkit-tap-highlight-color: transparent;
        }
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        /* Mobile touch responsiveness optimization */
        button, a, input, select, textarea {
            touch-action: manipulation;
        }
    </style>
</head>
<body x-data="{ loginOpen: false, registerOpen: false }" x-init="if ({{ $errors->has('name') || $errors->has('password_confirmation') ? 'true' : 'false' }}) { registerOpen = true } else if ({{ $errors->any() ? 'true' : 'false' }}) { loginOpen = true }" class="font-sans bg-[#F8FAFC] text-slate-800 antialiased flex flex-col min-h-screen">

    <!-- Premium Floating Navbar -->
    <header x-data="{ open: false }" class="sticky top-0 z-[1050] w-full px-3 sm:px-6 lg:px-8 pt-2.5 sm:pt-4">
        <nav class="max-w-7xl mx-auto bg-white/90 backdrop-blur-xl border border-slate-200/60 rounded-2xl sm:rounded-3xl shadow-sm px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between transition-all duration-300">
            <!-- Logo & Brand -->
            <a href="{{ route('home') }}" class="flex items-center space-x-2 group">
                <img src="{{ asset('images/logo.jpeg') }}" onerror="this.onerror=null; this.src='{{ asset('images/logo.png') }}';" class="h-10 sm:h-12 w-auto object-contain group-hover:scale-105 transition-transform duration-300" alt="Amal Abadi">
            </a>

            <!-- Desktop Menu Links -->
            <div class="hidden md:flex items-center space-x-1 font-bold text-xs uppercase tracking-wider text-slate-500">
                <a href="{{ route('home') }}" class="px-4 py-2 rounded-xl transition duration-200 hover:text-primary-600 {{ request()->routeIs('home') ? 'bg-primary-50 text-primary-700' : '' }}">Home</a>
                <a href="{{ route('campaigns.index') }}" class="px-4 py-2 rounded-xl transition duration-200 hover:text-primary-600 {{ request()->routeIs('campaigns.*') ? 'bg-primary-50 text-primary-700' : '' }}">Kampanye</a>
                <a href="{{ route('zakat.index') }}" class="px-4 py-2 rounded-xl transition duration-200 hover:text-primary-600 {{ request()->routeIs('zakat.*') ? 'bg-primary-50 text-primary-700' : '' }}">Zakat</a>
                <a href="{{ route('prayers.index') }}" class="px-4 py-2 rounded-xl transition duration-200 hover:text-primary-600 {{ request()->routeIs('prayers.*') ? 'bg-primary-50 text-primary-700' : '' }}">Doa</a>
                <a href="{{ route('news') }}" class="px-4 py-2 rounded-xl transition duration-200 hover:text-primary-600 {{ request()->routeIs('news*') ? 'bg-primary-50 text-primary-700' : '' }}">Berita</a>
                
                <!-- Dropdown Menu Lainnya -->
                <div x-data="{ openLainnya: false }" class="relative">
                    <button @click="openLainnya = !openLainnya" class="px-4 py-2 rounded-xl transition duration-200 hover:text-primary-600 flex items-center space-x-1 outline-none">
                        <span>Lainnya</span>
                        <i class="fa-solid fa-chevron-down text-[9px] transition-transform duration-200" :class="openLainnya ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="openLainnya" x-transition.origin.top.left @click.away="openLainnya = false" style="display: none;" class="absolute left-0 mt-2 w-48 bg-white border border-slate-100 rounded-2xl shadow-xl py-2 z-50 text-[11px] font-bold text-slate-500">
                        <a href="{{ route('volunteer') }}" class="flex items-center space-x-2 px-4 py-2.5 hover:bg-slate-50 hover:text-primary-600 transition-colors">
                            <i class="fa-solid fa-people-carry-box text-slate-400"></i>
                            <span>Gabung Relawan</span>
                        </a>
                        <a href="{{ route('about') }}" class="flex items-center space-x-2 px-4 py-2.5 hover:bg-slate-50 hover:text-primary-600 transition-colors">
                            <i class="fa-solid fa-circle-info text-slate-400"></i>
                            <span>Tentang Kami</span>
                        </a>
                        <a href="{{ route('contact') }}" class="flex items-center space-x-2 px-4 py-2.5 hover:bg-slate-50 hover:text-primary-600 transition-colors">
                            <i class="fa-solid fa-comments text-slate-400"></i>
                            <span>Kontak</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Profile Dropdown or Auth Buttons -->
            <div class="hidden md:flex items-center space-x-3">
                @auth
                    <!-- Authenticated Dropdown -->
                    <div x-data="{ userMenu: false }" class="relative">
                        <button @click="userMenu = !userMenu" class="flex items-center space-x-3 bg-slate-50 border border-slate-100 hover:border-slate-200 px-4 py-2 rounded-2xl transition duration-200">
                            @if(Auth::user()->hasCustomPhoto())
                                <img src="{{ Auth::user()->profile_photo_url }}" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=ea580c&color=fff';" class="w-7 h-7 rounded-xl object-cover" alt="Profile">
                            @else
                                <div class="w-7 h-7 bg-primary-100 text-primary-700 rounded-xl flex items-center justify-center font-bold text-xs uppercase">
                                    {{ substr(Auth::user()->name, 0, 2) }}
                                </div>
                            @endif
                            <div class="text-left">
                                <span class="font-bold text-xs text-slate-800 block leading-tight">{{ Auth::user()->name }}</span>
                                <span class="text-[9px] font-bold text-primary-600 uppercase block leading-none tracking-wider">{{ Auth::user()->role_label }}</span>
                            </div>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                        </button>
                        <div x-show="userMenu" @click.away="userMenu = false" class="absolute right-0 mt-2 w-56 bg-white border border-slate-200 rounded-2xl shadow-xl py-2 z-50">
                            <!-- Role based dashboard -->
                            @if(Auth::user()->hasAdminAccess())
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 text-xs font-bold text-slate-600 hover:bg-slate-50 hover:text-primary-600 transition-colors uppercase tracking-wider">
                                    <i class="fa-solid fa-chart-line text-sm text-slate-400"></i>
                                    <span>Dashboard Admin</span>
                                </a>
                            @elseif(Auth::user()->isFieldWorker())
                                <a href="{{ route('field.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 text-xs font-bold text-slate-600 hover:bg-slate-50 hover:text-primary-600 transition-colors uppercase tracking-wider">
                                    <i class="fa-solid fa-users-gear text-sm text-slate-400"></i>
                                    <span>Dashboard Petugas</span>
                                </a>
                            @endif
                            
                            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 text-xs font-bold text-slate-600 hover:bg-slate-50 hover:text-primary-600 transition-colors uppercase tracking-wider">
                                <i class="fa-solid fa-house-user text-sm text-slate-400"></i>
                                <span>Dashboard Donatur</span>
                            </a>
                            <a href="{{ route('profile.custom') }}" class="flex items-center space-x-3 px-4 py-3 text-xs font-bold text-slate-600 hover:bg-slate-50 hover:text-primary-600 transition-colors uppercase tracking-wider">
                                <i class="fa-solid fa-user-pen text-sm text-slate-400"></i>
                                <span>Edit Profil</span>
                            </a>
                            <hr class="border-slate-100 my-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center space-x-3 px-4 py-3 text-xs font-bold text-red-600 hover:bg-red-50 transition-colors uppercase tracking-wider text-left">
                                    <i class="fa-solid fa-right-from-bracket text-sm text-red-400"></i>
                                    <span>Keluar Akun</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Guest Buttons -->
                    <button @click="loginOpen = true; registerOpen = false" class="text-xs font-bold uppercase tracking-widest text-slate-600 hover:text-primary-600 px-4 py-2 transition-colors">Login</button>
                    <button @click="registerOpen = true; loginOpen = false" class="bg-primary-600 text-white text-xs font-black uppercase tracking-widest px-6 py-3 rounded-2xl hover:bg-primary-700 shadow-lg shadow-primary-600/10 hover:shadow-primary-600/20 transition-all duration-300">Daftar</button>
                @endauth
            </div>

            <!-- Hamburger Button -->
            <div class="md:hidden flex items-center">
                <button @click="open = !open" type="button" aria-label="Menu" class="text-slate-700 p-2.5 rounded-xl hover:bg-slate-100 active:scale-95 transition-all focus:outline-none">
                    <i class="fa-solid text-xl" :class="open ? 'fa-xmark text-primary-600' : 'fa-bars text-slate-700'"></i>
                </button>
            </div>
        </nav>

        <!-- Mobile Menu Dropdown -->
        <div x-show="open" 
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-3"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-3"
             @click.away="open = false" 
             style="display: none;" 
             class="md:hidden mt-2 max-w-7xl mx-auto bg-white/95 backdrop-blur-2xl border border-slate-200/90 rounded-3xl p-5 sm:p-6 shadow-2xl space-y-4 max-h-[80vh] overflow-y-auto">
            <div class="flex flex-col space-y-1.5 font-bold text-xs uppercase tracking-wider text-slate-600">
                <a href="{{ route('home') }}" @click="open = false" class="py-3 px-4 rounded-xl transition-colors {{ request()->routeIs('home') ? 'bg-primary-50 text-primary-700 font-black' : 'hover:bg-slate-50' }}">
                    <i class="fa-solid fa-house mr-2 text-slate-400"></i> Home
                </a>
                <a href="{{ route('campaigns.index') }}" @click="open = false" class="py-3 px-4 rounded-xl transition-colors {{ request()->routeIs('campaigns.*') ? 'bg-primary-50 text-primary-700 font-black' : 'hover:bg-slate-50' }}">
                    <i class="fa-solid fa-hand-holding-heart mr-2 text-slate-400"></i> Kampanye
                </a>
                <a href="{{ route('zakat.index') }}" @click="open = false" class="py-3 px-4 rounded-xl transition-colors {{ request()->routeIs('zakat.*') ? 'bg-primary-50 text-primary-700 font-black' : 'hover:bg-slate-50' }}">
                    <i class="fa-solid fa-calculator mr-2 text-slate-400"></i> Zakat
                </a>
                <a href="{{ route('prayers.index') }}" @click="open = false" class="py-3 px-4 rounded-xl transition-colors {{ request()->routeIs('prayers.*') ? 'bg-primary-50 text-primary-700 font-black' : 'hover:bg-slate-50' }}">
                    <i class="fa-solid fa-hands-praying mr-2 text-slate-400"></i> Doa
                </a>
                <a href="{{ route('news') }}" @click="open = false" class="py-3 px-4 rounded-xl transition-colors {{ request()->routeIs('news*') ? 'bg-primary-50 text-primary-700 font-black' : 'hover:bg-slate-50' }}">
                    <i class="fa-solid fa-newspaper mr-2 text-slate-400"></i> Berita
                </a>
                <a href="{{ route('volunteer') }}" @click="open = false" class="py-3 px-4 rounded-xl transition-colors {{ request()->routeIs('volunteer') ? 'bg-primary-50 text-primary-700 font-black' : 'hover:bg-slate-50' }}">
                    <i class="fa-solid fa-users mr-2 text-slate-400"></i> Relawan
                </a>
                <a href="{{ route('about') }}" @click="open = false" class="py-3 px-4 rounded-xl transition-colors {{ request()->routeIs('about') ? 'bg-primary-50 text-primary-700 font-black' : 'hover:bg-slate-50' }}">
                    <i class="fa-solid fa-circle-info mr-2 text-slate-400"></i> Tentang Kami
                </a>
                <a href="{{ route('contact') }}" @click="open = false" class="py-3 px-4 rounded-xl transition-colors {{ request()->routeIs('contact') ? 'bg-primary-50 text-primary-700 font-black' : 'hover:bg-slate-50' }}">
                    <i class="fa-solid fa-envelope mr-2 text-slate-400"></i> Kontak
                </a>
            </div>
            <hr class="border-slate-100">
            @auth
                <div class="space-y-2 text-xs font-bold uppercase tracking-wider">
                    @if(Auth::user()->hasAdminAccess())
                        <a href="{{ route('admin.dashboard') }}" @click="open = false" class="flex items-center space-x-3 py-2.5 px-4 text-slate-700 hover:text-primary-600 hover:bg-slate-50 rounded-xl">
                            <i class="fa-solid fa-chart-line text-slate-400"></i>
                            <span>Dashboard Admin</span>
                        </a>
                    @elseif(Auth::user()->isFieldWorker())
                        <a href="{{ route('field.dashboard') }}" @click="open = false" class="flex items-center space-x-3 py-2.5 px-4 text-slate-700 hover:text-primary-600 hover:bg-slate-50 rounded-xl">
                            <i class="fa-solid fa-users-gear text-slate-400"></i>
                            <span>Dashboard Petugas</span>
                        </a>
                    @endif
                    <a href="{{ route('dashboard') }}" @click="open = false" class="flex items-center space-x-3 py-2.5 px-4 text-slate-700 hover:text-primary-600 hover:bg-slate-50 rounded-xl">
                        <i class="fa-solid fa-house-user text-slate-400"></i>
                        <span>Dashboard Donatur</span>
                    </a>
                    <a href="{{ route('profile.custom') }}" @click="open = false" class="flex items-center space-x-3 py-2.5 px-4 text-slate-700 hover:text-primary-600 hover:bg-slate-50 rounded-xl">
                        <i class="fa-solid fa-user-pen text-slate-400"></i>
                        <span>Edit Profil</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="pt-1">
                        @csrf
                        <button type="submit" class="w-full flex items-center space-x-3 py-2.5 px-4 text-red-600 hover:bg-red-50 rounded-xl text-left transition-colors">
                            <i class="fa-solid fa-right-from-bracket text-red-400"></i>
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

    <!-- Main Content Area -->
    <main class="flex-grow pb-24 md:pb-0">
        <!-- Toast Alerts -->
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="max-w-7xl mx-auto px-4 mt-6">
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-6 py-4 rounded-2xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center space-x-3">
                        <i class="fa-solid fa-circle-check text-lg text-emerald-500"></i>
                        <span class="text-xs font-bold tracking-wide">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 focus:outline-none">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="max-w-7xl mx-auto px-4 mt-6">
                <div class="bg-rose-50 border border-rose-200 text-rose-800 px-6 py-4 rounded-2xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center space-x-3">
                        <i class="fa-solid fa-circle-exclamation text-lg text-rose-500"></i>
                        <span class="text-xs font-bold tracking-wide">{{ session('error') }}</span>
                    </div>
                    <button @click="show = false" class="text-rose-500 hover:text-rose-700 focus:outline-none">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            </div>
        @endif

        <!-- Layout slots -->
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <!-- Premium Footer -->
    <footer class="bg-slate-900 text-slate-400 pt-16 pb-8 px-6 sm:px-10 mt-20 border-t border-slate-800">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-10 mb-12">
            <!-- Brand & Tagline -->
            <div class="space-y-4">
                <div class="inline-block bg-white p-2.5 rounded-2xl shadow-sm">
                    <img src="{{ asset('images/logo.jpeg') }}" onerror="this.onerror=null; this.src='{{ asset('images/logo.png') }}';" class="h-10 w-auto object-contain" alt="Amal Abadi">
                </div>
                <p class="text-xs font-medium leading-relaxed">
                    Menghubungkan kebaikan hati Anda untuk mendukung kehidupan sesama yang membutuhkan secara transparan dan tepercaya.
                </p>
                <!-- Social media -->
                <div class="flex space-x-3 pt-2">
                    <a href="#" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-primary-600 text-slate-300 hover:text-white flex items-center justify-center transition-colors duration-200"><i class="fa-brands fa-instagram text-sm"></i></a>
                    <a href="#" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-primary-600 text-slate-300 hover:text-white flex items-center justify-center transition-colors duration-200"><i class="fa-brands fa-facebook-f text-sm"></i></a>
                    <a href="#" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-primary-600 text-slate-300 hover:text-white flex items-center justify-center transition-colors duration-200"><i class="fa-brands fa-twitter text-sm"></i></a>
                    <a href="#" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-primary-600 text-slate-300 hover:text-white flex items-center justify-center transition-colors duration-200"><i class="fa-brands fa-youtube text-sm"></i></a>
                </div>
            </div>

            <!-- Links: Navigation -->
            <div class="space-y-4">
                <h4 class="text-xs font-black uppercase tracking-widest text-white">Navigasi</h4>
                <ul class="space-y-2.5 text-xs font-bold uppercase tracking-wider">
                    <li><a href="{{ route('home') }}" class="hover:text-primary-500 transition-colors">Home</a></li>
                    <li><a href="{{ route('campaigns.index') }}" class="hover:text-primary-500 transition-colors">Daftar Kampanye</a></li>
                    <li><a href="{{ route('zakat.index') }}" class="hover:text-primary-500 transition-colors">Kalkulator Zakat</a></li>
                    <li><a href="{{ route('prayers.index') }}" class="hover:text-primary-500 transition-colors">Doa Bersama</a></li>
                </ul>
            </div>

            <!-- Links: Legal / Support -->
            <div class="space-y-4">
                <h4 class="text-xs font-black uppercase tracking-widest text-white">Dukungan</h4>
                <ul class="space-y-2.5 text-xs font-bold uppercase tracking-wider">
                    <li><a href="{{ route('about') }}" class="hover:text-primary-500 transition-colors">Tentang Kami</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-primary-500 transition-colors">Hubungi Kami</a></li>
                    <li><a href="{{ route('volunteer') }}" class="hover:text-primary-500 transition-colors">Gabung Relawan</a></li>
                </ul>
            </div>

            <!-- Newsletter subscribe -->
            <div class="space-y-4">
                <h4 class="text-xs font-black uppercase tracking-widest text-white">Langganan Berita</h4>
                <p class="text-xs font-medium leading-relaxed">
                    Dapatkan update berkala mengenai penyaluran donasi dan cerita dampak langsung dari lapangan.
                </p>
                <form action="{{ route('newsletter') }}" method="POST" class="flex gap-2">
                    @csrf
                    <input type="email" name="email" required placeholder="Email Anda" class="w-full bg-slate-800 border border-slate-700 text-white rounded-xl px-4 py-2.5 text-xs outline-none focus:border-primary-500 font-bold transition-all">
                    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 rounded-xl flex items-center justify-center transition-colors"><i class="fa-solid fa-paper-plane text-xs"></i></button>
                </form>
            </div>
        </div>

        <hr class="border-slate-800 my-8">

        <!-- Copyright -->
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between text-[11px] font-bold text-slate-500 uppercase tracking-widest text-center gap-4">
            <p>Copyright &copy; 2026 Amal Abadi. All Rights Reserved.</p>
            <p>Dikembangkan oleh <span class="text-slate-300">velora.id</span></p>
        </div>
    </footer>

    <!-- ================= MOBILE BOTTOM NAVIGATION (App-like Experience) ================= -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 z-[1040] bg-white/95 backdrop-blur-xl border-t border-slate-200/80 px-2 py-2 shadow-[0_-8px_30px_rgba(0,0,0,0.06)] flex items-center justify-around pb-safe">
        <a href="{{ route('home') }}" class="flex flex-col items-center py-1 px-2.5 rounded-xl transition-all {{ request()->routeIs('home') ? 'text-primary-600 font-black' : 'text-slate-500 hover:text-slate-800' }}">
            <i class="fa-solid fa-house text-lg mb-1 {{ request()->routeIs('home') ? 'text-primary-600 scale-110' : 'text-slate-400' }}"></i>
            <span class="text-[9px] font-bold tracking-tight">Home</span>
        </a>
        <a href="{{ route('campaigns.index') }}" class="flex flex-col items-center py-1 px-2.5 rounded-xl transition-all {{ request()->routeIs('campaigns.*') ? 'text-primary-600 font-black' : 'text-slate-500 hover:text-slate-800' }}">
            <i class="fa-solid fa-hand-holding-heart text-lg mb-1 {{ request()->routeIs('campaigns.*') ? 'text-primary-600 scale-110' : 'text-slate-400' }}"></i>
            <span class="text-[9px] font-bold tracking-tight">Kampanye</span>
        </a>
        <a href="{{ route('zakat.index') }}" class="flex flex-col items-center py-1 px-2.5 rounded-xl transition-all {{ request()->routeIs('zakat.*') ? 'text-primary-600 font-black' : 'text-slate-500 hover:text-slate-800' }}">
            <i class="fa-solid fa-calculator text-lg mb-1 {{ request()->routeIs('zakat.*') ? 'text-primary-600 scale-110' : 'text-slate-400' }}"></i>
            <span class="text-[9px] font-bold tracking-tight">Zakat</span>
        </a>
        <a href="{{ route('prayers.index') }}" class="flex flex-col items-center py-1 px-2.5 rounded-xl transition-all {{ request()->routeIs('prayers.*') ? 'text-primary-600 font-black' : 'text-slate-500 hover:text-slate-800' }}">
            <i class="fa-solid fa-hands-praying text-lg mb-1 {{ request()->routeIs('prayers.*') ? 'text-primary-600 scale-110' : 'text-slate-400' }}"></i>
            <span class="text-[9px] font-bold tracking-tight">Doa</span>
        </a>
        @auth
            <a href="{{ Auth::user()->hasAdminAccess() ? route('admin.dashboard') : (Auth::user()->isFieldWorker() ? route('field.dashboard') : route('dashboard')) }}" class="flex flex-col items-center py-1 px-2.5 rounded-xl transition-all {{ request()->routeIs('admin.*') || request()->routeIs('field.*') || request()->routeIs('dashboard') ? 'text-primary-600 font-black' : 'text-slate-500 hover:text-slate-800' }}">
                <i class="fa-solid fa-user-circle text-lg mb-1 {{ request()->routeIs('admin.*') || request()->routeIs('field.*') || request()->routeIs('dashboard') ? 'text-primary-600 scale-110' : 'text-slate-400' }}"></i>
                <span class="text-[9px] font-bold tracking-tight">Akun</span>
            </a>
        @else
            <button type="button" @click="loginOpen = true; registerOpen = false;" class="flex flex-col items-center py-1 px-2.5 rounded-xl text-slate-500 hover:text-primary-600 transition-all">
                <i class="fa-solid fa-right-to-bracket text-lg mb-1 text-slate-400"></i>
                <span class="text-[9px] font-bold tracking-tight">Masuk</span>
            </button>
        @endauth
    </nav>

    <!-- ================= LOGIN MODAL ================= -->
    <div x-show="loginOpen" 
         x-cloak 
         style="display: none;" 
         class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-md overflow-y-auto">
        <div @click.away="loginOpen = false" class="bg-white border border-slate-100 w-full max-w-md rounded-3xl p-6 sm:p-10 shadow-2xl relative space-y-6 my-auto max-h-[90vh] overflow-y-auto">
            <button type="button" @click="loginOpen = false" class="absolute top-5 right-5 p-2 text-slate-400 hover:text-slate-800 active:scale-95 transition-all focus:outline-none">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
            <div class="space-y-1 pr-6">
                <h2 class="text-xl sm:text-2xl font-montserrat font-black uppercase text-slate-900 tracking-tight italic">Selamat <span class="text-primary-500">Datang Kembali</span></h2>
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Masuk untuk melanjutkan kebaikan Anda</p>
            </div>

            <!-- Session Status & Errors -->
            @if ($errors->any() && !($errors->has('name') || $errors->has('password_confirmation')))
                <div class="bg-red-50 border border-red-150 text-red-800 text-[10px] font-bold uppercase tracking-wider p-4 rounded-2xl">
                    Ada beberapa kesalahan dalam data yang dimasukkan.
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div class="space-y-1.5">
                    <label for="modal_login_email" class="block text-[9px] font-black uppercase tracking-wider text-slate-450 mb-1.5 ml-1">Alamat Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400"><i class="fa-solid fa-envelope text-xs"></i></span>
                        <input id="modal_login_email" type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com" class="w-full bg-slate-50 border border-slate-150 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 rounded-2xl pl-10 pr-4 py-3.5 text-xs font-bold text-slate-800 placeholder-slate-400 outline-none transition-all">
                    </div>
                    @if ($errors->has('email'))
                        <span class="text-[9px] font-bold uppercase text-red-500">{{ $errors->first('email') }}</span>
                    @endif
                </div>
                <div class="space-y-1.5">
                    <div class="flex justify-between items-center">
                        <label for="modal_login_password" class="block text-[9px] font-black uppercase tracking-wider text-slate-450 mb-1.5 ml-1">Kata Sandi</label>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400"><i class="fa-solid fa-lock text-xs"></i></span>
                        <input id="modal_login_password" type="password" name="password" required placeholder="••••••••" class="w-full bg-slate-50 border border-slate-150 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 rounded-2xl pl-10 pr-4 py-3.5 text-xs font-bold text-slate-800 placeholder-slate-400 outline-none transition-all">
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <input id="modal_login_remember" type="checkbox" name="remember" class="rounded border-slate-300 bg-slate-50 text-primary-650 focus:ring-primary-500/20">
                    <label for="modal_login_remember" class="text-[9px] font-black uppercase tracking-wider text-slate-450 select-none cursor-pointer">Ingat Saya</label>
                </div>
                <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 active:scale-[0.99] text-white font-black text-xs uppercase tracking-widest py-4 rounded-2xl shadow-lg shadow-primary-600/10 transition duration-300">Masuk Akun <i class="fa-solid fa-right-to-bracket ml-2"></i></button>
            </form>
            <div class="text-center pt-2">
                <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wide">Belum punya akun?</span>
                <button type="button" @click="loginOpen = false; registerOpen = true" class="text-[10px] font-black uppercase text-primary-500 hover:text-primary-600 transition-colors ml-1">Daftar Sekarang</button>
            </div>
        </div>
    </div>

    <!-- ================= REGISTER MODAL ================= -->
    <div x-show="registerOpen" 
         x-cloak 
         style="display: none;" 
         class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-md overflow-y-auto">
        <div @click.away="registerOpen = false" class="bg-white border border-slate-100 w-full max-w-md rounded-3xl p-6 sm:p-10 shadow-2xl relative space-y-6 my-auto max-h-[90vh] overflow-y-auto">
            <button type="button" @click="registerOpen = false" class="absolute top-5 right-5 p-2 text-slate-400 hover:text-slate-800 active:scale-95 transition-all focus:outline-none">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
            <div class="space-y-1 pr-6">
                <h2 class="text-xl sm:text-2xl font-montserrat font-black uppercase text-slate-900 tracking-tight italic">Buat <span class="text-primary-500">Akun Baru</span></h2>
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Daftar untuk mulai berbagi kebaikan bersama kami</p>
            </div>

            <!-- Validation Errors -->
            @if ($errors->any() && ($errors->has('name') || $errors->has('password_confirmation')))
                <div class="bg-red-50 border border-red-150 text-red-800 text-[10px] font-bold uppercase tracking-wider p-4 rounded-2xl">
                    Ada beberapa kesalahan dalam data pendaftaran.
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf
                <div class="space-y-1.5">
                    <label for="modal_reg_name" class="block text-[9px] font-black uppercase tracking-wider text-slate-450 mb-1.5 ml-1">Nama Lengkap</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400"><i class="fa-solid fa-user text-xs"></i></span>
                        <input id="modal_reg_name" type="text" name="name" value="{{ old('name') }}" required placeholder="Nama Lengkap" class="w-full bg-slate-50 border border-slate-150 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 rounded-2xl pl-10 pr-4 py-3.5 text-xs font-bold text-slate-800 placeholder-slate-400 outline-none transition-all">
                    </div>
                    @if ($errors->has('name'))
                        <span class="text-[9px] font-bold uppercase text-red-500">{{ $errors->first('name') }}</span>
                    @endif
                </div>
                <div class="space-y-1.5">
                    <label for="modal_reg_email" class="block text-[9px] font-black uppercase tracking-wider text-slate-450 mb-1.5 ml-1">Alamat Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400"><i class="fa-solid fa-envelope text-xs"></i></span>
                        <input id="modal_reg_email" type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com" class="w-full bg-slate-50 border border-slate-150 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 rounded-2xl pl-10 pr-4 py-3.5 text-xs font-bold text-slate-800 placeholder-slate-400 outline-none transition-all">
                    </div>
                    @if ($errors->has('email') && ($errors->has('name') || $errors->has('password_confirmation')))
                        <span class="text-[9px] font-bold uppercase text-red-500">{{ $errors->first('email') }}</span>
                    @endif
                </div>
                <div class="space-y-1.5">
                    <label for="modal_reg_password" class="block text-[9px] font-black uppercase tracking-wider text-slate-450 mb-1.5 ml-1">Kata Sandi</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400"><i class="fa-solid fa-lock text-xs"></i></span>
                        <input id="modal_reg_password" type="password" name="password" required placeholder="Minimal 8 karakter" class="w-full bg-slate-50 border border-slate-150 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 rounded-2xl pl-10 pr-4 py-3.5 text-xs font-bold text-slate-800 placeholder-slate-400 outline-none transition-all">
                    </div>
                    @if ($errors->has('password'))
                        <span class="text-[9px] font-bold uppercase text-red-500">{{ $errors->first('password') }}</span>
                    @endif
                </div>
                <div class="space-y-1.5">
                    <label for="modal_reg_confirm" class="block text-[9px] font-black uppercase tracking-wider text-slate-450 mb-1.5 ml-1">Konfirmasi Sandi</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400"><i class="fa-solid fa-circle-check text-xs"></i></span>
                        <input id="modal_reg_confirm" type="password" name="password_confirmation" required placeholder="Ulangi kata sandi" class="w-full bg-slate-50 border border-slate-150 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 rounded-2xl pl-10 pr-4 py-3.5 text-xs font-bold text-slate-800 placeholder-slate-400 outline-none transition-all">
                    </div>
                </div>
                <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 active:scale-[0.99] text-white font-black text-xs uppercase tracking-widest py-4 rounded-2xl shadow-lg shadow-primary-600/10 transition duration-300">Daftar Akun <i class="fa-solid fa-user-plus ml-2"></i></button>
            </form>
            <div class="text-center pt-2">
                <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wide">Sudah punya akun?</span>
                <button type="button" @click="registerOpen = false; loginOpen = true" class="text-[10px] font-black uppercase text-primary-500 hover:text-primary-600 transition-colors ml-1">Masuk Sekarang</button>
            </div>
        </div>
    </div>

</body>
</html>
