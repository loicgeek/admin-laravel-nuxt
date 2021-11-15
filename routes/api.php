<?php

use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ItemController;
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

Route::get('/', [Controller::class, 'routes'])
    ->name('route information')
    ->withoutMiddleware('api');
Route::get('/example', [Controller::class, 'example'])->name('example route');
Route::get('/error', [Controller::class, 'error'])->name('error route');

// Authentication
Route::get('/login', [Controller::class, 'auth'])->name('login')->name('proper login redirect');
Route::get('redirect/{provider}', [AuthController::class, 'redirect'])->name('redirect to provider');
Route::get('callback/{provider}', [AuthController::class, 'callback'])->name('provider callback');
Route::get('onetap/{credential}', [AuthController::class, 'onetap'])->name('onetap support');
Route::post('attempt', [AuthController::class, 'attempt'])->name('email login attempt');
Route::post('login', [AuthController::class, 'login'])->name('attempt to start session');
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('remove session');
Route::get('me', [AuthController::class, 'me'])->middleware('auth:api')->name('get logged in user');


Route::group(["prefix" => 'api'], function () {
    Route::apiResource("cities", CityController::class);
    Route::apiResource("categories", CategoryController::class);

    Route::get("items/{item}/comments", [ItemController::class, 'getComments']);
    Route::post("items/{item}/comments", [ItemController::class, 'addComment']);
    Route::put("items/{item}/comments/{commentId}", [ItemController::class, 'updateComment']);
    Route::delete("items/{item}/comments/{commentId}", [ItemController::class, 'deleteComment']);
    Route::apiResource("items", ItemController::class);
});


Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', [ApiAuthController::class, 'login']);
    Route::post('logout', [ApiAuthController::class, 'logout']);
    Route::post('refresh', [ApiAuthController::class, 'refresh']);
    Route::get('user', [ApiAuthController::class, 'me']);
});
