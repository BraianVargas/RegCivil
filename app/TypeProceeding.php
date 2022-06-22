<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class TypeProceeding extends Model
{
    use LogsActivity;

    protected $table = 'types_proceedings';
}
