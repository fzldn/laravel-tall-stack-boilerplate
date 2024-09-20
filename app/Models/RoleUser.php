<?php

namespace App\Models;

use App\Models\Traits\LogsModel;
use Illuminate\Database\Eloquent\Model;
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

    protected function getLogFirstSubject(): Model
    {
        return $this->role;
    }

    protected function getLogFirstSubjectName(): string
    {
        return $this->getLogFirstSubject()->name;
    }

    protected function getLogSecondSubject(): Model
    {
        return $this->user;
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
