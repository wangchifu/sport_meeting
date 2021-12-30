<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'code',
        'semester',
        'edu_key',
        'name',
        'sex',
        'student_year',
        'student_class',
        'num',
        'number',
        'disable',
    ];
}
