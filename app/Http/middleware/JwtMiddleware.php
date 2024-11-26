<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $authorizationToken = $request->header("Authorization");
        if (!$authorizationToken){
            return response()->json(['message' => 'Authorization token require'], 401);
        }

        try{
            $user = JWTAuth::parseToken()->authenticate();$user = JWTAuth::parseToken()->authenticate();
            $request->merge(['user' => $user]);
        }catch (Exception $e){
            return response()->json(['message' => 'Invalid jwt token or expired'], 401);
        }
        
        return $next($request);
    }
}
