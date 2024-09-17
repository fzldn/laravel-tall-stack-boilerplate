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
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return config('permission.table_names.model_has_roles', parent::getTable());
    }

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * @return BelongsTo<Role>
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * @return MorphTo<User>
     */
    public function user(): MorphTo
    {
        return $this->morphTo('model');
    }
}
