<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IsJury
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user()) {
            $permission = DB::table('groups')->select('permission')
                ->where('id', auth()->user()->group_id)
                ->first();
            $permission = !is_null($permission) ? $permission->permission : null;
            if ($permission == 'jury') {
                return $next($request);
            }
        }

        return redirect('home')->with('error', 'You have not jury access');
    }
}
