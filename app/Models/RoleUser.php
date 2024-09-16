<?php

namespace App\Models;

use App\Models\Traits\LogsModel;
use Illuminate\Database\Eloquent\Relations\MorphPivot;

class RoleUser extends MorphPivot
{
    use LogsModel;
}
