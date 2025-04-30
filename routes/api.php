<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public login route
Route::post('/login', [UsersController::class, 'login']);

Route::post('/register', [UsersController::class, 'register']);
