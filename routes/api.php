<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controllers

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\DatabaserollbackController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('register',[AuthController::class, 'register']);
Route::post('login',[AuthController::class, 'login']);
Route::get('logout',[AuthController::class, 'logout'])->middleware('auth:api');

// Single Middleware for routes
// --------------------------------------------------------------------------
Route::post('post',[PostsController::class, 'store'])->middleware('auth:api');
Route::get('post',[PostsController::class, 'index'])->middleware('auth:api');
Route::delete('post/{id}',[PostsController::class, 'destroy'])->middleware('auth:api');
Route::get('post/{like}',[PostsController::class, 'show'])->middleware('auth:api');
Route::put('post/{id}',[PostsController::class, 'update'])->middleware('auth:api');

Route::post('product/store',[DatabaserollbackController::class, 'store'])->middleware('auth:api');
// Middleware group for routing
// -------------------------------------------------------------------------
// Route::group(['middleware' => 'auth:api'], function () {
//     Route::post('post',[PostsController::class, 'store']);
//     Route::get('post',[PostsController::class, 'index']);
//     Route::delete('post/{id}',[PostsController::class, 'destroy']);
//     Route::get('post/{like}',[PostsController::class, 'show']);
//     Route::put('post/{id}',[PostsController::class, 'update']);
// });
