<?php

namespace Arispati\LaravelInstaller\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Installed
{
    /**
     * Installer route name
     *
     * @var array
     */
    protected $routeInstaller = ['installer.index', 'installer.validation', 'installer.form', 'installer.submit'];
    
    /**
     * Updater route name
     *
     * @var array
     */
    protected $routeUpdater = ['installer.update', 'installer.update-submit'];

    /**
     * Request
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $this->request = $request;

        try {
            // if request from installer routes
            if ($this->isInstallerRoute()) {
                // if already installed
                if ($this->isAlreadyInstalled()) {
                    // only update request can be handled
                    if (!$this->isUpdateRequest()) {
                        throw new \Exception('abort', 404);
                    }
                } else {
                    // if not installed,
                    // only installer request can be handled
                    if (!$this->isInstallerRequest()) {
                        throw new \Exception('installer', 302);
                    }
                }
            } else {
                // otherwise, on general routes
                // redirect if not installed
                if (!$this->isAlreadyInstalled()) {
                    throw new \Exception('installer', 302);
                }
            }
            
            // handled request
            return $next($request);
        } catch (\Exception $e) {
            switch ($e->getCode()) {
                case 404:
                    App::abort(404);
                    break;
                case 302:
                    return Redirect::route('installer.index');
                    break;
                default:
                    return Redirect::route('home');
                    break;
            }
        }
    }

    /**
     * Is Installer Route
     *
     * @return boolean
     */
    public function isInstallerRoute(): bool
    {
        return Str::of($this->request->getPathInfo())->contains('installer');
    }

    /**
     * Is already installed
     *
     * @return boolean
     */
    public function isAlreadyInstalled(): bool
    {
        return Storage::exists('installed');
    }

    /**
     * Is update request
     *
     * @return boolean
     */
    public function isUpdateRequest(): bool
    {
        return in_array(
            // get route name
            $this->request->route()->getName(),
            // updater route name
            $this->routeUpdater
        );
    }

    /**
     * Is installer request
     *
     * @return boolean
     */
    public function isInstallerRequest(): bool
    {
        return in_array(
            // get route name
            $this->request->route()->getName(),
            // installer route name
            $this->routeInstaller
        );
    }
}
