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

    $defaultReceiver = Auth::check() ? Auth::user()->name : 'Staff Amal Abadi';
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Kwitansi Massal ({{ count($donations) }} Data) - Amal Abadi</title>

    <!-- Favicon & App Icons -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,600;0,700;0,800;0,900;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Amiri:ital,wght@0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/harpa.css') }}?v={{ file_exists(public_path('css/harpa.css')) ? filemtime(public_path('css/harpa.css')) : '4' }}">
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
            border-bottom: 2px dotted #f7ad1a;
            display: inline-block;
        }

        @media screen {
            .receipt-wrapper {
                margin-bottom: 2.5rem;
            }
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
                page-break-after: always;
                page-break-inside: avoid;
            }
            .receipt-wrapper:last-child {
                page-break-after: auto;
            }
            @page {
                size: A4 landscape;
                margin: 8mm;
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
    stampSize: parseInt(localStorage.getItem('receipt_stamp_size') || '105'),
    sigSize: parseInt(localStorage.getItem('receipt_sig_size') || '75'),

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
        localStorage.setItem('receipt_stamp_size', this.stampSize);
        localStorage.setItem('receipt_sig_size', this.sigSize);
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
        this.stampSize = 105;
        this.sigSize = 75;
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
        localStorage.removeItem('receipt_stamp_size');
        localStorage.removeItem('receipt_sig_size');
    }
}" class="p-4 sm:p-8 flex flex-col items-center min-h-screen">

    {{-- Top Action Toolbar (Hide on print) --}}
    <div class="no-print sticky top-3 z-50 mb-6 bg-white border border-slate-200 shadow-xl px-5 py-3 rounded-2xl flex flex-wrap items-center justify-between gap-3 w-full max-w-[1020px]">
        <div class="flex items-center gap-3">
            <span class="w-8 h-8 rounded-xl bg-primary-50 text-[#063f5c] flex items-center justify-center font-bold text-sm">
                <i class="fa-solid fa-file-invoice" aria-hidden="true"></i>
            </span>
            <div>
                <h2 class="text-xs sm:text-sm font-black text-slate-800 uppercase tracking-wider">
                    Cetak Kwitansi Massal
                </h2>
                <p class="text-xs font-bold text-slate-600">
                    Total: <span class="text-[#063f5c] font-black">{{ count($donations) }} Kwitansi</span> {{ request()->anyFilled(['search', 'start_date', 'end_date', 'program_id', 'payment_via_id', 'start_reg_date', 'end_reg_date', 'gender', 'segment', 'office_id']) ? '(Hasil Filter)' : '(Seluruh Data)' }}
                </p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <button type="button" onclick="window.print()" class="bg-[#063f5c] hover:bg-primary-700 text-white font-extrabold px-5 py-2.5 min-h-[44px] rounded-xl shadow-sm text-xs flex items-center gap-2 transition-colors">
                <i class="fa-solid fa-print" aria-hidden="true"></i> Cetak / Simpan Semua PDF
            </button>
            <button type="button" @click="showCustomModal = true" class="bg-[#f7ad1a] hover:bg-amber-400 text-[#063f5c] font-extrabold px-4 py-2.5 min-h-[44px] rounded-xl shadow-lg shadow-amber-200 text-xs flex items-center gap-2 transition-colors">
                <i class="fa-solid fa-pen-ruler"></i> Kustomisasi Logo & TTD
            </button>
            <button onclick="window.close()" class="bg-slate-700 hover:bg-slate-800 text-white font-bold px-4 py-2.5 rounded-xl shadow text-xs transition-all active:scale-95">
                <i class="fa-solid fa-xmark"></i> Tutup
            </button>
        </div>
    </div>

    @if($donations->isEmpty())
        <div class="w-full max-w-[1020px] bg-white border border-slate-200 rounded-3xl p-12 text-center shadow-lg my-12 space-y-3">
            <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-500 mx-auto flex items-center justify-center text-2xl">
                <i class="fa-solid fa-circle-exclamation"></i>
            </div>
            <h3 class="text-base font-black text-slate-800">Tidak Ada Kwitansi yang Ditemukan</h3>
            <p class="text-xs text-slate-500 max-w-md mx-auto">
                Tidak ada data donasi yang cocok dengan kriteria filter saat ini. Silakan ubah filter pencarian Anda di tabel.
            </p>
        </div>
    @endif

    {{-- LOOP ALL RECEIPTS --}}
    @foreach($donations as $donation)
        @php
            $donorName = $donation->donor_name ?: ($donation->user ? $donation->user->name : 'Donatur');
            $amount = (float)($donation->amount ?: 0);
            $amountInWords = ucwords(strtolower(terbilangRupiah($amount))) . ' Rupiah';

            // MR. No
            $mrNo = '108808';
            if (!empty($donation->receipt_no)) {
                $mrNo = $donation->receipt_no;
            } elseif (!empty($donation->transaction_id)) {
                $digits = preg_replace('/[^0-9]/', '', $donation->transaction_id);
                $mrNo = strlen($digits) >= 6 ? substr($digits, -6) : $donation->transaction_id;
            } else {
                $mrNo = str_pad($donation->id, 6, '0', STR_PAD_LEFT);
            }

            // Tanggal Donasi
            $tglCarbon = null;
            if ($donation->donation_date) {
                $tglCarbon = \Carbon\Carbon::parse($donation->donation_date);
            } elseif ($donation->created_at) {
                $tglCarbon = \Carbon\Carbon::parse($donation->created_at);
            } else {
                $tglCarbon = now();
            }
            $tglDonasi = $tglCarbon->translatedFormat('d F Y');

            // Metode Pembayaran & Bank
            $paymentMethod = $donation->payment_method ?? ($donation->paymentMethod ?? 'Electronic Payment');
            $isCash = in_array(strtolower($paymentMethod), ['cash', 'tunai']);
            $isElectronic = !$isCash;

            $bankName = 'MANDIRI';
            if ($donation->paymentVia) {
                $bankName = strtoupper($donation->paymentVia->name ?? $donation->paymentVia->provider ?? 'MANDIRI');
            }

            // Peruntukan / Program
            $purpose = 'Sedekah';
            if ($donation->campaign) {
                $purpose = $donation->campaign->title;
            }
        @endphp

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
                <div class="absolute -top-10 -right-10 w-28 h-28 rounded-3xl bg-[#063f5c] shadow-md transform rotate-12"></div>
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
                        <h1 class="font-montserrat font-black text-2xl sm:text-3xl text-[#063f5c] tracking-[0.18em] uppercase drop-shadow-sm">
                            MONEY RECEIPT
                        </h1>
                    </div>

                    {{-- Top Right: Date Field --}}
                    <div class="col-span-3 text-right pr-6">
                        <div class="inline-flex items-baseline gap-2">
                            <span class="font-montserrat font-black text-xs text-[#063f5c]">Date</span>
                            <span class="font-bold text-xs text-slate-900 border-b-2 border-dotted border-[#f7ad1a] pb-0.5 px-2 min-w-[130px] text-center">
                                {{ $tglDonasi }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- 2. RECEIPT FORM FIELDS --}}
                <div class="space-y-4 pt-2 text-sm">
                    
                    {{-- MR. No Row --}}
                    <div class="flex items-baseline gap-2">
                        <span class="font-montserrat font-black text-xl sm:text-2xl text-[#063f5c] tracking-tight">MR. No</span>
                        <span class="font-montserrat font-black text-lg sm:text-xl text-slate-900 tracking-wider px-2">
                            {{ $mrNo }}
                        </span>
                    </div>

                    {{-- Received with thanks from Row --}}
                    <div class="flex items-baseline gap-2 w-full">
                        <span class="font-semibold text-xs text-[#063f5c] whitespace-nowrap min-w-[165px]">
                            Received with thanks from
                        </span>
                        <div class="flex-grow border-b-2 border-dotted border-[#f7ad1a] pb-0.5 px-2">
                            <span class="font-bold text-slate-900 text-sm tracking-wide">
                                {{ $donorName }}
                            </span>
                        </div>
                    </div>

                    {{-- Amount in word Row --}}
                    <div class="flex items-baseline gap-2 w-full">
                        <span class="font-semibold text-xs text-[#063f5c] whitespace-nowrap min-w-[165px]">
                            Amount in word
                        </span>
                        <div class="flex-grow border-b-2 border-dotted border-[#f7ad1a] pb-0.5 px-2">
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
                                <span class="w-5 h-5 rounded border-2 border-[#f7ad1a] flex items-center justify-center bg-white">
                                    @if($isCash)
                                        <i class="fa-solid fa-check text-[#f7ad1a] text-xs"></i>
                                    @endif
                                </span>
                                <span class="font-semibold text-xs text-[#063f5c]">By cash</span>
                            </label>

                            {{-- Electronic Payment --}}
                            <label class="inline-flex items-center gap-2 cursor-default select-none">
                                <span class="w-5 h-5 rounded border-2 border-[#f7ad1a] flex items-center justify-center bg-white shadow-sm">
                                    @if($isElectronic)
                                        <i class="fa-solid fa-check text-[#f7ad1a] text-xs"></i>
                                    @endif
                                </span>
                                <span class="font-semibold text-xs text-[#063f5c]">Electronic Payment</span>
                            </label>
                        </div>

                        {{-- Bank Field --}}
                        <div class="sm:col-span-4 flex items-baseline gap-2">
                            <span class="font-semibold text-xs text-[#063f5c] whitespace-nowrap">Bank</span>
                            <div class="flex-grow border-b-2 border-dotted border-[#f7ad1a] pb-0.5 px-2">
                                <span class="font-extrabold text-slate-900 text-xs tracking-wider uppercase">
                                    {{ $bankName }}
                                </span>
                            </div>
                        </div>

                        {{-- Date Field --}}
                        <div class="sm:col-span-3 flex items-baseline gap-2">
                            <span class="font-semibold text-xs text-[#063f5c] whitespace-nowrap">Date</span>
                            <div class="flex-grow border-b-2 border-dotted border-[#f7ad1a] pb-0.5 px-2 text-right">
                                <span class="font-bold text-slate-900 text-xs">
                                    {{ $tglDonasi }}
                                </span>
                            </div>
                        </div>

                    </div>

                    {{-- For the purpose Row --}}
                    <div class="flex items-baseline gap-2 w-full pt-1">
                        <span class="font-semibold text-xs text-[#063f5c] whitespace-nowrap min-w-[165px]">
                            For the purpose
                        </span>
                        <div class="flex-grow border-b-2 border-dotted border-[#f7ad1a] pb-0.5 px-2">
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
                        <span class="font-montserrat font-black text-xl text-[#063f5c] tracking-tight">IDR</span>
                        <div class="bg-[#063f5c] text-white px-6 py-2.5 rounded-2xl shadow-lg shadow-sky-100 flex items-center justify-center">
                            <span class="font-montserrat font-black text-xl sm:text-2xl tracking-wide whitespace-nowrap">
                                Rp.{{ number_format($amount, 0, ',', '.') }},-
                            </span>
                        </div>
                    </div>

                    {{-- Center: Doa dalam Bahasa Arab & Terjemahan --}}
                    <div class="md:col-span-4 text-center space-y-1.5 px-2">
                        <p class="font-amiri font-bold text-base text-[#063f5c] leading-relaxed dir-rtl" dir="rtl">
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
                            <div class="flex items-center justify-center relative w-full" :style="`height: ${sigSize}px;`">
                                <template x-if="receiverSigType === 'digital'">
                                    <svg :style="`height: ${Math.round(sigSize * 0.85)}px; width: ${Math.round(sigSize * 1.8)}px;`" class="text-[#1e1b4b]" viewBox="0 0 100 50" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M10 38 C 20 20, 25 10, 30 25 C 35 40, 40 5, 45 35 C 50 35, 60 25, 75 28 C 85 30, 90 20, 95 35" stroke-linecap="round"/>
                                        <path d="M22 28 L 65 30" stroke-width="1.5"/>
                                    </svg>
                                </template>
                                <template x-if="receiverSigType === 'image' && receiverSigImage">
                                    <img :src="receiverSigImage" :style="`max-height: ${sigSize}px;`" class="max-w-full object-contain" alt="TTD Penerima">
                                </template>
                                <template x-if="receiverSigType === 'blank'">
                                    <div :style="`height: ${sigSize}px;`" class="w-full"></div>
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
                                <div class="absolute -top-7 -right-2 sm:-right-4 pointer-events-none opacity-85 transform -rotate-12 z-20"
                                     :style="`width: ${stampSize}px; height: ${stampSize}px;`">
                                    <template x-if="customStampImage">
                                        <img :src="customStampImage" class="w-full h-full object-contain" alt="Stempel">
                                    </template>
                                    <template x-if="!customStampImage">
                                        <svg viewBox="0 0 100 100" class="w-full h-full" fill="none">
                                            <circle cx="50" cy="50" r="46" stroke="#f7ad1a" stroke-width="3" stroke-dasharray="4 2"/>
                                            <circle cx="50" cy="50" r="38" stroke="#063f5c" stroke-width="2"/>
                                            <text x="50" y="32" font-size="8" font-weight="900" fill="#f7ad1a" text-anchor="middle" font-family="Montserrat">AMAL ABADI</text>
                                            <text x="50" y="52" font-size="7" font-weight="bold" fill="#063f5c" text-anchor="middle" font-family="Montserrat">VERIFIED</text>
                                            <text x="50" y="70" font-size="8" font-weight="900" fill="#f7ad1a" text-anchor="middle" font-family="Montserrat">YAYASAN</text>
                                        </svg>
                                    </template>
                                </div>
                            </template>

                            <div class="flex items-center justify-center relative z-10 w-full" :style="`height: ${sigSize}px;`">
                                <template x-if="authorizedSigType === 'digital'">
                                    <svg :style="`height: ${Math.round(sigSize * 0.85)}px; width: ${Math.round(sigSize * 1.8)}px;`" class="text-[#1e1b4b]" viewBox="0 0 100 50" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M15 40 C 25 15, 30 8, 38 20 C 45 32, 50 12, 60 28 C 70 35, 80 18, 90 25" stroke-linecap="round"/>
                                        <path d="M10 32 L 85 24" stroke-width="1.5"/>
                                    </svg>
                                </template>
                                <template x-if="authorizedSigType === 'image' && authorizedSigImage">
                                    <img :src="authorizedSigImage" :style="`max-height: ${sigSize}px;`" class="max-w-full object-contain" alt="TTD Pimpinan">
                                </template>
                                <template x-if="authorizedSigType === 'blank'">
                                    <div :style="`height: ${sigSize}px;`" class="w-full"></div>
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
            <div class="w-full bg-[#063f5c] py-2.5 px-8 sm:px-10 flex flex-wrap items-center justify-between text-white text-xs font-montserrat font-bold tracking-wide">
                <div class="flex items-center gap-2">
                    <span>www.amalabadi.org</span>
                </div>
                <div class="flex items-center gap-2">
                    <span>Find Us On: @amalabadinusantara</span>
                </div>
            </div>

        </div>
    @endforeach

    <!-- Modal: Kustomisasi Kwitansi (No Print) -->
    <div x-show="showCustomModal" 
         x-cloak 
         @keydown.escape.window="showCustomModal = false"
         role="dialog" aria-modal="true" aria-labelledby="modal-batch-custom-title"
         class="no-print fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-slate-950/75 overflow-y-auto">
        
        <div @click.away="showCustomModal = false" class="bg-white border border-slate-200 w-full max-w-2xl rounded-3xl p-6 sm:p-8 shadow-2xl relative space-y-6 my-auto max-h-[92vh] overflow-y-auto">
            
            {{-- Modal Header --}}
            <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-800 flex items-center justify-center text-lg shadow-sm">
                        <i class="fa-solid fa-pen-ruler" aria-hidden="true"></i>
                    </div>
                    <div>
                        <h2 id="modal-batch-custom-title" class="text-base sm:text-lg font-black text-slate-900">Kustomisasi Kwitansi (Money Receipt)</h2>
                        <p class="text-xs font-bold text-slate-600 uppercase tracking-widest">Perubahan ini otomatis diterapkan ke seluruh kwitansi</p>
                    </div>
                </div>
                <button type="button" @click="showCustomModal = false" aria-label="Tutup modal kustomisasi kwitansi" class="min-w-[44px] min-h-[44px] rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center transition-colors">
                    <i class="fa-solid fa-xmark text-sm" aria-hidden="true"></i>
                </button>
            </div>

            <div class="space-y-4">

                <!-- 1. PENGATURAN LOGO -->
                <div class="bg-slate-50/80 p-4 sm:p-5 rounded-2xl border border-slate-200/70 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black uppercase tracking-wider text-slate-700 flex items-center gap-2">
                            <i class="fa-solid fa-image text-sky-600"></i> Logo Kwitansi
                        </span>
                        <button type="button" @click="receiptLogo = '{{ asset('images/logo.jpeg') }}'; localStorage.removeItem('receipt_logo');" class="text-[10px] font-bold text-sky-600 hover:underline flex items-center gap-1">
                            <i class="fa-solid fa-rotate-left"></i> Reset ke Logo Amal Abadi
                        </button>
                    </div>
                    <div class="flex flex-col sm:flex-row items-center gap-4">
                        <div class="h-16 w-36 bg-white p-2 rounded-xl border border-slate-200 flex items-center justify-center shadow-xs shrink-0">
                            <img :src="receiptLogo" onerror="this.onerror=null; this.src='{{ asset('images/logo.png') }}';" class="max-h-full max-w-full object-contain" alt="Logo">
                        </div>
                        <div class="flex-1 space-y-2 w-full">
                            <div class="flex items-center gap-2 flex-wrap">
                                <label class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl border border-sky-200 bg-sky-50 hover:bg-sky-100 text-sky-700 text-xs font-bold cursor-pointer transition-all active:scale-95 shadow-xs">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                    <span>Pilih Gambar Logo Baru</span>
                                    <input type="file" @change="handleLogoUpload" accept="image/*" class="hidden">
                                </label>
                            </div>
                            <span class="text-xs text-slate-600 block font-medium">Format didukung: PNG, JPG, JPEG (Disarankan PNG transparan).</span>
                        </div>
                    </div>
                </div>

                <!-- 2. PENGATURAN PENERIMA (RECEIVED BY) -->
                <div class="bg-slate-50/80 p-4 sm:p-5 rounded-2xl border border-slate-200/70 space-y-3">
                    <span class="text-xs font-black uppercase tracking-wider text-slate-700 flex items-center gap-2">
                        <i class="fa-solid fa-user-check text-sky-600"></i> Penerima (Received By)
                    </span>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-1">Nama Penerima</label>
                            <input type="text" x-model="receiverName" placeholder="Nama Staff Penerima" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-bold text-slate-800 outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-500/20 transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-1">Label Jabatan</label>
                            <input type="text" x-model="receiverTitle" placeholder="RECEIVED BY" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-bold text-slate-800 outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-500/20 transition-all">
                        </div>
                    </div>

                    <div class="pt-2">
                        <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-1.5">Model Tanda Tangan Penerima</label>
                        <div class="grid grid-cols-3 gap-2">
                            <button type="button" @click="receiverSigType = 'digital'" 
                                    :class="receiverSigType === 'digital' ? 'bg-sky-600 text-white border-sky-600 shadow-md shadow-sky-500/20 font-black' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-100 font-bold'" 
                                    class="p-2.5 rounded-xl border text-[10px] uppercase text-center transition-all focus:outline-none">
                                <i class="fa-solid fa-signature mr-1"></i> TTD Digital
                            </button>
                            <button type="button" @click="receiverSigType = 'image'" 
                                    :class="receiverSigType === 'image' ? 'bg-sky-600 text-white border-sky-600 shadow-md shadow-sky-500/20 font-black' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-100 font-bold'" 
                                    class="p-2.5 rounded-xl border text-[10px] uppercase text-center transition-all focus:outline-none">
                                <i class="fa-solid fa-upload mr-1"></i> Upload TTD
                            </button>
                            <button type="button" @click="receiverSigType = 'blank'" 
                                    :class="receiverSigType === 'blank' ? 'bg-sky-600 text-white border-sky-600 shadow-md shadow-sky-500/20 font-black' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-100 font-bold'" 
                                    class="p-2.5 rounded-xl border text-[10px] uppercase text-center transition-all focus:outline-none">
                                <i class="fa-solid fa-pen-nib mr-1"></i> TTD Basah
                            </button>
                        </div>
                        <template x-if="receiverSigType === 'image'">
                            <div class="pt-2 p-3 bg-white rounded-xl border border-sky-100 space-y-2 mt-2">
                                <div class="flex items-center gap-3">
                                    <template x-if="receiverSigImage">
                                        <div class="h-10 w-24 border border-slate-200 rounded-lg p-1 bg-slate-50 flex items-center justify-center shrink-0">
                                            <img :src="receiverSigImage" class="max-h-full max-w-full object-contain" alt="TTD">
                                        </div>
                                    </template>
                                    <label class="inline-flex items-center gap-2 min-h-[44px] px-3 py-1.5 rounded-xl border border-sky-200 bg-sky-50 hover:bg-sky-100 text-sky-800 text-xs font-bold cursor-pointer transition-colors shadow-xs">
                                        <i class="fa-solid fa-cloud-arrow-up" aria-hidden="true"></i>
                                        <span x-text="receiverSigImage ? 'Ganti Foto TTD' : 'Pilih Foto Tanda Tangan'"></span>
                                        <input type="file" @change="handleReceiverSigUpload" accept="image/*" class="hidden">
                                    </label>
                                </div>
                                <p class="text-xs text-slate-600 font-medium">Unggah foto atau scan tanda tangan penerima (PNG transparan disarankan).</p>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- 3. PENGATURAN AUTHORIZED SIGNATURE (PIMPINAN / PEJABAT) & STEMPEL -->
                <div class="bg-slate-50 p-4 sm:p-5 rounded-2xl border border-slate-200 space-y-3">
                    <span class="text-xs font-black uppercase tracking-wider text-slate-700 flex items-center gap-2">
                        <i class="fa-solid fa-stamp text-[#063f5c]" aria-hidden="true"></i> Pimpinan (Authorized Signature) &amp; Stempel
                    </span>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label for="batch_auth_name" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Nama Pimpinan / Pejabat</label>
                            <input id="batch_auth_name" type="text" x-model="authorizedName" placeholder="Djihan Ramadhan" class="w-full min-h-[44px] bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs font-bold text-slate-800 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-colors">
                        </div>
                        <div>
                            <label for="batch_auth_title" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Label Jabatan</label>
                            <input id="batch_auth_title" type="text" x-model="authorizedTitle" placeholder="AUTHORIZED SIGNATURE" class="w-full min-h-[44px] bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs font-bold text-slate-800 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-colors">
                        </div>
                    </div>

                    <div class="pt-2">
                        <span class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Model Tanda Tangan Pimpinan</span>
                        <div class="grid grid-cols-3 gap-2">
                            <button type="button" @click="authorizedSigType = 'digital'" 
                                    :aria-pressed="authorizedSigType === 'digital'"
                                    :class="authorizedSigType === 'digital' ? 'bg-[#063f5c] text-white border-[#063f5c] shadow-sm font-black' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-100 font-bold'" 
                                    class="min-h-[44px] p-2.5 rounded-xl border text-xs uppercase text-center transition-colors">
                                <i class="fa-solid fa-signature mr-1" aria-hidden="true"></i> TTD Digital
                            </button>
                            <button type="button" @click="authorizedSigType = 'image'" 
                                    :aria-pressed="authorizedSigType === 'image'"
                                    :class="authorizedSigType === 'image' ? 'bg-[#063f5c] text-white border-[#063f5c] shadow-sm font-black' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-100 font-bold'" 
                                    class="min-h-[44px] p-2.5 rounded-xl border text-xs uppercase text-center transition-colors">
                                <i class="fa-solid fa-upload mr-1" aria-hidden="true"></i> Upload TTD
                            </button>
                            <button type="button" @click="authorizedSigType = 'blank'" 
                                    :aria-pressed="authorizedSigType === 'blank'"
                                    :class="authorizedSigType === 'blank' ? 'bg-[#063f5c] text-white border-[#063f5c] shadow-sm font-black' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-100 font-bold'" 
                                    class="min-h-[44px] p-2.5 rounded-xl border text-xs uppercase text-center transition-colors">
                                <i class="fa-solid fa-pen-nib mr-1" aria-hidden="true"></i> TTD Basah
                            </button>
                        </div>
                        <template x-if="authorizedSigType === 'image'">
                            <div class="pt-2 p-3 bg-white rounded-xl border border-primary-200 space-y-2 mt-2">
                                <div class="flex items-center gap-3">
                                    <template x-if="authorizedSigImage">
                                        <div class="h-10 w-24 border border-slate-200 rounded-lg p-1 bg-slate-50 flex items-center justify-center shrink-0">
                                            <img :src="authorizedSigImage" class="max-h-full max-w-full object-contain" alt="TTD">
                                        </div>
                                    </template>
                                    <label class="inline-flex items-center gap-2 min-h-[44px] px-3 py-1.5 rounded-xl border border-primary-200 bg-primary-50 hover:bg-primary-100 text-[#063f5c] text-xs font-bold cursor-pointer transition-colors shadow-xs">
                                        <i class="fa-solid fa-cloud-arrow-up" aria-hidden="true"></i>
                                        <span x-text="authorizedSigImage ? 'Ganti Foto TTD' : 'Pilih Foto Tanda Tangan'"></span>
                                        <input type="file" @change="handleAuthorizedSigUpload" accept="image/*" class="hidden">
                                    </label>
                                </div>
                                <p class="text-xs text-slate-600 font-medium">Unggah foto atau scan tanda tangan pimpinan (PNG transparan disarankan).</p>
                            </div>
                        </template>
                    </div>

                    <!-- Stempel Settings -->
                    <div class="pt-3 border-t border-slate-200 space-y-2">
                        <label class="inline-flex items-center gap-2.5 min-h-[44px] cursor-pointer select-none">
                            <input type="checkbox" x-model="showStamp" class="w-4 h-4 rounded border-slate-300 text-[#063f5c] focus:ring-primary-500">
                            <span class="text-xs font-bold text-slate-800">Tampilkan Stempel Resmi Yayasan</span>
                        </label>
                        <template x-if="showStamp">
                            <div class="pt-1 p-3 bg-white rounded-xl border border-primary-200 space-y-2 mt-1">
                                <div class="flex items-center gap-3 flex-wrap">
                                    <template x-if="customStampImage">
                                        <div class="h-12 w-12 border border-slate-200 rounded-lg p-1 bg-slate-50 flex items-center justify-center shrink-0">
                                            <img :src="customStampImage" class="max-h-full max-w-full object-contain" alt="Stempel Custom">
                                        </div>
                                    </template>
                                    <label class="inline-flex items-center gap-2 min-h-[44px] px-3 py-1.5 rounded-xl border border-primary-200 bg-primary-50 hover:bg-primary-100 text-[#063f5c] text-xs font-bold cursor-pointer transition-colors shadow-xs">
                                        <i class="fa-solid fa-cloud-arrow-up" aria-hidden="true"></i>
                                        <span x-text="customStampImage ? 'Ganti Stempel Custom' : 'Upload Stempel Custom (Opsional)'"></span>
                                        <input type="file" @change="handleStampUpload" accept="image/*" class="hidden">
                                    </label>
                                    <template x-if="customStampImage">
                                        <button type="button" @click="customStampImage = ''; localStorage.removeItem('receipt_custom_stamp');" class="min-h-[44px] px-2 text-xs font-bold text-rose-700 hover:text-rose-800 flex items-center gap-1 transition-colors">
                                            <i class="fa-solid fa-trash-can" aria-hidden="true"></i> Hapus Stempel Custom
                                        </button>
                                    </template>
                                </div>
                                <p class="text-xs text-slate-600 font-medium">Jika tidak diupload, sistem otomatis menampilkan Stempel Digital Resmi Amal Abadi.</p>
                            </div>
                        </template>
                    </div>

                    <!-- Slider Ukuran Stempel & Tanda Tangan -->
                    <div class="pt-3 border-t border-slate-200">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="bg-white p-3.5 rounded-2xl border border-slate-200 shadow-xs space-y-2">
                                <div class="flex items-center justify-between">
                                    <label for="batch_stamp_size" class="text-xs font-black uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                                        <i class="fa-solid fa-stamp text-[#063f5c]" aria-hidden="true"></i> Ukuran Stempel
                                    </label>
                                    <span class="px-2 py-0.5 rounded-md bg-primary-50 border border-primary-200 text-[#063f5c] text-xs font-black" x-text="stampSize + ' px'"></span>
                                </div>
                                <input id="batch_stamp_size" type="range" min="65" max="140" step="5" x-model="stampSize" class="w-full accent-[#063f5c] cursor-pointer h-2 bg-slate-100 rounded-lg">
                                <div class="flex justify-between text-xs text-slate-600 font-bold">
                                    <span>Kecil (65px)</span>
                                    <span>Standar (105px)</span>
                                    <span>Besar (140px)</span>
                                </div>
                            </div>

                            <div class="bg-white p-3.5 rounded-2xl border border-slate-200 shadow-xs space-y-2">
                                <div class="flex items-center justify-between">
                                    <label for="batch_sig_size" class="text-xs font-black uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                                        <i class="fa-solid fa-signature text-sky-700" aria-hidden="true"></i> Ukuran TTD
                                    </label>
                                    <span class="px-2 py-0.5 rounded-full bg-sky-50 border border-sky-200 text-sky-800 text-xs font-black" x-text="sigSize + ' px'"></span>
                                </div>
                                <input id="batch_sig_size" type="range" min="50" max="115" step="5" x-model="sigSize" class="w-full accent-sky-600 cursor-pointer h-2 bg-slate-100 rounded-lg">
                                <div class="flex justify-between text-xs text-slate-600 font-bold">
                                    <span>Kecil (50px)</span>
                                    <span>Standar (75px)</span>
                                    <span>Besar (115px)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Modal Footer --}}
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-5 border-t border-slate-200/80">
                <button type="button" @click="resetToDefault()" class="text-xs font-bold text-slate-500 hover:text-rose-600 transition-colors flex items-center gap-1.5">
                    <i class="fa-solid fa-rotate-left"></i>
                    <span>Reset ke Standar Bawaan</span>
                </button>
                <div class="flex items-center gap-2.5 w-full sm:w-auto justify-end">
                    <button type="button" @click="showCustomModal = false" class="px-5 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 text-xs font-bold text-slate-700 transition-all active:scale-95">
                        Tutup
                    </button>
                    <button type="button" @click="savePreferences()" 
                            style="background: linear-gradient(135deg, #063f5c 0%, #04324a 100%); color: #ffffff;"
                            class="px-6 py-2.5 rounded-xl text-white text-xs font-black uppercase tracking-wider shadow-lg shadow-sky-500/25 hover:shadow-sky-500/40 transition-all flex items-center gap-2 active:scale-95">
                        <i class="fa-solid fa-check text-sm text-white"></i>
                        <span class="text-white">Terapkan ke Seluruh Kwitansi</span>
                    </button>
                </div>
            </div>

        </div>
    </div>

</body>
</html>
