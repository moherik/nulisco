<?php

use App\Http\Controllers\Api\ClapController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\TagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Auth endpoint
Route::get('login/{provider}', [AuthController::class, 'redirectToProvider']);
Route::get('login/{provider}/callback', [AuthController::class, 'handleProviderCallback']);
Route::get('logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Post endpoint
Route::get('posts/status/{status}', [PostController::class, 'status']);
Route::get('posts/{slug}/edit', [PostController::class, 'showEdit']);
Route::get('posts/{id}/bookmark', [PostController::class, 'bookmark']);
Route::apiResource('/posts', PostController::class);

// Tag endpoint
Route::apiResource('/tags', TagController::class);

// Clap endpoint
Route::patch('/claps', [ClapController::class, 'claps']);
Route::get('/claps/post/{id}', [ClapController::class, 'postClaps']);

// 404 not found
Route::fallback(function () {
    return response()->json([
        'message' => 'Page Not Found. If error persists, leave me?!'
    ], 404);
});
