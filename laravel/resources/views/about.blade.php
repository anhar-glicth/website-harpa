@extends('layouts.app')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-20">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
        <div class="space-y-6">
            <span class="text-xs font-black uppercase tracking-[0.2em] text-primary-600">SIAPA KAMI</span>
            <h1 class="text-3xl sm:text-5xl font-montserrat font-black uppercase tracking-tight italic text-slate-900 leading-tight">
                Misi Kemanusiaan <span class="text-primary-600">Tanpa Batas</span>
            </h1>
            <div class="w-12 h-1.5 bg-primary-500 rounded-full"></div>
            <p class="text-slate-600 leading-relaxed font-semibold text-xs sm:text-sm">
                <strong>Yayasan Harpa Indonesia Madani</strong> merupakan lembaga sekaligus Platform Crowdfunding yang mendukung berbagai Program Kemanusiaan di Luar dan Dalam Negeri. Terdaftar resmi di Kementerian Hukum dan HAM RI (SK No. AHU-0017523.AH.01.04.Tahun 2025), kami berdedikasi penuh untuk menghadirkan perubahan positif nyata di tengah masyarakat melalui program kemanusiaan, pendidikan, dan pemberdayaan umat secara amanah, cepat, dan 100% transparan.
            </p>
            <div class="bg-slate-50 border border-slate-100 p-6 rounded-3xl space-y-4">
                <h3 class="font-montserrat font-black uppercase text-xs sm:text-sm tracking-wider text-slate-800">Prinsip Utama Kami</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="flex items-center space-x-3">
                        <i class="fa-solid fa-circle-check text-primary-500 text-sm"></i>
                        <span class="text-[11px] font-bold text-slate-700 uppercase tracking-wide">100% Transparan</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <i class="fa-solid fa-circle-check text-primary-500 text-sm"></i>
                        <span class="text-[11px] font-bold text-slate-700 uppercase tracking-wide">Tepat Sasaran</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <i class="fa-solid fa-circle-check text-primary-500 text-sm"></i>
                        <span class="text-[11px] font-bold text-slate-700 uppercase tracking-wide">Laporan Terbuka</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <i class="fa-solid fa-circle-check text-primary-500 text-sm"></i>
                        <span class="text-[11px] font-bold text-slate-700 uppercase tracking-wide">Respon Cepat</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="relative rounded-3xl overflow-hidden shadow-2xl h-[450px]">
            <img 
                src="{{ $settings->about_image ?? 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=1200&h=800&fit=crop' }}" 
                class="w-full h-full object-cover" 
                alt="About us"
            >
        </div>
    </div>
</section>
@endsection
