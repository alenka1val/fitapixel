<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

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
        if (Auth::user() &&  Auth::user()->group->permmision == 'jury') {
            return $next($request);
        }

        return redirect('home')->with('error', 'You have not jury access');
    }
}
