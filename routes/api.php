<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/login', [UsersController::class, 'login']);
Route::post('/register', [UsersController::class, 'register']);

Route::middleware(['auth:api'])->group(function () {
    Route::get('/profile', [UsersController::class, 'profile']); 
    Route::post('/logout', [UsersController::class, 'logout']);
});
