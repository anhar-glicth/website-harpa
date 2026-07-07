@extends('layouts.app')

@section('content')
<section class="max-w-3xl mx-auto px-6 py-20">
    <div class="bg-white border border-slate-100 shadow-xl rounded-3xl p-8 sm:p-10 space-y-6">
        <div class="space-y-2">
            <h1 class="text-2xl sm:text-3xl font-montserrat font-black text-slate-900 uppercase tracking-tight italic">
                Buat <span class="text-primary-600">Kampanye Baru</span>
            </h1>
            <p class="text-slate-400 font-bold text-xs tracking-widest uppercase">Ajukan program donasi kemanusiaan Anda sendiri</p>
        </div>

        <form action="{{ route('campaigns.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Judul Kampanye</label>
                <input type="text" name="title" required placeholder="Contoh: Bantuan Sembako Gempa Cianjur" class="w-full bg-slate-50 border border-slate-100 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Kategori</label>
                    <select name="category" required class="w-full bg-slate-50 border border-slate-100 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all">
                        <option value="Kemanusiaan">Kemanusiaan</option>
                        <option value="Pendidikan">Pendidikan</option>
                        <option value="Kesehatan">Kesehatan</option>
                        <option value="Dakwah">Dakwah</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Target Jumlah Donasi (Rupiah)</label>
                    <input type="number" name="target_amount" required min="1000" placeholder="Contoh: 50000000" class="w-full bg-slate-50 border border-slate-100 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Foto Sampul Kampanye</label>
                <input type="file" name="foto" required class="w-full bg-slate-50 border border-slate-100 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all">
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Deskripsi / Penjelasan Lengkap Program</label>
                <textarea name="description" required rows="6" placeholder="Tulis rincian program donasi, latar belakang, serta pembagian alokasi dana..." class="w-full bg-slate-50 border border-slate-100 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all"></textarea>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-widest py-4.5 rounded-2xl shadow-lg shadow-primary-600/10 hover:shadow-primary-600/20 transition-all duration-300">
                    Ajukan Kampanye <i class="fa-solid fa-paper-plane ml-2"></i>
                </button>
            </div>
        </form>
    </div>
</section>
@endsection
