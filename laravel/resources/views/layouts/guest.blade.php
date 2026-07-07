<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Peduli Sesama') }} - Masuk / Daftar</title>

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
                    colors: {
                        primary: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
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
        <div class="absolute inset-0 bg-gradient-to-br from-slate-950/90 via-emerald-950/80 to-slate-950/90"></div>
    </div>

    <!-- Inner Wrapper -->
    <div class="relative z-10 w-full max-w-5xl bg-slate-950/40 backdrop-blur-2xl border border-white/10 rounded-3xl sm:rounded-[2.5rem] shadow-2xl overflow-hidden grid grid-cols-1 md:grid-cols-12 min-h-[550px] sm:min-h-[600px] animate-in fade-in zoom-in duration-500">
        
        <!-- Left Side: Interactive Branding Card -->
        <div class="hidden md:flex md:col-span-5 bg-gradient-to-br from-emerald-600/90 to-primary-700/90 p-10 flex-col justify-between text-white relative overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_20%,rgba(255,255,255,0.15),transparent)]"></div>
            
            <!-- Logo -->
            <a href="/" class="flex items-center space-x-2 group relative z-10">
                <div class="bg-white text-primary-700 w-10 h-10 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform duration-300">
                    <i class="fa-solid fa-dove text-lg"></i>
                </div>
                <div>
                    <span class="font-montserrat font-black uppercase tracking-tighter text-sm leading-none block">
                        Peduli <span class="text-slate-100">Sesama</span>
                    </span>
                    <span class="text-[8px] uppercase font-bold tracking-widest text-emerald-200 block -mt-0.5">Platform Humanity</span>
                </div>
            </a>

            <!-- Welcome Text -->
            <div class="space-y-4 relative z-10">
                <h2 class="text-2xl font-montserrat font-black uppercase tracking-tight leading-snug">
                    Satu Langkah Kecil, Berjuta Kebahagiaan
                </h2>
                <p class="text-xs text-emerald-100/90 leading-relaxed font-semibold">
                    Mari bergabung dalam lingkaran kebaikan bersama ribuan donatur lainnya untuk mendukung keberlanjutan hidup sesama yang membutuhkan secara transparan.
                </p>
            </div>

            <!-- Footer indicator -->
            <div class="text-[9px] font-bold tracking-widest text-emerald-200/60 uppercase relative z-10">
                Yayasan Peduli Sesama &copy; 2026
            </div>
        </div>

        <!-- Right Side: Form Container -->
        <div class="col-span-1 md:col-span-7 p-8 sm:p-12 flex flex-col justify-center bg-slate-900/50">
            <!-- Mobile Brand Header (Visible only on mobile) -->
            <div class="md:hidden flex flex-col items-center mb-8">
                <div class="bg-primary-600 text-white w-12 h-12 rounded-2xl flex items-center justify-center shadow-lg mb-3">
                    <i class="fa-solid fa-dove text-xl"></i>
                </div>
                <span class="font-montserrat font-black uppercase tracking-tighter text-base text-white">
                    Peduli <span class="text-primary-500">Sesama</span>
                </span>
                <span class="text-[9px] uppercase font-bold tracking-widest text-slate-500 mt-0.5">Platform Humanity</span>
            </div>

            <div class="w-full max-w-sm mx-auto space-y-6">
                {{ $slot }}
            </div>
        </div>

    </div>

</body>
</html>
