<?php

namespace App\Models;

use App\Models\Traits\LogsModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PermissionRole extends Pivot
{
    use LogsModel;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return config('permission.table_names.role_has_permissions', parent::getTable());
    }

    protected function getLogFirstSubject(): object
    {
        return $this->permission;
    }

    protected function getLogFirstSubjectName(): string
    {
        return $this->permission->label;
    }

    protected function getLogSecondSubject(): object
    {
        return $this->role;
    }

    protected function getLogSecondSubjectName(): string
    {
        return $this->role->name;
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
