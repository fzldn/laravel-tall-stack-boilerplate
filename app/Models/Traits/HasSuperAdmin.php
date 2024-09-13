<?php

namespace App\Models\Traits;

use App\Enums\RolesEnum;

trait HasSuperAdmin
{
    public function isSuperAdmin(): bool
    {
        return $this->hasRole(RolesEnum::SUPER_ADMIN);
    }
}
