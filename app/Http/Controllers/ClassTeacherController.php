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
            ->where('action_id',$action->id)
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
        $action = Action::find($request->input('action_id'));

        //檢查是否超出報名限制
        foreach($boy_select as $k=>$v){
            foreach($v as $k1=>$v1) {
                $check_item = Item::find($k1);
                if($check_item->limit){
                    if(!isset($check_name1[$v1])) $check_name1[$v1] = 0;
                    $check_name1[$v1]++;
                }
            }
        }
        foreach($check_name1 as $c){
            if($c > $action->frequency){
                return back()->withErrors(['eroor'=>['***********失敗，有學生報名超過限報項目***********']])->withInput();
            }
        }

        foreach($girl_select as $k=>$v){
            foreach($v as $k1=>$v1) {
                $check_item = Item::find($k1);
                if($check_item->limit){
                    if(!isset($check_name2[$v1])) $check_name2[$v1] = 0;
                    $check_name2[$v1]++;
                }
            }
        }
        foreach($check_name2 as $c){
            if($c > $action->frequency){
                return back()->withErrors(['eroor'=>['***********失敗，有學生報名超過限報項目***********']])->withInput();
            }
        }



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

                $check = StudentSign::where('action_id',$request->input('action_id'))
                    ->where('item_id',$k1)
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

                $check = StudentSign::where('action_id',$request->input('action_id'))
                    ->where('item_id',$k1)
                    ->where('student_id',$att2['student_id'])
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


        $students = Student::where('code',auth()->user()->code)
            ->where('semester',auth()->user()->semester)
            ->where('student_year',$student_class->student_year)
            ->where('student_class',$student_class->student_class)
            ->orderBy('num')
            ->get();

        foreach($students as $student){
            if($student->sex == "男") $boys[$student->id] = $student->num.'-'.$student->name;
            if($student->sex == "女") $girls[$student->id] = $student->num.'-'.$student->name;
        }

        $items = Item::where('code',auth()->user()->code)
            ->where('disable',null)
            ->orderBy('order')
            ->get();

        $data = [
            'action'=>$action,
            'items'=>$items,
            'student_year'=>$student_class->student_year,
            'student_class'=>$student_class->student_class,
            'boys'=>$boys,
            'girls'=>$girls,
        ];
        return view('class_teachers.sign_up_show',$data);
    }

    public function student_sign_update(Request $request)
    {
        $student_sign = StudentSign::find($request->input('student_sign_id'));

        //檢查此學生報名過了嗎
        $check_has = StudentSign::where('action_id',$request->input('action_id'))
            ->where('item_id',$student_sign->item_id)
            ->where('student_id',$request->input('student_id'))
            ->first();
        if(!empty($check_has)){
            return back()->withErrors(['eroor'=>['***********失敗，此學生報名相同項目***********']])->withInput();
        }

        $this_item = Item::find($student_sign->item_id);
        if($this_item->limit){
            //檢查該生是否報名超過限制
            $action = Action::find($student_sign->action_id);
            $check_signs = StudentSign::where('student_id',$request->input('student_id'))
                ->where('action_id',$student_sign->action_id)
                ->get();
            $this_student = 1;
            foreach($check_signs as $check_sign){
                $item = Item::find($check_sign->item_id);
                if($item->limit){
                    $this_student++;
                }
            }
            if($this_student > $action->frequency){
                return back()->withErrors(['eroor'=>['***********失敗，有學生報名超過限報項目***********']])->withInput();
            }
        }


        //$att['student_id '] = $request->input('student_id');
        //$student_sign->update($att);//不知為何無法順利用 update
        $student_sign->fill(['student_id'=>$request->input('student_id')])->save();
        return redirect()->route('class_teachers.sign_up_show',$request->input('action_id'));
    }

    public function student_sign_make(Request  $request)
    {
        //檢查此學生報名過了嗎
        $check_has = StudentSign::where('action_id',$request->input('action_id'))
            ->where('item_id',$request->input('item_id'))
            ->where('student_id',$request->input('student_id'))
            ->first();
        if(!empty($check_has)){
            return back()->withErrors(['eroor'=>['***********失敗，此學生報名相同項目***********']])->withInput();
        }

        //檢查該生是否報名超過限制
        $action = Action::find($request->input('action_id'));
        $this_item = Item::find($request->input('item_id'));
        if($this_item->limit){
            $check_signs = StudentSign::where('student_id',$request->input('student_id'))
                ->where('action_id',$action->id)
                ->get();
            $this_student = 1;
            foreach($check_signs as $check_sign){
                $item = Item::find($check_sign->item_id);
                if($item->limit){
                    $this_student++;
                }
            }
            if($this_student > $action->frequency){
                return back()->withErrors(['eroor'=>['***********失敗，有學生報名超過限報項目***********']])->withInput();
            }
        }


        $att['code'] = auth()->user()->code;
        $att['item_id'] = $request->input('item_id');
        $item = Item::find($att['item_id']);
        $att['item_name'] = $item->name;;
        $att['student_id'] = $request->input('student_id');
        $att['action_id'] = $request->input('action_id');
        $student = Student::find($att['student_id']);
        $att['student_year'] = $student->student_year;
        $att['student_class'] = $student->student_class;
        $att['sex'] = $student->sex;

        $check = StudentSign::where('item_id',$att['item_id'])
            ->where('student_id',$att['student_id'])
            ->first();
        if(empty($check)){
            StudentSign::create($att);
        }

        return redirect()->route('class_teachers.sign_up_show',$request->input('action_id'));
    }

}
