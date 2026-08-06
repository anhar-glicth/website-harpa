<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterProspectResponse extends Model
{
    use HasFactory;

    protected $table = 'master_prospect_responses';

    protected $fillable = ['name', 'description', 'status'];
}
