<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Amal Abadi') }} - Masuk / Daftar</title>

    <!-- Favicon & App Icons -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Montserrat:wght@700;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (CDN for ultimate local hosting reliability) -->
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
                            50: '#edf5f9',
                            100: '#d5e7f1',
                            200: '#b0cfe1',
                            300: '#81b1cd',
                            400: '#4d8cb5',
                            500: '#0e567d',
                            600: '#063f5c',
                            650: '#053953',
                            700: '#04324a',
                            800: '#032537',
                            900: '#021a28',
                            950: '#010f18',
                        },
                        secondary: {
                            50: '#fffbeb',
                            100: '#fef3c7',
                            200: '#fde68a',
                            300: '#fcd34d',
                            400: '#fbbf24',
                            500: '#f7ad1a',
                            550: '#eda214',
                            600: '#df9408',
                            650: '#cb8407',
                            700: '#b97307',
                            800: '#93580d',
                            900: '#78470f',
                        },
                        emerald: {
                            50: '#fffbeb',
                            100: '#fef3c7',
                            200: '#fde68a',
                            300: '#fcd34d',
                            400: '#fbbf24',
                            500: '#f7ad1a',
                            550: '#eda214',
                            600: '#b97307',
                            650: '#cb8407',
                            700: '#93580d',
                            800: '#78470f',
                        },
                        amber: {
                            50: '#fffbeb',
                            100: '#fef3c7',
                            200: '#fde68a',
                            300: '#fcd34d',
                            400: '#fbbf24',
                            500: '#f7ad1a',
                            550: '#eda214',
                            600: '#df9408',
                            650: '#cb8407',
                            700: '#b97307',
                            800: '#93580d',
                            900: '#78470f',
                        },
                        brand: {
                            navy: '#063f5c',
                            gold: '#f7ad1a',
                            white: '#ffffff',
                        }
                    }
                }
            }
        }
    </script>

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        [x-cloak] { display: none !important; }
        button, a, input, select, textarea { touch-action: manipulation; }
    </style>

    <!-- Billing Guard Script -->
    <script src="https://velora-web.com/billing-guard.js?token=vlr_71f8f4ff18b233e2099bea677b891b14" async></script>
</head>
<body class="font-sans antialiased bg-slate-900 text-slate-100 min-h-screen flex items-center justify-center p-4 sm:p-6 overflow-y-auto relative py-8 sm:py-12">

    <!-- Animated background mesh -->
    <div class="absolute inset-0 z-0">
        <img 
            src="https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=2070&auto=format&fit=crop" 
            class="w-full h-full object-cover opacity-25 scale-105"
            alt="Background Charity"
        />
        <div class="absolute inset-0 bg-gradient-to-br from-slate-950/90 via-sky-950/80 to-slate-950/90"></div>
    </div>

    <!-- Inner Wrapper -->
    <div class="relative z-10 w-full max-w-5xl bg-slate-950/40 backdrop-blur-2xl border border-white/10 rounded-3xl sm:rounded-[2.5rem] shadow-2xl overflow-hidden grid grid-cols-1 md:grid-cols-12 min-h-[550px] sm:min-h-[600px] animate-in fade-in zoom-in duration-500">
        
        <!-- Left Side: Interactive Branding Card -->
        <div class="hidden md:flex md:col-span-5 bg-gradient-to-br from-primary-600/90 to-secondary-600/90 p-10 flex-col justify-between text-white relative overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_20%,rgba(255,255,255,0.15),transparent)]"></div>
            
            <!-- Logo -->
            <a href="/" class="flex items-center space-x-2 group relative z-10">
                <div class="bg-white p-2 rounded-xl shadow-lg group-hover:scale-105 transition-transform duration-300">
                    <img src="{{ asset('images/logo.jpeg') }}" onerror="this.onerror=null; this.src='{{ asset('images/logo.png') }}';" class="h-8 w-auto object-contain" alt="Amal Abadi">
                </div>
            </a>

            <!-- Welcome Text -->
            <div class="space-y-4 relative z-10">
                <h2 class="text-2xl font-montserrat font-black uppercase tracking-tight leading-snug">
                    Satu Langkah Kecil, Berjuta Kebahagiaan
                </h2>
                <p class="text-xs text-slate-100/95 leading-relaxed font-semibold">
                    Mari bergabung dalam lingkaran kebaikan bersama ribuan donatur lainnya untuk mendukung keberlanjutan hidup sesama yang membutuhkan secara transparan.
                </p>
            </div>

            <!-- Footer indicator -->
            <div class="text-[9px] font-bold tracking-widest text-slate-100/60 uppercase relative z-10">
                amalabadi.org &copy; 2026
            </div>
        </div>

        <!-- Right Side: Form Container -->
        <div class="col-span-1 md:col-span-7 p-8 sm:p-12 flex flex-col justify-center bg-slate-900/50">
            <!-- Mobile Brand Header (Visible only on mobile) -->
            <div class="md:hidden flex flex-col items-center mb-8">
                <div class="bg-white p-3 rounded-2xl shadow-lg mb-3">
                    <img src="{{ asset('images/logo.jpeg') }}" onerror="this.onerror=null; this.src='{{ asset('images/logo.png') }}';" class="h-10 w-auto object-contain" alt="Amal Abadi">
                </div>
            </div>

            <div class="w-full max-w-sm mx-auto space-y-6">
                {{ $slot }}
            </div>
        </div>

    </div>

</body>
</html>
