<?php

namespace App\Http\Middleware;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\Account;
use Closure;

class IsStudent
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
        $user = JWTAuth::parseToken()->authenticate();
        if($user['type'] === 'student') {
            return $next($request);
        } else {
            return response()->json([
                'status' => false,
                'msg' => 'You\'r not the student!'
            ], 401);
        }
    }
}
