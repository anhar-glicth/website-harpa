<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Distribution extends Model
{
    protected $fillable = [
        'distribution_code',
        'program_id',
        'worker_id',
        'title',
        'description',
        'latitude',
        'longitude',
        'location_name',
        'photo_url',
        'maps_link',
        'recipient_name',
        'pic_name',
        'pic_phone',
        'amount',
        'distribution_date',
        'status',
        'notes',
        'asnaf',
        'city',
        'province',
        'source_of_fund',
        'pm_count',
        'category_name',
    ];

    protected $casts = [
        'distribution_date' => 'date',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'program_id');
    }

    public function worker()
    {
        return $this->belongsTo(User::class, 'worker_id');
    }
}
