<?php

namespace App\Models;

use App\Models\Scopes\OrderByIdDesc;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\HtmlString;
use Spatie\Activitylog\Models\Activity as ModelsActivity;

#[ScopedBy(OrderByIdDesc::class)]
class Activity extends ModelsActivity
{
    public function scopeForSubject(Builder $query, Model $subject): Builder
    {
        return $query->where(function ($q) use ($subject) {
            parent::scopeForSubject($q, $subject);

            if (method_exists($subject, 'logIncludes')) {
                $q->orWhere(fn($q2) => $subject->logIncludes($q2));
            }
        });
    }

    /**
     * Get the activity's description formatted.
     */
    protected function descriptionFormatted(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->subject instanceof Pivot) {
                    return new HtmlString(sprintf(
                        '%s <strong>%s</strong> was <strong>%s</strong> to %s <strong>%s</strong> by %s',
                        match ($this->subject_type) {
                            RoleUser::class => 'Role',
                            PermissionRole::class => 'Permission',
                        },
                        match ($this->subject_type) {
                            RoleUser::class => $this->subject->role->name,
                            PermissionRole::class => $this->subject->permission->label,
                        },
                        match ($this->event) {
                            'created' => 'attached',
                            'deleted' => 'detached',
                            default => $this->event,
                        },
                        match ($this->subject_type) {
                            RoleUser::class => 'User',
                            PermissionRole::class => 'Role',
                        },
                        match ($this->subject_type) {
                            RoleUser::class => $this->subject->user->name,
                            PermissionRole::class => $this->subject->role->name,
                        },
                        $this->causer?->name ? "<strong>{$this->causer->name}</strong>" :  '<em>' . __('System') . '</em>',
                    ));
                }

                return new HtmlString(sprintf(
                    '%s <strong>%s</strong> was <strong>%s</strong> by %s',
                    str(class_basename($this->subject))->headline(),
                    $this->subject->name,
                    $this->event,
                    $this->causer?->name ? "<strong>{$this->causer->name}</strong>" :  '<em>' . __('System') . '</em>',
                ));
            },
        );
    }
}
