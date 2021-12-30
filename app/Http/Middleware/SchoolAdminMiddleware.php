<?php

namespace App\Http\Middleware;

use App\SchoolAdmin;
use Closure;
use Illuminate\Support\Facades\Auth;

class SchoolAdminMiddleware
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
                ->where('type', '1')
                ->first();

            if (!empty($school_admin)) {
                if ($school_admin->type===1) {
                    return $next($request);
                }
            }
        }
        return redirect('/');
    }
}
