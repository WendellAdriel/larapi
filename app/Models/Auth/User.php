<?php

namespace LarAPI\Models\Auth;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use LarAPI\Models\Traits\HasRole;
use Tymon\JWTAuth\Contracts\JWTSubject;

use LarAPI\Models\Traits\HasUuid;

class User extends Authenticatable implements JWTSubject
{
    use HasUuid;
    use HasRole;
    use Notifiable;

    protected $fillable = ['name', 'email', 'password', 'active', 'role_id', 'settings'];
    protected $hidden   = ['password'];
    protected $appends  = ['is_admin', 'is_manager', 'is_user', 'is_viewer', 'role_label'];

    protected $casts = [
        'active'     => 'boolean',
        'last_login' => 'datetime',
        'settings'   => 'array'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
