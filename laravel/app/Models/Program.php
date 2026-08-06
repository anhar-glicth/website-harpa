<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $table = 'programs';

    protected $fillable = ['name', 'program_group_id', 'description', 'status'];

    public function programGroup()
    {
        return $this->belongsTo(ProgramGroup::class, 'program_group_id');
    }
}
