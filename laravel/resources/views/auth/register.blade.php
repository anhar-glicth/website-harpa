<x-guest-layout>
    <!-- Heading -->
    <div class="space-y-1">
        <h2 class="text-xl sm:text-2xl font-montserrat font-black uppercase text-white tracking-tight italic">
            Buat <span class="text-primary-500">Akun Baru</span>
        </h2>
        <p class="text-[10px] text-slate-450 font-bold uppercase tracking-widest text-slate-400">Daftar untuk mulai berbagi kebaikan bersama kami</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4 pt-2">
        @csrf

        <!-- Name -->
        <div class="space-y-1.5">
            <label for="name" class="block text-[9px] font-black uppercase tracking-wider text-slate-400">Nama Lengkap</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                    <i class="fa-solid fa-user text-xs"></i>
                </span>
                <input 
                    id="name" 
                    type="text" 
                    name="name" 
                    value="{{ old('name') }}" 
                    required 
                    autofocus 
                    placeholder="Masukkan nama lengkap"
                    class="w-full bg-slate-950/50 border border-slate-800 focus:border-primary-500 rounded-2xl pl-10 pr-4 py-3.5 text-xs font-bold text-white placeholder-slate-500 outline-none transition-all"
                >
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

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
                    placeholder="nama@email.com"
                    class="w-full bg-slate-950/50 border border-slate-800 focus:border-primary-500 rounded-2xl pl-10 pr-4 py-3.5 text-xs font-bold text-white placeholder-slate-500 outline-none transition-all"
                >
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div class="space-y-1.5">
            <label for="password" class="block text-[9px] font-black uppercase tracking-wider text-slate-400">Kata Sandi</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                    <i class="fa-solid fa-lock text-xs"></i>
                </span>
                <input 
                    id="password" 
                    type="password" 
                    name="password" 
                    required 
                    placeholder="Minimal 8 karakter"
                    class="w-full bg-slate-950/50 border border-slate-800 focus:border-primary-500 rounded-2xl pl-10 pr-4 py-3.5 text-xs font-bold text-white placeholder-slate-500 outline-none transition-all"
                >
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Confirm Password -->
        <div class="space-y-1.5">
            <label for="password_confirmation" class="block text-[9px] font-black uppercase tracking-wider text-slate-400">Konfirmasi Sandi</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                    <i class="fa-solid fa-circle-check text-xs"></i>
                </span>
                <input 
                    id="password_confirmation" 
                    type="password" 
                    name="password_confirmation" 
                    required 
                    placeholder="Ulangi kata sandi"
                    class="w-full bg-slate-950/50 border border-slate-800 focus:border-primary-500 rounded-2xl pl-10 pr-4 py-3.5 text-xs font-bold text-white placeholder-slate-500 outline-none transition-all"
                >
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button 
                type="submit" 
                class="w-full bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-widest py-4 rounded-2xl shadow-lg shadow-primary-600/10 hover:shadow-primary-600/20 transition-all duration-300 transform hover:-translate-y-0.5"
            >
                Daftar Akun <i class="fa-solid fa-user-plus ml-2"></i>
            </button>
        </div>
    </form>

    <!-- Footer Login link -->
    <div class="text-center pt-2">
        <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wide">Sudah punya akun?</span>
        <a href="{{ route('login') }}" class="text-[10px] font-black uppercase text-primary-500 hover:text-primary-600 transition-colors ml-1">
            Masuk Sekarang
        </a>
    </div>
</x-guest-layout>
