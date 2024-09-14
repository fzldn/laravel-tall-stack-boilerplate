<?php

namespace App\Models\Traits;

use App\Enums\Role;

trait HasSuperAdmin
{
    public function isSuperAdmin(): bool
    {
        return $this->hasRole(Role::SUPER_ADMIN);
    }
}
