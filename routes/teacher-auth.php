<?php

use App\Http\Controllers\Teacher\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Teacher\Auth\RegisteredUserController;
use App\Http\Controllers\Teacher\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:teacher')->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('registerForm'); // Updated to teacher.registerForm

    Route::post('register', [RegisteredUserController::class, 'store'])
        ->name('register'); // Updated to teacher.register

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('loginForm'); // Updated to teacher.loginForm

    Route::post('login', [AuthenticatedSessionController::class, 'store'])
        ->name('login'); // Updated to teacher.login
});

Route::middleware('auth:teacher')->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', function () {
        return view('teacher.dashboard');
    })->middleware(['verified'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    
});
