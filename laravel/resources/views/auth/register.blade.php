<x-guest-layout>
    <div class="space-y-1">
        <h1 class="text-xl sm:text-2xl font-montserrat font-black uppercase text-slate-900 tracking-tight">
            Buat <span class="text-primary-600">Akun Baru</span>
        </h1>
        <p class="text-xs text-slate-600 font-semibold">Daftar untuk mulai berdonasi dan memantau laporan penyaluran</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4 pt-2">
        @csrf

        <div class="space-y-1.5">
            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Nama Lengkap</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-500">
                    <i class="fa-solid fa-user text-xs" aria-hidden="true"></i>
                </span>
                <input 
                    id="name" 
                    type="text" 
                    name="name" 
                    value="{{ old('name') }}" 
                    required 
                    autofocus 
                    placeholder="Masukkan nama lengkap"
                    class="w-full bg-slate-50 border border-slate-300 focus:border-primary-600 rounded-xl pl-10 pr-4 py-3 text-xs font-bold text-slate-900 placeholder-slate-500 transition-colors"
                >
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <div class="space-y-1.5">
            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Alamat Email</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-500">
                    <i class="fa-solid fa-envelope text-xs" aria-hidden="true"></i>
                </span>
                <input 
                    id="email" 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
                    placeholder="nama@email.com"
                    class="w-full bg-slate-50 border border-slate-300 focus:border-primary-600 rounded-xl pl-10 pr-4 py-3 text-xs font-bold text-slate-900 placeholder-slate-500 transition-colors"
                >
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <div class="space-y-1.5" x-data="{ showPassword: false }">
            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Kata Sandi</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-500">
                    <i class="fa-solid fa-lock text-xs" aria-hidden="true"></i>
                </span>
                <input 
                    id="password" 
                    :type="showPassword ? 'text' : 'password'" 
                    name="password" 
                    required 
                    placeholder="Minimal 8 karakter"
                    class="w-full bg-slate-50 border border-slate-300 focus:border-primary-600 rounded-xl pl-10 pr-12 py-3 text-xs font-bold text-slate-900 placeholder-slate-500 transition-colors"
                >
                <button 
                    type="button" 
                    @click="showPassword = !showPassword" 
                    :aria-label="showPassword ? 'Sembunyikan kata sandi' : 'Lihat kata sandi'"
                    class="absolute inset-y-0 right-0 flex items-center justify-center px-3 min-w-[44px] text-slate-600 hover:text-primary-600 transition-colors"
                >
                    <i class="fa-solid text-sm" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'" aria-hidden="true"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <div class="space-y-1.5" x-data="{ showPassword: false }">
            <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Konfirmasi Sandi</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-500">
                    <i class="fa-solid fa-circle-check text-xs" aria-hidden="true"></i>
                </span>
                <input 
                    id="password_confirmation" 
                    :type="showPassword ? 'text' : 'password'" 
                    name="password_confirmation" 
                    required 
                    placeholder="Ulangi kata sandi"
                    class="w-full bg-slate-50 border border-slate-300 focus:border-primary-600 rounded-xl pl-10 pr-12 py-3 text-xs font-bold text-slate-900 placeholder-slate-500 transition-colors"
                >
                <button 
                    type="button" 
                    @click="showPassword = !showPassword" 
                    :aria-label="showPassword ? 'Sembunyikan konfirmasi sandi' : 'Lihat konfirmasi sandi'"
                    class="absolute inset-y-0 right-0 flex items-center justify-center px-3 min-w-[44px] text-slate-600 hover:text-primary-600 transition-colors"
                >
                    <i class="fa-solid text-sm" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'" aria-hidden="true"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <div class="pt-2">
            <button 
                type="submit" 
                class="w-full min-h-[44px] bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-wider py-3.5 rounded-xl shadow-md transition-colors"
            >
                Daftar Akun
            </button>
        </div>
    </form>

    <div class="text-center pt-2">
        <span class="text-xs font-semibold text-slate-600">Sudah punya akun?</span>
        <a href="{{ route('login') }}" class="text-xs font-black uppercase text-primary-700 hover:underline ml-1">
            Masuk Sekarang
        </a>
    </div>
</x-guest-layout>
