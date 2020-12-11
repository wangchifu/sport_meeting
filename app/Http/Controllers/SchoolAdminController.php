<?php

namespace App\Http\Controllers;

use App\SchoolApi;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchoolAdminController extends Controller
{
    public function api()
    {
        $school_api = SchoolApi::where('code',auth()->user()->code)->first();
        $data = [
            'school_api'=>$school_api,
        ];
        return view('school_admins.api',$data);
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
        $users = User::where('code',auth()->user()->code)->get();
        $data = [
            'users'=>$users,
        ];
        return view('school_admins.account',$data);
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
}
