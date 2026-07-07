<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $fillable = [
        'author_id',
        'group_id',
        'title',
        'description',
        'category',
        'target_amount',
        'current_amount',
        'foto',
        'status',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function group()
    {
        return $this->belongsTo(ProgramGroup::class, 'group_id');
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function distributions()
    {
        return $this->hasMany(Distribution::class, 'program_id');
    }

    public function updates()
    {
        return $this->hasMany(CampaignUpdate::class);
    }

    public function targets()
    {
        return $this->hasMany(Target::class, 'program_id');
    }
}
