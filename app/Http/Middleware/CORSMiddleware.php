<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CORSMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        return $next($request)
            ->header('Access-Control-Allow-Origin', '*');
    }
}
