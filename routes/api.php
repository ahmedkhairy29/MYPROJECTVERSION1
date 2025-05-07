<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/login', [UsersController::class, 'login']);
Route::post('/register', [UsersController::class, 'register']);

Route::middleware(['jwt.verify'])->group(function () {
    Route::get('/profile', [UsersController::class, 'profile']); 
    Route::post('/logout', [UsersController::class, 'logout']);


    Route::post('/departments', [UsersController::class, 'addDepartment']);
    Route::put('/departments/{id}', [UsersController::class, 'updateDepartment']);
    Route::delete('/departments/{id}', [UsersController::class, 'deleteDepartment']);
    Route::get('/departments', [UsersController::class, 'getDepartments']);
});
Route::get('/activate/{token}', [UsersController::class, 'activate']);
