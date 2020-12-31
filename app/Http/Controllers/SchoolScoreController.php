<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Action;
use App\Item;
use App\StudentSign;

class SchoolScoreController extends Controller
{
    public function score_input($action_id=null)
    {
        $actions = Action::orderBy('id','DESC')->get();
        $action_array = [];
        foreach($actions as $action){
            $action_array[$action->id] = $action->name;
        }
        $select_action = null;
        if(empty($action_id)){
            $select_action = key($action_array);
        }else{
            $select_action = $action_id;
        }

        $items = Item::where('action_id',$select_action)
            ->where('code',auth()->user()->code)
            ->where('disable',null)
            ->orderBy('order')
            ->get();


        $data = [
            'action_array'=>$action_array,
            'select_action'=>$select_action,
            'action'=>$action,
            'items'=>$items,
        ];

        return view('school_scores.score_input',$data);
    }

    public function score_input_do(Action $action,Item $item)
    {
        $student_signs = StudentSign::where('item_id',$item->id)
            ->orderBy('student_year')
            ->orderBy('student_class')
            ->orderBy('sex')
            ->get();

        foreach($student_signs as $student_sign){
            $student_array[$student_sign->student_year][$student_sign->sex][$student_sign->id]['id'] = $student_sign->id;
            $student_array[$student_sign->student_year][$student_sign->sex][$student_sign->id]['number'] = $student_sign->student->number;
            $student_array[$student_sign->student_year][$student_sign->sex][$student_sign->id]['name'] = $student_sign->student->name;
            $student_array[$student_sign->student_year][$student_sign->sex][$student_sign->id]['achievement'] = $student_sign->achievement;
            $student_array[$student_sign->student_year][$student_sign->sex][$student_sign->id]['ranking'] = $student_sign->ranking;
        }

        $data = [
            'action'=>$action,
            'item'=>$item,
            'student_signs'=>$student_signs,
            'student_array'=>$student_array,
        ];

        return view('school_scores.score_input_do',$data);

    }

    public function score_input_update(Request  $request)
    {
        $checkbox = $request->input('checkbox');
        $achievement = $request->input('achievement');
        $ranking = $request->input('ranking');
        $action_id = $request->input('action_id');
        $item_id = $request->input('item_id');
        $item = Item::find($item_id);
        //徑賽
        if($item->type==1){
            //小到大
            asort($achievement);
        }
        //田賽
        if($item->type==2){
            //大到小
            arsort($achievement);
        }
        $r=1;
        foreach($achievement as $k=>$v){
            $student_sign = StudentSign::find($k);
            $att['achievement'] = $achievement[$k];
            if($checkbox =="on" and $item->type <> 3){
                $att['ranking'] = $r;
            }else{
                $att['ranking'] = $ranking[$k];
            }
            $student_sign->update($att);
            $r++;
        }

        return redirect()->route('school_scores.score_input_do',['action'=>$action_id,'item'=>$item_id]);
    }






    public function score_print($action_id=null)
    {
        $actions = Action::orderBy('id','DESC')->get();
        $action_array = [];
        foreach($actions as $action){
            $action_array[$action->id] = $action->name;
        }
        $select_action = null;
        if(empty($action_id)){
            $select_action = key($action_array);
        }else{
            $select_action = $action_id;
        }
        $data = [
            'action_array'=>$action_array,
            'select_action'=>$select_action,
            'action'=>$action,
        ];

        return view('school_scores.score_print',$data);
    }
}
