<?php

use App\Http\Controllers\testbed;
use App\Http\Controllers\auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::post("/login", [LoginController::class, 'authenticate']);
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
