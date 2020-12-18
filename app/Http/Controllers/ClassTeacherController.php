<?php

namespace App\Http\Controllers;

use App\StudentClass;
use App\Action;
use App\Item;
use App\Student;
use App\StudentSign;
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
        $check1 = StudentClass::where('semester', auth()->user()->semester)->where('code', auth()->user()->code)->where('user_ids', auth()->user()->id)->first();
        $check2 = StudentClass::where('semester', auth()->user()->semester)->where('code', auth()->user()->code)->where('user_ids', 'like', auth()->user()->id . ',%')->first();
        $check3 = StudentClass::where('semester', auth()->user()->semester)->where('code', auth()->user()->code)->where('user_ids', 'like', '%,' . auth()->user()->id)->first();

        if(!empty($check1)) $student_class = $check1;
        if(!empty($check2)) $student_class = $check2;
        if(!empty($check3)) $student_class = $check3;



        $actions = Action::where('disable',null)->where('code',auth()->user()->code)->orderBy('id','DESC')->get();
        $data = [
            'actions'=>$actions,
            'student_year'=>$student_class->student_year,
            'student_class'=>$student_class->student_class,
        ];
        return view('class_teachers.sign_up',$data);
    }

    public function sign_up_do(Action $action)
    {
        //不是本校即退回
        if($action->code != auth()->user()->code) return back();

        $check1 = StudentClass::where('semester', auth()->user()->semester)->where('code', auth()->user()->code)->where('user_ids', auth()->user()->id)->first();
        $check2 = StudentClass::where('semester', auth()->user()->semester)->where('code', auth()->user()->code)->where('user_ids', 'like', auth()->user()->id . ',%')->first();
        $check3 = StudentClass::where('semester', auth()->user()->semester)->where('code', auth()->user()->code)->where('user_ids', 'like', '%,' . auth()->user()->id)->first();

        if(!empty($check1)) $student_class = $check1;
        if(!empty($check2)) $student_class = $check2;
        if(!empty($check3)) $student_class = $check3;

        $students = Student::where('code',auth()->user()->code)
            ->where('semester',auth()->user()->semester)
            ->where('student_year',$student_class->student_year)
            ->where('student_class',$student_class->student_class)
            ->orderBy('num')
            ->get();

        foreach($students as $student){
            if($student->sex == "男") $boys[$student->id] = $student->num.'-'.$student->name;
            if($student->sex == "女") $girls[$student->id] = $student->num.'-'.$student->name;
            $all_students[$student->id] = $student->num.'-'.$student->name;
        }

        $items = Item::where('code',auth()->user()->code)
            ->where('disable',null)
            ->orderBy('order')
            ->get();

        $data = [
            'boys'=>$boys,
            'girls'=>$girls,
            'all_students'=>$all_students,
            'action'=>$action,
            'items'=>$items,
            'student_year'=>$student_class->student_year,
            'student_class'=>$student_class->student_class,
        ];
        return view('class_teachers.sign_up_do',$data);
    }

    public function sign_up_add(Request $request)
    {
        $boy_select = $request->input('boy_select');
        $girl_select = $request->input('girl_select');
        foreach($boy_select as $k=>$v){
            foreach($v as $k1=>$v1){
                $att['code'] = auth()->user()->code;
                $att['item_id'] = $k1;
                $item = Item::find($k1);
                $att['item_name'] = $item->name;
                $att['student_id'] = $v1;
                $att['action_id'] = $request->input('action_id');
                $att['student_year'] = $request->input('student_year');
                $att['student_class'] = $request->input('student_class');
                $att['sex'] = "男";

                $check = StudentSign::where('item_id',$k1)
                    ->where('student_id')
                    ->first();
                if(empty($check)){
                    StudentSign::create($att);
                }
            }
        }
        foreach($girl_select as $k=>$v){
            foreach($v as $k1=>$v1){
                $att2['code'] = auth()->user()->code;
                $att2['item_id'] = $k1;
                $item = Item::find($k1);
                $att2['item_name'] = $item->name;
                $att2['student_id'] = $v1;
                $att2['action_id'] = $request->input('action_id');
                $att2['student_year'] = $request->input('student_year');
                $att2['student_class'] = $request->input('student_class');
                $att2['sex'] = "女";

                $check = StudentSign::where('item_id',$k1)
                    ->where('student_id')
                    ->first();
                if(empty($check)){
                    StudentSign::create($att2);
                }
            }
        }

        return redirect()->route('class_teachers.sign_up');
    }

    public function sign_up_show(Action $action)
    {
        //不是本校即退回
        if($action->code != auth()->user()->code) return back();
        $check1 = StudentClass::where('semester', auth()->user()->semester)->where('code', auth()->user()->code)->where('user_ids', auth()->user()->id)->first();
        $check2 = StudentClass::where('semester', auth()->user()->semester)->where('code', auth()->user()->code)->where('user_ids', 'like', auth()->user()->id . ',%')->first();
        $check3 = StudentClass::where('semester', auth()->user()->semester)->where('code', auth()->user()->code)->where('user_ids', 'like', '%,' . auth()->user()->id)->first();

        if(!empty($check1)) $student_class = $check1;
        if(!empty($check2)) $student_class = $check2;
        if(!empty($check3)) $student_class = $check3;


        $items = Item::where('code',auth()->user()->code)
            ->where('disable',null)
            ->orderBy('order')
            ->get();


        $data = [
            'action'=>$action,
            'items'=>$items,
            'student_year'=>$student_class->student_year,
            'student_class'=>$student_class->student_class,
        ];
        return view('class_teachers.sign_up_show',$data);
    }
}
