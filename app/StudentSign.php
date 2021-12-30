<?php

namespace App;

use App\Student;
use Illuminate\Database\Eloquent\Model;
use App\Item;
use App\Action;

class StudentSign extends Model
{
    protected $fillable = [
        'code',
        'item_id',
        'item_name',
        'game_type',
        'is_official',
        'group_num',
        'student_id',
        'action_id',
        'student_year',
        'student_class',
        'num',
        'sex',
        'achievement',
        'ranking',
        'order',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function get_student_class()
    {
        return $this->belongsTo(StudentClass::class,'student_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function action()
    {
        return $this->belongsTo(Action::class);
    }
}
