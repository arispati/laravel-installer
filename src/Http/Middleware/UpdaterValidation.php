<?php

namespace Arispati\LaravelInstaller\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class UpdaterValidation
{
    protected $updaterRoute = 'dashboard.index';

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Storage::exists('installed')) {
            try {
                $installed = json_decode(Crypt::decrypt(Storage::get('installed')));

                if (is_null($installed)) {
                    throw new \Exception('update', 99);
                }

                if ($installed->app->code <= Config::get('app.version_code')) {
                    throw new \Exception('update', 99);
                }
            } catch (\Exception $e) {
                if ($e->getCode() == 99) {
                    $request->session()->put('has_update', true);

                    return Redirect::route($this->updaterRoute);
                } else {
                    App::abort(404);
                }
            }

            return $next($request);
        }

        return Redirect::route('installer.index');
    }
}
