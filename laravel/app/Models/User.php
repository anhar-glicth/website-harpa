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

    /**
     * Get the user's avatar URL.
     */
    public function getProfilePhotoUrlAttribute(): string
    {
        if (!empty($this->profile_photo)) {
            if (str_starts_with($this->profile_photo, 'http://') || 
                str_starts_with($this->profile_photo, 'https://') || 
                str_starts_with($this->profile_photo, 'data:image/')) {
                return $this->profile_photo;
            }

            $cacheBust = $this->updated_at ? '?v=' . $this->updated_at->timestamp : '';
            return asset(ltrim($this->profile_photo, '/')) . $cacheBust;
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name ?? 'User') . '&background=ea580c&color=fff';
    }

    /**
     * Check if user has uploaded a custom profile photo.
     */
    public function hasCustomPhoto(): bool
    {
        return !empty($this->profile_photo);
    }

    // Role Constants
    public const ROLE_ADMIN = 'admin';
    public const ROLE_OPERATOR = 'operator';
    public const ROLE_FINANCE = 'finance';
    public const ROLE_PROGRAM_MANAGER = 'program_manager';
    public const ROLE_CRM_STAFF = 'crm_staff';
    public const ROLE_FIELD_WORKER = 'field_worker';
    public const ROLE_MARKETING = 'marketing';
    public const ROLE_USER = 'user';

    // Role helper methods
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isMarketing(): bool
    {
        return $this->role === self::ROLE_MARKETING;
    }

    public function isOperator(): bool
    {
        return $this->role === self::ROLE_OPERATOR;
    }

    public function isFinance(): bool
    {
        return $this->role === self::ROLE_FINANCE;
    }

    public function isProgramManager(): bool
    {
        return $this->role === self::ROLE_PROGRAM_MANAGER;
    }

    public function isCrmStaff(): bool
    {
        return $this->role === self::ROLE_CRM_STAFF;
    }

    public function isFieldWorker(): bool
    {
        return $this->role === self::ROLE_FIELD_WORKER;
    }

    public function isUser(): bool
    {
        return $this->role === self::ROLE_USER;
    }

    public function hasAdminAccess(): bool
    {
        return in_array($this->role, [
            self::ROLE_ADMIN,
            self::ROLE_OPERATOR,
            self::ROLE_FINANCE,
            self::ROLE_PROGRAM_MANAGER,
            self::ROLE_CRM_STAFF,
            self::ROLE_MARKETING,
        ]);
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            self::ROLE_ADMIN => 'Super Admin',
            self::ROLE_OPERATOR => 'Admin Data Entry',
            self::ROLE_FINANCE => 'Staff Keuangan',
            self::ROLE_PROGRAM_MANAGER => 'Manager Program',
            self::ROLE_CRM_STAFF => 'Staff CRM & Telemarketing',
            self::ROLE_FIELD_WORKER => 'Petugas Lapangan',
            self::ROLE_MARKETING => 'Staff Marketing & Meta Ads',
            default => 'Donatur',
        };
    }

    public function canAccessMenu(string $menuKey): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        return match ($this->role) {
            self::ROLE_OPERATOR => in_array($menuKey, [
                'dashboard', 'offices', 'donor_channels', 'payment_via',
                'prospect_media', 'prospect_methods', 'prospect_types', 'fundraisers',
                'prospect_responses', 'donors_list', 'transactions_list', 'transactions_dashboard',
                'prospects_list', 'potential_donors', 'attendance', 'tasks'
            ]),
            self::ROLE_FINANCE => in_array($menuKey, [
                'dashboard', 'analytics', 'donations', 'payment_via', 'donors_list',
                'transactions_list', 'transactions_dashboard', 'donors_dashboard', 'target_vs_achievement',
                'distribution_dashboard', 'distributions', 'attendance', 'tasks'
            ]),
            self::ROLE_PROGRAM_MANAGER => in_array($menuKey, [
                'dashboard', 'campaigns', 'program_groups', 'programs',
                'distribution_dashboard', 'distributions', 'donors_list', 'attendance', 'tasks'
            ]),
            self::ROLE_CRM_STAFF => in_array($menuKey, [
                'dashboard', 'analytics', 'prospect_media', 'prospect_methods',
                'prospect_types', 'prospect_responses', 'targets', 'donors_list', 'donors_dashboard',
                'prospects_list', 'potential_donors', 'prospects_dashboard', 'target_vs_achievement',
                'rfm_dashboard', 'attendance', 'tasks'
            ]),
            self::ROLE_MARKETING => in_array($menuKey, [
                'dashboard', 'marketing_dashboard', 'marketing_input', 'analytics', 'campaigns', 'attendance', 'tasks'
            ]),
            default => false,
        };
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

    public function latestDonation()
    {
        return $this->hasOne(Donation::class)->latestOfMany();
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
