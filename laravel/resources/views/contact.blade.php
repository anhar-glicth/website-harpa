@extends('layouts.app')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-20">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
        <!-- Contact Information -->
        <div class="space-y-8">
            <div class="space-y-3">
                <span class="text-xs font-black uppercase tracking-[0.2em] text-primary-600">HUBUNGI KAMI</span>
                <h1 class="text-3xl sm:text-5xl font-montserrat font-black uppercase tracking-tight italic text-slate-900 leading-tight">
                    Kami Senang <span class="text-primary-600">Mendengar</span> Anda
                </h1>
                <div class="w-12 h-1.5 bg-primary-500 rounded-full"></div>
                <p class="text-slate-500 leading-relaxed font-semibold text-xs sm:text-sm">
                    Punya pertanyaan mengenai program kami, cara donasi, atau ingin bermitra? Silahkan kirim pesan atau hubungi kami melalui kontak di bawah ini.
                </p>
            </div>

            <div class="space-y-6">
                <!-- Phone -->
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-primary-50 text-primary-600 rounded-2xl flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-phone text-lg"></i>
                    </div>
                    <div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block leading-none">Telepon / WhatsApp</span>
                        <a href="https://wa.me/6287748030166" target="_blank" class="text-sm font-bold text-slate-800 hover:text-primary-600 tracking-wider block mt-1 transition-colors">+62 877-4803-0166</a>
                    </div>
                </div>

                <!-- Email -->
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-primary-50 text-primary-600 rounded-2xl flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-envelope text-lg"></i>
                    </div>
                    <div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block leading-none">Alamat Email</span>
                        <a href="mailto:info@indonesiamadani.org" class="text-sm font-bold text-slate-800 hover:text-primary-600 tracking-wider block mt-1 transition-colors">info@indonesiamadani.org</a>
                    </div>
                </div>

                <!-- Website -->
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-primary-50 text-primary-600 rounded-2xl flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-globe text-lg"></i>
                    </div>
                    <div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block leading-none">Website Resmi</span>
                        <a href="https://indonesiamadani.org" target="_blank" class="text-sm font-bold text-slate-800 hover:text-primary-600 tracking-wider block mt-1 transition-colors">indonesiamadani.org</a>
                    </div>
                </div>

                <!-- Address -->
                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 bg-primary-50 text-primary-600 rounded-2xl flex items-center justify-center shrink-0 mt-0.5">
                        <i class="fa-solid fa-location-dot text-lg"></i>
                    </div>
                    <div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block leading-none">Kantor Yayasan</span>
                        <span class="text-xs font-semibold text-slate-700 leading-relaxed block mt-1">Jl. Panji Tilar Negara No. 150A, Tanjung Karang Permai, Kota Mataram, Nusa Tenggara Barat 83116</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Form Card -->
        <div class="bg-white border border-slate-100 shadow-xl rounded-3xl p-8 sm:p-10 space-y-6">
            <h3 class="font-montserrat font-black uppercase text-base text-slate-900">Kirim Pesan</h3>
            <form action="{{ route('contact') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" required class="w-full bg-slate-50 border border-slate-100 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all">
                </div>
                
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Alamat Email</label>
                    <input type="email" name="email" required class="w-full bg-slate-50 border border-slate-100 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Subjek</label>
                    <input type="text" name="subject" required class="w-full bg-slate-50 border border-slate-100 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Isi Pesan</label>
                    <textarea name="message" required rows="4" class="w-full bg-slate-50 border border-slate-100 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all"></textarea>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-widest py-4.5 rounded-2xl shadow-lg shadow-primary-600/10 hover:shadow-primary-600/20 transition-all duration-300">
                        Kirim Pesan <i class="fa-solid fa-paper-plane ml-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
