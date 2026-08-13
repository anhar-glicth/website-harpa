<x-guest-layout>
    <!-- Heading -->
    <div class="space-y-1">
        <h2 class="text-xl sm:text-2xl font-montserrat font-black uppercase text-white tracking-tight italic">
            Selamat <span class="text-primary-500">Datang Kembali</span>
        </h2>
        <p class="text-[10px] text-slate-450 font-bold uppercase tracking-widest text-slate-400">Masuk untuk melanjutkan kebaikan Anda</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4 pt-2">
        @csrf

        <!-- Email Address -->
        <div class="space-y-1.5">
            <label for="email" class="block text-[9px] font-black uppercase tracking-wider text-slate-400">Alamat Email</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                    <i class="fa-solid fa-envelope text-xs"></i>
                </span>
                <input 
                    id="email" 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
                    autofocus 
                    placeholder="nama@email.com"
                    class="w-full bg-slate-950/50 border border-slate-800 focus:border-primary-500 rounded-2xl pl-10 pr-4 py-3.5 text-xs font-bold text-white placeholder-slate-500 outline-none transition-all"
                >
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div class="space-y-1.5" x-data="{ showPassword: false }">
            <div class="flex justify-between items-center">
                <label for="password" class="block text-[9px] font-black uppercase tracking-wider text-slate-400">Kata Sandi</label>
                @if (Route::has('password.request'))
                    <a class="text-[9px] font-black uppercase text-primary-500 hover:text-primary-600 transition-colors" href="{{ route('password.request') }}">
                        Lupa Sandi?
                    </a>
                @endif
            </div>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                    <i class="fa-solid fa-lock text-xs"></i>
                </span>
                <input 
                    id="password" 
                    :type="showPassword ? 'text' : 'password'" 
                    name="password" 
                    required 
                    placeholder="••••••••"
                    class="w-full bg-slate-950/50 border border-slate-800 focus:border-primary-500 rounded-2xl pl-10 pr-12 py-3.5 text-xs font-bold text-white placeholder-slate-500 outline-none transition-all"
                >
                <button 
                    type="button" 
                    @click="showPassword = !showPassword" 
                    class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 hover:text-white transition-colors"
                    title="Tampilkan / Sembunyikan Password"
                >
                    <i class="fa-solid" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center space-x-2">
            <input 
                id="remember_me" 
                type="checkbox" 
                name="remember"
                class="rounded border-slate-800 bg-slate-950/50 text-primary-600 focus:ring-primary-500/20"
            >
            <label for="remember_me" class="text-[9px] font-black uppercase tracking-wider text-slate-400 select-none cursor-pointer">
                Ingat Saya
            </label>
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button 
                type="submit" 
                class="w-full bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-widest py-4 rounded-2xl shadow-lg shadow-primary-600/10 hover:shadow-primary-600/20 transition-all duration-300 transform hover:-translate-y-0.5"
            >
                Masuk Akun <i class="fa-solid fa-right-to-bracket ml-2"></i>
            </button>
        </div>
    </form>

    <!-- Footer Register link -->
    <div class="text-center pt-2">
        <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wide">Belum punya akun?</span>
        <a href="{{ route('register') }}" class="text-[10px] font-black uppercase text-primary-500 hover:text-primary-600 transition-colors ml-1">
            Daftar Sekarang
        </a>
    </div>
</x-guest-layout>
