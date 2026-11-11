@extends('layouts.app')

@section('content')
<section class="max-w-3xl mx-auto px-6 py-20">
    <div class="bg-white border border-slate-100 shadow-xl rounded-3xl p-8 sm:p-10 space-y-6">
        <div class="space-y-2">
            <h1 class="text-2xl sm:text-3xl font-montserrat font-black text-slate-900 uppercase tracking-tight italic">
                Buat <span class="text-primary-600">Kampanye Baru</span>
            </h1>
            <p class="text-slate-600 font-bold text-xs tracking-widest uppercase">Ajukan program donasi kemanusiaan Anda sendiri</p>
        </div>

        <form action="{{ route('campaigns.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label for="camp_title" class="block text-xs font-black uppercase tracking-wider text-slate-600 mb-1.5">Judul Kampanye</label>
                <input id="camp_title" type="text" name="title" required placeholder="Contoh: Bantuan Sembako Gempa Cianjur" class="w-full min-h-[44px] bg-slate-50 border border-slate-300 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 transition-colors">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="camp_category" class="block text-xs font-black uppercase tracking-wider text-slate-600 mb-1.5">Kategori</label>
                    <select id="camp_category" name="category" required class="w-full min-h-[44px] bg-slate-50 border border-slate-300 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 transition-colors">
                        @if(isset($groups) && $groups->count() > 0)
                            @foreach($groups as $grp)
                                <option value="{{ $grp->name }}">{{ $grp->name }}</option>
                            @endforeach
                        @else
                            <option value="Kemanusiaan">Kemanusiaan</option>
                            <option value="Pendidikan">Pendidikan</option>
                            <option value="Kesehatan">Kesehatan</option>
                            <option value="Dakwah">Dakwah</option>
                        @endif
                    </select>
                </div>
                <div>
                    <label for="camp_target" class="block text-xs font-black uppercase tracking-wider text-slate-600 mb-1.5">Target Jumlah Donasi (Rupiah)</label>
                    <input id="camp_target" type="number" name="target_amount" required min="1000" placeholder="Contoh: 50000000" class="w-full min-h-[44px] bg-slate-50 border border-slate-300 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 transition-colors">
                </div>
            </div>

            <div>
                <label for="camp_foto" class="block text-xs font-black uppercase tracking-wider text-slate-600 mb-1.5">Foto Sampul Kampanye</label>
                <input id="camp_foto" type="file" name="foto" required class="w-full min-h-[44px] bg-slate-50 border border-slate-300 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 transition-colors">
            </div>

            <div>
                <label for="camp_desc" class="block text-xs font-black uppercase tracking-wider text-slate-600 mb-1.5">Deskripsi / Penjelasan Lengkap Program</label>
                <textarea id="camp_desc" name="description" required rows="6" placeholder="Tulis rincian program donasi, latar belakang, serta pembagian alokasi dana..." class="w-full bg-slate-50 border border-slate-300 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 transition-colors"></textarea>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full min-h-[48px] bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-widest py-4 rounded-2xl shadow-lg shadow-primary-600/10 hover:shadow-primary-600/20 transition-all duration-300">
                    Ajukan Kampanye <i class="fa-solid fa-paper-plane ml-2"></i>
                </button>
            </div>
        </form>
    </div>
</section>
@endsection
