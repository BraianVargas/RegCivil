<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Person extends Model
{
    use LogsActivity;

    protected $table = 'persons';

    protected $fillable = [
        'first_name',
        'last_name',
        'document_number',
        'document_type_id',
        'birth_date',
        'address',
        'location',
        'postal_code',
        'phone',
        'cell_phone',
        'another_phone',
        'email',
        'gender'
    ];
}
