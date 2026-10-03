@php
    // Helper fungsi Terbilang Rupiah
    if (!function_exists('terbilangRupiah')) {
        function terbilangRupiah($number) {
            $number = abs((float)$number);
            $words = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];
            $result = "";

            if ($number < 12) {
                $result = " " . $words[(int)$number];
            } else if ($number < 20) {
                $result = terbilangRupiah($number - 10) . " Belas";
            } else if ($number < 100) {
                $result = terbilangRupiah(intval($number / 10)) . " Puluh" . terbilangRupiah($number % 10);
            } else if ($number < 200) {
                $result = " Seratus" . terbilangRupiah($number - 100);
            } else if ($number < 1000) {
                $result = terbilangRupiah(intval($number / 100)) . " Ratus" . terbilangRupiah($number % 100);
            } else if ($number < 2000) {
                $result = " Seribu" . terbilangRupiah($number - 1000);
            } else if ($number < 1000000) {
                $result = terbilangRupiah(intval($number / 1000)) . " Ribu" . terbilangRupiah($number % 1000);
            } else if ($number < 1000000000) {
                $result = terbilangRupiah(intval($number / 1000000)) . " Juta" . terbilangRupiah($number % 1000000);
            } else if ($number < 1000000000000) {
                $result = terbilangRupiah(intval($number / 1000000000)) . " Miliar" . terbilangRupiah(fmod($number, 1000000000));
            } else {
                $result = terbilangRupiah(intval($number / 1000000000000)) . " Triliun" . terbilangRupiah(fmod($number, 1000000000000));
            }

            return trim($result);
        }
    }

    $donorName = $lastDonation->donor_name ?: ($donor->name ?? 'Didi Prawiro');
    $amount = $lastDonation ? (float)$lastDonation->amount : 50000;
    $amountInWords = ucwords(strtolower(terbilangRupiah($amount))) . ' Rupiah';
    
    // Format MR. No
    $mrNo = '108808';
    if ($lastDonation) {
        if (!empty($lastDonation->receipt_no)) {
            $mrNo = $lastDonation->receipt_no;
        } elseif (!empty($lastDonation->transaction_id)) {
            $digits = preg_replace('/[^0-9]/', '', $lastDonation->transaction_id);
            $mrNo = strlen($digits) >= 6 ? substr($digits, -6) : $lastDonation->transaction_id;
        } else {
            $mrNo = str_pad($lastDonation->id, 6, '0', STR_PAD_LEFT);
        }
    }

    // Tanggal Donasi
    $tglCarbon = null;
    if ($lastDonation && $lastDonation->donation_date) {
        $tglCarbon = \Carbon\Carbon::parse($lastDonation->donation_date);
    } elseif ($lastDonation && $lastDonation->created_at) {
        $tglCarbon = \Carbon\Carbon::parse($lastDonation->created_at);
    } else {
        $tglCarbon = now();
    }
    $tglDonasi = $tglCarbon->translatedFormat('d F Y');

    // Metode Pembayaran & Bank
    $paymentMethod = $lastDonation->payment_method ?? ($lastDonation->paymentMethod ?? 'Electronic Payment');
    $isCash = in_array(strtolower($paymentMethod), ['cash', 'tunai']);
    $isElectronic = !$isCash;

    $bankName = 'MANDIRI';
    if ($lastDonation && $lastDonation->paymentVia) {
        $bankName = strtoupper($lastDonation->paymentVia->name ?? $lastDonation->paymentVia->provider ?? 'MANDIRI');
    }

    // Peruntukan / Program
    $purpose = 'Sedekah Subuh';
    if ($lastDonation && $lastDonation->campaign) {
        $purpose = $lastDonation->campaign->title;
    }
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Money Receipt - {{ $mrNo }} - {{ $donorName }}</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,600;0,700;0,800;0,900;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Amiri:ital,wght@0,700;1,400&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f1f5f9;
            color: #1e1b4b;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .font-montserrat {
            font-family: 'Montserrat', sans-serif;
        }

        .font-amiri {
            font-family: 'Amiri', serif;
        }

        /* Dot leaders style */
        .dotted-line {
            border-bottom: 2px dotted #ea580c;
            display: inline-block;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .receipt-wrapper {
                box-shadow: none !important;
                border: 1px solid #cbd5e1 !important;
                margin: 0 auto !important;
                page-break-inside: avoid;
            }
            @page {
                size: A4 landscape;
                margin: 10mm;
            }
        }
    </style>
</head>
<body class="p-4 sm:p-8 flex flex-col items-center justify-center min-h-screen">

    {{-- Top Action Buttons (Hide on print) --}}
    <div class="no-print mb-6 flex flex-wrap gap-3 items-center justify-center">
        <button onclick="window.print()" class="bg-[#0284c7] hover:bg-[#0369a1] text-white font-extrabold px-6 py-2.5 rounded-xl shadow-lg shadow-sky-200 text-xs flex items-center gap-2 transition-all">
            <i class="fa-solid fa-print"></i> Cetak Kwitansi
        </button>
        <button onclick="window.close()" class="bg-slate-700 hover:bg-slate-800 text-white font-bold px-5 py-2.5 rounded-xl shadow text-xs transition-all">
            <i class="fa-solid fa-xmark"></i> Tutup
        </button>
    </div>

    {{-- Printable Money Receipt Container --}}
    <div class="receipt-wrapper relative bg-white border border-slate-200 rounded-3xl shadow-2xl overflow-hidden w-full max-w-[1020px] transition-all">

        {{-- Background Logo Watermark --}}
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-[0.045] select-none z-0">
            <div class="flex flex-col items-center text-center">
                <svg viewBox="0 0 200 120" class="w-[360px] h-[220px]" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="70" cy="35" r="16" fill="#ea580c"/>
                    <circle cx="120" cy="35" r="16" fill="#0284c7"/>
                    <path d="M40 90C40 65 60 55 75 55C90 55 95 65 105 75C115 65 120 55 135 55C150 55 170 65 170 90" stroke="#0284c7" stroke-width="14" stroke-linecap="round"/>
                </svg>
                <span class="font-montserrat font-black text-6xl tracking-tight text-slate-800 mt-2">amalabadi.org</span>
            </div>
        </div>

        {{-- Top Right Decorative Corner Badge --}}
        <div class="absolute top-0 right-0 w-24 h-24 overflow-hidden pointer-events-none z-10">
            <div class="absolute -top-10 -right-10 w-28 h-28 rounded-3xl bg-gradient-to-br from-[#1e3a8a] via-[#0284c7] to-[#00c0f3] shadow-md transform rotate-12"></div>
        </div>

        <div class="relative z-10 p-8 sm:p-10 space-y-6">

            {{-- 1. HEADER ROW --}}
            <div class="grid grid-cols-12 items-center gap-4">
                {{-- Logo Top Left --}}
                <div class="col-span-4 flex items-center gap-3">
                    <div class="flex items-center gap-2">
                        <!-- Custom Stylized Vector Logo matching user image -->
                        <div class="w-12 h-12 flex-shrink-0 relative">
                            <svg viewBox="0 0 100 80" class="w-full h-full" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="35" cy="22" r="12" fill="#ea580c"/>
                                <circle cx="68" cy="22" r="12" fill="#00a8cc"/>
                                <path d="M18 65 C 22 45, 45 40, 52 48 C 58 40, 80 45, 85 65" stroke="#00a8cc" stroke-width="10" stroke-linecap="round"/>
                                <path d="M15 62 C 20 45, 38 42, 48 52" stroke="#ea580c" stroke-width="10" stroke-linecap="round"/>
                            </svg>
                        </div>
                        <div class="leading-none">
                            <span class="block font-montserrat font-black text-xl text-[#ea580c] tracking-tight">amal</span>
                            <span class="block font-montserrat font-black text-xl text-[#00a8cc] tracking-tight">abadi<span class="text-slate-400 font-semibold text-sm">.org</span></span>
                        </div>
                    </div>
                </div>

                {{-- Center Title: MONEY RECEIPT --}}
                <div class="col-span-5 text-center">
                    <h1 class="font-montserrat font-black text-2xl sm:text-3xl text-[#00c0f3] tracking-[0.18em] uppercase drop-shadow-sm">
                        MONEY RECEIPT
                    </h1>
                </div>

                {{-- Top Right: Date Field --}}
                <div class="col-span-3 text-right pr-6">
                    <div class="inline-flex items-baseline gap-2">
                        <span class="font-montserrat font-black text-xs text-[#312e81]">Date</span>
                        <span class="font-bold text-xs text-slate-900 border-b-2 border-dotted border-[#ea580c] pb-0.5 px-2 min-w-[130px] text-center">
                            {{ $tglDonasi }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- 2. RECEIPT FORM FIELDS --}}
            <div class="space-y-4 pt-2 text-sm">
                
                {{-- MR. No Row --}}
                <div class="flex items-baseline gap-2">
                    <span class="font-montserrat font-black text-xl sm:text-2xl text-[#312e81] tracking-tight">MR. No</span>
                    <span class="font-montserrat font-black text-lg sm:text-xl text-slate-900 tracking-wider px-2">
                        {{ $mrNo }}
                    </span>
                </div>

                {{-- Received with thanks from Row --}}
                <div class="flex items-baseline gap-2 w-full">
                    <span class="font-semibold text-xs text-[#312e81] whitespace-nowrap min-w-[165px]">
                        Received with thanks from
                    </span>
                    <div class="flex-grow border-b-2 border-dotted border-[#ea580c] pb-0.5 px-2">
                        <span class="font-bold text-slate-900 text-sm tracking-wide">
                            {{ $donorName }}
                        </span>
                    </div>
                </div>

                {{-- Amount in word Row --}}
                <div class="flex items-baseline gap-2 w-full">
                    <span class="font-semibold text-xs text-[#312e81] whitespace-nowrap min-w-[165px]">
                        Amount in word
                    </span>
                    <div class="flex-grow border-b-2 border-dotted border-[#ea580c] pb-0.5 px-2">
                        <span class="font-bold text-slate-900 text-sm tracking-wide italic">
                            {{ $amountInWords }}
                        </span>
                    </div>
                </div>

                {{-- Payment Method Checkboxes & Bank & Date Row --}}
                <div class="grid grid-cols-1 sm:grid-cols-12 items-center gap-4 pt-1">
                    
                    {{-- Checkboxes By cash / Electronic --}}
                    <div class="sm:col-span-5 flex items-center gap-6">
                        {{-- By cash --}}
                        <label class="inline-flex items-center gap-2 cursor-default select-none">
                            <span class="w-5 h-5 rounded border-2 border-[#ea580c] flex items-center justify-center bg-white">
                                @if($isCash)
                                    <i class="fa-solid fa-check text-[#ea580c] text-xs"></i>
                                @endif
                            </span>
                            <span class="font-semibold text-xs text-[#312e81]">By cash</span>
                        </label>

                        {{-- Electronic Payment --}}
                        <label class="inline-flex items-center gap-2 cursor-default select-none">
                            <span class="w-5 h-5 rounded border-2 border-[#ea580c] flex items-center justify-center bg-white shadow-sm">
                                @if($isElectronic)
                                    <i class="fa-solid fa-check text-[#ea580c] text-xs"></i>
                                @endif
                            </span>
                            <span class="font-semibold text-xs text-[#312e81]">Electronic Payment</span>
                        </label>
                    </div>

                    {{-- Bank Field --}}
                    <div class="sm:col-span-4 flex items-baseline gap-2">
                        <span class="font-semibold text-xs text-[#312e81] whitespace-nowrap">Bank</span>
                        <div class="flex-grow border-b-2 border-dotted border-[#ea580c] pb-0.5 px-2">
                            <span class="font-extrabold text-slate-900 text-xs tracking-wider uppercase">
                                {{ $bankName }}
                            </span>
                        </div>
                    </div>

                    {{-- Date Field --}}
                    <div class="sm:col-span-3 flex items-baseline gap-2">
                        <span class="font-semibold text-xs text-[#312e81] whitespace-nowrap">Date</span>
                        <div class="flex-grow border-b-2 border-dotted border-[#ea580c] pb-0.5 px-2 text-right">
                            <span class="font-bold text-slate-900 text-xs">
                                {{ $tglDonasi }}
                            </span>
                        </div>
                    </div>

                </div>

                {{-- For the purpose Row --}}
                <div class="flex items-baseline gap-2 w-full pt-1">
                    <span class="font-semibold text-xs text-[#312e81] whitespace-nowrap min-w-[165px]">
                        For the purpose
                    </span>
                    <div class="flex-grow border-b-2 border-dotted border-[#ea580c] pb-0.5 px-2">
                        <span class="font-extrabold text-slate-900 text-sm tracking-wide">
                            {{ $purpose }}
                        </span>
                    </div>
                </div>

            </div>

            {{-- 3. BOTTOM SECTION: IDR BADGE, DOA ARAB, SIGNATURES --}}
            <div class="grid grid-cols-1 md:grid-cols-12 items-end gap-6 pt-3">
                
                {{-- Left: IDR Box / Amount Badge --}}
                <div class="md:col-span-4 flex items-center gap-3">
                    <span class="font-montserrat font-black text-xl text-[#312e81] tracking-tight">IDR</span>
                    <div class="bg-gradient-to-r from-[#00c0f3] via-[#0284c7] to-[#3730a3] text-white px-6 py-2.5 rounded-2xl shadow-lg shadow-sky-100 flex items-center justify-center">
                        <span class="font-montserrat font-black text-xl sm:text-2xl tracking-wide whitespace-nowrap">
                            Rp.{{ number_format($amount, 0, ',', '.') }},-
                        </span>
                    </div>
                </div>

                {{-- Center: Doa dalam Bahasa Arab & Terjemahan --}}
                <div class="md:col-span-4 text-center space-y-1.5 px-2">
                    <p class="font-amiri font-bold text-base text-[#312e81] leading-relaxed dir-rtl" dir="rtl">
                        أَجَرَكَ اللهُ فِيمَا أَعْطَيْتَ، وَبَارَكَ لَكَ فِيمَا أَبْقَيْتَ وَجَعَلَهُ لَكَ طَهُورًا
                    </p>
                    <p class="text-[9px] text-slate-600 leading-normal italic font-medium">
                        &ldquo;Semoga Allah memberikan pahala kepadamu pada harta yang engkau berikan, dan semoga Allah memberkahimu dalam harta-harta yang masih engkau sisakan, dan semoga pula menjadikannya sebagai pembersih (dosa) bagimu.&rdquo;
                    </p>
                </div>

                {{-- Right: Signatures (Received By & Authorized Signature) --}}
                <div class="md:col-span-4 grid grid-cols-2 gap-4 text-center">
                    
                    {{-- Signature 1: Received By --}}
                    <div class="flex flex-col items-center justify-end">
                        <div class="h-14 flex items-center justify-center relative">
                            <!-- Handwritten Signature Graphic -->
                            <svg class="w-24 h-12 text-[#1e1b4b]" viewBox="0 0 100 50" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M10 38 C 20 20, 25 10, 30 25 C 35 40, 40 5, 45 35 C 50 35, 60 25, 75 28 C 85 30, 90 20, 95 35" stroke-linecap="round"/>
                                <path d="M22 28 L 65 30" stroke-width="1.5"/>
                            </svg>
                        </div>
                        <div class="w-full border-t border-slate-400 pt-1 mt-1">
                            <span class="font-bold text-xs text-slate-900 block truncate underline">
                                {{ Auth::user()->name ?? 'Wywien Fajryati' }}
                            </span>
                            <span class="text-[10px] text-slate-500 font-semibold block uppercase tracking-wider">
                                Received By
                            </span>
                        </div>
                    </div>

                    {{-- Signature 2: Authorized Signature + Stamp --}}
                    <div class="flex flex-col items-center justify-end relative">
                        <!-- Official Stamp Watermark behind signature -->
                        <div class="absolute -top-3 right-0 w-16 h-16 pointer-events-none opacity-80 transform -rotate-12">
                            <svg viewBox="0 0 100 100" class="w-full h-full" fill="none">
                                <circle cx="50" cy="50" r="46" stroke="#ea580c" stroke-width="3" stroke-dasharray="4 2"/>
                                <circle cx="50" cy="50" r="38" stroke="#00a8cc" stroke-width="2"/>
                                <text x="50" y="32" font-size="8" font-weight="900" fill="#ea580c" text-anchor="middle" font-family="Montserrat">AMAL ABADI</text>
                                <text x="50" y="52" font-size="7" font-weight="bold" fill="#00a8cc" text-anchor="middle" font-family="Montserrat">VERIFIED</text>
                                <text x="50" y="70" font-size="8" font-weight="900" fill="#ea580c" text-anchor="middle" font-family="Montserrat">YAYASAN</text>
                            </svg>
                        </div>

                        <div class="h-14 flex items-center justify-center relative z-10">
                            <!-- Handwritten Signature Graphic -->
                            <svg class="w-24 h-12 text-[#1e1b4b]" viewBox="0 0 100 50" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M15 40 C 25 15, 30 8, 38 20 C 45 32, 50 12, 60 28 C 70 35, 80 18, 90 25" stroke-linecap="round"/>
                                <path d="M10 32 L 85 24" stroke-width="1.5"/>
                            </svg>
                        </div>
                        <div class="w-full border-t border-slate-400 pt-1 mt-1 relative z-10">
                            <span class="font-bold text-xs text-slate-900 block truncate underline">
                                Djihan Ramadhan
                            </span>
                            <span class="text-[10px] text-slate-500 font-semibold block uppercase tracking-wider">
                                Authorized Signature
                            </span>
                        </div>
                    </div>

                </div>

            </div>

        </div>

        {{-- 4. BOTTOM BANNER (Full Width Bar) --}}
        <div class="w-full bg-gradient-to-r from-[#00a8cc] via-[#0284c7] to-[#312e81] py-2.5 px-8 sm:px-10 flex flex-wrap items-center justify-between text-white text-xs font-montserrat font-bold tracking-wide">
            <div class="flex items-center gap-2">
                <span>www.amalabadi.org</span>
            </div>
            <div class="flex items-center gap-2">
                <span>Find Us On: @amalabadinusantara</span>
            </div>
        </div>

    </div>

</body>
</html>
