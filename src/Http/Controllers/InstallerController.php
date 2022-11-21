<?php

namespace Arispati\LaravelInstaller\Http\Controllers;

use Arispati\LaravelInstaller\Libraries\License;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class InstallerController
{
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
            return Redirect::route('install.form');
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
        try {
            Artisan::call('migrate', [
                '--force' => true
            ]);
            Artisan::call('db:seed', [
                '--class' => 'AdminSeeder',
                '--force' => true
            ]);

            Storage::put('installed', '');

            $status = true;
        } catch (\Exception $e) {
            $status = false;
        }

        return Response::json([
            'status' => $status
        ]);
    }
}
