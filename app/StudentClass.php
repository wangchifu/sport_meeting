<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentClass extends Model
{
    protected $fillable = [
        'code',
        'semester',
        'student_year',
        'student_class',
        'user_ids',
        'user_names',
    ];

}
