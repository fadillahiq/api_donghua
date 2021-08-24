<?php

use App\Http\Controllers\DonghuaController;
use App\Http\Controllers\EpisodeController;
use App\Http\Controllers\GenreController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('donghua', DonghuaController::class)->except('create', 'edit');
Route::apiResource('genre', GenreController::class)->except('create', 'edit');
Route::apiResource('episode', EpisodeController::class)->except('create', 'edit');
