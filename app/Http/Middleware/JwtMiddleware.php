<?php

namespace App\Http\Middleware;

use App\Http\Responses\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return ApiResponse::errorResponse('User not found', 404);
            }
            return $next($request);
        } catch (TokenExpiredException $e) {
            return ApiResponse::errorResponse('Token expired', 401);
        } catch (TokenInvalidException $e) {
            return ApiResponse::errorResponse('Token is invalid', 401);
        } catch (JWTException $e) {
            return ApiResponse::errorResponse('Unauthorized', 401);
        }
    }
}
