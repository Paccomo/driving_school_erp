<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Branch\BranchController;
use App\Http\Controllers\Course\PricingController;
use App\Http\Controllers\Employee\InstructorController;
use App\Http\Controllers\References\LinksController;
use App\Http\Controllers\References\VideosController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Auth::routes();
Route::post("/newUserPdf", [RegisterController::class, "UserPdf"])->name('user.credentials.download');

// Branch
Route::get("/branch", [BranchController::class, "list"])->name("branch.list");
Route::get("/branch/new", [BranchController::class, "add"])->name("branch.add");
Route::post("/branch/new", [BranchController::class, "save"])->name("branch.save");
Route::put("/branch/new", [BranchController::class, "save"])->name("branch.save");
Route::get("/branch/{id}", [BranchController::class, "index"])->name("branch.index");
Route::delete("/branch/{id}", [BranchController::class, "destroy"])->name("branch.destroy");
Route::get("/branch/{id}/edit", [BranchController::class, "edit"])->name("branch.edit");

// Pricing
Route::get("/pricing", [PricingController::class, "list"])->name("pricing.list");
Route::get("/pricing/{courseid}/{branchid}/edit", [PricingController::class, "edit"])->name("pricing.edit");
Route::put("/pricing/new", [PricingController::class, "save"])->name("pricing.save");

//Instructor
Route::get("/instructor", [InstructorController::class, "list"])->name("instructor.list");

//Useful links
Route::get("/information", [LinksController::class, "list"])->name("link.list");
Route::get("/information/new", [LinksController::class, "add"])->name("link.add");
Route::post("/information/new", [LinksController::class, "save"])->name("link.save");
Route::put("/information/new", [LinksController::class, "save"])->name("link.save");
Route::delete("/information/{id}", [LinksController::class, "destroy"])->name("link.destroy");
Route::get("/information/{id}/edit", [LinksController::class, "edit"])->name("link.edit");

// Videos
Route::get("/video", [VideosController::class, "list"])->name("video.list");
Route::get("/video/new", [VideosController::class, "add"])->name("video.add");
Route::post("/video/new", [VideosController::class, "save"])->name("video.save");
Route::put("/video/new", [VideosController::class, "save"])->name("video.save");
Route::delete("/video/{id}", [VideosController::class, "destroy"])->name("video.destroy");
Route::get("/video/{id}/edit", [VideosController::class, "edit"])->name("video.edit");