<?php

use App\Http\Controllers\PlaneController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::prefix('api')->group(function () {
    Route::get('/planes', [PlaneController::class, 'index']);
    Route::post('/planes', [PlaneController::class, 'create']);
    Route::get('/planes/{id}', [PlaneController::class, 'show']);
    Route::match(['put', 'patch'], '/planes/{id}', [PlaneController::class, 'update']);
    Route::delete('/planes/{id}', [PlaneController::class, 'delete']);
});
