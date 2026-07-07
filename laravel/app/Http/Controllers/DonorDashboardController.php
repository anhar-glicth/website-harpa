<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Zakat;
use App\Models\Prayer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

        return view('dashboard', compact('donations', 'totalDonated', 'totalZakat'));
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
            'gender' => 'nullable|in:L,P,Other',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $user->name = $request->name;
        $user->gender = $request->gender;
        $user->phone = $request->phone;
        $user->address = $request->address;

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profiles', 'public');
            $user->profile_photo = '/storage/' . $path;
        }

        $user->save();

        return redirect()->back()->with('success', 'Profil Anda berhasil diperbarui.');
    }
}
