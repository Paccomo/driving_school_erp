<?php

use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Auth::routes();

Route::post("/newUserPdf", [RegisterController::class, "UserPdf"])->name('user.credentials.download');
