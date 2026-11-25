<x-guest-layout>
    <div class="space-y-1 mb-2">
        <h1 class="text-xl sm:text-2xl font-montserrat font-black uppercase text-slate-900 tracking-tight">
            Verifikasi <span class="text-primary-600">Kode OTP</span>
        </h1>
        <p class="text-xs text-slate-600 font-semibold">Masukkan 6 digit kode OTP dari email dan buat kata sandi baru</p>
    </div>

    <x-auth-session-status class="mb-3" :status="session('status')" />

    @if ($errors->has('otp'))
        <div class="p-3 bg-rose-50 border border-rose-200 rounded-xl flex items-start space-x-2 mb-3">
            <i class="fa-solid fa-circle-exclamation text-rose-600 text-sm mt-0.5 shrink-0" aria-hidden="true"></i>
            <p class="text-xs font-bold text-rose-800 leading-relaxed">
                {{ $errors->first('otp') }}
            </p>
        </div>
    @endif

    <form method="POST" action="{{ route('password.store') }}" class="space-y-4 pt-1">
        @csrf

        <!-- Email Address -->
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
                    value="{{ old('email', session('email', $request->email)) }}"
                    required
                    placeholder="nama@email.com"
                    class="w-full bg-slate-50 border border-slate-300 focus:border-primary-600 rounded-xl pl-10 pr-4 py-3 text-xs font-bold text-slate-900 placeholder-slate-500 transition-colors"
                >
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- 6-Digit OTP Code -->
        <div class="space-y-1.5">
            <div class="flex justify-between items-center">
                <label for="otp" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Kode OTP (6 Digit)</label>
                <span class="text-[11px] font-semibold text-slate-500"><i class="fa-regular fa-clock mr-1" aria-hidden="true"></i>Berlaku 15 menit</span>
            </div>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-500">
                    <i class="fa-solid fa-shield-halved text-xs" aria-hidden="true"></i>
                </span>
                <input
                    id="otp"
                    type="text"
                    name="otp"
                    inputmode="numeric"
                    pattern="[0-9]*"
                    maxlength="6"
                    value="{{ old('otp') }}"
                    required
                    autofocus
                    placeholder="123456"
                    class="w-full bg-slate-50 border {{ $errors->has('otp') ? 'border-rose-400' : 'border-slate-300' }} focus:border-primary-600 rounded-xl pl-10 pr-4 py-3 text-base font-black text-center tracking-[0.4em] font-mono text-slate-900 placeholder-slate-400 transition-colors"
                >
            </div>
        </div>

        <!-- New Password -->
        <div class="space-y-1.5" x-data="{ showPassword: false }">
            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Kata Sandi Baru</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-500">
                    <i class="fa-solid fa-lock text-xs" aria-hidden="true"></i>
                </span>
                <input
                    id="password"
                    :type="showPassword ? 'text' : 'password'"
                    name="password"
                    required
                    autocomplete="new-password"
                    placeholder="Minimal 8 karakter"
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

        <!-- Confirm Password -->
        <div class="space-y-1.5" x-data="{ showConfirm: false }">
            <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Konfirmasi Kata Sandi Baru</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-500">
                    <i class="fa-solid fa-lock text-xs" aria-hidden="true"></i>
                </span>
                <input
                    id="password_confirmation"
                    :type="showConfirm ? 'text' : 'password'"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="Ulangi kata sandi baru"
                    class="w-full bg-slate-50 border border-slate-300 focus:border-primary-600 rounded-xl pl-10 pr-12 py-3 text-xs font-bold text-slate-900 placeholder-slate-500 transition-colors"
                >
                <button
                    type="button"
                    @click="showConfirm = !showConfirm"
                    :aria-label="showConfirm ? 'Sembunyikan konfirmasi kata sandi' : 'Lihat konfirmasi kata sandi'"
                    class="absolute inset-y-0 right-0 flex items-center justify-center px-3 min-w-[44px] text-slate-600 hover:text-primary-600 transition-colors"
                >
                    <i class="fa-solid" :class="showConfirm ? 'fa-eye-slash' : 'fa-eye'" aria-hidden="true"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <div class="pt-2">
            <button
                type="submit"
                class="w-full min-h-[44px] bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-wider py-3.5 rounded-xl shadow-md transition-colors"
            >
                Simpan Kata Sandi Baru
            </button>
        </div>
    </form>

    <!-- Resend OTP Option -->
    <div class="border-t border-slate-100 pt-3 mt-3 text-center space-y-2">
        <p class="text-xs text-slate-600 font-semibold">
            Belum menerima kode OTP atau sudah kedaluwarsa?
        </p>
        <form method="POST" action="{{ route('password.email') }}" class="inline-block">
            @csrf
            <input type="hidden" name="email" value="{{ old('email', session('email', $request->email)) }}">
            <button type="submit" class="text-xs font-black uppercase text-primary-700 hover:text-primary-800 underline transition-colors">
                <i class="fa-solid fa-rotate-right mr-1" aria-hidden="true"></i> Kirim Ulang Kode OTP
            </button>
        </form>
    </div>

    <div class="text-center pt-2">
        <a href="{{ route('login') }}" class="text-xs font-bold text-slate-600 hover:text-primary-700 transition-colors">
            <i class="fa-solid fa-arrow-left mr-1" aria-hidden="true"></i> Kembali ke Halaman Masuk
        </a>
    </div>
</x-guest-layout>
