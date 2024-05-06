<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RefreshJwtToken
{
    public function handle(Request $request, Closure $next)
    {
        try {
            $token = JWTAuth::parseToken()->refresh();
            $request->headers->set('Authorization', 'Bearer ' . $token);
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            logger()->error('Token refresh failed: ' . $e->getMessage());
            
            // Return a 401 Unauthorized response to indicate token expiration or invalidity
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
