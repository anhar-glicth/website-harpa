<x-guest-layout>
    <div class="space-y-1">
        <h1 class="text-xl sm:text-2xl font-montserrat font-black uppercase text-slate-900 tracking-tight">
            Selamat <span class="text-primary-600">Datang Kembali</span>
        </h1>
        <p class="text-xs text-slate-600 font-semibold">Masuk ke akun Anda untuk mengelola donasi dan doa</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4 pt-2">
        @csrf

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
                    autofocus 
                    placeholder="nama@email.com"
                    class="w-full bg-slate-50 border border-slate-300 focus:border-primary-600 rounded-xl pl-10 pr-4 py-3 text-xs font-bold text-slate-900 placeholder-slate-500 transition-colors"
                >
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <div class="space-y-1.5" x-data="{ showPassword: false }">
            <div class="flex justify-between items-center">
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Kata Sandi</label>
                @if (Route::has('password.request'))
                    <a class="text-xs font-bold text-primary-700 hover:underline transition-colors" href="{{ route('password.request') }}">
                        Lupa Sandi?
                    </a>
                @endif
            </div>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-500">
                    <i class="fa-solid fa-lock text-xs" aria-hidden="true"></i>
                </span>
                <input 
                    id="password" 
                    :type="showPassword ? 'text' : 'password'" 
                    name="password" 
                    required 
                    placeholder="Masukkan kata sandi"
                    class="w-full bg-slate-50 border border-slate-300 focus:border-primary-600 rounded-xl pl-10 pr-12 py-3 text-xs font-bold text-slate-900 placeholder-slate-500 transition-colors"
                >
                <button 
                    type="button" 
                    @click="showPassword = !showPassword" 
                    :aria-label="showPassword ? 'Sembunyikan kata sandi' : 'Lihat kata sandi'"
                    class="absolute inset-y-0 right-0 flex items-center justify-center px-3 min-w-[44px] text-slate-600 hover:text-primary-600 transition-colors"
                >
                    <i class="fa-solid" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'" aria-hidden="true"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <div class="flex items-center space-x-2">
            <input 
                id="remember_me" 
                type="checkbox" 
                name="remember"
                class="w-4 h-4 rounded border-slate-300 bg-slate-50 text-primary-600 focus:ring-primary-500"
            >
            <label for="remember_me" class="text-xs font-bold text-slate-700 select-none">
                Ingat Saya
            </label>
        </div>

        <div class="pt-2">
            <button 
                type="submit" 
                class="w-full min-h-[44px] bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-wider py-3.5 rounded-xl shadow-md transition-colors"
            >
                Masuk Akun
            </button>
        </div>
    </form>

    <div class="text-center pt-2">
        <span class="text-xs font-semibold text-slate-600">Belum punya akun?</span>
        <a href="{{ route('register') }}" class="text-xs font-black uppercase text-primary-700 hover:underline ml-1">
            Daftar Sekarang
        </a>
    </div>
</x-guest-layout>
