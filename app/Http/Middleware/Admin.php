<?php

namespace Snek\Http\Middleware;

use Auth;

use Closure;

class Admin
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
        if (Auth::user()->is_admin != true)
        {
            return redirect('home');
        }

        return $next($request);
    }
}
