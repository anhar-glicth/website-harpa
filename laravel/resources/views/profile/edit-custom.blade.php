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

    <!-- Error Alert -->
    @if($errors->any())
        <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl font-semibold text-xs space-y-1">
            <div class="flex items-center gap-2 text-rose-700 font-bold mb-1">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span>Terdapat kesalahan pada pengisian form:</span>
            </div>
            <ul class="list-disc list-inside space-y-0.5 text-rose-600 pl-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar Links -->
        <div class="lg:col-span-1 space-y-6">
            @if(Auth::user()->hasAdminAccess())
                @include('admin.sidebar', ['active' => 'profile'])
            @else
                @include('layouts.donor-sidebar', ['active' => 'profile'])
            @endif
        </div>

        <!-- Edit Profile Form -->
        <div class="lg:col-span-3 space-y-8">
            <div class="space-y-2">
                <h1 class="text-2xl sm:text-3xl font-montserrat font-black text-slate-900 uppercase tracking-tight italic">
                    Pengaturan <span class="text-primary-600">Profil & Akun</span>
                </h1>
                <div class="w-12 h-1 bg-primary-500 rounded-full"></div>
            </div>

            <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 space-y-6 shadow-sm">
                <form action="{{ route('profile.custom.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <div>
                        <h2 class="text-xs font-black uppercase tracking-wider text-slate-400 border-b pb-2 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-id-card text-primary-600"></i> Informasi Identitas Akun
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-[9px] font-black uppercase tracking-wider text-slate-450 mb-1.5 ml-1">Nama Lengkap *</label>
                                <input type="text" name="name" required value="{{ old('name', Auth::user()->name) }}" class="w-full bg-slate-50 border border-slate-150 focus:border-primary-500 rounded-2xl px-5 py-3.5 text-xs font-bold text-slate-800 outline-none transition-all focus:ring-4 focus:ring-primary-500/10">
                            </div>
                            <div>
                                <label class="block text-[9px] font-black uppercase tracking-wider text-slate-450 mb-1.5 ml-1">Username (Opsional)</label>
                                <input type="text" name="username" value="{{ old('username', Auth::user()->username) }}" placeholder="Username unik" class="w-full bg-slate-50 border border-slate-150 focus:border-primary-500 rounded-2xl px-5 py-3.5 text-xs font-bold text-slate-800 outline-none transition-all focus:ring-4 focus:ring-primary-500/10">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-[9px] font-black uppercase tracking-wider text-slate-450 mb-1.5 ml-1">Alamat Email *</label>
                                <input type="email" name="email" required value="{{ old('email', Auth::user()->email) }}" class="w-full bg-slate-50 border border-slate-150 focus:border-primary-500 rounded-2xl px-5 py-3.5 text-xs font-bold text-slate-800 outline-none transition-all focus:ring-4 focus:ring-primary-500/10">
                            </div>
                            <div>
                                <label class="block text-[9px] font-black uppercase tracking-wider text-slate-450 mb-1.5 ml-1">Nomor Telepon / WA</label>
                                <input type="text" name="phone" value="{{ old('phone', Auth::user()->phone) }}" class="w-full bg-slate-50 border border-slate-150 focus:border-primary-500 rounded-2xl px-5 py-3.5 text-xs font-bold text-slate-800 outline-none transition-all focus:ring-4 focus:ring-primary-500/10">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-[9px] font-black uppercase tracking-wider text-slate-450 mb-1.5 ml-1">Jenis Kelamin</label>
                                <select name="gender" class="w-full bg-slate-50 border border-slate-150 focus:border-primary-500 rounded-2xl px-5 py-3.5 text-xs font-bold text-slate-800 outline-none transition-all focus:ring-4 focus:ring-primary-500/10">
                                    <option value="" {{ is_null(Auth::user()->gender) ? 'selected' : '' }}>Pilih Jenis Kelamin</option>
                                    <option value="L" {{ Auth::user()->gender === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ Auth::user()->gender === 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[9px] font-black uppercase tracking-wider text-slate-450 mb-1.5 ml-1">Foto Profil Baru</label>
                                <div class="flex items-center gap-3">
                                    @if(Auth::user()->profile_photo)
                                        <img src="{{ asset(Auth::user()->profile_photo) }}" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=ea580c&color=fff';" class="w-10 h-10 rounded-xl object-cover border border-slate-200 shrink-0" alt="Foto Saat Ini">
                                    @endif
                                    <input type="file" name="profile_photo" accept="image/*" class="w-full bg-slate-50 border border-slate-150 focus:border-primary-500 rounded-2xl px-5 py-2.5 text-xs font-bold text-slate-800 outline-none transition-all focus:ring-4 focus:ring-primary-500/10">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[9px] font-black uppercase tracking-wider text-slate-450 mb-1.5 ml-1">Alamat Tempat Tinggal</label>
                            <textarea name="address" rows="3" class="w-full bg-slate-50 border border-slate-150 focus:border-primary-500 rounded-2xl px-5 py-3.5 text-xs font-semibold text-slate-800 outline-none transition-all focus:ring-4 focus:ring-primary-500/10">{{ old('address', Auth::user()->address) }}</textarea>
                        </div>
                    </div>

                    <!-- Ganti Password Section -->
                    <div class="pt-4 border-t border-slate-100">
                        <h2 class="text-xs font-black uppercase tracking-wider text-slate-400 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-key text-amber-500"></i> Ubah Password (Opsional)
                        </h2>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-[9px] font-black uppercase tracking-wider text-slate-450 mb-1.5 ml-1">Password Saat Ini</label>
                                <input type="password" name="current_password" placeholder="Password lama Anda" class="w-full bg-slate-50 border border-slate-150 focus:border-primary-500 rounded-2xl px-5 py-3.5 text-xs font-bold text-slate-800 outline-none transition-all focus:ring-4 focus:ring-primary-500/10">
                            </div>
                            <div>
                                <label class="block text-[9px] font-black uppercase tracking-wider text-slate-450 mb-1.5 ml-1">Password Baru</label>
                                <input type="password" name="new_password" placeholder="Min. 6 karakter" class="w-full bg-slate-50 border border-slate-150 focus:border-primary-500 rounded-2xl px-5 py-3.5 text-xs font-bold text-slate-800 outline-none transition-all focus:ring-4 focus:ring-primary-500/10">
                            </div>
                            <div>
                                <label class="block text-[9px] font-black uppercase tracking-wider text-slate-450 mb-1.5 ml-1">Konfirmasi Password Baru</label>
                                <input type="password" name="new_password_confirmation" placeholder="Ulangi password baru" class="w-full bg-slate-50 border border-slate-150 focus:border-primary-500 rounded-2xl px-5 py-3.5 text-xs font-bold text-slate-800 outline-none transition-all focus:ring-4 focus:ring-primary-500/10">
                            </div>
                        </div>
                        <p class="text-[10px] text-slate-400 font-medium mt-2 ml-1">* Kosongkan kolom password jika Anda tidak ingin mengubah password.</p>
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
