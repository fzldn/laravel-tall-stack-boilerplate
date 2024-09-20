<?php

namespace App\Models;

use App\Enums\Role as EnumsRole;
use App\Models\Traits\LogsModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Spatie\Permission\Models\Role as ModelsRole;

class Role extends ModelsRole
{
    use HasFactory;
    use LogsModel;

    public function logIncludes(Builder $query): Builder
    {
        return $query
            ->where('subject_type', Relation::getMorphAlias(PermissionRole::class))
            ->where(function ($q) {
                $rolePivotKey = config('permission.column_names.role_pivot_key') ?? 'role_id';

                $q
                    ->where("properties->attributes->{$rolePivotKey}", $this->getKey())
                    ->orWhere("properties->old->{$rolePivotKey}", $this->getKey());
            });
    }

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

    /**
     * The permissions that belong to the role.
     *
     * @return BelongsToMany<Permission>
     */
    public function permissions(): BelongsToMany
    {
        return parent::permissions()->using(PermissionRole::class);
    }
}
