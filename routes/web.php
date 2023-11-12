<?php

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/getdata', [App\Http\Controllers\HomeController::class, 'getData']);
Route::get('/get-friend-list', [App\Http\Controllers\HomeController::class, 'getFriendList']);
Route::post('/search', [App\Http\Controllers\HomeController::class, 'search']);

Route::get('/chat', [App\Http\Controllers\MessageController::class, 'chat'])->name('chat');
Route::get('/getChat/{id}', [App\Http\Controllers\MessageController::class, 'getChat']);
Route::post('/insert-chat/{id}', [App\Http\Controllers\MessageController::class, 'insertChat']);





//mail verify
Route::get('/email/verify', function () {
    return view('auth.verify');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
 
    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
 
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');
