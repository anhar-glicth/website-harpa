<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentVia extends Model
{
    protected $table = 'payment_via';
    protected $fillable = ['name', 'provider', 'account_no', 'status'];

    public function donations()
    {
        return $this->hasMany(Donation::class, 'payment_via_id');
    }
}
