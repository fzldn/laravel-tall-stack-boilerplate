<?php

namespace App\Models;

use App\Models\Traits\LogsModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphPivot;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class RoleUser extends MorphPivot
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
        return config('permission.table_names.model_has_roles', parent::getTable());
    }

    public function logDescription(string $eventName): string
    {
        return __('Role <strong>:role.name</strong> was <strong>:event</strong> :to User <strong>:user.name</strong> by :causer.name', [
            'role.name' => e($this->role->name),
            'event' => match ($eventName) {
                'created' => __('assigned'),
                'deleted' => __('revoked'),
                default => $eventName,
            },
            'to' => match ($eventName) {
                'created' => __('to'),
                'deleted' => __('from'),
                default => __('for'),
            },
            'user.name' => e($this->user->name),
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
     * @return MorphTo
     */
    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return MorphTo<User>
     */
    public function user(): MorphTo
    {
        return $this->model()->whereHasMorph('model', [User::class]);
    }
}
