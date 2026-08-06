<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramGroup extends Model
{
    use HasFactory;

    protected $table = 'program_groups';

    protected $fillable = ['name', 'code', 'description', 'status'];

    public function campaigns()
    {
        return $this->hasMany(Campaign::class, 'group_id');
    }
}
