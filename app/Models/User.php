<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;

use Spatie\Permission\Traits\HasRoles;
use Filament\Panel;
use Filament\Models\Contracts\FilamentUser;

<<<<<<< HEAD:hr-callcenter-system/app/Models/User.php
class User extends Authenticatable implements HasAvatar
=======
class User extends Authenticatable implements FilamentUser
>>>>>>> eda5f637f61aba7a99db1ae1b51ac1ad4e697aba:app/Models/User.php
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'woreda_id',
        'sub_city_id',
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
        ];
    }

<<<<<<< HEAD:hr-callcenter-system/app/Models/User.php
    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->employee?->photo_url;
=======
    public function woreda()
    {
        return $this->belongsTo(Woreda::class);
    }

    public function subCity()
    {
        return $this->belongsTo(SubCity::class);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        // For this HR system, all authenticated users with roles can access the panel.
        return true;
>>>>>>> eda5f637f61aba7a99db1ae1b51ac1ad4e697aba:app/Models/User.php
    }
}
