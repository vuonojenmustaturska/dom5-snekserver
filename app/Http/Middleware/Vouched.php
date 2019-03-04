<?php

namespace Snek\Http\Middleware;

use Closure;
use Auth;

class Vouched
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user()->is_vouched != true || Auth::user()->is_admin != true)
        {
            return redirect('home');
        }

        return $next($request);
    }
}
