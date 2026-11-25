<x-guest-layout>
    <div class="space-y-1">
        <h1 class="text-xl sm:text-2xl font-montserrat font-black uppercase text-slate-900 tracking-tight">
            Lupa <span class="text-primary-600">Kata Sandi?</span>
        </h1>
        <p class="text-xs text-slate-600 font-semibold">Masukkan email terdaftar untuk menerima 6 digit kode OTP</p>
    </div>

    <div class="text-xs font-medium text-slate-600 leading-relaxed pt-1">
        Kami akan mengirimkan 6 digit kode OTP verifikasi ke email Anda untuk membuat kata sandi baru.
    </div>

    <x-auth-session-status class="mb-2 mt-2" :status="session('status')" />

    @if ($errors->has('email'))
        <div class="p-3.5 bg-rose-50 border border-rose-200 rounded-xl space-y-1.5 mt-2">
            <div class="flex items-start space-x-2">
                <i class="fa-solid fa-circle-exclamation text-rose-600 text-sm mt-0.5 shrink-0" aria-hidden="true"></i>
                <p class="text-xs font-bold text-rose-800 leading-relaxed">
                    {{ $errors->first('email') }}
                </p>
            </div>
            @if (str_contains($errors->first('email'), 'tidak terdaftar'))
                <div class="pt-1 pl-6">
                    <a href="{{ route('register') }}" class="inline-flex items-center text-xs font-black uppercase tracking-wider text-primary-700 hover:text-primary-800 underline">
                        <i class="fa-solid fa-user-plus mr-1.5" aria-hidden="true"></i> Daftar Akun Baru Sekarang
                    </a>
                </div>
            @endif
        </div>
    @endif

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
                    value="{{ old('email', request('email')) }}" 
                    required 
                    autofocus 
                    placeholder="nama@email.com"
                    class="w-full bg-slate-50 border {{ $errors->has('email') ? 'border-rose-400' : 'border-slate-300' }} focus:border-primary-600 rounded-xl pl-10 pr-4 py-3 text-xs font-bold text-slate-900 placeholder-slate-500 transition-colors"
                >
            </div>
        </div>

        <div class="pt-2">
            <button 
                type="submit" 
                class="w-full min-h-[44px] bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-wider py-3.5 rounded-xl shadow-md transition-colors"
            >
                <i class="fa-solid fa-paper-plane mr-1.5" aria-hidden="true"></i> Kirim Kode OTP (6 Digit)
            </button>
        </div>
    </form>

    <div class="text-center pt-2">
        <a href="{{ route('login') }}" class="text-xs font-bold text-primary-700 hover:underline transition-colors">
            <i class="fa-solid fa-arrow-left mr-1" aria-hidden="true"></i> Kembali ke Halaman Masuk
        </a>
    </div>
</x-guest-layout>
