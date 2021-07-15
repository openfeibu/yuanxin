<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */

    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                //return response('Unauthorized.', 401);
                return response()->json([
                    'msg' => '登录失效，请刷新重新登录',
                    'code' => 401,
                ]);
            } else {
                $guard =  current(explode(".", $guard));
                return redirect()->guest("{$guard}/login");
            }

        }
        return $next($request);
    }

}
