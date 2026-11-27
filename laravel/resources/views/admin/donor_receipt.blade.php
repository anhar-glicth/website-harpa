@php
    // Helper fungsi Terbilang Rupiah
    if (!function_exists('terbilangPenyebut')) {
        function terbilangPenyebut($nilai) {
            $nilai = abs($nilai);
            $huruf = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];
            $temp = "";
            if ($nilai < 12) {
                $temp = " " . $huruf[(int)$nilai];
            } else if ($nilai < 20) {
                $temp = terbilangPenyebut($nilai - 10) . " Belas";
            } else if ($nilai < 100) {
                $temp = terbilangPenyebut(intval($nilai / 10)) . " Puluh" . terbilangPenyebut($nilai % 10);
            } else if ($nilai < 200) {
                $temp = " Seratus" . terbilangPenyebut($nilai - 100);
            } else if ($nilai < 1000) {
                $temp = terbilangPenyebut(intval($nilai / 100)) . " Ratus" . terbilangPenyebut($nilai % 100);
            } else if ($nilai < 2000) {
                $temp = " Seribu" . terbilangPenyebut($nilai - 1000);
            } else if ($nilai < 1000000) {
                $temp = terbilangPenyebut(intval($nilai / 1000)) . " Ribu" . terbilangPenyebut($nilai % 1000);
            } else if ($nilai < 1000000000) {
                $temp = terbilangPenyebut(intval($nilai / 1000000)) . " Juta" . terbilangPenyebut($nilai % 1000000);
            } else if ($nilai < 1000000000000) {
                $temp = terbilangPenyebut(intval($nilai / 1000000000)) . " Miliar" . terbilangPenyebut(fmod($nilai, 1000000000));
            } else {
                $temp = terbilangPenyebut(intval($nilai / 1000000000000)) . " Triliun" . terbilangPenyebut(fmod($nilai, 1000000000000));
            }
            return $temp;
        }
    }

    if (!function_exists('terbilangRupiah')) {
        function terbilangRupiah($number) {
            $number = (float)$number;
            if ($number == 0) {
                return 'Nol';
            }
            return trim(preg_replace('/\s+/', ' ', terbilangPenyebut($number)));
        }
    }

    $donorName = ($lastDonation?->donor_name) ?: ($donor?->name ?? 'Donatur');
    $amount = $lastDonation ? (float)$lastDonation->amount : 0;
    $amountInWords = ucwords(strtolower(terbilangRupiah($amount))) . ' Rupiah';
    
    // Format No Kwitansi
    $kwitansiNo = 'W0638';
    if ($lastDonation) {
        if (!empty($lastDonation->receipt_no)) {
            $kwitansiNo = $lastDonation->receipt_no;
        } elseif (!empty($lastDonation->transaction_id)) {
            $kwitansiNo = $lastDonation->transaction_id;
        } else {
            $kwitansiNo = 'W' . str_pad($lastDonation->id, 4, '0', STR_PAD_LEFT);
        }
    } else {
        $kwitansiNo = 'W' . str_pad($donor?->id ?? '638', 4, '0', STR_PAD_LEFT);
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

    $bankName = 'BSI';
    if ($lastDonation && $lastDonation->paymentVia) {
        $bankName = strtoupper($lastDonation->paymentVia->name ?? $lastDonation->paymentVia->provider ?? 'BSI');
    }

    // Peruntukan / Program
    $purpose = 'DARURAT! Penuhi Kebutuhan Mendesak Palestina';
    if ($lastDonation && $lastDonation->campaign) {
        $purpose = $lastDonation->campaign->title;
    }

    $defaultReceiver = 'Putri Yulia Indrawati';
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kwitansi Donasi - {{ $kwitansiNo }} - {{ $donorName }}</title>

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
    <link rel="stylesheet" href="{{ asset('css/harpa.css') }}?v={{ file_exists(public_path('css/harpa.css')) ? filemtime(public_path('css/harpa.css')) : '4' }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- html2canvas for Download Image -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

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

        .receipt-wrapper {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
            border: 1px solid #e2e8f0;
            border-radius: 28px;
            background: #ffffff;
            overflow: hidden;
            position: relative;
            width: 100%;
            max-width: 1020px;
        }

        .receipt-body {
            padding: 38px 48px 32px 48px !important;
            display: flex !important;
            flex-direction: column !important;
            gap: 20px !important;
            position: relative !important;
            z-index: 10 !important;
        }

        .receipt-disclaimer-box {
            background-color: #fefaf3 !important;
            border-top: 1px solid rgba(245, 223, 170, 0.7) !important;
            border-bottom: 1px solid rgba(245, 223, 170, 0.7) !important;
            padding: 14px 48px !important;
            text-align: center !important;
            color: #334155 !important;
            font-size: 11px !important;
            line-height: 1.5 !important;
        }

        .receipt-footer-strip {
            width: 100% !important;
            background-color: #063f5c !important;
            padding: 12px 48px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            color: #ffffff !important;
            font-size: 12px !important;
            font-family: 'Montserrat', sans-serif !important;
            font-weight: 700 !important;
            letter-spacing: 0.05em !important;
        }

        @page {
            size: A4 landscape;
            margin: 6mm;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            html, body {
                background: white !important;
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
                height: auto !important;
                min-height: 0 !important;
                display: block !important;
            }
            .receipt-wrapper {
                box-shadow: none !important;
                border: 1px solid #cbd5e1 !important;
                border-radius: 12px !important;
                margin: 0 auto !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
                max-width: 100% !important;
                width: 100% !important;
                overflow: hidden !important;
            }
            .receipt-body {
                padding: 20px 36px 14px 36px !important;
                gap: 12px !important;
            }
            .receipt-disclaimer-box {
                padding: 8px 36px !important;
                font-size: 10px !important;
            }
            .receipt-footer-strip {
                padding: 8px 36px !important;
                font-size: 11px !important;
            }
        }
    </style>
</head>
<body x-data="{
    showCustomModal: false,
    receiptLogo: localStorage.getItem('receipt_logo') || '{{ asset('images/logo.png') }}',
    receiverName: localStorage.getItem('receipt_receiver_name') || '{{ addslashes($defaultReceiver) }}',
    receiverTitle: localStorage.getItem('receipt_receiver_title') || 'Diterima oleh Finance',
    receiverSigType: localStorage.getItem('receipt_receiver_sig_type') || 'digital',
    receiverSigImage: localStorage.getItem('receipt_receiver_sig_image') || '',
    authorizedName: localStorage.getItem('receipt_authorized_name') || 'Fadli Manrulanggi',
    authorizedTitle: localStorage.getItem('receipt_authorized_title') || 'Chief Executive Officer',
    authorizedSigType: localStorage.getItem('receipt_authorized_sig_type') || 'digital',
    authorizedSigImage: localStorage.getItem('receipt_authorized_sig_image') || '',
    showStamp: localStorage.getItem('receipt_show_stamp') !== 'false',
    customStampImage: localStorage.getItem('receipt_custom_stamp') || '',
    stampSize: parseInt(localStorage.getItem('receipt_stamp_size') || '105'),
    sigSize: parseInt(localStorage.getItem('receipt_sig_size') || '75'),
    receiptDonorName: '{{ addslashes($donorName) }}',
    receiptPurpose: '{{ addslashes($purpose) }}',
    receiptBank: '{{ addslashes($bankName) }}',
    footerWebsite: localStorage.getItem('receipt_footer_website') || 'indonesiamadani.org',
    footerSocial: localStorage.getItem('receipt_footer_social') || 'Ikuti kami: @indonesiamadani',
    isDownloading: false,

    async downloadImage() {
        this.isDownloading = true;
        try {
            // Scroll to top to ensure complete coordinate alignment in html2canvas
            window.scrollTo(0, 0);

            const element = document.querySelector('.receipt-wrapper');
            const fullHeight = element.scrollHeight || element.offsetHeight;
            const fullWidth = element.scrollWidth || element.offsetWidth;

            const canvas = await html2canvas(element, {
                scale: 2,
                useCORS: true,
                allowTaint: true,
                backgroundColor: '#ffffff',
                scrollX: 0,
                scrollY: 0,
                width: fullWidth,
                height: fullHeight,
                windowWidth: document.documentElement.offsetWidth,
                windowHeight: document.documentElement.scrollHeight,
                onclone: (clonedDoc) => {
                    const clonedEl = clonedDoc.querySelector('.receipt-wrapper');
                    if (clonedEl) {
                        clonedEl.style.overflow = 'visible';
                        clonedEl.style.transform = 'none';
                        clonedEl.style.maxHeight = 'none';
                        clonedEl.style.height = 'auto';
                        clonedEl.style.margin = '0';
                    }
                }
            });
            const link = document.createElement('a');
            link.download = 'Kwitansi-' + '{{ $kwitansiNo }}' + '-' + '{{ Str::slug($donorName) }}' + '.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        } catch (err) {
            console.error('Error generating image:', err);
            alert('Gagal mengunduh gambar. Silakan gunakan tombol Cetak Kwitansi lalu pilih Simpan sebagai PDF.');
        } finally {
            this.isDownloading = false;
        }
    },

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
        localStorage.setItem('receipt_footer_website', this.footerWebsite);
        localStorage.setItem('receipt_footer_social', this.footerSocial);
        this.showCustomModal = false;
    },
    resetToDefault() {
        this.receiptLogo = '{{ asset('images/logo.png') }}';
        this.receiverName = '{{ addslashes($defaultReceiver) }}';
        this.receiverTitle = 'Diterima oleh Finance';
        this.receiverSigType = 'digital';
        this.receiverSigImage = '';
        this.authorizedName = 'Fadli Manrulanggi';
        this.authorizedTitle = 'Chief Executive Officer';
        this.authorizedSigType = 'digital';
        this.authorizedSigImage = '';
        this.showStamp = true;
        this.customStampImage = '';
        this.stampSize = 105;
        this.sigSize = 75;
        this.receiptDonorName = '{{ addslashes($donorName) }}';
        this.receiptPurpose = '{{ addslashes($purpose) }}';
        this.receiptBank = '{{ addslashes($bankName) }}';
        this.footerWebsite = 'indonesiamadani.org';
        this.footerSocial = 'Ikuti kami: @indonesiamadani';
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
        localStorage.removeItem('receipt_footer_website');
        localStorage.removeItem('receipt_footer_social');
    }
}" class="p-4 sm:p-8 flex flex-col items-center justify-center min-h-screen">

    {{-- Top Action Buttons (Hide on print) --}}
    <div class="no-print mb-6 flex flex-wrap gap-3 items-center justify-center">
        <button onclick="window.print()" class="bg-[#063f5c] hover:bg-[#04324a] text-white font-extrabold px-6 py-2.5 rounded-xl shadow-lg shadow-sky-200 text-xs flex items-center gap-2 transition-all active:scale-95">
            <i class="fa-solid fa-print"></i> Cetak Kwitansi
        </button>
        <button type="button" @click="downloadImage()" :disabled="isDownloading" class="bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold px-5 py-2.5 rounded-xl shadow-lg shadow-emerald-200 text-xs flex items-center gap-2 transition-all active:scale-95 disabled:opacity-50">
            <i class="fa-solid fa-image" x-show="!isDownloading"></i>
            <i class="fa-solid fa-spinner fa-spin" x-show="isDownloading" x-cloak></i>
            <span x-text="isDownloading ? 'Memproses Gambar...' : 'Download Gambar (PNG)'"></span>
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

        {{-- Top Accent Gold Line --}}
        <div class="h-1.5 w-full bg-[#f7ad1a]"></div>

        {{-- Top Right Decorative Corner Badge --}}
        <div style="position: absolute; top: 0; right: 0; width: 100px; height: 28px; pointer-events: none; z-index: 10;">
            <svg style="width: 100%; height: 100%;" viewBox="0 0 100 28" preserveAspectRatio="none" fill="none">
                <polygon points="25,0 100,0 100,28 0,0" fill="#063f5c"/>
                <line x1="0" y1="0" x2="100" y2="28" stroke="#f7ad1a" stroke-width="3"/>
            </svg>
        </div>

        {{-- Background Logo Watermark --}}
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-[0.035] select-none z-0">
            <div class="flex flex-col items-center text-center">
                <img :src="receiptLogo" onerror="this.onerror=null; this.src='{{ asset('images/logo.png') }}';" class="w-[280px] h-auto object-contain grayscale" alt="">
                <span class="font-montserrat font-black text-3xl tracking-tight text-slate-800 mt-2">Yayasan Harpa Indonesia Madani</span>
            </div>
        </div>

        <div class="receipt-body" style="position: relative; z-index: 10; padding: 38px 48px 32px 48px; display: flex; flex-direction: column; gap: 20px;">

            {{-- 1. HEADER ROW (3 GROUPS: LOGOS, TITLE, KWITANSI NUMBER) --}}
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 16px; width: 100%;">
                {{-- Col 1 & 2: Diselenggarakan Oleh + Program --}}
                <div style="display: flex; align-items: center; gap: 16px;">
                    <div>
                        <span style="display: block; font-size: 9px; font-weight: 700; letter-spacing: 0.05em; color: #64748b; text-transform: uppercase;">DISELENGGARAKAN OLEH</span>
                        <div style="display: flex; align-items: center; margin-top: 4px;">
                            <img :src="receiptLogo" onerror="this.onerror=null; this.src='{{ asset('images/logo.png') }}';" style="height: 42px; width: auto; max-width: 140px; object-fit: contain;" alt="Indonesia Madani">
                        </div>
                    </div>

                    <div style="width: 1px; height: 36px; background-color: #cbd5e1;"></div>

                    <div>
                        <span style="display: block; font-size: 9px; font-weight: 700; letter-spacing: 0.05em; color: #64748b; text-transform: uppercase;">PROGRAM</span>
                        <div style="display: flex; align-items: center; gap: 8px; margin-top: 4px;">
                            <svg style="width: 34px; height: 34px; flex-shrink: 0;" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="50" cy="50" r="46" fill="#0b5563" stroke="#f7ad1a" stroke-width="3"/>
                                <circle cx="50" cy="50" r="14" fill="#063f5c" stroke="#f7ad1a" stroke-width="2.5"/>
                                <g stroke="#ffffff" stroke-width="3.5" stroke-linecap="round">
                                    <path d="M50 14 V32 M50 68 V86 M14 50 H32 M68 50 H86" />
                                    <path d="M24 24 L38 38 M62 62 L76 76 M76 24 L62 38 M38 62 L24 76" stroke="#f7ad1a" stroke-width="2.5"/>
                                </g>
                            </svg>
                            <div style="line-height: 1.15;">
                                <span style="display: block; font-family: 'Montserrat', sans-serif; font-weight: 900; font-size: 13px; color: #063f5c;">Harapan</span>
                                <span style="display: block; font-family: 'Montserrat', sans-serif; font-weight: 900; font-size: 13px; color: #063f5c;">Palestina</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Center: KWITANSI DONASI --}}
                <div style="text-align: center;">
                    <h1 style="font-family: 'Montserrat', sans-serif; font-weight: 900; font-size: 24px; letter-spacing: 0.05em; text-transform: uppercase; margin: 0; line-height: 1.1;">
                        <span style="color: #063f5c;">KWITANSI</span> <span style="color: #f7ad1a;">DONASI</span>
                    </h1>
                    <p style="font-family: 'Montserrat', sans-serif; font-weight: 800; font-size: 10px; color: #334155; letter-spacing: 0.16em; text-transform: uppercase; margin-top: 3px;">
                        YAYASAN HARPA INDONESIA MADANI
                    </p>
                </div>

                {{-- Right: No Kwitansi & Tanggal Donasi --}}
                <div style="text-align: right; padding-right: 8px; margin-top: 4px;">
                    <div style="margin-bottom: 4px;">
                        <span style="display: block; font-size: 9px; font-weight: 700; letter-spacing: 0.05em; color: #64748b; text-transform: uppercase;">NO. KWITANSI</span>
                        <span style="font-family: 'Montserrat', sans-serif; font-weight: 900; font-size: 15px; color: #0f172a; letter-spacing: 0.05em;">
                            {{ $kwitansiNo }}
                        </span>
                    </div>
                    <div>
                        <span style="display: block; font-size: 9px; font-weight: 700; letter-spacing: 0.05em; color: #64748b; text-transform: uppercase;">TANGGAL DONASI</span>
                        <span style="font-family: 'Montserrat', sans-serif; font-weight: 800; font-size: 12px; color: #0f172a;">
                            {{ $tglDonasi }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- 2. RECEIPT FORM FIELDS --}}
            <div style="display: flex; flex-direction: column; gap: 14px; padding-top: 4px;">
                
                {{-- Diterima dengan syukur dari Row --}}
                <div style="display: flex; align-items: baseline; width: 100%; gap: 12px;">
                    <span style="width: 175px; min-width: 175px; flex-shrink: 0;" class="font-medium text-xs text-slate-600">
                        Diterima dengan syukur dari
                    </span>
                    <div style="flex-grow: 1; border-bottom: 2px dotted #f7ad1a; padding-bottom: 2px; padding-left: 4px;">
                        <span class="font-black text-slate-900 text-base tracking-wide" x-text="receiptDonorName">
                            {{ $donorName }}
                        </span>
                    </div>
                </div>

                {{-- Terbilang Row --}}
                <div style="display: flex; align-items: baseline; width: 100%; gap: 12px;">
                    <span style="width: 175px; min-width: 175px; flex-shrink: 0;" class="font-medium text-xs text-slate-600">
                        Terbilang
                    </span>
                    <div style="flex-grow: 1; border-bottom: 2px dotted #f7ad1a; padding-bottom: 2px; padding-left: 4px;">
                        <span class="font-bold text-slate-900 text-sm tracking-wide italic">
                            {{ $amountInWords }}
                        </span>
                    </div>
                </div>

                {{-- Metode Pembayaran Checkboxes & Bank Row (All on one row) --}}
                <div style="display: flex; align-items: center; justify-content: space-between; width: 100%; gap: 16px;">
                    {{-- Left: Label + Checkboxes --}}
                    <div style="display: flex; align-items: center; gap: 24px;">
                        <span style="width: 175px; min-width: 175px; flex-shrink: 0;" class="font-medium text-xs text-slate-600">
                            Metode pembayaran
                        </span>
                        <div style="display: flex; align-items: center; gap: 20px; white-space: nowrap;">
                            {{-- Tunai --}}
                            <label style="display: inline-flex; align-items: center; gap: 8px; cursor: default; user-select: none;">
                                <span style="width: 16px; height: 16px; border-radius: 3px; border: 2px solid #f7ad1a; display: flex; align-items: center; justify-content: center; background-color: {{ $isCash ? '#f7ad1a' : '#ffffff' }}; color: white;">
                                    @if($isCash)
                                        <i class="fa-solid fa-check" style="font-size: 10px; line-height: 1;"></i>
                                    @endif
                                </span>
                                <span class="font-medium text-xs text-slate-700">Tunai</span>
                            </label>

                            {{-- Transfer / non-tunai --}}
                            <label style="display: inline-flex; align-items: center; gap: 8px; cursor: default; user-select: none;">
                                <span style="width: 16px; height: 16px; border-radius: 3px; border: 2px solid #f7ad1a; display: flex; align-items: center; justify-content: center; background-color: {{ $isElectronic ? '#f7ad1a' : '#ffffff' }}; color: white;">
                                    @if($isElectronic)
                                        <i class="fa-solid fa-check" style="font-size: 10px; line-height: 1;"></i>
                                    @endif
                                </span>
                                <span class="font-medium text-xs text-slate-700">Transfer / non-tunai</span>
                            </label>
                        </div>
                    </div>

                    {{-- Right: Bank on the right --}}
                    <div style="display: flex; align-items: baseline; gap: 8px; min-width: 220px; flex-grow: 1; max-width: 320px;">
                        <span class="font-medium text-xs text-slate-600" style="flex-shrink: 0;">Bank</span>
                        <div style="flex-grow: 1; border-bottom: 2px dotted #f7ad1a; padding-bottom: 2px; padding-left: 6px;">
                            <span class="font-black text-slate-900 text-xs tracking-wider uppercase" x-text="receiptBank">
                                {{ $bankName }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Untuk program Row --}}
                <div style="display: flex; align-items: baseline; width: 100%; gap: 12px;">
                    <span style="width: 175px; min-width: 175px; flex-shrink: 0;" class="font-medium text-xs text-slate-600">
                        Untuk program
                    </span>
                    <div style="flex-grow: 1; border-bottom: 2px dotted #f7ad1a; padding-bottom: 2px; padding-left: 4px;">
                        <span class="font-black text-slate-900 text-sm tracking-wide" x-text="receiptPurpose">
                            {{ $purpose }}
                        </span>
                    </div>
                </div>

            </div>

            {{-- 3. ARABIC DOA CARD (FULL WIDTH) --}}
            <div class="w-full bg-[#fef9ee] border border-[#f5dfaa] rounded-2xl py-3.5 px-6 text-center space-y-1.5 shadow-xs">
                <p class="font-amiri font-bold text-lg text-[#063f5c] leading-relaxed dir-rtl" dir="rtl">
                    أَجَرَكَ اللهُ فِيمَا أَعْطَيْتَ، وَبَارَكَ لَكَ فِيمَا أَبْقَيْتَ وَجَعَلَهُ لَكَ طَهُورًا
                </p>
                <p class="text-[11px] text-slate-600 leading-normal font-medium max-w-2xl mx-auto">
                    Semoga Allah memberi pahala atas apa yang engkau berikan, memberkahi harta yang masih engkau sisakan, dan menjadikannya pembersih dosa bagimu.
                </p>
            </div>

            {{-- 4. BOTTOM SECTION: IDR BADGE & SIGNATURES --}}
            <div style="display: flex; align-items: flex-end; justify-content: space-between; gap: 24px; padding-top: 4px; width: 100%;">
                
                {{-- Left: IDR Box / Amount Badge --}}
                <div style="flex-shrink: 0;">
                    <div class="bg-[#063f5c] text-white px-6 py-3 rounded-2xl shadow-md border-l-4 border-[#f7ad1a] min-w-[220px]">
                        <span class="text-[#f7ad1a] font-montserrat font-black text-[10px] tracking-widest uppercase block">IDR</span>
                        <span class="font-montserrat font-black text-2xl tracking-tight text-white block mt-0.5">
                            Rp {{ number_format($amount, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                {{-- Right: Dual Signature Block --}}
                <div style="display: flex; align-items: flex-end; gap: 32px; min-width: 440px; justify-content: flex-end;">
                    
                    {{-- Signature 1: Finance --}}
                    <div style="width: 190px; text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: flex-end;">
                        <div class="flex items-center justify-center relative w-full" :style="`height: ${sigSize}px;`">
                            <template x-if="receiverSigType === 'digital'">
                                <svg :style="`height: ${Math.round(sigSize * 0.85)}px; width: ${Math.round(sigSize * 1.8)}px;`" class="text-[#063f5c]" viewBox="0 0 100 50" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M10 38 C 20 20, 25 10, 30 25 C 35 40, 40 5, 45 35 C 50 35, 60 25, 75 28 C 85 30, 90 20, 95 35" stroke-linecap="round"/>
                                    <path d="M22 28 L 65 30" stroke-width="1.5"/>
                                </svg>
                            </template>
                            <template x-if="receiverSigType === 'image' && receiverSigImage">
                                <img :src="receiverSigImage" :style="`max-height: ${sigSize}px;`" class="max-w-full object-contain" alt="TTD Finance">
                            </template>
                            <template x-if="receiverSigType === 'blank'">
                                <div :style="`height: ${sigSize}px;`" class="w-full"></div>
                            </template>
                        </div>
                        <div class="w-full border-t border-slate-400 pt-1 mt-1">
                            <span class="font-bold text-xs text-slate-900 block truncate" x-text="receiverName">
                                {{ $defaultReceiver }}
                            </span>
                            <span class="text-[10px] text-slate-500 font-semibold block uppercase tracking-wider" x-text="receiverTitle">
                                Diterima oleh Finance
                            </span>
                        </div>
                    </div>

                    {{-- Signature 2: CEO + Stamp --}}
                    <div style="width: 190px; text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: flex-end; position: relative;">
                        <!-- Official Stamp Watermark overlapping signature -->
                        <template x-if="showStamp">
                            <div style="position: absolute; right: 6px; top: -14px; pointer-events: none; opacity: 0.88; transform: rotate(-5deg); z-index: 20;"
                                 :style="`width: ${stampSize}px; height: ${stampSize}px;`">
                                <template x-if="customStampImage">
                                    <img :src="customStampImage" class="w-full h-full object-contain" alt="Stempel">
                                </template>
                                <template x-if="!customStampImage">
                                    <svg viewBox="0 0 160 110" class="w-full h-full" fill="none">
                                        <g opacity="0.88">
                                            <circle cx="56" cy="30" r="14" fill="#f7ad1a"/>
                                            <circle cx="104" cy="30" r="14" fill="#063f5c"/>
                                            <path d="M42 54 C42 42, 70 42, 70 54 V78 H42 Z" fill="#f7ad1a"/>
                                            <path d="M90 54 C90 42, 118 42, 118 54 V78 H90 Z" fill="#063f5c"/>
                                            <text x="80" y="93" font-size="12" font-weight="900" letter-spacing="1" fill="#f7ad1a" text-anchor="middle" font-family="Montserrat">INDONESIA</text>
                                            <text x="80" y="106" font-size="12" font-weight="900" letter-spacing="2" fill="#063f5c" text-anchor="middle" font-family="Montserrat">MADANI</text>
                                        </g>
                                    </svg>
                                </template>
                            </div>
                        </template>

                        <div class="flex items-center justify-center relative z-10 w-full" :style="`height: ${sigSize}px;`">
                            <template x-if="authorizedSigType === 'digital'">
                                <svg :style="`height: ${Math.round(sigSize * 0.85)}px; width: ${Math.round(sigSize * 1.8)}px;`" class="text-[#063f5c]" viewBox="0 0 100 50" fill="none" stroke="currentColor" stroke-width="2">
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
                            <span class="font-bold text-xs text-slate-900 block truncate" x-text="authorizedName">
                                Fadli Manrulanggi
                            </span>
                            <span class="text-[10px] text-slate-500 font-semibold block uppercase tracking-wider" x-text="authorizedTitle">
                                Chief Executive Officer
                            </span>
                        </div>
                    </div>

                </div>

            </div>

        </div>

        {{-- 5. LEGAL & DISCLAIMER BOX --}}
        <div class="receipt-disclaimer-box" style="background-color: #fefaf3; border-top: 1px solid rgba(245, 223, 170, 0.7); border-bottom: 1px solid rgba(245, 223, 170, 0.7); padding: 14px 48px; text-align: center; color: #334155; font-size: 11px; line-height: 1.5;">
            <p class="font-bold text-slate-800">
                Harapan Palestina adalah program kemanusiaan Yayasan Harpa Indonesia Madani — HARPA singkatan dari HARapan PAlestina.
            </p>
            <p class="font-semibold text-slate-700">
                <span class="text-rose-600 font-black">Disclaimer*</span> Kami tidak menerima donasi dari hasil korupsi, penjualan narkoba, dan penjualan minuman keras.
            </p>
            <p class="text-[10px] text-slate-500 font-medium">
                Kwitansi ini diterbitkan secara elektronik oleh Yayasan Harpa Indonesia Madani dan sah tanpa tanda tangan basah. Simpan atau bagikan tautan halaman ini sebagai bukti donasi Anda.
            </p>
        </div>

        {{-- 6. BOTTOM BANNER (Full Width Bar) --}}
        <div class="receipt-footer-strip" style="width: 100%; background-color: #063f5c; padding: 12px 48px; display: flex; align-items: center; justify-content: space-between; color: #ffffff; font-size: 12px; font-family: 'Montserrat', sans-serif; font-weight: 700; letter-spacing: 0.05em;">
            <div class="flex items-center gap-2">
                <span x-text="footerWebsite">indonesiamadani.org</span>
            </div>
            <div class="flex items-center gap-2">
                <span x-text="footerSocial">Ikuti kami: @indonesiamadani</span>
            </div>
        </div>

    </div>

    <!-- Modal: Kustomisasi Kwitansi (No Print) -->
    <div x-show="showCustomModal" 
         x-cloak 
         @keydown.escape.window="showCustomModal = false"
         role="dialog" aria-modal="true" aria-labelledby="modal-donor-custom-title"
         class="no-print fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-slate-950/75 overflow-y-auto">
        
        <div @click.away="showCustomModal = false" class="bg-white border border-slate-200 w-full max-w-2xl rounded-3xl p-6 sm:p-8 shadow-2xl relative space-y-6 my-auto max-h-[92vh] overflow-y-auto">
            
            {{-- Modal Header --}}
            <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-800 flex items-center justify-center text-lg shadow-sm">
                        <i class="fa-solid fa-pen-ruler" aria-hidden="true"></i>
                    </div>
                    <div>
                        <h2 id="modal-donor-custom-title" class="text-base sm:text-lg font-black text-slate-900">Kustomisasi Kwitansi (Money Receipt)</h2>
                        <p class="text-xs font-bold text-slate-600 uppercase tracking-widest">Sesuaikan Logo, TTD, Stempel, &amp; Identitas Kwitansi</p>
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
                        <button type="button" @click="receiptLogo = '{{ asset('images/logo.png') }}'; localStorage.removeItem('receipt_logo');" class="text-[10px] font-bold text-sky-600 hover:underline flex items-center gap-1">
                            <i class="fa-solid fa-rotate-left"></i> Reset ke Logo Indonesia Madani
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
                            <input type="text" x-model="receiverTitle" placeholder="Diterima oleh Finance" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-bold text-slate-800 outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-500/20 transition-all">
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
                            <label for="donor_auth_name" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Nama Pimpinan / Pejabat</label>
                            <input id="donor_auth_name" type="text" x-model="authorizedName" placeholder="Fadli Manrulanggi" class="w-full min-h-[44px] bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs font-bold text-slate-800 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-colors">
                        </div>
                        <div>
                            <label for="donor_auth_title" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Label Jabatan</label>
                            <input id="donor_auth_title" type="text" x-model="authorizedTitle" placeholder="Chief Executive Officer" class="w-full min-h-[44px] bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs font-bold text-slate-800 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-colors">
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
                                <p class="text-xs text-slate-600 font-medium">Jika tidak diupload, sistem otomatis menampilkan Stempel Digital Resmi Harapan Palestina.</p>
                            </div>
                        </template>
                    </div>

                    <!-- Slider Ukuran Stempel & Tanda Tangan -->
                    <div class="pt-3 border-t border-slate-200">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="bg-white p-3.5 rounded-2xl border border-slate-200 shadow-xs space-y-2">
                                <div class="flex items-center justify-between">
                                    <label for="donor_stamp_size" class="text-xs font-black uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                                        <i class="fa-solid fa-stamp text-[#063f5c]" aria-hidden="true"></i> Ukuran Stempel
                                    </label>
                                    <span class="px-2 py-0.5 rounded-md bg-primary-50 border border-primary-200 text-[#063f5c] text-xs font-black" x-text="stampSize + ' px'"></span>
                                </div>
                                <input id="donor_stamp_size" type="range" min="65" max="140" step="5" x-model="stampSize" class="w-full accent-[#063f5c] cursor-pointer h-2 bg-slate-100 rounded-lg">
                                <div class="flex justify-between text-xs text-slate-600 font-bold">
                                    <span>Kecil (65px)</span>
                                    <span>Standar (105px)</span>
                                    <span>Besar (140px)</span>
                                </div>
                            </div>

                            <div class="bg-white p-3.5 rounded-2xl border border-slate-200 shadow-xs space-y-2">
                                <div class="flex items-center justify-between">
                                    <label for="donor_sig_size" class="text-xs font-black uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                                        <i class="fa-solid fa-signature text-sky-700" aria-hidden="true"></i> Ukuran TTD
                                    </label>
                                    <span class="text-xs font-black text-[#063f5c] tabular-nums" x-text="sigSize + ' px'"></span>
                                </div>
                                <input id="donor_sig_size" type="range" min="50" max="115" step="5" x-model="sigSize" class="w-full accent-sky-600 cursor-pointer h-2 bg-slate-100 rounded-lg">
                                <div class="flex justify-between text-xs text-slate-600 font-bold">
                                    <span>Kecil (50px)</span>
                                    <span>Standar (75px)</span>
                                    <span>Besar (115px)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. EDIT KETERANGAN CEPAT -->
                <div class="bg-slate-50/80 p-4 sm:p-5 rounded-2xl border border-slate-200 space-y-3">
                    <span class="text-xs font-black uppercase tracking-wider text-slate-700 flex items-center gap-2">
                        <i class="fa-solid fa-file-pen text-emerald-700"></i> Koreksi Teks Kwitansi (Opsional)
                    </span>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label for="receipt_donor_name" class="block text-xs font-black uppercase tracking-wider text-slate-600 mb-1">Nama Donatur</label>
                            <input id="receipt_donor_name" type="text" x-model="receiptDonorName" class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 min-h-[44px] text-xs font-bold text-slate-800 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 transition-all">
                        </div>
                        <div>
                            <label for="receipt_bank" class="block text-xs font-black uppercase tracking-wider text-slate-600 mb-1">Bank</label>
                            <input id="receipt_bank" type="text" x-model="receiptBank" class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 min-h-[44px] text-xs font-bold text-slate-800 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 transition-all">
                        </div>
                        <div class="sm:col-span-2">
                            <label for="receipt_purpose" class="block text-xs font-black uppercase tracking-wider text-slate-600 mb-1">Peruntukan Donasi (For the purpose)</label>
                            <input id="receipt_purpose" type="text" x-model="receiptPurpose" class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 min-h-[44px] text-xs font-bold text-slate-800 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 transition-all">
                        </div>
                    </div>
                </div>

                <!-- 5. PENGATURAN FOOTER / BANNER BAWAH -->
                <div class="bg-slate-50/80 p-4 sm:p-5 rounded-2xl border border-slate-200 space-y-3">
                    <span class="text-xs font-black uppercase tracking-wider text-slate-700 flex items-center gap-2">
                        <i class="fa-solid fa-globe text-[#063f5c]"></i> Teks Banner Bawah (Footer Kwitansi)
                    </span>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label for="receipt_footer_website" class="block text-xs font-black uppercase tracking-wider text-slate-600 mb-1">Website Resmi</label>
                            <input id="receipt_footer_website" type="text" x-model="footerWebsite" placeholder="indonesiamadani.org" class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 min-h-[44px] text-xs font-bold text-slate-800 focus:border-[#063f5c] focus:ring-2 focus:ring-[#063f5c]/20 transition-all">
                        </div>
                        <div>
                            <label for="receipt_footer_social" class="block text-xs font-black uppercase tracking-wider text-slate-600 mb-1">Media Sosial</label>
                            <input id="receipt_footer_social" type="text" x-model="footerSocial" placeholder="Ikuti kami: @indonesiamadani" class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 min-h-[44px] text-xs font-bold text-slate-800 focus:border-[#063f5c] focus:ring-2 focus:ring-[#063f5c]/20 transition-all">
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
                        <span class="text-white">Terapkan ke Kwitansi</span>
                    </button>
                </div>
            </div>

        </div>
    </div>

</body>
</html>
