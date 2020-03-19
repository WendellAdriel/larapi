<?php

namespace LarAPI\Models\Common;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

use LarAPI\Support\Traits\HasUuid;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use HasUuid;

    protected $fillable = ['name', 'email', 'password'];
    protected $hidden   = ['password', 'remember_token'];
    protected $appends  = ['is_admin', 'is_manager'];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getIsAdminAttribute(): bool
    {
        return $this->role_id === Role::ROLE_SUPER_ADMIN;
    }

    public function getIsManagerAttribute(): bool
    {
        return $this->role_id === Role::ROLE_MANAGER;
    }
}
