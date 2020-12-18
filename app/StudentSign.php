<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Student;
use App\Item;

class StudentSign extends Model
{
    protected $fillable = [
        'code',
        'item_id',
        'item_name',
        'student_id',
        'action_id',
        'student_year',
        'student_class',
        'sex',
        'achievement',
        'ranking',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
