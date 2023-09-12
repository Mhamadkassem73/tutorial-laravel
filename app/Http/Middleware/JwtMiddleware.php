<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Closure;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use App\Http\Controllers\ApiController;
use Tymon\JWTAuth\Facades\JWTAuth as FacadesJWTAuth;

class JwtMiddleware extends ApiController
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = FacadesJWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return ApiController::errorResponse("Invalid Token",401);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return ApiController::errorResponse("Expired Token",401);
            }else{
                return ApiController::errorResponse('Authorization token not found',401);
            }
        }
        return $next($request);
    }
}
