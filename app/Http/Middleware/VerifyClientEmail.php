<?php

namespace App\Http\Middleware;

use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Closure;

class VerifyClientEmail
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $email = $request->email;
        $user = User::where('email', '=', $email)->first();
        if (!$user)
            return response()->json([
                'message' => 'Your email does not match any records.',
            ]);

        if ($user->email_verified_at !== null)
            return $next($request);

        return response()->json([
            'message' => 'Your email must be verified before requesting for a password change.',
        ]);
    }
}
