@extends('layouts.app')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-20">
    
    <!-- Success Alert -->
    @if(session('success'))
        <div role="status" class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl font-semibold text-xs flex items-center gap-3">
            <i class="fa-solid fa-circle-check text-emerald-700 text-base" aria-hidden="true"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Error Alert -->
    @if($errors->any())
        <div role="alert" class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl font-semibold text-xs space-y-1">
            <div class="flex items-center gap-2 text-rose-700 font-bold mb-1">
                <i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i>
                <span>Terdapat kesalahan pada pengisian form:</span>
            </div>
            <ul class="list-disc list-inside space-y-0.5 text-rose-700 pl-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar Links -->
        <div class="hidden lg:block lg:col-span-1 space-y-6">
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

            <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 space-y-6 shadow-sm">
                <form action="{{ route('profile.custom.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <div>
                        <h2 class="text-xs font-black uppercase tracking-wider text-slate-700 border-b border-slate-200 pb-2 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-id-card text-primary-700" aria-hidden="true"></i> Informasi Identitas Akun
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="profile_name" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5 ml-1">Nama Lengkap *</label>
                                <input id="profile_name" type="text" name="name" required value="{{ old('name', Auth::user()->name) }}" class="w-full min-h-[44px] bg-slate-50 border border-slate-300 focus:border-primary-600 rounded-2xl px-5 py-3 text-sm font-semibold text-slate-800 transition-all focus:ring-4 focus:ring-primary-500/10">
                            </div>
                            <div>
                                <label for="profile_username" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5 ml-1">Username (Opsional)</label>
                                <input id="profile_username" type="text" name="username" value="{{ old('username', Auth::user()->username) }}" placeholder="Username unik" class="w-full min-h-[44px] bg-slate-50 border border-slate-300 focus:border-primary-600 rounded-2xl px-5 py-3 text-sm font-semibold text-slate-800 transition-all focus:ring-4 focus:ring-primary-500/10">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="profile_email" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5 ml-1">Alamat Email *</label>
                                <input id="profile_email" type="email" name="email" required value="{{ old('email', Auth::user()->email) }}" class="w-full min-h-[44px] bg-slate-50 border border-slate-300 focus:border-primary-600 rounded-2xl px-5 py-3 text-sm font-semibold text-slate-800 transition-all focus:ring-4 focus:ring-primary-500/10">
                            </div>
                            <div>
                                <label for="profile_phone" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5 ml-1">Nomor Telepon / WA</label>
                                <input id="profile_phone" type="text" name="phone" value="{{ old('phone', Auth::user()->phone) }}" class="w-full min-h-[44px] bg-slate-50 border border-slate-300 focus:border-primary-600 rounded-2xl px-5 py-3 text-sm font-semibold text-slate-800 transition-all focus:ring-4 focus:ring-primary-500/10">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="profile_gender" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5 ml-1">Jenis Kelamin</label>
                                <select id="profile_gender" name="gender" class="w-full min-h-[44px] bg-slate-50 border border-slate-300 focus:border-primary-600 rounded-2xl px-5 py-3 text-sm font-semibold text-slate-800 transition-all focus:ring-4 focus:ring-primary-500/10">
                                    <option value="" {{ is_null(Auth::user()->gender) ? 'selected' : '' }}>Pilih Jenis Kelamin</option>
                                    <option value="L" {{ Auth::user()->gender === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ Auth::user()->gender === 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                            <div>
                                <label for="profile_photo_input" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5 ml-1">Foto Profil</label>
                                <div class="flex items-center gap-3">
                                    <div class="relative shrink-0">
                                        <img id="photo-preview" src="{{ Auth::user()->profile_photo_url }}" width="48" height="48" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=063f5c&color=fff';" class="w-12 h-12 rounded-2xl object-cover border-2 border-slate-200 shadow-sm" alt="Preview Foto Profil">
                                        <span id="new-photo-badge" class="hidden absolute -top-1 -right-1 bg-emerald-700 text-white rounded-full w-4 h-4 flex items-center justify-center text-xs" title="Foto baru dipilih">
                                            <i class="fa-solid fa-check" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                    <div class="flex-1 space-y-1">
                                        <input type="file" id="profile_photo_input" name="profile_photo" accept="image/jpeg,image/png,image/webp,image/jpg,image/gif" class="w-full min-h-[44px] bg-slate-50 border border-slate-300 focus:border-primary-600 rounded-2xl px-3 py-2 text-xs font-bold text-slate-800 transition-all focus:ring-4 focus:ring-primary-500/10 file:mr-2 file:py-1 file:px-2.5 file:rounded-xl file:border-0 file:text-xs file:font-black file:uppercase file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 cursor-pointer">
                                        <div class="flex items-center justify-between text-xs text-slate-600 font-medium px-1">
                                            <span>Format: JPG, PNG, WEBP. Maks 5MB.</span>
                                            @if(Auth::user()->hasCustomPhoto())
                                                <label for="remove_photo_check" class="flex items-center gap-1.5 min-h-[44px] text-rose-700 hover:text-rose-800 cursor-pointer font-bold ml-2" title="Centang untuk menghapus foto">
                                                    <input type="checkbox" name="remove_photo" value="1" id="remove_photo_check" class="rounded text-rose-700 focus:ring-rose-500 w-4 h-4">
                                                    <span>Hapus Foto</span>
                                                </label>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @error('profile_photo')
                                    <p class="text-rose-700 text-xs font-bold mt-1.5 ml-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="profile_address" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5 ml-1">Alamat Tempat Tinggal</label>
                            <textarea id="profile_address" name="address" rows="3" class="w-full bg-slate-50 border border-slate-300 focus:border-primary-600 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-800 transition-all focus:ring-4 focus:ring-primary-500/10">{{ old('address', Auth::user()->address) }}</textarea>
                        </div>
                    </div>

                    <!-- Ganti Password Section -->
                    <div class="pt-4 border-t border-slate-200">
                        <h2 class="text-xs font-black uppercase tracking-wider text-slate-700 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-key text-amber-600" aria-hidden="true"></i> Ubah Password (Opsional)
                        </h2>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div x-data="{ show: false }">
                                <label for="current_password" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5 ml-1">Password Saat Ini</label>
                                <div class="relative">
                                    <input id="current_password" :type="show ? 'text' : 'password'" name="current_password" placeholder="Password lama Anda" class="w-full min-h-[44px] bg-slate-50 border border-slate-300 focus:border-primary-600 rounded-2xl pl-5 pr-12 py-3 text-sm font-semibold text-slate-800 transition-all focus:ring-4 focus:ring-primary-500/10">
                                    <button type="button" @click="show = !show" :aria-label="show ? 'Sembunyikan kata sandi saat ini' : 'Lihat kata sandi saat ini'" class="absolute inset-y-0 right-0 w-11 flex items-center justify-center text-slate-600 hover:text-primary-700 transition-colors" :title="show ? 'Sembunyikan kata sandi' : 'Lihat kata sandi'">
                                        <i class="fa-solid text-xs" :class="show ? 'fa-eye-slash' : 'fa-eye'" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                            <div x-data="{ show: false }">
                                <label for="new_password" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5 ml-1">Password Baru</label>
                                <div class="relative">
                                    <input id="new_password" :type="show ? 'text' : 'password'" name="new_password" placeholder="Min. 6 karakter" class="w-full min-h-[44px] bg-slate-50 border border-slate-300 focus:border-primary-600 rounded-2xl pl-5 pr-12 py-3 text-sm font-semibold text-slate-800 transition-all focus:ring-4 focus:ring-primary-500/10">
                                    <button type="button" @click="show = !show" :aria-label="show ? 'Sembunyikan kata sandi baru' : 'Lihat kata sandi baru'" class="absolute inset-y-0 right-0 w-11 flex items-center justify-center text-slate-600 hover:text-primary-700 transition-colors" :title="show ? 'Sembunyikan kata sandi' : 'Lihat kata sandi'">
                                        <i class="fa-solid text-xs" :class="show ? 'fa-eye-slash' : 'fa-eye'" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                            <div x-data="{ show: false }">
                                <label for="new_password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5 ml-1">Konfirmasi Password Baru</label>
                                <div class="relative">
                                    <input id="new_password_confirmation" :type="show ? 'text' : 'password'" name="new_password_confirmation" placeholder="Ulangi password baru" class="w-full min-h-[44px] bg-slate-50 border border-slate-300 focus:border-primary-600 rounded-2xl pl-5 pr-12 py-3 text-sm font-semibold text-slate-800 transition-all focus:ring-4 focus:ring-primary-500/10">
                                    <button type="button" @click="show = !show" :aria-label="show ? 'Sembunyikan konfirmasi kata sandi' : 'Lihat konfirmasi kata sandi'" class="absolute inset-y-0 right-0 w-11 flex items-center justify-center text-slate-600 hover:text-primary-700 transition-colors" :title="show ? 'Sembunyikan kata sandi' : 'Lihat kata sandi'">
                                        <i class="fa-solid text-xs" :class="show ? 'fa-eye-slash' : 'fa-eye'" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <p class="text-xs text-slate-600 font-medium mt-2 ml-1">* Kosongkan kolom password jika Anda tidak ingin mengubah password.</p>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full min-h-[48px] bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-wider py-4 rounded-2xl shadow-lg shadow-primary-600/10 transition duration-300">
                            Simpan Perubahan <i class="fa-solid fa-floppy-disk ml-2" aria-hidden="true"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const photoInput = document.getElementById('profile_photo_input');
        const previewImg = document.getElementById('photo-preview');
        const newBadge = document.getElementById('new-photo-badge');
        const removeCheck = document.getElementById('remove_photo_check');
        const defaultAvatar = "{{ Auth::user()->profile_photo_url }}";

        if (photoInput && previewImg) {
            photoInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    if (removeCheck) removeCheck.checked = false;
                    const reader = new FileReader();
                    reader.onload = function (event) {
                        previewImg.src = event.target.result;
                        if (newBadge) newBadge.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        if (removeCheck) {
            removeCheck.addEventListener('change', function () {
                if (this.checked) {
                    if (photoInput) photoInput.value = '';
                    if (newBadge) newBadge.classList.add('hidden');
                    previewImg.src = "https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=063f5c&color=fff";
                } else {
                    previewImg.src = defaultAvatar;
                }
            });
        }
    });
</script>
@endsection
