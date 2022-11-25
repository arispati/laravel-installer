<?php

namespace Arispati\LaravelInstaller\Http\Controllers;

use Arispati\LaravelInstaller\Libraries\License;
use Illuminate\Http\Request;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class InstallerController
{
    /**
     * Force command
     *
     * @var array
     */
    protected $forceCommand = ['--force' => true];

    /**
     * License page
     *
     * @return void
     */
    public function index()
    {
        $identifier = License::request();

        return View::make('installer::index', compact('identifier'));
    }

    /**
     * License validation
     *
     * @param Request $request
     * @return void
     */
    public function validateLicense(Request $request)
    {
        $request->validate([
            'license' => 'required'
        ]);

        if (License::isValid($request->input('license'))) {
            return Redirect::route('installer.form');
        }

        return Redirect::back()->withErrors([
            'license' => 'Lisensi tidak valid!'
        ])->onlyInput('license');
    }

    /**
     * Install application
     *
     * @return void
     */
    public function install()
    {
        // clear bootstrap cached
        $this->clearBootstrapCache();

        try {
            // rollback any migrations
            Artisan::call('migrate:rollback', $this->forceCommand);
        } catch (\Exception $e) {
            // do nothing
        }

        try {
            // run install commands
            $commands = Config::get('installer.commands_install', []);

            foreach ($commands as $command) {
                $args = array_merge($command['args'], $this->forceCommand);

                Artisan::call($command['command'], $args);
            }

            Storage::put('installed', Crypt::encrypt($this->getAppInfo()));

            $status = true;
        } catch (\Exception $e) {
            $status = false;
        }

        // optimize app
        $this->optimize();

        return Response::json([
            'status' => $status
        ]);
    }

    /**
     * Updater
     *
     * @param Request $request
     * @return void
     */
    public function update(Request $request)
    {
        // clear bootstrap cached
        $this->clearBootstrapCache();

        try {
            // run install commands
            $commands = Config::get('installer.commands_update', []);

            foreach ($commands as $command) {
                $args = array_merge($command['args'], $this->forceCommand);

                Artisan::call($command['command'], $args);
            }

            Storage::put('installed', Crypt::encrypt($this->getAppInfo()));

            $request->session()->forget('has_update');

            $status = true;
        } catch (\Exception $e) {
            $status = false;
        }

        // optimize app
        $this->optimize();

        return Response::json([
            'status' => $status
        ]);
    }

    /**
     * Get app info
     *
     * @return object
     */
    protected function getAppInfo(): object
    {
        return (object) [
            'app' => (object) [
                'version' => Env::get('APP_VERSION'),
                'code' => Env::get('APP_VERSION_CODE')
            ]
        ];
    }

    /**
     * Optimize app
     *
     * @return void
     */
    protected function optimize()
    {
        try {
            // optimize app
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('view:cache');
        } catch (\Exception $e) {
            // do nothing
        }
    }

    /**
     * Clear bootstrap cache
     *
     * @return void
     */
    public function clearBootstrapCache()
    {
        try {
            // clear all cache
            Artisan::call('optimize:clear');
        } catch (\Exception $e) {
            // do nothing
        }
    }
}
