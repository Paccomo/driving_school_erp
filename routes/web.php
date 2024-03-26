<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\testbed;
use App\Http\Controllers\auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post("/newUserPdf", [RegisterController::class, "UserPdf"])->name('user.credentials.download');
