<?php

use App\Http\Controllers\Student\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Student\Auth\RegisteredUserController;
use App\Http\Controllers\Student\ProfileController;
use App\Http\Controllers\Student\Auth\StudentDashboardController;
use Illuminate\Support\Facades\Route;

// Routes for unauthenticated students
Route::middleware('guest:student')->prefix('student')->name('student.')->group(function () {
    // Registration Routes
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('registerForm'); // Student registration form

    Route::post('register', [RegisteredUserController::class, 'store'])
        ->name('register'); // Student registration submission

    // Login Routes
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('loginForm'); // Student login form

    Route::post('login', [AuthenticatedSessionController::class, 'store'])
        ->name('login'); // Student login submission
});

// Routes for authenticated students
Route::middleware('auth:student')->prefix('student')->name('student.')->group(function () {
    // Student Dashboard Route
    Route::get('/dashboard', [StudentDashboardController::class, 'index'], function () {
        return view('student.dashboard');
    })->middleware(['verified'])->name('dashboard'); // Dashboard view

    // Profile Management Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit'); // Edit profile
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update'); // Update profile
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy'); // Delete profile

    // Logout Route
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout'); // Logout


});
