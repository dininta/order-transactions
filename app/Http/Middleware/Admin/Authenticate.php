<?php

namespace App\Http\Middleware\Admin;

use Closure;
use JWTAuth;

class Authenticate
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
        $token = JWTAuth::setRequest($request)->getToken();
        $user = JWTAuth::toUser($token);
        if (!$user->isAdmin()) {
            return response()->json([
                'status' => 404,
                'message' => 'Failed to authenticate user',
                'result' => null
            ]);
        }
        return $next($request);
    }
}
