<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Amal Abadi') }} - Masuk / Daftar</title>

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
                            50: '#fff7ed',
                            100: '#ffedd5',
                            200: '#fed7aa',
                            500: '#f97316',
                            600: '#ea580c',
                            700: '#c2410c',
                        },
                        secondary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            500: '#29abe2',
                            600: '#0284c7',
                            700: '#0369a1',
                            850: '#0c4a6e',
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

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="font-sans antialiased bg-slate-900 text-slate-100 min-h-screen flex items-center justify-center p-4 overflow-hidden relative">

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
