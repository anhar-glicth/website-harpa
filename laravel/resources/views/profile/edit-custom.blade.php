@extends('layouts.app')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-20">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar Links -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white border border-slate-100 p-8 rounded-3xl space-y-6 shadow-sm">
                <div class="flex items-center space-x-3">
                    @if(Auth::user()->profile_photo)
                        <img src="{{ Auth::user()->profile_photo }}" class="w-12 h-12 rounded-2xl object-cover" alt="">
                    @else
                        <div class="w-12 h-12 bg-primary-100 text-primary-700 rounded-2xl flex items-center justify-center font-bold text-base uppercase">
                            {{ substr(Auth::user()->name, 0, 2) }}
                        </div>
                    @endif
                    <div>
                        <h4 class="font-montserrat font-black uppercase text-xs sm:text-sm text-slate-800 leading-tight">{{ Auth::user()->name }}</h4>
                        <span class="text-[9px] font-bold text-slate-400 uppercase block tracking-wider mt-1">{{ Auth::user()->email }}</span>
                    </div>
                </div>
                
                <hr class="border-slate-100">

                <div class="flex flex-col space-y-2 font-bold text-[10px] uppercase tracking-wider text-slate-500">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 py-3 px-4 rounded-xl hover:bg-slate-50 hover:text-slate-800 transition-colors">
                        <i class="fa-solid fa-gauge"></i>
                        <span>Ringkasan</span>
                    </a>
                    <a href="{{ route('zakat.index') }}" class="flex items-center space-x-3 py-3 px-4 rounded-xl hover:bg-slate-50 hover:text-slate-800 transition-colors">
                        <i class="fa-solid fa-calculator"></i>
                        <span>Bayar Zakat</span>
                    </a>
                    <a href="{{ route('profile.custom') }}" class="flex items-center space-x-3 py-3 px-4 rounded-xl bg-primary-50 text-primary-700">
                        <i class="fa-solid fa-user-pen"></i>
                        <span>Edit Profil</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Edit Profile Form -->
        <div class="lg:col-span-3 space-y-8">
            <div class="space-y-2">
                <h1 class="text-2xl sm:text-3xl font-montserrat font-black text-slate-900 uppercase tracking-tight italic">
                    Pengaturan <span class="text-primary-600">Profil Donatur</span>
                </h1>
                <div class="w-12 h-1 bg-primary-500 rounded-full"></div>
            </div>

            <div class="bg-white border border-slate-100 rounded-3xl p-8 sm:p-10 space-y-6 shadow-sm">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Nama Lengkap</label>
                            <input type="text" name="name" required value="{{ Auth::user()->name }}" class="w-full bg-slate-50 border border-slate-100 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Jenis Kelamin</label>
                            <select name="gender" class="w-full bg-slate-50 border border-slate-100 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all">
                                <option value="" {{ is_null(Auth::user()->gender) ? 'selected' : '' }}>Pilih Jenis Kelamin</option>
                                <option value="L" {{ Auth::user()->gender === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ Auth::user()->gender === 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Nomor Telepon / WA</label>
                            <input type="text" name="phone" value="{{ Auth::user()->phone }}" class="w-full bg-slate-50 border border-slate-100 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Foto Profil</label>
                            <input type="file" name="profile_photo" class="w-full bg-slate-50 border border-slate-100 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Alamat Tempat Tinggal</label>
                        <textarea name="address" rows="3" class="w-full bg-slate-50 border border-slate-100 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all">{{ Auth::user()->address }}</textarea>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-widest py-4.5 rounded-2xl shadow-lg shadow-primary-600/10 transition duration-300">
                            Simpan Perubahan <i class="fa-solid fa-floppy-disk ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</section>
@endsection
