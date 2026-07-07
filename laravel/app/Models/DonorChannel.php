<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonorChannel extends Model
{
    protected $fillable = ['name', 'code', 'status'];
}
