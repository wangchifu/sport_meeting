<?php

namespace App\Http\Controllers;

use App\SchoolApi;
use App\User;
use App\StudentClass;
use App\Student;
use App\SchoolAdmin;
use App\Item;
use App\Action;
use App\StudentSign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Rap2hpoutre\FastExcel\FastExcel;

class SchoolAdminController extends Controller
{
    public function api_teach()
    {
        return view('school_admins.api_teach');
    }
    public function api()
    {
        $school_api = SchoolApi::where('code',auth()->user()->code)->first();

        $students = Student::where('code',auth()->user()->code)
            ->orderBy('semester','DESC')
            ->get();
        $student_classes = StudentClass::where('code',auth()->user()->code)
            ->get();
        $student_data = [];
        if(!empty($students)){
            foreach($students as $student){
                if(!isset($student_data[$student->semester])) $student_data[$student->semester]=0;
                $student_data[$student->semester]++;
            }
        }
        if(!empty($student_classes)){
            $class_data = [];
            foreach($student_classes as $student_class){
                if(!isset($class_data[$student_class->semester])) $class_data[$student_class->semester]=0;
                $class_data[$student_class->semester]++;
            }
        }

        $data = [
            'school_api'=>$school_api,
            'class_data'=>$class_data,
            'student_data'=>$student_data,
        ];
        return view('school_admins.api',$data);
    }

    public function api_pull(Request $request)
    {
        $school_api = SchoolApi::where('code',auth()->user()->code)->first();

        $API_client_id = $school_api->client_id;
        $API_client_secret = $school_api->client_secret;

	$data = $this->get_API($API_client_id,$API_client_secret);
	if(empty($data)){
		return back()->withErrors(['error'=>['沒有資料']]);
	}
	if(!isset($data->學年)){
		return back()->withErrors(['error'=>['沒有學年資料']]);
	}
	if(!isset($data->學期)){
		return back()->withErrors(['error'=>['沒有學期資料']]);
	}
	if(!isset($data->學期教職員)){
		return back()->withErrors(['error'=>['沒有學期教職員資料']]);
	}

        $semester = $data->學年.$data->學期;

        $techer_data = $data->學期教職員;
        foreach($techer_data as $k=>$v){
            $edu_key = $v->身分證編碼;
            $user = User::where('edu_key',$edu_key)->first();
            $att['semester'] = $semester;
            $att['edu_key'] = $v->身分證編碼;
            $att['name'] = $v->姓名;
            $att['title'] = $v->職稱;
            $att['code'] = auth()->user()->code;

            if(empty($user)){
                User::create($att);
            }else{
                $user->update($att);
            }
        }



        $class_data = $data->學期編班;//各班資料
        foreach($class_data as $k=>$v){
            $student_teacher_data = $v->導師;
            $user_ids = "";
            foreach($student_teacher_data as $k1=>$v1){
                $user = User::where('edu_key',$v1->身分證編碼)->first();
                $user_ids .= $user->id.',';
            }
            $user_ids = substr($user_ids,0,-1);
            $att2['code'] = auth()->user()->code;
            $att2['semester'] = $semester;
            $att2['student_year'] = $v->年級;
            $att2['student_class'] = $v->班序;
            $att2['user_ids'] = $user_ids;
            $student_class= StudentClass::where('code',$att2['code'])
                ->where('semester',$att2['semester'])
                ->where('student_year',$att2['student_year'])
                ->where('student_class',$att2['student_class'])
                ->first();
            if(empty($student_class)){
                StudentClass::create($att2);
            }else{
                //避免先前有匯入過excel
                $att2['user_names'] = null;
                $student_class->update($att2);
            }

            $student_array = $v->學期編班;
            foreach($student_array as $k3=>$v3){
                $att3['code'] = auth()->user()->code;
                $att3['semester'] = $semester;
                $att3['edu_key'] = $v3->身分證編碼;
                $att3['name'] = $v3->姓名;
                $att3['sex'] = $v3->性別;
                $att3['student_year'] = $v->年級;
                $att3['student_class'] = $v->班序;
                $att3['num'] = $v3->座號;
                $student = Student::where('code',$att3['code'])
                    ->where('semester',$att3['semester'])
                    ->where('student_year',$att3['student_year'])
                    ->where('student_class',$att3['student_class'])
                    ->where('num',$att3['num'])
                    ->first();
                /**
                $student = Student::where('semester',$att3['semester'])
                    ->where('edu_key',$att3['edu_key'])
                    ->first();
                 * */
                if(empty($student)){
                    Student::create($att3);
                }else{
                    $student->update($att3);
                }
            }
        }

        return redirect()->route('school_admins.api');

    }

    public function api_store(Request $request)
    {
        $find_api = SchoolApi::where('code',auth()->user()->code)->first();
        if(empty($find_api)){
            $att = $request->all();
            $att['code'] = auth()->user()->code;
            SchoolApi::create($att);
        }

        return redirect()->route('school_admins.api');
    }

    public function api_destroy(Request $request,SchoolApi $school_api)
    {
        $school_api->delete();
        return redirect()->route('school_admins.api');
    }

    public function student_disable(Student $student)
    {
        if($student->code == auth()->user()->code){
            if($student->disable){
                $att['disable'] = null ;
            }else{
                $att['disable'] = 1 ;
            }
            $student->update($att);
        }
        return redirect()->back();

    }

    public function student_edit(Student $student)
    {
        if($student->code <> auth()->user()->code){
            return redirect()->back();
        }

        $student_classes = StudentClass::where('semester',$student->semester)
            ->where('code',auth()->user()->code)
            ->get();
        foreach($student_classes as $student_class){
            $class_data[$student_class->id]['id'] = $student_class->id;
            $class_data[$student_class->id]['年級'] = $student_class->student_year;
            $class_data[$student_class->id]['班級'] = $student_class->student_class;

            if(!empty($student_class->user_ids)){
                $teacher_array = explode(',',$student_class->user_ids);
                $teacher_name = "";
                foreach($teacher_array as $k=>$v){
                    $user = User::find($v);
                    $teacher_name .= $user->name.',';
                }
                $teacher_name = substr($teacher_name,0,-1);
            }else{
                $teacher_name = $student_class->user_names;
            }

            $class_data[$student_class->id]['導師'] = $teacher_name;
        }
        $data = [
            'student'=>$student,
            'class_data'=>$class_data,
        ];

        return view('school_admins.student_edit',$data);
    }

    public function student_update(Request $request,Student $student)
    {
        $att = $request->all();
        $student_class = StudentClass::find($att['student_class_id']);
        $att['student_year'] = $student_class->student_year;
        $att['student_class'] = $student_class->student_class;
        $student->update($att);
        echo "<body onload='opener.location.reload();window.close();'>";
    }

    public function student_create(StudentClass $student_class)
    {
        $student_classes = StudentClass::where('semester',$student_class->semester)
            ->where('code',auth()->user()->code)
            ->get();
        $class_data = [];
        foreach($student_classes as $sc){
            $class_data[$sc->id]['id'] = $sc->id;
            $class_data[$sc->id]['年級'] = $sc->student_year;
            $class_data[$sc->id]['班級'] = $sc->student_class;

            if(!empty($sc->user_ids)){
                $teacher_array = explode(',',$sc->user_ids);
                $teacher_name = "";
                foreach($teacher_array as $k=>$v){
                    $user = User::find($v);
                    $teacher_name .= $user->name.',';
                }
                $teacher_name = substr($teacher_name,0,-1);
            }else{
                $teacher_name = $sc->user_names;
            }

            $class_data[$sc->id]['導師'] = $teacher_name;
        }
        $data = [
            'student_class'=>$student_class,
            'class_data'=>$class_data,
        ];

        return view('school_admins.student_create',$data);
    }

    public function student_store(Request $request)
    {
        $att = $request->all();
        $student_class = StudentClass::find($att['student_class_id']);
        $att['student_year'] = $student_class->student_year;
        $att['student_class'] = $student_class->student_class;
        $att['code'] = $student_class->code;
        $att['semester'] = $student_class->semester;
        Student::create($att);
        echo "<body onload='opener.location.reload();window.close();'>";
    }

    public function import()
    {
        return view('school_admins.import');
    }

    public function do_import(Request $request)
    {
        //處理檔案上傳
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $collection = (new FastExcel)->import($file);
            //dd($collection);
            foreach($collection as $line){
                if(!isset($line['姓名']) or !isset($line['性別']) or !isset($line['年級(數字)']) or !isset($line['班序(數字)']) or !isset($line['班序(數字)']) or !isset($line['座號']) or !isset($line['導師姓名'])){
                    return back()->withErrors(['欄位有錯，請檢查 excel 檔']);
                }


                if(empty($line['姓名']) and empty($line['年級(數字)'])){
                    break;
                }
                $class_teacher[$line['年級(數字)']][$line['班序(數字)']] = $line['導師姓名'];

                $att['code'] = auth()->user()->code;
                $att['semester'] = $request->input('semester');
                $att['name'] = $line['姓名'];
                $att['sex'] = $line['性別'];
                $att['student_year'] = $line['年級(數字)'];
                $att['student_class'] = $line['班序(數字)'];
                $att['num'] = $line['座號'];
                if(isset($line['身分證號'])){
                    $att['edu_key'] = hash('sha256',$line['身分證號']);
                }

                $student = Student::where('code',$att['code'])
                    ->where('semester',$att['semester'])
                    ->where('student_year',$att['student_year'])
                    ->where('student_class',$att['student_class'])
                    ->where('num',$att['num'])
                    ->first();
                if(empty($student)){
                    Student::create($att);
                }else{
                    $student->update($att);
                }
            }
            foreach($class_teacher as $k=>$v){
                foreach($v as $k1=>$v1){
                    $att2['code'] = auth()->user()->code;
                    $att2['semester'] = $request->input('semester');
                    $att2['student_year'] = $k;
                    $att2['student_class'] = $k1;
                    $att2['user_names'] = $v1;

                    $student_class= StudentClass::where('code',$att2['code'])
                        ->where('semester',$att2['semester'])
                        ->where('student_year',$att2['student_year'])
                        ->where('student_class',$att2['student_class'])
                        ->first();
                    if(empty($student_class)){
                        StudentClass::create($att2);
                    }else{
                        //避免先前拉過API 已經有導師了
                        $att2['user_ids'] = null;
                        $student_class->update($att2);
                    }
                }
            }
        }

        return redirect()->route('school_admins.api');
    }

    public function account()
    {
        $users = User::where('code',auth()->user()->code)
            ->where('disable',null)
            ->orderBy('semester','DESC')
            ->get();
        $action = "at";
        $data = [
            'users'=>$users,
            'action'=>$action,
        ];
        return view('school_admins.account',$data);
    }

    public function account_not()
    {
        $users = User::where('code',auth()->user()->code)
            ->where('disable',1)
            ->orderBy('semester','DESC')
            ->get();
        $action = "not_at";
        $data = [
            'users'=>$users,
            'action'=>$action,
        ];
        return view('school_admins.account',$data);
    }

    public function account_set1(User $user)
    {
        //不是本校即退回
        if($user->code != auth()->user()->code) return back();

        $att['code'] = $user->code;
        $att['user_id'] = $user->id;
        $att['type'] = 1;
        $check = SchoolAdmin::where('code',$user->code)->where('user_id',$user->id)->first();
        if(empty($check)){
            SchoolAdmin::create($att);
        }
        return redirect()->route('school_admins.account');
    }

    public function account_set2(User $user)
    {
        //不是本校即退回
        if($user->code != auth()->user()->code) return back();

        $att['code'] = $user->code;
        $att['user_id'] = $user->id;
        $att['type'] = 2;
        $check = SchoolAdmin::where('code',$user->code)->where('user_id',$user->id)->first();
        if(empty($check)){
            SchoolAdmin::create($att);
        }
        return redirect()->route('school_admins.account');
    }

    public function account_disable(User $user)
    {
        //不是本校即退回
        if($user->code != auth()->user()->code) return back();

        $att['disable'] = 1;
        $user->update($att);
        return redirect()->route('school_admins.account');
    }

    public function account_enable(User $user)
    {
        //不是本校即退回
        if($user->code != auth()->user()->code) return back();

        $att['disable'] = null;
        $user->update($att);
        return redirect()->route('school_admins.account');
    }

    public function account_remove_power(User $user)
    {
        //不是本校即退回
        if($user->code != auth()->user()->code) return back();

        $school_admin = SchoolAdmin::where('code',$user->code)
            ->where('user_id',$user->id)
            ->first();
        if(!empty($school_admin)){
            $school_admin->delete();
        }
        return redirect()->route('school_admins.account');
    }

    public function impersonate(User $user)
    {
        //不是本校即退回
        if($user->code != auth()->user()->code) return back();

        Auth::user()->impersonate($user);
        return redirect()->route('index');
    }
    public function impersonate_leave()
    {
        Auth::user()->leaveImpersonation();
        return redirect()->route('index');
    }

    public function student_class($semester,$select_class_id=null)
    {
        $student_classes = StudentClass::where('semester',$semester)
            ->where('code',auth()->user()->code)
            ->get();
        foreach($student_classes as $student_class){
            $class_data[$student_class->id]['id'] = $student_class->id;
            $class_data[$student_class->id]['年級'] = $student_class->student_year;
            $class_data[$student_class->id]['班級'] = $student_class->student_class;

            if(!empty($student_class->user_ids)){
                $teacher_array = explode(',',$student_class->user_ids);
                $teacher_name = "";
                foreach($teacher_array as $k=>$v){
                    $user = User::find($v);
                    $teacher_name .= $user->name.',';
                }
                $teacher_name = substr($teacher_name,0,-1);
            }else{
                $teacher_name = $student_class->user_names;
            }

            $class_data[$student_class->id]['導師'] = $teacher_name;
        }

         if(!$select_class_id){
             $s = current($class_data);
             $select_class_id = $s['id'];
         }

        $select_class = StudentClass::find($select_class_id);
        $students = Student::where('semester',$semester)
            ->where('code',auth()->user()->code)
            ->where('student_year',$select_class->student_year)
            ->where('student_class',$select_class->student_class)
            ->orderBy('num')
            ->get();

        $data = [
            'select_class_id'=>$select_class_id,
            'student_classes'=>$student_classes,
            'class_data'=>$class_data,
            'semester'=>$semester,
            'select_class'=>$select_class,
            'students'=>$students,
        ];
        return view('school_admins.student_class',$data);
    }

    function get_API($API_client_id,$API_client_secret){

        // =================================================
        //    學生榮譽榜 (url: https://api.chc.edu.tw)
        //    校務佈告欄 (url: https://api.chc.edu.tw/school-news)
        //    同步學期資料 (url: https://api.chc.edu.tw/semester-data)
        //    更改師生密碼 (url: https://api.chc.edu.tw/change-password)

        // API NAME
        $api_name = '/semester-data';
        //$api_name = '/school-news';
        // 更改師生密碼 (url: https://api.chc.edu.tw/change-password)

        // API URL
        $api_url = 'https://api.chc.edu.tw';
        //: https://api.chc.edu.tw/school-news
        // 建立 CURL 連線
        $ch = curl_init();
        // 取 access token
        curl_setopt($ch, CURLOPT_URL, $api_url."/oauth?authorize");
        // 設定擷取的URL網址
        curl_setopt($ch, CURLOPT_POST, TRUE);
        // the variable
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
            'client_id' => $API_client_id,
            'client_secret' => $API_client_secret,
            'grant_type' => 'client_credentials'
        ));

        $data = curl_exec($ch);
        $data = json_decode($data);

        $access_token = $data->access_token;
        $authorization = "Authorization: Bearer ".$access_token;

        curl_setopt($ch, CURLOPT_URL, $api_url.$api_name);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // **Inject Token into Header**
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        return json_decode($result);
    }

    public function item($action_id=null)
    {

        $actions = Action::where('code',auth()->user()->code)->orderBy('id','DESC')->get();
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

        $items = [];
        if(!empty($select_action)){
            $items = Item::where('code',auth()->user()->code)
                ->where('action_id',$select_action)
                ->orderBy('disable')
                ->orderBy('order')->get();
        }
        $data = [
            'actions'=>$actions,
            'action_array'=>$action_array,
            'select_action'=>$select_action,
            'items'=>$items,
        ];
        return view('school_admins.item',$data);
    }

    public function item_create(Action $action)
    {
        //不是本校即退回
        if($action->code != auth()->user()->code) return back();

        $data = [
          'action'=>$action,
        ];

        return view('school_admins.item_create',$data);
    }

    public function item_add(Request $request)
    {
        $att = $request->all();
        $att['years'] = serialize($att['years']);
        $att['code'] = auth()->user()->code;
        $att['limit'] = ($request->input('limit'))?1:null;
        if($att['game_type'] == "personal"){
            $att['official'] = null;
            $att['reserve'] = null;
        }
        if($att['game_type'] == "class"){
            $att['official'] = null;
            $att['reserve'] = null;
            $att['group'] = 4;
            $att['people'] = 1;
        }
        $item = Item::create($att);
        if($att['game_type'] == "class"){
            $years = unserialize($item->years);
            $student_classes = StudentClass::where('semester', $item->action->semester)
                ->where('code', $item->code)
                ->whereIn('student_year',$years)
                ->orderBy('student_year')
                ->orderBy('student_class')
                ->get();
            foreach($student_classes as $student_class){
                $att['code'] = $item->code;
                $att['item_id'] = $item->id;
                $att['item_name'] = $item->name;
                $att['game_type'] = "class";
                $att['student_id'] = $student_class->id;
                $att['action_id'] = $item->action_id;
                $att['student_year'] = $student_class->student_year;
                $att['student_class'] = $student_class->student_class;
                $att['sex'] = 4;
                StudentSign::create($att);
            }
        }
        return redirect()->route('school_admins.item',$item->action_id);
    }

    public function item_import(Request $request)
    {
        $items = Item::where('action_id',$request->input('old_action_id'))->get();
        foreach($items as $item){
            $att = $item->getAttributes();
            $att['action_id'] = $request->input('new_action_id');
            $att['disable'] = null;
            unset($att['id']);
            unset($att['created_at']);
            unset($att['updated_at']);
            Item::create($att);
        }
        return redirect()->route('school_admins.item');
    }

    public function item_destroy(Item $item)
    {
        if($item->code != auth()->user()->code){
            return redirect()->route('school_admins.item',$item->action_id);
        }
        StudentSign::where('item_id',$item->id)->delete();
        $item->delete();
        return redirect()->route('school_admins.item',$item->action_id);
    }

    public function item_edit(Item $item)
    {
        //不是本校即退回
        if($item->code != auth()->user()->code) return back();

        $data = [
            'item'=>$item
        ];
        return view('school_admins.item_edit',$data);
    }

    public function item_update(Request $request,Item $item)
    {
        $att = $request->all();
        //如果原來是班際賽，改為團體，要刪掉已報名的班際賽
        if($item->game_type == "class" and $att['game_type'] != "class"){
            StudentSign::where('item_id',$item->id)->delete();
        }
        $att['years'] = serialize($att['years']);
        $att['limit'] = ($request->input('limit'))?1:null;
        if($att['game_type'] == "personal"){
            $att['official'] = null;
            $att['reserve'] = null;
        }
        if($att['game_type'] == "class"){
            $att['official'] = null;
            $att['reserve'] = null;
            $att['group'] = 4;
            $att['people'] = 1;
        }
        $item->update($att);

        if($att['game_type'] == "class"){
            $years = unserialize($item->years);
            $student_classes = StudentClass::where('semester', $item->action->semester)
                ->where('code', $item->code)
                ->whereIn('student_year',$years)
                ->orderBy('student_year')
                ->orderBy('student_class')
                ->get();
            StudentSign::where('item_id',$item->id)->delete();
            foreach($student_classes as $student_class){
                $att_student_sign['code'] = $item->code;
                $att_student_sign['item_id'] = $item->id;
                $att_student_sign['item_name'] = $item->name;
                $att_student_sign['game_type'] = "class";
                $att_student_sign['student_id'] = $student_class->id;
                $att_student_sign['action_id'] = $item->action_id;
                $att_student_sign['student_year'] = $student_class->student_year;
                $att_student_sign['student_class'] = $student_class->student_class;
                $att_student_sign['sex'] = 4;
                StudentSign::create($att_student_sign);
            }
        }

        return redirect()->route('school_admins.item');
    }

    public function item_delete(Item $item)
    {
        //不是本校即退回
        if($item->code != auth()->user()->code) return back();

        $att['disable'] =1;
        $item->update($att);
        return redirect()->route('school_admins.item');
    }

    public function item_enable(Item $item)
    {
        //不是本校即退回
        if($item->code != auth()->user()->code) return back();

        $att['disable'] =null;
        $item->update($att);
        return redirect()->route('school_admins.item');
    }

    public function action()
    {
        $actions = Action::where('code',auth()->user()->code)
            ->orderBy('id','DESC')
            ->get();
        $data = [
            'actions'=>$actions,
        ];
        return view('school_admins.action',$data);
    }

    public function action_show(Action $action)
    {
        //不是本校即退回
        if($action->code != auth()->user()->code) return back();

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


        $data = [
            'action'=>$action,
            'items'=>$items,
            'student_classes'=>$student_classes,

        ];
        return view('school_admins.action_show',$data);

    }

    public function action_set_number_null(Action $action)
    {
        $att_set_null['number'] = null;
        Student::where('code',auth()->user()->code)
            ->where('semester',auth()->user()->semester)
            ->update($att_set_null);
        return redirect()->back()->withErrors(['error'=>['編入號碼清空完成！']]);
    }

    public function action_set_number(Action $action)
    {
        $att_set_null['number'] = null;
        Student::where('code',auth()->user()->code)
            ->where('semester',auth()->user()->semester)
            ->update($att_set_null);
        $student_signs = StudentSign::where('action_id',$action->id)
            ->orderBy('student_year')
            ->orderBy('student_class')
            ->orderBy('num')
            ->get();

        $students = [];
        if(!empty($student_signs)){
            foreach($student_signs as $student_sign){
                if($student_sign->item->game_type <> "class"){
                    $students[$student_sign->student_id]['name'] = $student_sign->student->name;
                    $students[$student_sign->student_id]['year'] = $student_sign->student_year;
                    $students[$student_sign->student_id]['class'] = $student_sign->student_class;
                    $students[$student_sign->student_id]['num'] = $student_sign->student->num;
                }
            }
        }
        $s = 1;
	$last_class = "";
	foreach($students as $k => $v){
	    if($last_class <> $v['year'].$v['class']) $s =1;
            if($action->numbers == 4){
                $number = $v['year'].$v['class'].sprintf("%02s",$s);
            }
            if($action->numbers == 5){
                $number = $v['year'].sprintf("%02s",$v['class']).sprintf("%02s",$s);
            }
            $student = Student::find($k);
            $att['number'] = $number;
            $student->update($att);
	    $s++;
	    $last_class = $v['year'].$v['class'];
        }

        return redirect()->back()->withErrors(['error'=>['編入號碼完成！']]);

    }

    public function action_create()
    {
        $data = [

        ];
        return view('school_admins.action_create',$data);
    }

    public function action_add(Request $request)
    {
        $att = $request->all();
        $att['code'] = auth()->user()->code;
        $att['open'] = ($request->input('open'))?1:null;
        Action::create($att);
        return redirect()->route('school_admins.action');
    }

    public function action_edit(Action $action)
    {
        //不是本校即退回
        if($action->code != auth()->user()->code) return back();

        $data = [
            'action'=>$action
        ];
        return view('school_admins.action_edit',$data);
    }

    public function action_update(Request $request,Action $action)
    {
        $att = $request->all();
        $att['open'] = ($request->input('open'))?1:null;
        $action->update($att);
        return redirect()->route('school_admins.action');
    }

    public function action_delete(Action $action)
    {
        //不是本校即退回
        if($action->code != auth()->user()->code) return back();

        $att['disable'] =1;
        $action->update($att);
        return redirect()->route('school_admins.action');
    }

    public function action_destroy(Action $action)
    {
        //不是本校即退回
        if($action->code != auth()->user()->code) return back();

        Item::where('action_id',$action->id)->delete();
        StudentSign::where('action_id',$action->id)->delete();
        $action->delete();
        return redirect()->route('school_admins.action');
    }

    public function action_enable(Action $action)
    {
        //不是本校即退回
        if($action->code != auth()->user()->code) return back();

        $att['disable'] =null;
        $action->update($att);
        return redirect()->route('school_admins.action');
    }

    public function students($action_id=null)
    {
        $actions = Action::where('code',auth()->user()->code)->orderBy('id','DESC')->get();
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
        $student_classes = [];
        if($select_action){
            $action = Action::find($select_action);

            //不是本校即退回
            if($action->code != auth()->user()->code) return back();

            $student_classes = StudentClass::where('semester',$action->semester)
                ->where('code',$action->code)
                ->orderBy('student_year')
		->orderBy('student_class')
                ->get();
        }

        $data = [
            'action_array'=>$action_array,
            'select_action'=>$select_action,
            'student_classes'=>$student_classes,
            'action'=>$action,
        ];

        return view('school_admins.students',$data);
    }

    public function records($action_id=null)
    {
        $actions = Action::where('code',auth()->user()->code)->orderBy('id','DESC')->get();
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

        $years = [];
        $year_students = [];

        $items = [];
        $student_classes = [];
        $student_signs = [];
        $action = [];
        $st_number = [];
        if($select_action){
            $action = Action::find($select_action);

            //不是本校即退回
            if($action->code != auth()->user()->code) return back();

            $items = Item::where('action_id',$select_action)
                ->get();

            $student_classes = StudentClass::where('semester',$action->semester)
                ->where('code',$action->code)
                ->orderBy('student_year')
                ->orderBy('student_class')
                ->get();

            $student_signs = StudentSign::where('action_id',$action->id)
                ->where('code',$action->code)
                ->orderBy('student_year')
		        ->orderBy('student_class')
		        ->orderBy('num')
                ->get();

            foreach($student_signs as $student_sign){
                $years[$student_sign->student_year] = 1;
                if($student_sign->item->game_type == "class"){
                    $year_students[$student_sign->student_year][$student_sign->item_id][$student_sign->sex][$student_sign->student_id] = $student_sign->get_student_class->student_year."年".$student_sign->get_student_class->student_class."班";
                }
                if($student_sign->item->game_type == "personal" or $student_sign->item->game_type == "group"){
                    $year_students[$student_sign->student_year][$student_sign->item_id][$student_sign->sex][$student_sign->student_id] = $student_sign->student->name;
                    $st_number[$student_sign->student_id] = $student_sign->student->number;
                }
            }
        }

        $data = [
            'items'=>$items,
            'student_signs'=>$student_signs,
            'action_array'=>$action_array,
            'select_action'=>$select_action,
            'student_classes'=>$student_classes,
            'action'=>$action,
            'years'=>$years,
            'year_students'=>$year_students,
            'st_number'=>$st_number,
        ];

        return view('school_admins.records',$data);
    }

    public function download_records(Action $action)
    {
        //不是本校即退回
        if($action->code != auth()->user()->code) return back();

        $items = Item::where('action_id',$action->id)
            ->orderBy('order')
            ->get();

        $student_signs = StudentSign::where('action_id',$action->id)
            ->where('code',$action->code)
            ->orderBy('student_year')
            ->orderBy('student_class')
            ->orderBy('is_official','DESC')
            ->orderBy('num')
            ->get();

        $cht_num = config('chcschool.cht_num');

        foreach($student_signs as $student_sign){
            if($student_sign->game_type == "personal"){
                $sign_data[$student_sign->item_id][$student_sign->id]['item_name'] = $student_sign->item->name;
                $sign_data[$student_sign->item_id][$student_sign->id]['year_class'] = $cht_num[$student_sign->student_year]."年".$student_sign->student_class."班";
                $sign_data[$student_sign->item_id][$student_sign->id]['num'] = $student_sign->num;
                $sign_data[$student_sign->item_id][$student_sign->id]['number'] = $student_sign->student->number;
                $sign_data[$student_sign->item_id][$student_sign->id]['name'] = $student_sign->student->name;
                $sign_data[$student_sign->item_id][$student_sign->id]['sex'] = $student_sign->sex."子組";
            }
            if($student_sign->game_type == "group"){
                if($student_sign->sex=="男"){
                    $k = 'b'.$student_sign->student_year.$student_sign->student_class.$student_sign->group_num;
                }
                if($student_sign->sex=="女"){
                    $k = 'g'.$student_sign->student_year.$student_sign->student_class.$student_sign->group_num;
                }
                $sign_data[$student_sign->item_id][$k]['item_name'] = $student_sign->item->name;
                $sign_data[$student_sign->item_id][$k]['year_class'] = $cht_num[$student_sign->student_year]."年".$student_sign->student_class."班";
                $sign_data[$student_sign->item_id][$k]['num'] = "";
                $sign_data[$student_sign->item_id][$k]['number'] = "";
                if(!isset($sign_data[$student_sign->item_id][$k]['name'])){
                    $sign_data[$student_sign->item_id][$k]['name'] = "";
                }
                if(empty($student_sign->is_official)){
                    $sign_data[$student_sign->item_id][$k]['name'] .= $student_sign->student->name."(候) ";
                }else{
                    $sign_data[$student_sign->item_id][$k]['name'] .= $student_sign->student->name." ";
                }

                $sign_data[$student_sign->item_id][$k]['sex'] = $student_sign->sex."子組";
            }
            if($student_sign->game_type == "class"){
                $sign_data[$student_sign->item_id][$student_sign->id]['item_name'] = $student_sign->item->name;
                $sign_data[$student_sign->item_id][$student_sign->id]['year_class'] = $cht_num[$student_sign->student_year]."年".$student_sign->student_class."班";
                $sign_data[$student_sign->item_id][$student_sign->id]['num'] = "";
                $sign_data[$student_sign->item_id][$student_sign->id]['number'] = "";
                $sign_data[$student_sign->item_id][$student_sign->id]['name'] = $cht_num[$student_sign->student_year]."年".$student_sign->student_class."班";
                $sign_data[$student_sign->item_id][$student_sign->id]['sex'] = "班際賽";
            }
        }
        $i = 0;
        foreach($items as $item){
            if(isset($sign_data[$item->id])){
                foreach($sign_data[$item->id] as $k=>$v){
                    $data[$i]['項目名稱'] = $v['item_name'];
                    $data[$i]['班級'] = $v['year_class'];
                    $data[$i]['座號'] = $v['num'];
                    $data[$i]['布牌號碼'] = $v['number'];
                    $data[$i]['姓名'] = $v['name'];
                    $data[$i]['組別'] = $v['sex'];
                    $i++;
                }
            }
        }

        $list = collect($data);

        return (new FastExcel($list))->download(auth()->user()->semester.'運動會學生報名資料.xlsx');


    }

    public function scores($action_id=null)
    {
        $actions = Action::where('code',auth()->user()->code)->orderBy('id','DESC')->get();
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
        if($select_action) {
            $action = Action::find($select_action);

            //不是本校即退回
            if ($action->code != auth()->user()->code) return back();
        }
        $data = [
            'action_array'=>$action_array,
            'select_action'=>$select_action,
            'action'=>$action,
        ];

        return view('school_admins.scores',$data);
    }

    public function all_scores($action_id=null)
    {
        $actions = Action::where('code',auth()->user()->code)->orderBy('id','DESC')->get();
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
        if($select_action) {
            $action = Action::find($select_action);

            //不是本校即退回
            if ($action->code != auth()->user()->code) return back();
        }
        $data = [
            'action_array'=>$action_array,
            'select_action'=>$select_action,
            'action'=>$action,
        ];

        return view('school_admins.all_scores',$data);
    }

    public function total_scores($action_id=null)
    {
        $actions = Action::where('code',auth()->user()->code)->orderBy('id','DESC')->get();
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
        if($select_action) {
            $action = Action::find($select_action);

            //不是本校即退回
            if ($action->code != auth()->user()->code) return back();
        }
        $data = [
            'action_array'=>$action_array,
            'select_action'=>$select_action,
            'action'=>$action,
        ];

        return view('school_admins.total_scores',$data);
    }


}
