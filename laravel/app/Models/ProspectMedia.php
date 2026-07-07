<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProspectMedia extends Model
{
    protected $table = 'prospect_media';
    protected $fillable = ['name', 'status'];
}
