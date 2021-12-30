<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Action;
use App\Item;
use App\StudentClass;
use App\SchoolApi;
use App\User;

class HomeController extends Controller
{
    public function class_teacher_error()
    {
        $data = [
            'words'=>'你不是導師',
        ];
        return view('errors.others',$data);
    }

    public function show($action_id=null)
    {
        $actions = Action::where('code',auth()->user()->code)
            ->where('disable','1')
            ->orderBy('id','DESC')->get();
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
        $action = [];
        $items = [];
        $student_classes = [];
        if($select_action) {
            $action = Action::find($select_action);

            //不是本校即退回
            if ($action->code != auth()->user()->code) return back();
            if(empty($action->disable)) return back();

            $items = Item::where('action_id',$action->id)
                ->where('code',auth()->user()->code)
                ->where('disable',null)
                ->orderBy('order')
                ->get();

            $student_classes = StudentClass::where('semester',$action->semester)
                ->where('code',auth()->user()->code)
                ->orderBy('student_year')
                ->orderBy('student_class')
                ->get();

        }

        $data = [
            'action_array'=>$action_array,
            'select_action'=>$select_action,
            'action'=>$action,
            'items'=>$items,
            'student_classes'=>$student_classes,
        ];

        return view('show',$data);

    }

    public function all()
    {
        $school_apis = SchoolApi::orderby('code')->get();
        $select_school = [];
        foreach($school_apis as $school_api){
            $select_school[$school_api->code] = $school_api->client_id;
        }
        $schools = config('chcschool.schools');
        $data = [
            'select_school'=>$select_school,
            'schools'=>$schools,
        ];
        return view('all',$data);
    }

    public function show_one(Request $request)
    {
        $school_code = $request->input('school_code');
        $select_action = $request->input('select_action');
        $actions = Action::where('code',$school_code)
            ->where('open',1)
            ->orderBy('id','DESC')->get();
        $action_array = [];
        foreach($actions as $action){
            $action_array[$action->id] = $action->name;
        }

        if(empty($select_action)){
            $select_action = key($action_array);
        }else{
            $select_action = $request->input('select_action');
        }

        $action = [];
        $items = [];
        $student_classes = [];
        if($select_action) {
            $action = Action::find($select_action);

            $items = Item::where('action_id',$action->id)
                ->where('code',$school_code)
                ->where('disable',null)
                ->orderBy('order')
                ->get();

            $student_classes = StudentClass::where('semester',$action->semester)
                ->where('code',$school_code)
                ->orderBy('student_year')
                ->orderBy('student_class')
                ->get();

        }
        $schools = config('chcschool.schools');

        $data = [
            'action_array'=>$action_array,
            'select_action'=>$select_action,
            'action'=>$action,
            'items'=>$items,
            'student_classes'=>$student_classes,
            'schools'=>$schools,
            'school_code'=>$school_code,
        ];
        return view('show_one',$data);
    }

    public function users()
    {
        if(auth()->user()->admin != 1){
            return back();
        }

        $schools = config('chcschool.schools');
        $users = User::all();
        $data = [
            'users'=>$users,
            'schools'=>$schools,
        ];
        return view('users',$data);
    }

    public function search(Request $request)
    {
        if(auth()->user()->admin != 1){
            return back();
        }
        $schools = config('chcschool.schools');
        $users = User::where('name','like','%'.$request->input('want').'%')->get();
        $data = [
            'users'=>$users,
            'schools'=>$schools,
        ];
        return view('users',$data);

    }

    public function search_school(Request $request)
    {
        if(auth()->user()->admin != 1){
            return back();
        }
        $schools = config('chcschool.schools');
        $schools2 = array_flip($schools);

        $users = [];
        if(isset($schools2[$request->input('want_school')])){
            $users = User::where('code',$schools2[$request->input('want_school')])->get();
        }
        $data = [
            'users'=>$users,
            'schools'=>$schools,
        ];
        return view('users',$data);

    }
}
