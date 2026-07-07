<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'gender',
        'phone',
        'address',
        'profile_photo',
        'office_id',
        'pic_prospect_id',
        'last_prospect_media_id',
        'segment',
        'rfm_alltime',
        'prospect_date',
        'source_info',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'prospect_date' => 'date',
        ];
    }

    // Role helper methods
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isFieldWorker(): bool
    {
        return $this->role === 'field_worker';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    // Relations
    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    public function picProspect()
    {
        return $this->belongsTo(User::class, 'pic_prospect_id');
    }

    public function prospectsManaged()
    {
        return $this->hasMany(User::class, 'pic_prospect_id');
    }

    public function lastProspectMedia()
    {
        return $this->belongsTo(ProspectMedia::class, 'last_prospect_media_id');
    }

    public function campaigns()
    {
        return $this->hasMany(Campaign::class, 'author_id');
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function todos()
    {
        return $this->hasMany(TodoList::class);
    }

    public function zakats()
    {
        return $this->hasMany(Zakat::class);
    }
}
