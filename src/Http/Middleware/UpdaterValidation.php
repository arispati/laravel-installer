<?php

namespace Arispati\LaravelInstaller\Http\Middleware;

use Closure;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class UpdaterValidation
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
        if (Storage::exists('installed')) {
            try {
                $installed = Crypt::decrypt(Storage::get('installed'));

                if (is_null($installed)) {
                    throw new \Exception('update', 99);
                }

                if ($installed->app->code < Env::get('APP_VERSION_CODE')) {
                    throw new \Exception('update', 99);
                }
            } catch (DecryptException $e) {
                $request->session()->put('has_update', true);

                    return Redirect::route('installer.update');
            } catch (\Exception $e) {
                if ($e->getCode() == 99) {
                    $request->session()->put('has_update', true);

                    return Redirect::route('installer.update');
                } else {
                    App::abort(404);
                }
            }

            return $next($request);
        }

        return Redirect::route('installer.index');
    }
}
