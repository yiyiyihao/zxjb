<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $params = $request->input();
        $token = $params['token'] ?? '';
        $tokenInfo = cache($token);
        if (!$token || !$tokenInfo) {
            return response()->json(['code' => '1', 'msg' => '未登录或登录过期!']);
        }

        return $next($request);
    }
}
