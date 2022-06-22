<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Turn extends Model
{
    use LogsActivity;

    protected $fillable = ['date', 'time' , 'creator_id', 'delegation_id', 'allocator_id', 'person_id', 'type_procedure_id', 'active', 'duration'];

}
