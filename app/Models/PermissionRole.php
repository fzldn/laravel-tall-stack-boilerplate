<?php

namespace App\Models;

use App\Models\Traits\LogsModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PermissionRole extends Pivot
{
    use LogsModel;

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return config('permission.table_names.role_has_permissions', parent::getTable());
    }

    protected function getLogFirstSubject(): Model
    {
        return $this->permission;
    }

    protected function getLogFirstSubjectName(): string
    {
        return $this->getLogFirstSubject()->label;
    }

    protected function getLogSecondSubject(): Model
    {
        return $this->role;
    }

    protected function getLogSecondSubjectName(): string
    {
        return $this->getLogSecondSubject()->name;
    }

    /**
     * @return BelongsTo<Role>
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * @return BelongsTo<Permission>
     */
    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class);
    }
}
