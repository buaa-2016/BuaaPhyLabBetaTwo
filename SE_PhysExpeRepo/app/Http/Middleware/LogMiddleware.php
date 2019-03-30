<?php

namespace App\Http\Middleware;

use Log;
use Closure;
use Illuminate\Http\Request;

final class LogMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        Log::info('request', [
            $request->method(),
            $request->url(),
        ]);

        return $response;
  }
}
