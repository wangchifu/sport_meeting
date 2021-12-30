<?php

namespace App\Http\Middleware;

use App\StudentClass;
use Closure;
use Illuminate\Support\Facades\Auth;

class ClassTeacherMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            $check1 = StudentClass::where('semester', auth()->user()->semester)->where('code', auth()->user()->code)->where('user_ids', auth()->user()->id)->first();
            $check2 = StudentClass::where('semester', auth()->user()->semester)->where('code', auth()->user()->code)->where('user_ids', 'like', auth()->user()->id . ',%')->first();
            $check3 = StudentClass::where('semester', auth()->user()->semester)->where('code', auth()->user()->code)->where('user_ids', 'like', '%,' . auth()->user()->id)->first();
            $check4 = StudentClass::where('semester', auth()->user()->semester)->where('code', auth()->user()->code)->where('user_names', 'like','%' .auth()->user()->name. '%')->first();
            if (empty($check1) and empty($check2) and empty($check3) and empty($check4)) {
                return redirect('class_teacher_error');
            } else {
                return $next($request);
            }
        }
    }
}
