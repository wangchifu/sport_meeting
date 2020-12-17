<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function class_teacher_error()
    {
        $data = [
            'words'=>'你不是導師',
        ];
        return view('errors.others',$data);
    }
}
