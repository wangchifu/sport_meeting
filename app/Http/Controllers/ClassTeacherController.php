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
        $check4 = StudentClass::where('semester', auth()->user()->semester)->where('code', auth()->user()->code)->where('user_names', 'like','%' .auth()->user()->name. '%')->first();

        if(!empty($check1)) $student_class = $check1;
        if(!empty($check2)) $student_class = $check2;
        if(!empty($check3)) $student_class = $check3;
        if(!empty($check4)) $student_class = $check4;


        $actions = Action::where('code',auth()->user()->code)
            ->where('semester',auth()->user()->semester)
            ->orderBy('id','DESC')->get();
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
        $check4 = StudentClass::where('semester', auth()->user()->semester)->where('code', auth()->user()->code)->where('user_names', 'like','%' .auth()->user()->name. '%')->first();

        if(!empty($check1)) $student_class = $check1;
        if(!empty($check2)) $student_class = $check2;
        if(!empty($check3)) $student_class = $check3;
        if(!empty($check4)) $student_class = $check4;

        $students = Student::where('code',auth()->user()->code)
            ->where('semester',auth()->user()->semester)
            ->where('student_year',$student_class->student_year)
            ->where('student_class',$student_class->student_class)
            ->orderBy('num')
            ->where('disable',null)
            ->get();

        $girls = [];
        $boys = [];
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
        $student_select = $request->input('student_select');

        $boy_group_official_select = $request->input('boy_group_official_select');
        $boy_group_reserve_select = $request->input('boy_group_reserve_select');
        $girl_group_official_select = $request->input('girl_group_official_select');
        $girl_group_reserve_select = $request->input('girl_group_reserve_select');
        $student_group_official_select = $request->input('student_group_official_select');
        $student_group_reserve_select = $request->input('student_group_reserve_select');

        $action = Action::find($request->input('action_id'));

        /**
        $check_name1 = [];
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
        if(!empty($boy_group_official_select)){
            foreach($boy_group_official_select as $k=>$v){
                foreach($v as $k1=>$v1){
                    foreach($v1 as $k2=>$v2){
                        $check_item = Item::find($k1);
                        if($check_item->limit){
                            if(!isset($check_name1[$v2])) $check_name1[$v2] = 0;
                            $check_name1[$v2]++;
                        }
                    }
                }
            }
        }
        if(!empty($boy_group_reserve_select)){
            foreach($boy_group_reserve_select as $k=>$v){
                foreach($v as $k1=>$v1){
                    foreach($v1 as $k2=>$v2){
                        $check_item = Item::find($k1);
                        if($check_item->limit){
                            if(!isset($check_name1[$v2])) $check_name1[$v2] = 0;
                            $check_name1[$v2]++;
                        }
                    }
                }
            }
        }


        foreach($check_name1 as $c){
            if($c > $action->frequency){
                return back()->withErrors(['eroor'=>['***********失敗，有學生報名超過限報項目***********']])->withInput();
            }
        }

        $check_name2 = [];
        foreach($girl_select as $k=>$v){
            foreach($v as $k1=>$v1) {
                $check_item = Item::find($k1);
                if($check_item->limit){
                    if(!isset($check_name2[$v1])) $check_name2[$v1] = 0;
                    $check_name2[$v1]++;
                }
            }
        }

        if(!empty($girl_group_official_select)){
            foreach($girl_group_official_select as $k=>$v){
                foreach($v as $k1=>$v1){
                    foreach($v1 as $k2=>$v2){
                        $check_item = Item::find($k1);
                        if($check_item->limit){
                            if(!isset($check_name2[$v2])) $check_name2[$v2] = 0;
                            $check_name2[$v2]++;
                        }
                    }
                }
            }
        }

        if(!empty($girl_group_reserve_select)){
            foreach($girl_group_reserve_select as $k=>$v){
                foreach($v as $k1=>$v1){
                    foreach($v1 as $k2=>$v2){
                        $check_item = Item::find($k1);
                        if($check_item->limit){
                            if(!isset($check_name2[$v2])) $check_name2[$v2] = 0;
                            $check_name2[$v2]++;
                        }
                    }
                }
            }
        }

        foreach($check_name2 as $c){
            if($c > $action->frequency){
                return back()->withErrors(['eroor'=>['***********失敗，有學生報名超過限報項目***********']])->withInput();
            }
        }
         * */

        $this_class_students = Student::where('code',auth()->user()->code)
            ->where('semester',$action->semester)
            ->where('student_year',$request->input('student_year'))
            ->where('student_class',$request->input('student_class'))
            ->orderBy('num')
            ->get();
        foreach($this_class_students as $this_class_student){
            $student_num[$this_class_student->id] = $this_class_student->num;
        }

        if(!empty($boy_select)){
            foreach($boy_select as $k=>$v){
                foreach($v as $k1=>$v1){
                    if($v1 <> null){
                        $att_boy['code'] = auth()->user()->code;
                        $att_boy['item_id'] = $k1;
                        $item = Item::find($k1);
                        $att_boy['item_name'] = $item->name;
                        $att_boy['game_type'] = $item->game_type;
                        $att_boy['student_id'] = $v1;
                        $att_boy['action_id'] = $request->input('action_id');
                        $att_boy['student_year'] = $request->input('student_year');
                        $att_boy['student_class'] = $request->input('student_class');
                        $att_boy['num'] = $student_num[$v1];
                        $att_boy['sex'] = "男";

                        $check = StudentSign::where('action_id',$request->input('action_id'))
                            ->where('item_id',$k1)
                            ->where('student_id',$v1)
                            ->first();
                        if(empty($check)){
                            StudentSign::create($att_boy);
                        }
                    }
                }
            }
        }
        if(!empty($boy_group_official_select)){
            foreach($boy_group_official_select as $k=>$v){
                foreach($v as $k1=>$v1){
                    foreach($v1 as $k2=>$v2){
                        if($v2 <> null){
                            $att_boy_official['code'] = auth()->user()->code;
                            $att_boy_official['item_id'] = $k1;
                            $item = Item::find($k1);
                            $att_boy_official['item_name'] = $item->name;
                            $att_boy_official['game_type'] = $item->game_type;
                            $att_boy_official['is_official'] = 1;
                            $att_boy_official['group_num'] = $k;
                            $att_boy_official['student_id'] = $v2;
                            $att_boy_official['action_id'] = $request->input('action_id');
                            $att_boy_official['student_year'] = $request->input('student_year');
                            $att_boy_official['num'] = $student_num[$v2];
                            $att_boy_official['student_class'] = $request->input('student_class');
                            $att_boy_official['sex'] = "男";

                            $check = StudentSign::where('action_id',$request->input('action_id'))
                                ->where('item_id',$k1)
                                ->where('student_id',$v2)
                                ->first();
                            if(empty($check)){
                                StudentSign::create($att_boy_official);
                            }
                        }
                    }
                }
            }
        }

        if(!empty($boy_group_reserve_select)){
            foreach($boy_group_reserve_select as $k=>$v){
                foreach($v as $k1=>$v1){
                    foreach($v1 as $k2=>$v2){
                        if($v2 <> null){
                            $att_boy_reserve['code'] = auth()->user()->code;
                            $att_boy_reserve['item_id'] = $k1;
                            $item = Item::find($k1);
                            $att_boy_reserve['item_name'] = $item->name;
                            $att_boy_reserve['game_type'] = $item->game_type;
                            $att_boy_reserve['is_official'] = null;
                            $att_boy_reserve['group_num'] = $k;
                            $att_boy_reserve['student_id'] = $v2;
                            $att_boy_reserve['action_id'] = $request->input('action_id');
                            $att_boy_reserve['student_year'] = $request->input('student_year');
                            $att_boy_reserve['student_class'] = $request->input('student_class');
                            $att_boy_reserve['num'] = $student_num[$v2];
                            $att_boy_reserve['sex'] = "男";

                            $check = StudentSign::where('action_id',$request->input('action_id'))
                                ->where('item_id',$k1)
                                ->where('student_id',$v2)
                                ->first();
                            if(empty($check)){
                                StudentSign::create($att_boy_reserve);
                            }
                        }
                    }
                }
            }
        }

        if(!empty($girl_select)){
            foreach($girl_select as $k=>$v){
                foreach($v as $k1=>$v1){
                    if($v1 <> null){
                        $att_girl['code'] = auth()->user()->code;
                        $att_girl['item_id'] = $k1;
                        $item = Item::find($k1);
                        $att_girl['item_name'] = $item->name;
                        $att_girl['game_type'] = $item->game_type;
                        $att_girl['student_id'] = $v1;
                        $att_girl['action_id'] = $request->input('action_id');
                        $att_girl['student_year'] = $request->input('student_year');
                        $att_girl['student_class'] = $request->input('student_class');
                        $att_girl['num'] = $student_num[$v1];
                        $att_girl['sex'] = "女";

                        $check = StudentSign::where('action_id',$request->input('action_id'))
                            ->where('item_id',$k1)
                            ->where('student_id',$v1)
                            ->first();
                        if(empty($check)){
                            StudentSign::create($att_girl);
                        }
                    }
                }
            }
        }
        if(!empty($girl_group_official_select)){
            foreach($girl_group_official_select as $k=>$v){
                foreach($v as $k1=>$v1){
                    foreach($v1 as $k2=>$v2){
                        if($v2 <> null){
                            $att_girl_official['code'] = auth()->user()->code;
                            $att_girl_official['item_id'] = $k1;
                            $item = Item::find($k1);
                            $att_girl_official['item_name'] = $item->name;
                            $att_girl_official['game_type'] = $item->game_type;
                            $att_girl_official['is_official'] = 1;
                            $att_girl_official['group_num'] = $k;
                            $att_girl_official['student_id'] = $v2;
                            $att_girl_official['action_id'] = $request->input('action_id');
                            $att_girl_official['student_year'] = $request->input('student_year');
                            $att_girl_official['student_class'] = $request->input('student_class');
                            $att_girl_official['num'] = $student_num[$v2];
                            $att_girl_official['sex'] = "女";

                            $check = StudentSign::where('action_id',$request->input('action_id'))
                                ->where('item_id',$k1)
                                ->where('student_id',$v2)
                                ->first();
                            if(empty($check)){
                                StudentSign::create($att_girl_official);
                            }
                        }
                    }
                }
            }
        }

        if(!empty($girl_group_reserve_select)){
            foreach($girl_group_reserve_select as $k=>$v){
                foreach($v as $k1=>$v1){
                    foreach($v1 as $k2=>$v2){
                        if($v2 <> null){
                            $att_girl_reserve['code'] = auth()->user()->code;
                            $att_girl_reserve['item_id'] = $k1;
                            $item = Item::find($k1);
                            $att_girl_reserve['item_name'] = $item->name;
                            $att_girl_reserve['game_type'] = $item->game_type;
                            $att_girl_reserve['is_official'] = null;
                            $att_girl_reserve['group_num'] = $k;
                            $att_girl_reserve['student_id'] = $v2;
                            $att_girl_reserve['action_id'] = $request->input('action_id');
                            $att_girl_reserve['student_year'] = $request->input('student_year');
                            $att_girl_reserve['student_class'] = $request->input('student_class');
                            $att_girl_reserve['num'] = $student_num[$v2];
                            $att_girl_reserve['sex'] = "女";

                            $check = StudentSign::where('action_id',$request->input('action_id'))
                                ->where('item_id',$k1)
                                ->where('student_id',$v2)
                                ->first();
                            if(empty($check)){
                                StudentSign::create($att_girl_reserve);
                            }
                        }
                    }
                }
            }
        }
        if(!empty($student_select)){
            foreach($student_select as $k=>$v){
                foreach($v as $k1=>$v1){
                    if($v1 <> null){
                        $att_student['code'] = auth()->user()->code;
                        $att_student['item_id'] = $k1;
                        $item = Item::find($k1);
                        $att_student['item_name'] = $item->name;
                        $att_student['game_type'] = $item->game_type;
                        $att_student['student_id'] = $v1;
                        $att_student['action_id'] = $request->input('action_id');
                        $att_student['student_year'] = $request->input('student_year');
                        $att_student['student_class'] = $request->input('student_class');
                        $att_student['num'] = $student_num[$v1];
                        $att_student['sex'] = "4";

                        $check = StudentSign::where('action_id',$request->input('action_id'))
                            ->where('item_id',$k1)
                            ->where('student_id',$v1)
                            ->first();
                        if(empty($check)){
                            StudentSign::create($att_student);
                        }
                    }
                }
            }
        }
        if(!empty($student_group_official_select)){
            foreach($student_group_official_select as $k=>$v){
                foreach($v as $k1=>$v1){
                    foreach($v1 as $k2=>$v2){
                        if($v2 <> null){
                            $att_student_official['code'] = auth()->user()->code;
                            $att_student_official['item_id'] = $k1;
                            $item = Item::find($k1);
                            $att_student_official['item_name'] = $item->name;
                            $att_student_official['game_type'] = $item->game_type;
                            $att_student_official['is_official'] = 1;
                            $att_student_official['group_num'] = $k;
                            $att_student_official['student_id'] = $v2;
                            $att_student_official['action_id'] = $request->input('action_id');
                            $att_student_official['student_year'] = $request->input('student_year');
                            $att_student_official['num'] = $student_num[$v2];
                            $att_student_official['student_class'] = $request->input('student_class');
                            $att_student_official['sex'] = "4";

                            $check = StudentSign::where('action_id',$request->input('action_id'))
                                ->where('item_id',$k1)
                                ->where('student_id',$v2)
                                ->first();
                            if(empty($check)){
                                StudentSign::create($att_student_official);
                            }
                        }
                    }
                }
            }
        }

        if(!empty($student_group_reserve_select)){
            foreach($student_group_reserve_select as $k=>$v){
                foreach($v as $k1=>$v1){
                    foreach($v1 as $k2=>$v2){
                        if($v2 <> null){
                            $att_student_reserve['code'] = auth()->user()->code;
                            $att_student_reserve['item_id'] = $k1;
                            $item = Item::find($k1);
                            $att_student_reserve['item_name'] = $item->name;
                            $att_student_reserve['game_type'] = $item->game_type;
                            $att_student_reserve['is_official'] = null;
                            $att_student_reserve['group_num'] = $k;
                            $att_student_reserve['student_id'] = $v2;
                            $att_student_reserve['action_id'] = $request->input('action_id');
                            $att_student_reserve['student_year'] = $request->input('student_year');
                            $att_student_reserve['student_class'] = $request->input('student_class');
                            $att_student_reserve['num'] = $student_num[$v2];
                            $att_student_reserve['sex'] = "男";

                            $check = StudentSign::where('action_id',$request->input('action_id'))
                                ->where('item_id',$k1)
                                ->where('student_id',$v2)
                                ->first();
                            if(empty($check)){
                                StudentSign::create($att_student_reserve);
                            }
                        }
                    }
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
        $check4 = StudentClass::where('semester', auth()->user()->semester)->where('code', auth()->user()->code)->where('user_names', 'like','%' .auth()->user()->name. '%')->first();

        if(!empty($check1)) $student_class = $check1;
        if(!empty($check2)) $student_class = $check2;
        if(!empty($check3)) $student_class = $check3;
        if(!empty($check4)) $student_class = $check4;


        $students = Student::where('code',auth()->user()->code)
            ->where('semester',auth()->user()->semester)
            ->where('student_year',$student_class->student_year)
            ->where('student_class',$student_class->student_class)
            ->where('disable',null)
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
            'action'=>$action,
            'items'=>$items,
            'student_year'=>$student_class->student_year,
            'student_class'=>$student_class->student_class,
            'boys'=>$boys,
            'girls'=>$girls,
            'all_students'=>$all_students,
        ];
        return view('class_teachers.sign_up_show',$data);
    }

    public function sign_up_delete(StudentSign $student_sign)
    {
        $check1 = StudentClass::where('semester', auth()->user()->semester)->where('code', auth()->user()->code)->where('user_ids', auth()->user()->id)->first();
        $check2 = StudentClass::where('semester', auth()->user()->semester)->where('code', auth()->user()->code)->where('user_ids', 'like', auth()->user()->id . ',%')->first();
        $check3 = StudentClass::where('semester', auth()->user()->semester)->where('code', auth()->user()->code)->where('user_ids', 'like', '%,' . auth()->user()->id)->first();
        $check4 = StudentClass::where('semester', auth()->user()->semester)->where('code', auth()->user()->code)->where('user_names', 'like','%' .auth()->user()->name. '%')->first();

        if(!empty($check1)) $student_class = $check1;
        if(!empty($check2)) $student_class = $check2;
        if(!empty($check3)) $student_class = $check3;
        if(!empty($check4)) $student_class = $check4;

        if($student_class->student_year == $student_sign->student_year and $student_class->student_class == $student_sign->student_class){
            $student_sign->delete();
        }
        return redirect()->route('class_teachers.sign_up_show',$student_sign->item->action_id);
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

        /**
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
         * **/


        $att['student_id'] = $request->input('student_id');
        $student = Student::find($request->input('student_id'));
        $att['num'] = $student->num;
        $student_sign->update($att);
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

        /**
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
         * */


        $att['code'] = auth()->user()->code;
        $att['item_id'] = $request->input('item_id');
        $att['is_official'] = $request->input('is_official');
        $att['group_num'] = $request->input('group_num');
        $item = Item::find($att['item_id']);
        $att['item_name'] = $item->name;
        $att['game_type'] = $item->game_type;
        $att['student_id'] = $request->input('student_id');
        $att['action_id'] = $request->input('action_id');
        $student = Student::find($att['student_id']);
        $att['student_year'] = $student->student_year;
        $att['student_class'] = $student->student_class;
        $att['num'] = $student->num;
        $att['sex'] = ($item->group==4)?4:$student->sex;

        StudentSign::create($att);

        return redirect()->route('class_teachers.sign_up_show',$request->input('action_id'));
    }

}
