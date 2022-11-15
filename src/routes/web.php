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
use Illuminate\Support\Facades\Route;

Route::get('/', [InstallerController::class, 'index'])->name('install.index')->middleware('throttle:3,60');
Route::post('validate', [InstallerController::class, 'validateLicense'])->name('install.validation');
Route::view('form', 'installer::form')->name('install.form');
Route::get('submit', [InstallerController::class, 'install'])->name('install.submit');
