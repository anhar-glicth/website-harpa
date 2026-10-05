@php
    // Helper fungsi Terbilang Rupiah
    if (!function_exists('terbilangRupiah')) {
        function terbilangRupiah($number) {
            $number = abs((float)$number);
            if ($number == 0) {
                return 'Nol';
            }
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

    $donorName = ($lastDonation?->donor_name) ?: ($donor?->name ?? 'Donatur');
    $amount = $lastDonation ? (float)$lastDonation->amount : 0;
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
    } else {
        $mrNo = 'D-' . str_pad($donor?->id ?? '0', 6, '0', STR_PAD_LEFT);
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
    $paymentMethod = $lastDonation?->payment_method ?? ($lastDonation?->paymentMethod ?? 'Electronic Payment');
    $isCash = in_array(strtolower($paymentMethod), ['cash', 'tunai']);
    $isElectronic = !$isCash;

    $bankName = 'MANDIRI';
    if ($lastDonation && $lastDonation->paymentVia) {
        $bankName = strtoupper($lastDonation->paymentVia->name ?? $lastDonation->paymentVia->provider ?? 'MANDIRI');
    }

    // Peruntukan / Program
    $purpose = 'Sedekah';
    if ($lastDonation && $lastDonation->campaign) {
        $purpose = $lastDonation->campaign->title;
    }

    $defaultReceiver = $lastDonation?->collector?->name ?? (Auth::check() ? Auth::user()->name : 'Staff Amal Abadi');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Money Receipt - {{ $mrNo }} - {{ $donorName }}</title>

    <!-- Favicon & App Icons -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,600;0,700;0,800;0,900;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Amiri:ital,wght@0,700;1,400&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }
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
<body x-data="{
    showCustomModal: false,
    receiptLogo: localStorage.getItem('receipt_logo') || '{{ asset('images/logo.jpeg') }}',
    receiverName: localStorage.getItem('receipt_receiver_name') || '{{ addslashes($defaultReceiver) }}',
    receiverTitle: localStorage.getItem('receipt_receiver_title') || 'RECEIVED BY',
    receiverSigType: localStorage.getItem('receipt_receiver_sig_type') || 'digital',
    receiverSigImage: localStorage.getItem('receipt_receiver_sig_image') || '',
    authorizedName: localStorage.getItem('receipt_authorized_name') || 'Djihan Ramadhan',
    authorizedTitle: localStorage.getItem('receipt_authorized_title') || 'AUTHORIZED SIGNATURE',
    authorizedSigType: localStorage.getItem('receipt_authorized_sig_type') || 'digital',
    authorizedSigImage: localStorage.getItem('receipt_authorized_sig_image') || '',
    showStamp: localStorage.getItem('receipt_show_stamp') !== 'false',
    customStampImage: localStorage.getItem('receipt_custom_stamp') || '',
    receiptDonorName: '{{ addslashes($donorName) }}',
    receiptPurpose: '{{ addslashes($purpose) }}',
    receiptBank: '{{ addslashes($bankName) }}',

    handleLogoUpload(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (event) => {
                this.receiptLogo = event.target.result;
                localStorage.setItem('receipt_logo', this.receiptLogo);
            };
            reader.readAsDataURL(file);
        }
    },
    handleReceiverSigUpload(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (event) => {
                this.receiverSigImage = event.target.result;
                this.receiverSigType = 'image';
                localStorage.setItem('receipt_receiver_sig_image', this.receiverSigImage);
                localStorage.setItem('receipt_receiver_sig_type', 'image');
            };
            reader.readAsDataURL(file);
        }
    },
    handleAuthorizedSigUpload(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (event) => {
                this.authorizedSigImage = event.target.result;
                this.authorizedSigType = 'image';
                localStorage.setItem('receipt_authorized_sig_image', this.authorizedSigImage);
                localStorage.setItem('receipt_authorized_sig_type', 'image');
            };
            reader.readAsDataURL(file);
        }
    },
    handleStampUpload(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (event) => {
                this.customStampImage = event.target.result;
                this.showStamp = true;
                localStorage.setItem('receipt_custom_stamp', this.customStampImage);
                localStorage.setItem('receipt_show_stamp', 'true');
            };
            reader.readAsDataURL(file);
        }
    },
    savePreferences() {
        localStorage.setItem('receipt_logo', this.receiptLogo);
        localStorage.setItem('receipt_receiver_name', this.receiverName);
        localStorage.setItem('receipt_receiver_title', this.receiverTitle);
        localStorage.setItem('receipt_receiver_sig_type', this.receiverSigType);
        localStorage.setItem('receipt_authorized_name', this.authorizedName);
        localStorage.setItem('receipt_authorized_title', this.authorizedTitle);
        localStorage.setItem('receipt_authorized_sig_type', this.authorizedSigType);
        localStorage.setItem('receipt_show_stamp', this.showStamp);
        this.showCustomModal = false;
    },
    resetToDefault() {
        this.receiptLogo = '{{ asset('images/logo.jpeg') }}';
        this.receiverName = '{{ addslashes($defaultReceiver) }}';
        this.receiverTitle = 'RECEIVED BY';
        this.receiverSigType = 'digital';
        this.receiverSigImage = '';
        this.authorizedName = 'Djihan Ramadhan';
        this.authorizedTitle = 'AUTHORIZED SIGNATURE';
        this.authorizedSigType = 'digital';
        this.authorizedSigImage = '';
        this.showStamp = true;
        this.customStampImage = '';
        this.receiptDonorName = '{{ addslashes($donorName) }}';
        this.receiptPurpose = '{{ addslashes($purpose) }}';
        this.receiptBank = '{{ addslashes($bankName) }}';
        localStorage.removeItem('receipt_logo');
        localStorage.removeItem('receipt_receiver_name');
        localStorage.removeItem('receipt_receiver_title');
        localStorage.removeItem('receipt_receiver_sig_type');
        localStorage.removeItem('receipt_receiver_sig_image');
        localStorage.removeItem('receipt_authorized_name');
        localStorage.removeItem('receipt_authorized_title');
        localStorage.removeItem('receipt_authorized_sig_type');
        localStorage.removeItem('receipt_authorized_sig_image');
        localStorage.removeItem('receipt_show_stamp');
        localStorage.removeItem('receipt_custom_stamp');
    }
}" class="p-4 sm:p-8 flex flex-col items-center justify-center min-h-screen">

    {{-- Top Action Buttons (Hide on print) --}}
    <div class="no-print mb-6 flex flex-wrap gap-3 items-center justify-center">
        <button onclick="window.print()" class="bg-[#0284c7] hover:bg-[#0369a1] text-white font-extrabold px-6 py-2.5 rounded-xl shadow-lg shadow-sky-200 text-xs flex items-center gap-2 transition-all active:scale-95">
            <i class="fa-solid fa-print"></i> Cetak Kwitansi
        </button>
        <button @click="showCustomModal = true" class="bg-amber-500 hover:bg-amber-600 text-white font-extrabold px-5 py-2.5 rounded-xl shadow-lg shadow-amber-200 text-xs flex items-center gap-2 transition-all active:scale-95">
            <i class="fa-solid fa-pen-ruler"></i> Kustomisasi Logo, TTD & Penerima
        </button>
        <button onclick="window.close()" class="bg-slate-700 hover:bg-slate-800 text-white font-bold px-5 py-2.5 rounded-xl shadow text-xs transition-all active:scale-95">
            <i class="fa-solid fa-xmark"></i> Tutup
        </button>
    </div>

    @if(!$lastDonation)
        <div class="no-print w-full max-w-[1020px] mb-5 bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded-2xl flex items-center gap-3 text-xs shadow-sm">
            <i class="fa-solid fa-circle-info text-amber-600 text-base shrink-0"></i>
            <div>
                <strong>Informasi:</strong> Donatur ini belum memiliki riwayat transaksi donasi tercatat di sistem. Kwitansi ditampilkan dengan data standar dan dapat disesuaikan melalui tombol <strong>Kustomisasi Logo, TTD & Penerima</strong>.
            </div>
        </div>
    @endif

    {{-- Printable Money Receipt Container --}}
    <div class="receipt-wrapper relative bg-white border border-slate-200 rounded-3xl shadow-2xl overflow-hidden w-full max-w-[1020px] transition-all">

        {{-- Background Logo Watermark --}}
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-[0.035] select-none z-0">
            <div class="flex flex-col items-center text-center">
                <img :src="receiptLogo" onerror="this.onerror=null; this.src='{{ asset('images/logo.png') }}';" class="w-[280px] h-auto object-contain grayscale" alt="">
                <span class="font-montserrat font-black text-4xl tracking-tight text-slate-800 mt-2">Amal Abadi</span>
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
                        <img :src="receiptLogo" onerror="this.onerror=null; this.src='{{ asset('images/logo.png') }}';" class="h-12 sm:h-14 w-auto object-contain max-w-[200px]" alt="Amal Abadi">
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
                        <span class="font-bold text-slate-900 text-sm tracking-wide" x-text="receiptDonorName">
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
                            <span class="font-extrabold text-slate-900 text-xs tracking-wider uppercase" x-text="receiptBank">
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
                        <span class="font-extrabold text-slate-900 text-sm tracking-wide" x-text="receiptPurpose">
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
                        <div class="h-14 flex items-center justify-center relative w-full">
                            <template x-if="receiverSigType === 'digital'">
                                <svg class="w-24 h-12 text-[#1e1b4b]" viewBox="0 0 100 50" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M10 38 C 20 20, 25 10, 30 25 C 35 40, 40 5, 45 35 C 50 35, 60 25, 75 28 C 85 30, 90 20, 95 35" stroke-linecap="round"/>
                                    <path d="M22 28 L 65 30" stroke-width="1.5"/>
                                </svg>
                            </template>
                            <template x-if="receiverSigType === 'image' && receiverSigImage">
                                <img :src="receiverSigImage" class="max-h-14 max-w-full object-contain" alt="TTD Penerima">
                            </template>
                            <template x-if="receiverSigType === 'blank'">
                                <div class="h-14 w-full"></div>
                            </template>
                        </div>
                        <div class="w-full border-t border-slate-400 pt-1 mt-1">
                            <span class="font-bold text-xs text-slate-900 block truncate underline" x-text="receiverName">
                                {{ $defaultReceiver }}
                            </span>
                            <span class="text-[10px] text-slate-500 font-semibold block uppercase tracking-wider" x-text="receiverTitle">
                                RECEIVED BY
                            </span>
                        </div>
                    </div>

                    {{-- Signature 2: Authorized Signature + Stamp --}}
                    <div class="flex flex-col items-center justify-end relative">
                        <!-- Official Stamp Watermark behind signature -->
                        <template x-if="showStamp">
                            <div class="absolute -top-3 right-0 w-16 h-16 pointer-events-none opacity-85 transform -rotate-12 z-20">
                                <template x-if="customStampImage">
                                    <img :src="customStampImage" class="w-full h-full object-contain" alt="Stempel">
                                </template>
                                <template x-if="!customStampImage">
                                    <svg viewBox="0 0 100 100" class="w-full h-full" fill="none">
                                        <circle cx="50" cy="50" r="46" stroke="#ea580c" stroke-width="3" stroke-dasharray="4 2"/>
                                        <circle cx="50" cy="50" r="38" stroke="#00a8cc" stroke-width="2"/>
                                        <text x="50" y="32" font-size="8" font-weight="900" fill="#ea580c" text-anchor="middle" font-family="Montserrat">AMAL ABADI</text>
                                        <text x="50" y="52" font-size="7" font-weight="bold" fill="#00a8cc" text-anchor="middle" font-family="Montserrat">VERIFIED</text>
                                        <text x="50" y="70" font-size="8" font-weight="900" fill="#ea580c" text-anchor="middle" font-family="Montserrat">YAYASAN</text>
                                    </svg>
                                </template>
                            </div>
                        </template>

                        <div class="h-14 flex items-center justify-center relative z-10 w-full">
                            <template x-if="authorizedSigType === 'digital'">
                                <svg class="w-24 h-12 text-[#1e1b4b]" viewBox="0 0 100 50" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M15 40 C 25 15, 30 8, 38 20 C 45 32, 50 12, 60 28 C 70 35, 80 18, 90 25" stroke-linecap="round"/>
                                    <path d="M10 32 L 85 24" stroke-width="1.5"/>
                                </svg>
                            </template>
                            <template x-if="authorizedSigType === 'image' && authorizedSigImage">
                                <img :src="authorizedSigImage" class="max-h-14 max-w-full object-contain" alt="TTD Pimpinan">
                            </template>
                            <template x-if="authorizedSigType === 'blank'">
                                <div class="h-14 w-full"></div>
                            </template>
                        </div>
                        <div class="w-full border-t border-slate-400 pt-1 mt-1 relative z-10">
                            <span class="font-bold text-xs text-slate-900 block truncate underline" x-text="authorizedName">
                                Djihan Ramadhan
                            </span>
                            <span class="text-[10px] text-slate-500 font-semibold block uppercase tracking-wider" x-text="authorizedTitle">
                                AUTHORIZED SIGNATURE
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

    <!-- ================= MODAL KUSTOMISASI KWITANSI (No Print) ================= -->
    <div x-show="showCustomModal" 
         x-cloak 
         class="no-print fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-sm overflow-y-auto">
        
        <div @click.away="showCustomModal = false" class="bg-white border border-slate-100 w-full max-w-2xl rounded-3xl p-6 sm:p-8 shadow-2xl relative space-y-6 my-auto max-h-[92vh] overflow-y-auto">
            
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg shadow-sm">
                        <i class="fa-solid fa-pen-ruler"></i>
                    </div>
                    <div>
                        <h3 class="text-base sm:text-lg font-black text-slate-900">Kustomisasi Kwitansi (Money Receipt)</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Sesuaikan Logo, TTD, Nama Penerima & Pimpinan</p>
                    </div>
                </div>
                <button type="button" @click="showCustomModal = false" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <div class="space-y-5">

                <!-- 1. PENGATURAN LOGO -->
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/60 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black uppercase tracking-wider text-slate-700 flex items-center gap-2">
                            <i class="fa-solid fa-image text-primary-600"></i> Logo Kwitansi
                        </span>
                        <button type="button" @click="receiptLogo = '{{ asset('images/logo.jpeg') }}'; localStorage.removeItem('receipt_logo');" class="text-[10px] font-bold text-primary-600 hover:underline">
                            Reset ke Logo Amal Abadi
                        </button>
                    </div>
                    <div class="flex flex-col sm:flex-row items-center gap-4">
                        <div class="h-16 w-36 bg-white p-2 rounded-xl border border-slate-200 flex items-center justify-center shadow-xs shrink-0">
                            <img :src="receiptLogo" onerror="this.onerror=null; this.src='{{ asset('images/logo.png') }}';" class="max-h-full max-w-full object-contain" alt="Logo">
                        </div>
                        <div class="flex-1 space-y-1.5 w-full">
                            <label class="block text-[10px] font-bold uppercase text-slate-500">Upload Logo Baru (Opsional)</label>
                            <input type="file" @change="handleLogoUpload" accept="image/*" class="w-full text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 cursor-pointer">
                            <span class="text-[9px] text-slate-400 block">Format: PNG, JPG, JPEG. Rekomendasi PNG transparan.</span>
                        </div>
                    </div>
                </div>

                <!-- 2. PENGATURAN PENERIMA (RECEIVED BY) -->
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/60 space-y-3">
                    <span class="text-xs font-black uppercase tracking-wider text-slate-700 flex items-center gap-2">
                        <i class="fa-solid fa-user-check text-sky-600"></i> Penerima (Received By)
                    </span>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-1">Nama Penerima</label>
                            <input type="text" x-model="receiverName" placeholder="Nama Staff Penerima" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-bold text-slate-800 outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-1">Label Jabatan</label>
                            <input type="text" x-model="receiverTitle" placeholder="RECEIVED BY" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-bold text-slate-800 outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">
                        </div>
                    </div>

                    <div class="pt-2">
                        <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-1.5">Model Tanda Tangan Penerima</label>
                        <div class="grid grid-cols-3 gap-2">
                            <button type="button" @click="receiverSigType = 'digital'" :class="receiverSigType === 'digital' ? 'bg-primary-600 text-white border-primary-600' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-100'" class="p-2.5 rounded-xl border text-[10px] font-black uppercase text-center transition-all">
                                <i class="fa-solid fa-signature mr-1"></i> TTD Digital
                            </button>
                            <button type="button" @click="receiverSigType = 'image'" :class="receiverSigType === 'image' ? 'bg-primary-600 text-white border-primary-600' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-100'" class="p-2.5 rounded-xl border text-[10px] font-black uppercase text-center transition-all">
                                <i class="fa-solid fa-upload mr-1"></i> Upload TTD
                            </button>
                            <button type="button" @click="receiverSigType = 'blank'" :class="receiverSigType === 'blank' ? 'bg-primary-600 text-white border-primary-600' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-100'" class="p-2.5 rounded-xl border text-[10px] font-black uppercase text-center transition-all">
                                <i class="fa-solid fa-pen-nib mr-1"></i> TTD Basah
                            </button>
                        </div>
                        <template x-if="receiverSigType === 'image'">
                            <div class="pt-2 space-y-1.5">
                                <input type="file" @change="handleReceiverSigUpload" accept="image/*" class="w-full text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100 cursor-pointer">
                                <span class="text-[9px] text-slate-400 block">Upload foto tanda tangan penerima (PNG transparan disarankan).</span>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- 3. PENGATURAN AUTHORIZED SIGNATURE (PIMPINAN / PEJABAT) -->
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/60 space-y-3">
                    <span class="text-xs font-black uppercase tracking-wider text-slate-700 flex items-center gap-2">
                        <i class="fa-solid fa-stamp text-indigo-600"></i> Pimpinan (Authorized Signature) & Stempel
                    </span>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-1">Nama Pimpinan / Pejabat</label>
                            <input type="text" x-model="authorizedName" placeholder="Djihan Ramadhan" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-bold text-slate-800 outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-1">Label Jabatan</label>
                            <input type="text" x-model="authorizedTitle" placeholder="AUTHORIZED SIGNATURE" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-bold text-slate-800 outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">
                        </div>
                    </div>

                    <div class="pt-2">
                        <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-1.5">Model Tanda Tangan Pimpinan</label>
                        <div class="grid grid-cols-3 gap-2">
                            <button type="button" @click="authorizedSigType = 'digital'" :class="authorizedSigType === 'digital' ? 'bg-primary-600 text-white border-primary-600' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-100'" class="p-2.5 rounded-xl border text-[10px] font-black uppercase text-center transition-all">
                                <i class="fa-solid fa-signature mr-1"></i> TTD Digital
                            </button>
                            <button type="button" @click="authorizedSigType = 'image'" :class="authorizedSigType === 'image' ? 'bg-primary-600 text-white border-primary-600' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-100'" class="p-2.5 rounded-xl border text-[10px] font-black uppercase text-center transition-all">
                                <i class="fa-solid fa-upload mr-1"></i> Upload TTD
                            </button>
                            <button type="button" @click="authorizedSigType = 'blank'" :class="authorizedSigType === 'blank' ? 'bg-primary-600 text-white border-primary-600' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-100'" class="p-2.5 rounded-xl border text-[10px] font-black uppercase text-center transition-all">
                                <i class="fa-solid fa-pen-nib mr-1"></i> TTD Basah
                            </button>
                        </div>
                        <template x-if="authorizedSigType === 'image'">
                            <div class="pt-2 space-y-1.5">
                                <input type="file" @change="handleAuthorizedSigUpload" accept="image/*" class="w-full text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100 cursor-pointer">
                                <span class="text-[9px] text-slate-400 block">Upload foto tanda tangan pimpinan (PNG transparan disarankan).</span>
                            </div>
                        </template>
                    </div>

                    <!-- Stempel Settings -->
                    <div class="pt-2 border-t border-slate-200/80 space-y-2">
                        <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" x-model="showStamp" class="rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                            <span class="text-xs font-bold text-slate-700">Tampilkan Stempel Resmi Yayasan</span>
                        </label>
                        <template x-if="showStamp">
                            <div class="pt-1 space-y-1.5">
                                <label class="block text-[10px] font-bold uppercase text-slate-500">Upload Stempel Sendiri (Opsional, kosongkan untuk stempel digital bawaan)</label>
                                <input type="file" @change="handleStampUpload" accept="image/*" class="w-full text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                                <template x-if="customStampImage">
                                    <button type="button" @click="customStampImage = ''; localStorage.removeItem('receipt_custom_stamp');" class="text-[10px] font-bold text-rose-600 hover:underline">
                                        Hapus stempel custom (Gunakan stempel digital standar)
                                    </button>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- 4. EDIT KETERANGAN CEPAT -->
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/60 space-y-3">
                    <span class="text-xs font-black uppercase tracking-wider text-slate-700 flex items-center gap-2">
                        <i class="fa-solid fa-file-pen text-emerald-600"></i> Koreksi Teks Kwitansi (Opsional)
                    </span>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-1">Nama Donatur</label>
                            <input type="text" x-model="receiptDonorName" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-bold text-slate-800 outline-none focus:border-primary-500">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-1">Bank</label>
                            <input type="text" x-model="receiptBank" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-bold text-slate-800 outline-none focus:border-primary-500">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-1">Peruntukan Donasi (For the purpose)</label>
                            <input type="text" x-model="receiptPurpose" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-bold text-slate-800 outline-none focus:border-primary-500">
                        </div>
                    </div>
                </div>

            </div>

            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-4 border-t border-slate-100">
                <button type="button" @click="resetToDefault()" class="text-xs font-bold text-slate-500 hover:text-rose-600 transition-colors">
                    <i class="fa-solid fa-rotate-left mr-1"></i> Reset ke Standar Bawaan
                </button>
                <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                    <button type="button" @click="showCustomModal = false" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition-all">
                        Tutup
                    </button>
                    <button type="button" @click="savePreferences()" class="px-5 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-700 text-white text-xs font-black uppercase tracking-wider shadow-lg shadow-primary-500/20 transition-all">
                        <i class="fa-solid fa-check mr-1.5"></i> Terapkan ke Kwitansi
                    </button>
                </div>
            </div>

        </div>
    </div>

</body>
</html>
