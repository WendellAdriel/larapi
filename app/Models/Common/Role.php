<?php

namespace LarAPI\Models\Common;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public const ROLE_SUPER_ADMIN = 1;
    public const ROLE_MANAGER     = 2;
    public const ROLE_CLIENT      = 3;

    protected $fillable = ['name'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
