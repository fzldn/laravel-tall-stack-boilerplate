<?php

namespace App\Models;

use App\Models\Scopes\OrderByIdDesc;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
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
}
