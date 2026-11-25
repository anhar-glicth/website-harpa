<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request via 6-digit OTP code.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp' => ['required', 'string', 'size:6'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'otp.required' => 'Kode OTP 6 digit wajib diisi.',
            'otp.size' => 'Kode OTP harus berupa 6 digit angka.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Alamat email tidak terdaftar di sistem kami.']);
        }

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$record) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['otp' => 'Permintaan reset kata sandi tidak ditemukan atau sudah kedaluwarsa. Silakan minta kode OTP baru.']);
        }

        // OTP expiration check (15 minutes)
        if (Carbon::parse($record->created_at)->addMinutes(15)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            return back()
                ->withInput($request->only('email'))
                ->withErrors(['otp' => 'Kode OTP ini sudah kedaluwarsa (berlaku 15 menit). Silakan klik "Kirim Ulang Kode OTP".']);
        }

        // Verify OTP against hashed token
        if (!Hash::check($request->otp, $record->token)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['otp' => 'Kode OTP yang Anda masukkan salah. Silakan periksa kembali email Anda.']);
        }

        // Success: update user password
        $user->forceFill([
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(60),
        ])->save();

        // Invalidate OTP
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        event(new PasswordReset($user));

        return redirect()->route('login')
            ->with('status', 'Kata sandi Anda berhasil diperbarui. Silakan masuk dengan kata sandi baru Anda.');
    }
}
