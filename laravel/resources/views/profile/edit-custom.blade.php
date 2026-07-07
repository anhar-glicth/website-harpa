@extends('layouts.app')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-20">
    
    <!-- Success Alert -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-250 text-emerald-800 rounded-2xl font-semibold text-xs flex items-center gap-3">
            <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar Links -->
        <div class="lg:col-span-1 space-y-6">
            @include('layouts.donor-sidebar', ['active' => 'profile'])
        </div>

        <!-- Edit Profile Form -->
        <div class="lg:col-span-3 space-y-8">
            <div class="space-y-2">
                <h1 class="text-2xl sm:text-3xl font-montserrat font-black text-slate-900 uppercase tracking-tight italic">
                    Pengaturan <span class="text-primary-600">Profil Donatur</span>
                </h1>
                <div class="w-12 h-1 bg-primary-500 rounded-full"></div>
            </div>

            <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 space-y-6 shadow-sm">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[9px] font-black uppercase tracking-wider text-slate-450 mb-1.5 ml-1">Nama Lengkap</label>
                            <input type="text" name="name" required value="{{ Auth::user()->name }}" class="w-full bg-slate-50 border border-slate-150 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all focus:ring-4 focus:ring-primary-500/10">
                        </div>
                        <div>
                            <label class="block text-[9px] font-black uppercase tracking-wider text-slate-450 mb-1.5 ml-1">Jenis Kelamin</label>
                            <select name="gender" class="w-full bg-slate-50 border border-slate-150 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all focus:ring-4 focus:ring-primary-500/10">
                                <option value="" {{ is_null(Auth::user()->gender) ? 'selected' : '' }}>Pilih Jenis Kelamin</option>
                                <option value="L" {{ Auth::user()->gender === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ Auth::user()->gender === 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[9px] font-black uppercase tracking-wider text-slate-450 mb-1.5 ml-1">Nomor Telepon / WA</label>
                            <input type="text" name="phone" value="{{ Auth::user()->phone }}" class="w-full bg-slate-50 border border-slate-150 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all focus:ring-4 focus:ring-primary-500/10">
                        </div>
                        <div>
                            <label class="block text-[9px] font-black uppercase tracking-wider text-slate-450 mb-1.5 ml-1">Foto Profil</label>
                            <input type="file" name="profile_photo" class="w-full bg-slate-50 border border-slate-150 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all focus:ring-4 focus:ring-primary-500/10">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[9px] font-black uppercase tracking-wider text-slate-450 mb-1.5 ml-1">Alamat Tempat Tinggal</label>
                        <textarea name="address" rows="3" class="w-full bg-slate-50 border border-slate-150 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-semibold text-slate-800 outline-none transition-all focus:ring-4 focus:ring-primary-500/10">{{ Auth::user()->address }}</textarea>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-wider py-4 rounded-2xl shadow-lg shadow-primary-600/10 transition duration-300">
                            Simpan Perubahan <i class="fa-solid fa-floppy-disk ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</section>
@endsection
