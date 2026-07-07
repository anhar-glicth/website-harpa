@extends('layouts.app')

@section('content')
<section class="max-w-xl mx-auto px-6 py-20">
    <div class="bg-white border border-slate-100 shadow-xl rounded-3xl p-8 sm:p-10 space-y-6">
        <div class="text-center space-y-2">
            <div class="w-16 h-16 bg-amber-50 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-4 border border-amber-100 shadow-sm">
                <i class="fa-solid fa-clock-rotate-left text-2xl"></i>
            </div>
            <h1 class="text-xl sm:text-2xl font-montserrat font-black text-slate-900 uppercase tracking-tight italic">
                Menunggu <span class="text-amber-500">Pembayaran</span>
            </h1>
            <p class="text-slate-400 font-bold text-[10px] uppercase tracking-widest mt-1">ID Transaksi: #{{ $donation->id }}</p>
        </div>

        <div class="bg-slate-50 border border-slate-100 rounded-2xl p-6 space-y-4">
            <h4 class="font-montserrat font-black uppercase text-xs text-slate-800 tracking-wider">Detail Pembayaran</h4>
            
            <div class="flex justify-between items-center text-xs font-bold text-slate-500 uppercase tracking-wide">
                <span>Program</span>
                <span class="text-slate-800 text-right max-w-[200px] line-clamp-1">{{ $donation->campaign->title }}</span>
            </div>

            <div class="flex justify-between items-center text-xs font-bold text-slate-500 uppercase tracking-wide">
                <span>Metode Transfer</span>
                <span class="text-slate-800">{{ $donation->paymentMethod }}</span>
            </div>

            @if($donation->paymentVia)
                <div class="flex justify-between items-center text-xs font-bold text-slate-500 uppercase tracking-wide">
                    <span>Nomor Rekening</span>
                    <span class="text-slate-800 select-all font-mono">{{ $donation->paymentVia->account_no }}</span>
                </div>
                <div class="flex justify-between items-center text-xs font-bold text-slate-500 uppercase tracking-wide">
                    <span>Atas Nama</span>
                    <span class="text-slate-800">YAYASAN PEDULI SESAMA</span>
                </div>
            @endif

            <hr class="border-slate-200">

            <div class="flex justify-between items-center text-xs font-bold text-slate-500 uppercase tracking-wide pt-2">
                <span>Jumlah Transfer</span>
                <span class="text-base font-montserrat font-black text-primary-600">Rp {{ number_format($donation->amount, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Verification/Upload Section -->
        <div class="space-y-4">
            <div class="bg-primary-50 border border-primary-100 rounded-2xl p-6 space-y-2">
                <h4 class="font-montserrat font-black uppercase text-xs text-primary-800 tracking-wider">Langkah-langkah</h4>
                <ol class="list-decimal list-inside text-[10px] font-bold text-slate-600 uppercase tracking-wide space-y-1.5">
                    <li>Lakukan transfer sesuai nominal di atas.</li>
                    <li>Ambil foto atau tangkapan layar bukti transfer.</li>
                    <li>Unggah foto bukti transfer pada formulir di bawah ini.</li>
                    <li>Tunggu admin melakukan verifikasi donasi Anda.</li>
                </ol>
            </div>

            @if($donation->proof_image)
                <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-6 text-center space-y-3">
                    <i class="fa-solid fa-circle-check text-emerald-500 text-xl"></i>
                    <p class="text-[10px] font-black uppercase tracking-widest text-emerald-800 leading-none">Bukti transfer sudah diunggah</p>
                    <a href="{{ $donation->proof_image }}" target="_blank" class="text-[9px] font-bold text-primary-600 uppercase tracking-wider block">Lihat Bukti Foto</a>
                </div>
            @else
                <form action="{{ route('donations.upload', $donation->id) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Pilih Foto Bukti Transfer</label>
                        <input type="file" name="proof_image" required class="w-full bg-slate-50 border border-slate-100 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all">
                    </div>
                    <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-widest py-4.5 rounded-2xl shadow-lg shadow-primary-600/10 transition duration-300">
                        Unggah Bukti <i class="fa-solid fa-cloud-arrow-up ml-2"></i>
                    </button>
                </form>
            @endif
        </div>
    </div>
</section>
@endsection
