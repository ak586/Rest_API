<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\Api\PostsController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/user/store', [UserController::class, 'store']);
Route::get('/users/get/{flag}', [UserController::class, 'index']);
Route::get('/user/{id}', [UserController::class,'show']);
Route::delete('user/delete/{id}',[UserController::class, 'destroy']);
Route::put('user/update/{id}', [UserController::class, 'update']);
Route::patch('user/change-password/{id}', [UserController::class, 'changePassword']);


//Route::post('/login',[SessionsController::class,'login'])->middleware('guest.jwt');
//Route::get('/me',[SessionsController::class , 'profile'])->middleware('auth.jwt');
//Route::get('/logout',[SessionsController::class,'logout'])->middleware('auth.jwt');
Route::post('register',[UserController::class,'register']);


Route::group([
    'middleware' => 'api',
    'namespace' => 'App\Http\Controllers',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', [AuthController::class,'login']);
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('refresh', [AuthController::class,'refresh']);
    Route::post('me', [AuthController::class,'me']);
});


Route::apiResource('posts',PostsController::class)->middleware(['auth:api']);
