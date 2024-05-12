<?php

namespace App\Http\Middleware;

use App\Helpers\AuthHelper;
use Closure;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

class JWTAuth
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();
        if ($token) {
            try {
                JWT::decode($token, new Key(AuthHelper::getKey(), 'HS256'));

                return $next($request);

            } catch (Exception $e) {

            }
        }
        return response()->json([
            'success' => false,
            'message' => 'Accès refusé !',
        ], 401);
    }
}
