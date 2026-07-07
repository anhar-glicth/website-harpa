<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramGroup extends Model
{
    protected $fillable = ['name', 'code', 'description'];

    public function campaigns()
    {
        return $this->hasMany(Campaign::class, 'group_id');
    }
}
