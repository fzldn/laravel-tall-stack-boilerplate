<?php

namespace App\Models;

use App\Enums\Role as EnumsRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Models\Role as ModelsRole;

class Role extends ModelsRole
{
    use HasFactory;

    public function isSuperAdmin(): bool
    {
        return $this->name === EnumsRole::SUPER_ADMIN->value;
    }

    /**
     * The users that belong to the role.
     *
     * @return BelongsToMany<User>
     */
    public function users(): BelongsToMany
    {
        return parent::users()->using(RoleUser::class);
    }
}
