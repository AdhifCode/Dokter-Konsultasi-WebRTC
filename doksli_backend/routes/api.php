<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\Auth\SocialiteController;
use App\Http\Controllers\api\Auth\RegisterController;
use App\Http\Controllers\api\Auth\LoginController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\RoomController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
    // Login And Register
        Route::post('/auth/register', [RegisterController::class, 'register']);
        Route::post('/auth/login', [LoginController::class, 'login']);
        Route::post('/auth/loginSocialite', [LoginController::class, 'loginSocialite']);
        Route::get('/getuserlogin/{id}', [UserController::class, 'getuserlogin']);

    // With Socialite
        Auth::routes();
        Route::get('/auth/{provider}', [SocialiteController::class, 'redirectToProvider']);
        Route::get('/auth/{provider}/callback', [SocialiteController::class, 'handleProviderCallback']);
        Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth');

    // Room Api
    Route::prefix('rooms')->group(function () {
      Route::post('create', [RoomController::class, 'createRoom']);
      Route::post('join/{roomId}', [RoomController::class, 'joinRoom']);
    });

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
