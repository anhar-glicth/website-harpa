@extends('layouts.app')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-20">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
        <!-- Text details -->
        <div class="space-y-6">
            <span class="text-xs font-black uppercase tracking-[0.2em] text-primary-600">GABUNG RELAWAN</span>
            <h1 class="text-3xl sm:text-5xl font-montserrat font-black uppercase tracking-tight italic text-slate-900 leading-tight">
                Jadilah Bagian Dari <span class="text-primary-600">Perubahan</span>
            </h1>
            <div class="w-12 h-1.5 bg-primary-500 rounded-full"></div>
            <p class="text-slate-600 leading-relaxed font-semibold text-xs sm:text-sm">
                Setiap kontribusi keahlian, waktu, dan dedikasi Anda adalah kekuatan besar bagi kelancaran distribusi bantuan kemanusiaan kami di lapangan. Mari bersatu hati dan terjun langsung membawa harapan baru bagi mereka yang membutuhkan.
            </p>
            <div class="bg-primary-50 border border-primary-100 p-6 rounded-3xl space-y-3">
                <h4 class="font-montserrat font-black uppercase text-xs tracking-wider text-primary-800">Manfaat Gabung Relawan</h4>
                <ul class="space-y-2 text-[11px] font-bold text-slate-700 uppercase tracking-wide">
                    <li><i class="fa-solid fa-check text-primary-600 mr-2"></i> Pengalaman Lapangan Kemanusiaan Nyata</li>
                    <li><i class="fa-solid fa-check text-primary-600 mr-2"></i> Memperluas Jaringan Sosial & Komunitas</li>
                    <li><i class="fa-solid fa-check text-primary-600 mr-2"></i> Sertifikat Penghargaan Resmi</li>
                </ul>
            </div>
        </div>

        <!-- Form card -->
        <div class="bg-white border border-slate-100 shadow-xl rounded-3xl p-8 sm:p-10 space-y-6">
            <h3 class="font-montserrat font-black uppercase text-base text-slate-900">Formulir Pendaftaran</h3>
            <form action="{{ route('volunteer') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Nama Lengkap</label>
                        <input type="text" name="name" required class="w-full bg-slate-50 border border-slate-100 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Alamat Email</label>
                        <input type="email" name="email" required class="w-full bg-slate-50 border border-slate-100 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">No. Telepon / WA</label>
                        <input type="text" name="phone" required class="w-full bg-slate-50 border border-slate-100 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Alamat Domisili</label>
                        <input type="text" name="address" required class="w-full bg-slate-50 border border-slate-100 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Keahlian / Keterampilan Utama</label>
                    <input type="text" name="skills" required placeholder="Contoh: Media, Logistik, Medis, dll." class="w-full bg-slate-50 border border-slate-100 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Alasan Ingin Bergabung</label>
                    <textarea name="reason" required rows="3" class="w-full bg-slate-50 border border-slate-100 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all"></textarea>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-widest py-4.5 rounded-2xl shadow-lg shadow-primary-600/10 hover:shadow-primary-600/20 transition-all duration-300">
                        Kirim Pendaftaran <i class="fa-solid fa-paper-plane ml-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
