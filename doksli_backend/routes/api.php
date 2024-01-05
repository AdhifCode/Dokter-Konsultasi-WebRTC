<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\Auth\SocialiteController;
use App\Http\Controllers\api\Auth\RegisterController;
use App\Http\Controllers\api\Auth\LoginController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\RoomController;
use App\Http\Controllers\api\DoctorController;
use App\Http\Controllers\api\FavoriteController;
use App\Http\Controllers\api\OrderController;
use App\Http\Controllers\api\ScheduleController;

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

    // With Socialite
        Auth::routes();
        Route::get('/auth/{provider}', [SocialiteController::class, 'redirectToProvider']);
        Route::get('/auth/{provider}/callback', [SocialiteController::class, 'handleProviderCallback']);
        // Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth');

    // Room Api
    Route::prefix('rooms')->group(function () {
      Route::post('create', [RoomController::class, 'createRoom']);
      Route::post('join/{roomId}', [RoomController::class, 'joinRoom']);
    });

    // User CRUD & Admin
    Route::post('/postuser', [UserController::class, 'store']);
    Route::post('/updateuser/{id}', [UserController::class, 'update']);
    Route::get('/users/{id}', [UserController::class, 'getUserById'])->name('users.get');
    Route::get('/getusers', [UserController::class, 'getAllUsers']);
    Route::get('/getuserchart', [UserController::class, 'getChartData']);

    // Doctor & Room
    Route::post('/join-doctor', [DoctorController::class, 'registerDoctor']);
    Route::get('/getrooms', [RoomController::class, 'getAllRooms']);
    Route::get('/getroombyid/{room}', [RoomController::class, 'getRoomById']);
    Route::post('/favorite/{room}', [FavoriteController::class, 'toggleFavorite']);
    Route::get('/favorite/{room}/status', [FavoriteController::class, 'getFavoriteStatus']);
    Route::get('/userfavorite', [FavoriteController::class, 'getUserFavorites']);
    Route::get('/top3', [RoomController::class, 'getTop3RoomsByFavorites']);

    // Order
    Route::post('/ordercreate', [OrderController::class, 'createOrder']);
    Route::get('/bookedroom/{room}', [OrderController::class, 'getBookedTimeSlots']);
    Route::get('/orders/{user_id}', [OrderController::class, 'getUserOrders']);

    
    // Schedule
    Route::get('/doctorschedule/{doctor}', [ScheduleController::class, 'viewOrdersByDoctor']);
    

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
