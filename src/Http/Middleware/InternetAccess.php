<?php

namespace Arispati\LaravelInstaller\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class InternetAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $connected = @fsockopen("www.google.com", 443);

        if ($connected) {
            fclose($connected);
            return $next($request);
        } else {
            abort(404);
        }
    }
}
