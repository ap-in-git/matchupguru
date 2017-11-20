<?php

namespace App\Http\Middleware;

use Closure;

class UserMiddleware
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

      if(!Auth::check())
        redirect("/");

      if(Auth::user()->role!=2){
        abort(403);
      }
        return $next($request);
    }
}
