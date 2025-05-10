<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ApiActivateController;
use Illuminate\Http\Request;

Route::post('/login', [UsersController::class, 'login']);
Route::post('/register', [UsersController::class, 'register']);

Route::middleware(['auth:api'])->group(function () {
    Route::get('/profile', [UsersController::class, 'profile']);
    Route::post('/logout', [UsersController::class, 'logout']);

    Route::post('/departments', [DepartmentController::class, 'addDepartment']);
    Route::put('/departments/{id}', [DepartmentController::class, 'updateDepartment']);
    Route::delete('/departments/{id}', [DepartmentController::class, 'deleteDepartment']);
    Route::get('/departments', [DepartmentController::class, 'getDepartments']);
    Route::get('/user', function (Request $request) {
        return response()->json(auth()->user());
    });
});

// Activation routes (choose only one controller)
Route::get('/activate/{token}', [ApiActivateController::class, 'activate']);
Route::post('/activate-user', [ApiActivateController::class, 'manualActivate']);
Route::post('/activate-token', [ApiActivateController::class, 'activateWithToken']);

