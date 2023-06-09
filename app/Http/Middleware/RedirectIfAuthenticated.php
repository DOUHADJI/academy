<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {

                if(Auth::user()=="student")
                {
                    return redirect() -> route("showStudent");     
                }

                if(Auth::user()=="admin")
                {
                    return redirect() -> route("showYear");     
                }

                if(Auth::user()=="professor")
                {
                    return redirect() -> route("showExamsProf");     
                }
            }
        }

        return $next($request);
    }
}