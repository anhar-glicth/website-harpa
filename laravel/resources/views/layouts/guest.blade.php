<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Harapan Palestina') }} - Masuk / Daftar</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=2">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}?v=2">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}?v=2">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}?v=2">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=2">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Montserrat:wght@700;900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/harpa.css') }}?v={{ file_exists(public_path('css/harpa.css')) ? filemtime(public_path('css/harpa.css')) : '4' }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script src="https://velora-web.com/billing-guard.js?token=vlr_71f8f4ff18b233e2099bea677b891b14" async></script>
</head>
<body class="font-sans antialiased bg-[#F8FAFC] text-slate-800 min-h-screen flex items-center justify-center p-4 sm:p-6 overflow-y-auto py-8 sm:py-12">
    <a href="#main-content" class="skip-link">Lewati ke konten utama</a>

    <main id="main-content" tabindex="-1" class="w-full max-w-5xl bg-white border border-slate-200 rounded-3xl shadow-xl overflow-hidden grid grid-cols-1 md:grid-cols-12 min-h-[540px]">
        <!-- Left Brand Panel (Navy & Gold Identity) -->
        <div class="hidden md:flex md:col-span-5 bg-[#063f5c] p-10 flex-col justify-between text-white">
            <a href="{{ route('home') }}" class="flex items-center space-x-3 group w-fit">
                <div class="bg-white p-2 rounded-xl shadow-sm">
                    <img src="{{ asset('images/logo.png') }}?v=2" onerror="this.onerror=null; this.src='{{ asset('images/logo.jpeg') }}';" width="110" height="36" class="h-9 w-auto object-contain" alt="Yayasan Harpa Indonesia Madani">
                </div>
            </a>

            <div class="space-y-4">
                <span class="inline-block text-xs font-black uppercase tracking-wider text-[#f7ad1a]">Yayasan Harpa Indonesia Madani</span>
                <h2 class="text-2xl font-montserrat font-black uppercase tracking-tight leading-snug text-white">
                    Salurkan Kebaikan Secara Transparan
                </h2>
                <div class="w-12 h-1.5 bg-[#f7ad1a] rounded-full"></div>
                <p class="text-xs text-slate-200 leading-relaxed font-medium">
                    Pantau riwayat donasi, kuitansi resmi, serta laporan penyaluran program kemanusiaan langsung dari akun Anda.
                </p>
            </div>

            <div class="text-xs font-bold tracking-wider text-slate-300 uppercase">
                harapanpalestina.com &copy; {{ date('Y') }}
            </div>
        </div>

        <!-- Right Form Container -->
        <div class="col-span-1 md:col-span-7 p-6 sm:p-12 flex flex-col justify-center bg-white">
            <div class="md:hidden flex flex-col items-center mb-8">
                <a href="{{ route('home') }}" class="bg-white border border-slate-200 p-3 rounded-2xl shadow-xs">
                    <img src="{{ asset('images/logo.png') }}?v=2" onerror="this.onerror=null; this.src='{{ asset('images/logo.jpeg') }}';" width="120" height="40" class="h-10 w-auto object-contain" alt="Yayasan Harpa Indonesia Madani">
                </a>
            </div>

            <div class="w-full max-w-sm mx-auto space-y-6">
                {{ $slot }}
            </div>
        </div>
    </main>
</body>
</html>
