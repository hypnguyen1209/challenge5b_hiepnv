<?php

namespace App\Http\Middleware;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\Account;
use Closure;

class IsTeacher
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
        if($user['type'] === 'admin' || $user['type'] === 'teacher') {
            return $next($request);
        } else {
            return response()->json([
                'status' => false
            ], 401);
        }
    }
}
