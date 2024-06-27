<?php

use App\Controllers\AuthenticationsController;
use App\Controllers\UsersController;
use App\Controllers\ProfileController;
use Core\Router\Route;

// Authentication
Route::get('/login', [AuthenticationsController::class, 'new'])->name('users.login');
Route::post('/login', [AuthenticationsController::class, 'authenticate'])->name('users.authenticate');

Route::middleware('auth')->group(function () {
  // Logout
  Route::get('/logout', [AuthenticationsController::class, 'destroy'])->name('users.logout');

  // Profile
  Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
  Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');

  // Users
  Route::get('/users', [UsersController::class, 'index'])->name('users.index');
  Route::get('/users/{id}', [UsersController::class, 'show'])->name('users.show');
  Route::post('/users', [UsersController::class, 'create'])->name('users.create');
  Route::put('/users/{id}', [UsersController::class, 'update'])->name('users.update');
  Route::delete('/users/{id}', [UsersController::class, 'destroy'])->name('users.destroy');
});
