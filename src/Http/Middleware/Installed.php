<?php

namespace Arispati\LaravelInstaller\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Installed
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
        // if file already exist
        if (Storage::exists('installed')) {
            // if request contains installer url
            if (Str::of($request->getPathInfo())->contains('installer')) {
                // if request not for update redirect to home route
                if (
                    ! in_array(
                        $request->route()->getName(),
                        // route name
                        ['installer.update', 'installer.update-submit']
                    )
                ) {
                    return Redirect::route('home');
                }
            }

            return $next($request);
        }

        return Redirect::route('installer.index');
    }
}
