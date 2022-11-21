<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Arispati\LaravelInstaller\Http\Controllers\InstallerController;
use Arispati\LaravelInstaller\Http\Middleware\InternetAccess;
use Arispati\LaravelInstaller\Http\Middleware\UpdaterHasUpdate;
use Illuminate\Support\Facades\Route;

// Install routes
Route::get('/', [InstallerController::class, 'index'])->name('index')->middleware([
    InternetAccess::class,
    'throttle:3,60'
]);
Route::post('validate', [InstallerController::class, 'validateLicense'])->name('validation');
Route::view('form', 'installer::form')->name('form');
Route::get('submit', [InstallerController::class, 'install'])->name('submit');
// Install routes

// Update routes
Route::view('update', 'installer::update')->name('update')->middleware(UpdaterHasUpdate::class);
Route::get('update-submit', [InstallerController::class, 'update'])->name('update-submit');
// Update routes
