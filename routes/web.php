<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Branch\BranchController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Auth::routes();
Route::post("/newUserPdf", [RegisterController::class, "UserPdf"])->name('user.credentials.download');

// Branch
Route::get("/branch", [BranchController::class, "list"])->name("branch.list");
Route::get("/branch/{id}", [BranchController::class, "index"])->name("branch.index");