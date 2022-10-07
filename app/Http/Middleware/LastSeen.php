<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LastSeen
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return $next($request);
        }
        /** @var User $user  */
        $user = Auth::user();

        $user->update([
            'last_seen' => new \DateTime(),
        ]);

        return $next($request);
    }
}
