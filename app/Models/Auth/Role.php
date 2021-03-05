<?php

namespace LarAPI\Models\Auth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    public const ROLE_ADMIN   = 1;
    public const ROLE_MANAGER = 2;
    public const ROLE_NORMAL  = 3;
    public const ROLE_VIEWER  = 4;

    public const ROLE_ADMIN_LABEL   = 'admin';
    public const ROLE_MANAGER_LABEL = 'manager';
    public const ROLE_NORMAL_LABEL  = 'user';
    public const ROLE_VIEWER_LABEL  = 'viewer';

    protected $fillable = ['name'];

    /**
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
