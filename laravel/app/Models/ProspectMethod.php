<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProspectMethod extends Model
{
    use HasFactory;

    protected $table = 'prospect_methods';

    protected $fillable = ['name', 'description', 'status'];
}
