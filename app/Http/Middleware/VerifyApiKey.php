<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Closure;

class VerifyApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $request_api_key = $request->header('X-API-KEY');
        $api_key = env('MOBILE_API_KEY');
        if ($request_api_key !== $api_key)
            return response()->json([
                'message' => 'Invalid API Key',
            ], 401);

        return $next($request);
    }
}
