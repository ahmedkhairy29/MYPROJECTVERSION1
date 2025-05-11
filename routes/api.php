<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ActivateController;
use Illuminate\Http\Request;

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

Route::post('/activate-user', [ActivateController::class, 'activateByEmail']);


Route::middleware(['auth:api'])->group(function () {
    return response()->json(auth()->user());
});
    
    Route::get('/profile', [UsersController::class, 'profile']);
    Route::post('/logout', [UsersController::class, 'logout']);

    Route::post('/departments', [DepartmentController::class, 'addDepartment']);
    Route::put('/departments/{id}', [DepartmentController::class, 'updateDepartment']);
    Route::delete('/departments/{id}', [DepartmentController::class, 'deleteDepartment']);
    Route::get('/departments', [DepartmentController::class, 'getDepartments']);
    Route::get('/user', function (Request $request) {
       
});



