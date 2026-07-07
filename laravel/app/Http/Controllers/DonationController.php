<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\PaymentVia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonationController extends Controller
{
    public function checkout($campaign_id)
    {
        $campaign = Campaign::findOrFail($campaign_id);
        $payments = PaymentVia::where('status', 'Aktif')->get();

        return view('donations.checkout', compact('campaign', 'payments'));
    }

    public function submitCheckout(Request $request, $campaign_id)
    {
        $request->validate([
            'donor_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1000',
            'payment_via_id' => 'required|exists:payment_via,id',
            'message' => 'nullable|string',
        ]);

        $campaign = Campaign::findOrFail($campaign_id);
        $paymentVia = PaymentVia::findOrFail($request->payment_via_id);

        $donation = Donation::create([
            'campaign_id' => $campaign->id,
            'user_id' => Auth::id(),
            'payment_via_id' => $paymentVia->id,
            'donor_name' => $request->donor_name,
            'amount' => $request->amount,
            'message' => $request->message,
            'payment_method' => $paymentVia->name,
            'payment_status' => 'Pending',
            'tracking_status' => 'Diterima',
        ]);

        return redirect()->route('donations.receipt', $donation->id);
    }

    public function receipt($id)
    {
        $donation = Donation::with(['campaign', 'paymentVia'])->findOrFail($id);
        return view('donations.receipt', compact('donation'));
    }

    public function uploadProof(Request $request, $id)
    {
        $request->validate([
            'proof_image' => 'required|image|max:4096',
        ]);

        $donation = Donation::findOrFail($id);

        if ($request->hasFile('proof_image')) {
            $path = $request->file('proof_image')->store('proofs', 'public');
            $donation->update([
                'proof_image' => '/storage/' . $path,
            ]);
        }

        return redirect()->back()->with('success', 'Bukti transfer berhasil diunggah. Menunggu verifikasi admin.');
    }
}
