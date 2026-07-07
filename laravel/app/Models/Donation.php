<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $fillable = [
        'campaign_id',
        'user_id',
        'payment_via_id',
        'donor_name',
        'amount',
        'message',
        'payment_method',
        'payment_status',
        'tracking_status',
        'proof_image',
        'admin_note',
        'donation_date',
    ];

    protected $casts = [
        'donation_date' => 'datetime',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentVia()
    {
        return $this->belongsTo(PaymentVia::class, 'payment_via_id');
    }
}
