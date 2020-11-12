<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\TopicController;
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

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('/topics', TopicController::class);
    Route::apiResource('/tags', TagController::class);
});

Route::get('login/{provider}', [LoginController::class, 'redirectToProvider']);
Route::get('login/{provider}/callback', [LoginController::class, 'handleProviderCallback']);

Route::fallback(function () {
    return response()->json([
        'message' => 'Page Not Found. If error persists, leave me?!'
    ], 404);
});
