<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\Api\LoginController as ApiLoginController;
use App\Http\Controllers\ActivateController;
use App\Http\Controllers\Web\PasswordResetController;
use Illuminate\Http\Request;

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/api-login', [ApiLoginController::class, 'login']);
Route::post('/activate-user', [ActivateController::class, 'activateByEmail']);
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail']);
Route::post('/reset-password', [PasswordResetController::class, 'reset']);
 



Route::middleware(['auth:api'])->group(function () {
    Route::get('/profile', [UsersController::class, 'profile']);
    Route::post('/logout', [UsersController::class, 'logout']);
    Route::resource('/departments', DepartmentController::class)->except(['show', 'create', 'edit']);
    Route::resource('/posts', PostController::class)->only(['index', 'store']);
    Route::get('/user', fn (Request $request) => response()->json(auth()->user()));
});



