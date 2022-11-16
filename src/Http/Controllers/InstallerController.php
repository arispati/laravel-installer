<?php

namespace Arispati\LaravelInstaller\Http\Controllers;

use Arispati\LaravelInstaller\Libraries\License;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

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

        return view('installer::index', compact('identifier'));
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
            return redirect()->route('install.form');
        }

        return back()->withErrors([
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

        return response()->json([
            'status' => $status
        ]);
    }
}
