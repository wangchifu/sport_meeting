<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Action;

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
        $data = [
            'action_array'=>$action_array,
            'select_action'=>$select_action,
            'action'=>$action,
        ];

        return view('school_scores.score_input',$data);
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
