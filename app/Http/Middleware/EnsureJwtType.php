<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class EnsureJwtType
{
    public function handle(Request $request, Closure $next, string $type): Response
    {
        try {
            $payload = JWTAuth::parseToken()->getPayload();
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
                'data' => null,
            ], 401);
        }

        $claim = (string) $payload->get('typ', '');

        if ($claim !== $type) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden for this token type.',
                'data' => null,
            ], 403);
        }

        return $next($request);
    }
}
