<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/login', function() { 
    return view('auth.login');
    })->name('login');
//Route::post('/login', [LoginController::class, 'login']);

// Forgot password form and submit
Route::get('/forgot-password', function() {
    return view('auth.forgot-password'); });
//Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);

// Reset password form and submit
Route::get('/reset-password', function () {
     return view('auth.reset-password'); });
//Route::post('/reset-password', [ResetPasswordController::class, 'reset']);
