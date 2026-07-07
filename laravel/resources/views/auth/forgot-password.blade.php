<x-guest-layout>
    <!-- Heading -->
    <div class="space-y-1">
        <h2 class="text-xl sm:text-2xl font-montserrat font-black uppercase text-white tracking-tight italic">
            Lupa <span class="text-primary-500">Kata Sandi?</span>
        </h2>
        <p class="text-[10px] text-slate-450 font-bold uppercase tracking-widest text-slate-400">Masukkan email Anda untuk memulihkan akses akun</p>
    </div>

    <!-- Info text -->
    <div class="text-[10px] font-semibold text-slate-400 uppercase tracking-wide leading-relaxed">
        Kami akan mengirimkan tautan pemulihan sandi ke alamat email Anda untuk membuat sandi baru.
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4 pt-2">
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

        <!-- Submit Button -->
        <div class="pt-2">
            <button 
                type="submit" 
                class="w-full bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-widest py-4 rounded-2xl shadow-lg shadow-primary-600/10 hover:shadow-primary-600/20 transition-all duration-300 transform hover:-translate-y-0.5"
            >
                Kirim Tautan <i class="fa-solid fa-paper-plane ml-2"></i>
            </button>
        </div>
    </form>

    <!-- Footer Register link -->
    <div class="text-center pt-2">
        <a href="{{ route('login') }}" class="text-[10px] font-black uppercase text-primary-500 hover:text-primary-600 transition-colors">
            Kembali ke Halaman Masuk
        </a>
    </div>
</x-guest-layout>
