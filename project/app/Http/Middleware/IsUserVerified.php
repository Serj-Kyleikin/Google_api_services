<?php

namespace App\Http\Middleware;

use App\SharedKernel\Utils\DevelopUtils;
use Closure;
use Illuminate\{
    Http\Request,
    Http\Response,
};

class IsUserVerified
{
    /**
     * @throws \Throwable
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if(DevelopUtils::isNotDevelop()) {

            $user = auth()->user();
            $phone = $user?->phone;

            if($user->email_verified_at == null && $phone?->verified_at == null) {

                return response()->json([
                    'success' => false,
                    'message' => 'User is not verified'
                ], Response::HTTP_FORBIDDEN);
            }
        }

        return $next($request);
    }
}
