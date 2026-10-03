<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Donasi - {{ $donor->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            .print-container { border: none !important; shadow: none !important; padding: 0 !important; }
        }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #1e293b; background-color: #f8fafc; }
    </style>
</head>
<body class="p-4 md:p-10 flex flex-col items-center justify-center min-h-screen">

    {{-- Top Action Buttons --}}
    <div class="no-print mb-6 flex gap-4">
        <button onclick="window.print()" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-6 py-2.5 rounded-xl shadow-md text-xs flex items-center gap-2">
            <i class="fa-solid fa-print"></i> Cetak Bukti Donasi
        </button>
        <button onclick="window.close()" class="bg-slate-700 hover:bg-slate-800 text-white font-bold px-6 py-2.5 rounded-xl shadow-md text-xs">
            Tutup Halaman
        </button>
    </div>

    {{-- Printable Receipt Card (Presisi Screenshot 2 User) --}}
    <div class="print-container bg-white border border-slate-200 rounded-3xl p-8 sm:p-12 max-w-4xl w-full shadow-lg space-y-6">
        
        {{-- Header Logo & Title --}}
        <div class="flex items-start justify-between border-b border-slate-100 pb-6">
            <div class="space-y-1">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.jpeg') }}" 
                         onerror="this.onerror=null; this.src='{{ asset('images/logo.png') }}';" 
                         class="h-14 w-auto object-contain" 
                         alt="Logo Amal Abadi">
                </div>
                <p class="text-[10px] text-slate-500 font-semibold pt-1">www.amalabadi.org</p>
                <p class="text-[10px] text-slate-500 font-semibold">Whatsapp : +62 878-6455-6406</p>
            </div>
            <div class="text-right space-y-1">
                <h2 class="text-lg font-black text-slate-900 uppercase">Bukti Donasi</h2>
                <p class="text-xs font-mono font-bold text-slate-600">Nomor : {{ '020726' . str_pad($donor->id, 4, '0', STR_PAD_LEFT) }}</p>
            </div>
        </div>

        {{-- Foundation Header Title --}}
        <div class="text-center">
            <h3 class="text-lg font-black text-slate-900">Yayasan Amal Bakti Dunia Islam</h3>
        </div>

        {{-- Data Penerima & Data Penyetor --}}
        <div class="space-y-4 text-xs font-semibold text-slate-700">
            
            {{-- Data Penerima --}}
            <div class="space-y-1">
                <p class="font-bold text-slate-900 uppercase tracking-wider text-[11px]">Data Penerima</p>
                <div class="grid grid-cols-[140px_10px_1fr] gap-1">
                    <span class="text-slate-500">Alamat / Telepon</span><span>:</span>
                    <span>JL. Panji Tilar Negara 150A, Tanjung Karang Kota Mataram-Lombok, Nusa Tenggara Barat</span>
                </div>
                <div class="grid grid-cols-[140px_10px_1fr] gap-1">
                    <span class="text-slate-500">No. / Tgl Pengukuhan</span><span>:</span>
                    <span>Keputusan Menteri Hukum dan HAM Republik Indonesia DAFTAR YAYASAN NOMOR AHU-0019124.AH.01.12.TAHUN 2</span>
                </div>
                <div class="grid grid-cols-[140px_10px_1fr] gap-1">
                    <span class="text-slate-500">NPWP</span><span>:</span>
                    <span>84.484.691.5-911.000</span>
                </div>
            </div>

            {{-- Data Penyetor --}}
            <div class="space-y-1 pt-2">
                <p class="font-bold text-slate-900 uppercase tracking-wider text-[11px]">Data Penyetor</p>
                <div class="grid grid-cols-[140px_10px_1fr] gap-1">
                    <span class="text-slate-500">Nama</span><span>:</span>
                    <span class="font-bold text-slate-900">{{ $donor->name }}</span>
                </div>
                <div class="grid grid-cols-[140px_10px_1fr] gap-1">
                    <span class="text-slate-500">ID Donatur</span><span>:</span>
                    <span class="font-mono font-bold text-slate-900">{{ '0226' . str_pad($donor->id, 10, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="grid grid-cols-[140px_10px_1fr] gap-1">
                    <span class="text-slate-500">No HP</span><span>:</span>
                    <span class="font-mono">{{ $donor->phone ?: '-' }}</span>
                </div>
            </div>

        </div>

        {{-- Transaction Detail Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse border border-slate-300">
                <thead>
                    <tr class="bg-slate-700 text-white text-[11px] font-black uppercase text-center">
                        <th class="py-2 px-3 border border-slate-400 w-12">No</th>
                        <th class="py-2 px-3 border border-slate-400">ID Transaksi</th>
                        <th class="py-2 px-3 border border-slate-400">Tgl. Donasi</th>
                        <th class="py-2 px-3 border border-slate-400">Program</th>
                        <th class="py-2 px-3 border border-slate-400 text-right">Nominal</th>
                    </tr>
                </thead>
                <tbody class="text-xs font-semibold text-slate-800 divide-y divide-slate-200">
                    @if($lastDonation)
                        <tr>
                            <td class="py-2 px-3 border border-slate-300 text-center font-mono">1</td>
                            <td class="py-2 px-3 border border-slate-300 font-mono">{{ $lastDonation->transaction_id ?? ('350207202600' . rand(1000,9999)) }}</td>
                            <td class="py-2 px-3 border border-slate-300 font-mono text-center">{{ $lastDonation->donation_date ? $lastDonation->donation_date->format('Y-m-d H:i:s') : '2026-06-07 04:18:00' }}</td>
                            <td class="py-2 px-3 border border-slate-300">{{ $lastDonation->campaign ? $lastDonation->campaign->title : 'SEDEKAH SUBUH | Ikhtiar Meraih Keberkahan Sepanjang Hari' }}</td>
                            <td class="py-2 px-3 border border-slate-300 text-right font-bold">{{ number_format($lastDonation->amount, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="font-bold bg-slate-50">
                            <td colspan="4" class="py-2 px-3 border border-slate-300 text-left font-black uppercase">Total</td>
                            <td class="py-2 px-3 border border-slate-300 text-right font-black">{{ number_format($lastDonation->amount, 0, ',', '.') }}</td>
                        </tr>
                    @else
                        <tr>
                            <td class="py-2 px-3 border border-slate-300 text-center font-mono">1</td>
                            <td class="py-2 px-3 border border-slate-300 font-mono">3502072026003566</td>
                            <td class="py-2 px-3 border border-slate-300 font-mono text-center">2026-06-07 04:18:00</td>
                            <td class="py-2 px-3 border border-slate-300">SEDEKAH SUBUH | Ikhtiar Meraih Keberkahan Sepanjang Hari</td>
                            <td class="py-2 px-3 border border-slate-300 text-right font-bold">100.381</td>
                        </tr>
                        <tr class="font-bold bg-slate-50">
                            <td colspan="4" class="py-2 px-3 border border-slate-300 text-left font-black uppercase">Total</td>
                            <td class="py-2 px-3 border border-slate-300 text-right font-black">100.381</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        {{-- Terbilang & Catatan --}}
        <div class="space-y-3 text-xs text-slate-700">
            <div class="p-2.5 bg-slate-50 border border-slate-200 rounded-xl">
                <span class="font-bold">Terbilang : </span>
                <span class="italic font-medium">Seratus Ribu Tiga Ratus Delapan Puluh Satu Rupiah</span>
            </div>

            <div class="space-y-1 text-[10px] text-slate-500 leading-relaxed italic">
                <p>Semoga Allah SWT membalas apa saja yang telah kamu keluarkan dan Allah SWT memberikan berkah pada harta yang masih tersisa.</p>
                <p>Dana yang didonasikan melalui Amal Bakti Dunia Islam bukan bersumber dan bukan untuk tujuan pencucian uang (money laundering), termasuk terorisme maupun tindak kejahatan lainnya.</p>
            </div>
        </div>

        {{-- Signatures Area --}}
        <div class="grid grid-cols-2 text-center text-xs font-semibold text-slate-800 pt-8">
            <div class="space-y-16">
                <p class="font-bold">Diterima Oleh Lembaga</p>
                <div class="space-y-1">
                    <p class="font-black underline">Putri Yulia Indrawati</p>
                </div>
            </div>
            <div class="space-y-16">
                <p class="font-bold">Penyetor</p>
                <div class="space-y-1">
                    <p class="font-black underline">{{ $donor->name }}</p>
                </div>
            </div>
        </div>

    </div>

</body>
</html>
