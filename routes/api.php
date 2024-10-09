<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\WordApiController;
use App\Http\Controllers\Api\V1\PuzzleApiController;
use App\Http\Controllers\Api\V1\ThemeApiController;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('v1')->group(function () {

        // Get all
        Route::apiResource('/words', WordApiController::class);
        Route::apiResource('/puzzles', PuzzleApiController::class);
        Route::apiResource('/themes', ThemeApiController::class);
    });
});
