<?php

namespace App\Http\Middleware;
use Auth;
use Closure;

class AuthorMiddleware
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
        if(!Auth::check()){
           return redirect()->route("login");
        }else{
          if(Auth::user()->role==1||Auth::user()->role==3||Auth::user()->role==4){
              return $next($request);
          }else{
            App::abort(403);
          }

        }




    }
}
