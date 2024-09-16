<?php

namespace App\Models;

use App\Models\Scopes\OrderByIdDesc;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Spatie\Activitylog\Models\Activity as ModelsActivity;

#[ScopedBy(OrderByIdDesc::class)]
class Activity extends ModelsActivity
{
    //
}
