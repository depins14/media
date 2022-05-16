<?php

use App\Http\Controllers\Api\MateriController;
use App\Http\Controllers\Api\SiswaController;
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

Route::middleware('auth:sanctum')->group(function() {
    Route::get('user', [SiswaController::class, 'fetch']);
    Route::get('logout', [SiswaController::class, 'logout']);

    Route::get('materi', [MateriController::class, 'all']);

});

Route::post('login', [SiswaController::class, 'login']);
