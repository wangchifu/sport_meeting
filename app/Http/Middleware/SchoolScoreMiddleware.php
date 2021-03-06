<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\SchoolAdmin;

class SchoolScoreMiddleware
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
        if(Auth::guard($guard)->check()) {
            $school_admin = SchoolAdmin::where('user_id', auth()->user()->id)
                ->where(function($query){
                    $query->where('type', '1')
                        ->orWhere('type', '2');
                })
                ->first();

            if (!empty($school_admin)) {
                if ($school_admin->type===1 or $school_admin->type===2) {
                    return $next($request);
                }
            }
        }
        return redirect('/');
    }
}
