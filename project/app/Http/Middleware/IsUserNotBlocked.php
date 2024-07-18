<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\{
    Http\Request,
    Http\Response,
};

class IsUserNotBlocked
{
    /**
     * @throws \Throwable
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if(auth()->user()->block) {

            return response()->json([
                'success' => false,
                'message' => 'User is blocked',
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
