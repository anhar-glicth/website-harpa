<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Zakat;
use App\Models\Prayer;
use App\Models\User;
use App\Models\Attendance;
use App\Models\TodoList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DonorDashboardController extends Controller
{
    public function dashboard()
    {
        $userId = Auth::id();
        $donations = Donation::with('campaign')
            ->where('user_id', $userId)
            ->latest()
            ->paginate(5);
        $totalDonated = Donation::where('user_id', $userId)
            ->where('payment_status', 'Success')
            ->sum('amount');
        $totalZakat = Zakat::where('user_id', $userId)
            ->where('payment_status', 'Success')
            ->sum('amount');

        $todayAttendance = Attendance::where('user_id', $userId)
            ->whereDate('created_at', now()->toDateString())
            ->get();
        $todos = TodoList::where('user_id', $userId)->latest()->get();

        return view('dashboard', compact('donations', 'totalDonated', 'totalZakat', 'todayAttendance', 'todos'));
    }

    public function zakatIndex()
    {
        $zakats = Zakat::where('user_id', Auth::id())->latest()->paginate(10);
        return view('donations.zakat', compact('zakats'));
    }

    public function submitZakat(Request $request)
    {
        $request->validate([
            'zakat_type' => 'required|string',
            'amount' => 'required|numeric|min:1000',
        ]);

        Zakat::create([
            'user_id' => Auth::id(),
            'zakat_type' => $request->zakat_type,
            'amount' => $request->amount,
            'payment_status' => 'Pending',
        ]);

        return redirect()->back()->with('success', 'Transaksi zakat berhasil dibuat. Silahkan lakukan pembayaran dan hubungi admin.');
    }

    public function prayers()
    {
        $prayers = Prayer::with('user')->latest()->paginate(15);
        return view('prayers.index', compact('prayers'));
    }

    public function submitPrayer(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'is_anonymous' => 'nullable|boolean',
        ]);

        Prayer::create([
            'user_id' => Auth::id(),
            'name' => Auth::user()->name,
            'message' => $request->message,
            'is_anonymous' => $request->has('is_anonymous'),
        ]);

        return redirect()->back()->with('success', 'Doa Anda berhasil diposting.');
    }

    public function upvotePrayer($id)
    {
        $prayer = Prayer::findOrFail($id);
        $prayer->increment('aamiin_count');

        return redirect()->back()->with('success', 'Aamiin!');
    }

    public function profile()
    {
        return view('profile.edit-custom');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'gender' => 'nullable|in:L,P,Other',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'profile_photo' => 'nullable|file|mimes:jpeg,jpg,png,webp,gif,svg|max:5120',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:6|confirmed',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
            'username.unique' => 'Username ini sudah digunakan oleh akun lain.',
            'profile_photo.file' => 'Berkas foto profil harus berupa file yang valid.',
            'profile_photo.mimes' => 'Format foto profil harus berupa JPG, JPEG, PNG, WEBP, GIF, atau SVG.',
            'profile_photo.max' => 'Ukuran foto profil tidak boleh melebihi 5MB.',
            'current_password.required_with' => 'Password lama wajib diisi jika ingin mengganti password baru.',
            'new_password.min' => 'Password baru minimal 6 karakter.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        // 1. Handle Penghapusan Foto Profil (kembali ke inisial default)
        if ($request->boolean('remove_photo')) {
            if ($user->profile_photo) {
                $oldPath = str_replace('/storage/', '', $user->profile_photo);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            $user->profile_photo = null;
        }

        // 2. Handle Upload Foto Profil Baru
        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo) {
                $oldPath = str_replace('/storage/', '', $user->profile_photo);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            $path = $request->file('profile_photo')->store('profiles', 'public');
            $user->profile_photo = '/storage/' . $path;
        }

        // 3. Handle Ubah Password
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.'])->withInput();
            }
            $user->password = Hash::make($request->new_password);
        }

        // 4. Update Field Identitas (Sanitasi string kosong menjadi NULL untuk hindari pelanggaran unique constraint)
        $user->name = trim($request->name);
        $user->username = $request->filled('username') ? trim($request->username) : null;
        $user->email = trim($request->email);
        $user->gender = $request->filled('gender') ? $request->gender : null;
        $user->phone = $request->filled('phone') ? trim($request->phone) : null;
        $user->address = $request->filled('address') ? trim($request->address) : null;

        $user->save();

        return redirect()->back()->with('success', 'Profil dan foto Anda berhasil diperbarui!');
    }
}
