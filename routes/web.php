<?php

use App\Http\Controllers\GoogleAuthenticatorController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');
Route::get('/configuraciones', function () {
    return view('configuraciones');
})->middleware(['auth'])->name('config');

require __DIR__.'/auth.php';

Route::get('/verificar2fa', [GoogleAuthenticatorController::class, 'aut2fac'])
->name('2fact');

Route::post('/verificar2fa', [GoogleAuthenticatorController::class, 'postVerifyTwoFactor']);

Route::post('/set/estado', [GoogleAuthenticatorController::class, 'updateGoogle2faEnabledStatus']);

Route::post('/set/keys', [GoogleAuthenticatorController::class, 'changeCredentials2FA']);