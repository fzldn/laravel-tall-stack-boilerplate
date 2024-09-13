<?php

namespace App\Models;

use App\Enums\RolesEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as ModelsRole;

class Role extends ModelsRole
{
    use HasFactory;

    public function isSuperAdmin(): bool
    {
        return $this->name === RolesEnum::SUPER_ADMIN->value;
    }
}
