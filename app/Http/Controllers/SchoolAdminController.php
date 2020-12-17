<?php

namespace App\Http\Controllers;

use App\SchoolApi;
use App\User;
use App\StudentClass;
use App\Student;
use App\SchoolAdmin;
use App\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchoolAdminController extends Controller
{
    public function api()
    {
        $school_api = SchoolApi::where('code',auth()->user()->code)->first();

        $students = Student::where('code',auth()->user()->code)->get();
        $student_classes = StudentClass::where('code',auth()->user()->code)->get();
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
                if(!isset($class_data[$student->semester])) $class_data[$student->semester]=0;
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

        $semester = $data->學年.$data->學期;

        $techer_data = $data->學期教職員;
        foreach($techer_data as $k=>$v){
            $edu_key = $v->身分證編碼;
            $user = User::where('edu_key',$edu_key)->first();
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
            $student_class= StudentClass::where('semester',$att2['semester'])
                ->where('student_year',$att2['student_year'])
                ->where('student_class',$att2['student_class'])
                ->first();
            if(empty($student_class)){
                StudentClass::create($att2);
            }else{
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
                $student = Student::where('semester',$att3['semester'])
                    ->where('edu_key',$att3['edu_key'])
                    ->first();
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

    public function account()
    {
        $users = User::where('code',auth()->user()->code)
            ->orderBy('disable')
            ->get();
        $data = [
            'users'=>$users,
        ];
        return view('school_admins.account',$data);
    }

    public function account_set1(User $user)
    {
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
        $att['disable'] = 1;
        $user->update($att);
        return redirect()->route('school_admins.account');
    }

    public function account_enable(User $user)
    {
        $att['disable'] = null;
        $user->update($att);
        return redirect()->route('school_admins.account');
    }

    public function account_remove_power(User $user)
    {
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
            $teacher_array = explode(',',$student_class->user_ids);
            $teacher_name = "";
            foreach($teacher_array as $k=>$v){
                $user = User::find($v);
                $teacher_name .= $user->name.',';
            }
            $teacher_name = substr($teacher_name,0,-1);
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

    public function item()
    {
        $items = Item::where('code',auth()->user()->code)->orderBy('disable')->orderBy('order')->get();
        $data = [
            'items'=>$items,
        ];
        return view('school_admins.item',$data);
    }

    public function item_create()
    {
        $data = [

        ];
        return view('school_admins.item_create',$data);
    }

    public function item_add(Request $request)
    {
        $att = $request->all();
        $att['years'] = serialize($att['years']);
        $att['code'] = auth()->user()->code;
        $att['limit'] = ($request->input('limit'))?1:null;
        Item::create($att);
        return redirect()->route('school_admins.item');
    }

    public function item_edit(Item $item)
    {
        $data = [
            'item'=>$item
        ];
        return view('school_admins.item_edit',$data);
    }

    public function item_update(Request $request,Item $item)
    {
        $att = $request->all();
        $att['years'] = serialize($att['years']);
        $att['limit'] = ($request->input('limit'))?1:null;
        $item->update($att);
        return redirect()->route('school_admins.item');
    }

    public function item_delete(Item $item)
    {
        $att['disable'] =1;
        $item->update($att);
        return redirect()->route('school_admins.item');
    }

    public function item_enable(Item $item)
    {
        $att['disable'] =null;
        $item->update($att);
        return redirect()->route('school_admins.item');
    }


}
