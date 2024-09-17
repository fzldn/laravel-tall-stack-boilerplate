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

    public function logDescription(string $eventName): string
    {
        return __('Permission <strong>:permission.name</strong> was <strong>:event</strong> :to Role <strong>:role.name</strong> by :causer.name', [
            'permission.name' => e($this->permission->label),
            'event' => match ($eventName) {
                'created' => __('attached'),
                'deleted' => __('detached'),
                default => $eventName,
            },
            'to' => match ($eventName) {
                'created' => __('to'),
                'deleted' => __('from'),
                default => __('for'),
            },
            'role.name' => e($this->role->name),
            'causer.name' => $this->getLogCauserName(),
        ]);
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
