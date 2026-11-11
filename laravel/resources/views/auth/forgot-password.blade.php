<x-guest-layout>
    <div class="space-y-1">
        <h1 class="text-xl sm:text-2xl font-montserrat font-black uppercase text-slate-900 tracking-tight">
            Lupa <span class="text-primary-600">Kata Sandi?</span>
        </h1>
        <p class="text-xs text-slate-600 font-semibold">Masukkan email Anda untuk memulihkan akses akun</p>
    </div>

    <div class="text-xs font-medium text-slate-600 leading-relaxed">
        Kami akan mengirimkan tautan pemulihan sandi ke alamat email Anda untuk membuat sandi baru.
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4 pt-2">
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

        <div class="pt-2">
            <button 
                type="submit" 
                class="w-full min-h-[44px] bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-wider py-3.5 rounded-xl shadow-md transition-colors"
            >
                Kirim Tautan Pemulihan
            </button>
        </div>
    </form>

    <div class="text-center pt-2">
        <a href="{{ route('login') }}" class="text-xs font-black uppercase text-primary-700 hover:underline transition-colors">
            Kembali ke Halaman Masuk
        </a>
    </div>
</x-guest-layout>
