<?php

use Illuminate\Support\Facades\Route;

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


Route::get('/login', function () {
    return view('login');
});
Route::post('login', [App\Http\Controllers\CustomAuthController::class, 'tesLogin'])->name('login');

Route::group(['middleware' => ['authed']], function () {
    Route::get('/', function () {
        return view('welcome');
    });
    // Route::get('cekAuth', [App\Http\Controllers\CustomAuthController::class, 'openAuth']);
    Route::get('gps', [App\Http\Controllers\gpsController::class, 'gps']);
    Route::get('logout', [App\Http\Controllers\CustomAuthController::class, 'logout']);
});
