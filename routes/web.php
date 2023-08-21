<?php

use App\Http\Controllers\CrudScheduleController;
use App\Http\Controllers\GoogleAuthenticatorController;
use App\Http\Controllers\HistorialController;
use App\Http\Controllers\LinkedinController;
use App\Http\Controllers\LinkedinPostController;
use App\Http\Controllers\PostsQueueController;
use App\Http\Controllers\PostsRedditController;
use App\Http\Controllers\RedditAuthController;
use App\Http\Controllers\TwitterController;
use App\Http\Controllers\TwitterPostController;
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

Route::get('/historial', [HistorialController::class, 'index'])->name('historial');

//twitter
Route::post('/connect/oaut/twitter', [TwitterController::class, 'ConnectOautTwitter'])->middleware(['auth']);

Route::get('auth/twitter/callback', [TwitterController::class, 'TwitterCallback'])->middleware(['auth']);

Route::get('/publicaciones/twitter', [TwitterPostController::class, 'index'])->name('publicaciones.twitter');

Route::post('/twitter/post', [TwitterPostController::class, 'postToTwitter'])->name('twitter.post');

//reddit
Route::get('auth/reddit', [RedditAuthController::class, 'redirectToReddit'])->name('reddit.auth');

Route::get('auth/reddit/callback', [RedditAuthController::class, 'handleRedditCallback']);

Route::get('/publicaciones', [RedditAuthController::class, 'index'])->name('publicaciones.index');

Route::post('/reddit/post', [PostsRedditController::class, 'store'])->name('reddit.post');

//linkedin
Route::get('auth/linkedin', [LinkedinController::class, 'linkedinToReddit'])->name('linkedin.auth');

Route::get('auth/Linkedin/callback', [LinkedinController::class, 'handleLinkedinCallback']);

Route::get('publicaciones/LinkedIn', [LinkedinPostController::class, 'index'])->name('publicaciones.linkedin');

Route::post('LinkedIn/post', [LinkedinPostController::class, 'postOnLinkedin'])->name('linkedin.post');


//cola

Route::post('/send/post/queue/reddit', [PostsQueueController::class, 'sendToPostQueueReddit'])->name('send-to-post-queue-reddit');

Route::post('/send/post/queue/linkedin', [PostsQueueController::class, 'sendToPostQueueLinkedin'])->name('send-to-post-queue-linkedin');

//Horarios
Route::get('/shedule/create', [CrudScheduleController::class, 'index'])->name('shedule.create');

Route::post('/pots/schedule', [CrudScheduleController::class, 'store'])->name('post-new-schedule');

Route::get('/schedule/delete/{id}', [CrudScheduleController::class, 'delete'])->name('schedule.eliminar');

Route::get('/schedule/update/view/{id}', [CrudScheduleController::class, 'editView'])->name('schedule.edit');

Route::put('/schedule/update/{id}', [CrudScheduleController::class, 'edit'])->name('schedule.update');




