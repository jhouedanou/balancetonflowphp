<?php

use App\Http\Controllers\VoteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Vote API routes
Route::middleware('auth:sanctum')->post('/vote', [VoteController::class, 'store']);
Route::middleware('auth:sanctum')->get('/votes/stats', [VoteController::class, 'getStats']);
