<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsPhotographer
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
        if (Auth::user() &&  Auth::user()->group->permmision == 'photographer') {
            return $next($request);
        }

        return redirect('home')->with('error', 'You have not photographer access');
    }
}
