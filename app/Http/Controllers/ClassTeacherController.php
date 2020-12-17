<?php

namespace App\Http\Controllers;

use App\StudentClass;
use Illuminate\Http\Request;

class ClassTeacherController extends Controller
{
    public function __construct()
    {
        //檢查是不是導師
        $this->middleware('class_teacher');
    }

    public function sign_up()
    {

        $data = [

        ];
        return view('class_teachers.sign_up',$data);
    }
}
