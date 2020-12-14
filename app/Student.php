<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'semester',
        'edu_key',
        'name',
        'sex',
        'student_year',
        'student_class',
        'num',
    ];
}
