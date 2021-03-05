<?php

namespace LarAPI\Models\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LarAPI\Models\Auth\Role;

trait HasRole
{
    /**
     * @return BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * @return bool
     */
    public function getIsAdminAttribute(): bool
    {
        return $this->role_id === Role::ROLE_ADMIN;
    }

    /**
     * @return bool
     */
    public function getIsManagerAttribute(): bool
    {
        return $this->role_id === Role::ROLE_MANAGER;
    }

    /**
     * @return bool
     */
    public function getIsUserAttribute(): bool
    {
        return $this->role_id === Role::ROLE_NORMAL;
    }

    /**
     * @return bool
     */
    public function getIsViewerAttribute(): bool
    {
        return $this->role_id === Role::ROLE_VIEWER;
    }

    /**
     * @return string|null
     */
    public function getRoleLabelAttribute(): ?string
    {
        return optional($this->role)->name;
    }
}
