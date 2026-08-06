<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'karyawans';

    protected $fillable = [
        'karyawan_code',
        'nama_lengkap',
        'jabatan',
        'no_hp',
        'email',
        'alamat',
        'status',
        'office_id',
        'parent_id',
    ];

    public function office()
    {
        return $this->belongsTo(Office::class, 'office_id');
    }

    public function parent()
    {
        return $this->belongsTo(Karyawan::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Karyawan::class, 'parent_id');
    }
}
