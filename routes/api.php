<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthenticationController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('auth:sanctum')->get('/posts',[PostController::class, 'index']);
Route::get('/posts/{id}',[PostController::class, 'show']);

Route::post('/login', [AuthenticationController::class, 'login']);
Route::middleware('auth:sanctum')->get('/logout', [AuthenticationController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/me', [AuthenticationController::class, 'me']);
