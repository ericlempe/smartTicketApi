<?php

use App\Http\Controllers\Auth\{LoginController, RegisterController};
use Illuminate\Support\Facades\Route;

Route::post('/login', LoginController::class)->name('login');
Route::post('/register', RegisterController::class)->name('register');
