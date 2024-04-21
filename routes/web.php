<?php

use App\Http\Controllers\Auth\PwController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Branch\BranchController;
use App\Http\Controllers\Contract\ContractsController;
use App\Http\Controllers\Course\CourseController;
use App\Http\Controllers\Course\PricingController;
use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\Employee\InstructorController;
use App\Http\Controllers\References\LinksController;
use App\Http\Controllers\References\VideosController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Auth::routes();
Route::post("/newUserPdf", [RegisterController::class, "UserPdf"])->name('user.credentials.download');
Route::get('/changePassword/{id?}', [PwController::class, "showForm"])->name('pw.form');
Route::post('/changePassword/{id?}', [PwController::class, "save"])->name('pw.save');

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
Route::get("/video/link", [VideosController::class, "add"])->name("video.addLink");
Route::post("/video/new", [VideosController::class, "save"])->name("video.save");
Route::put("/video/new", [VideosController::class, "save"])->name("video.save");
Route::delete("/video/{id}", [VideosController::class, "destroy"])->name("video.destroy");
Route::get("/video/{id}/edit", [VideosController::class, "edit"])->name("video.edit");

// Course
Route::get("/course", [CourseController::class, "list"])->name("course.list");
Route::get("/course/new", [CourseController::class, "add"])->name("course.add");
Route::post("/course/new", [CourseController::class, "save"])->name("course.save");
Route::put("/course/new", [CourseController::class, "save"])->name("course.save");
Route::get('/course/register/{course}/{branch?}', [CourseController::class, 'register'])->name("course.register");
Route::get("/course/{id}", [CourseController::class, "index"])->name("course.index");
Route::delete("/course/{id}", [CourseController::class, "destroy"])->name("course.destroy");
Route::get("/course/{id}/edit", [CourseController::class, "edit"])->name("course.edit");

// Course descriptions
Route::get("/description/{id}/list", [CourseController::class, "descList"])->name("description.list");
Route::get("/description/new", [CourseController::class, "descAdd"])->name("description.add");
Route::post("/description/new", [CourseController::class, "descSave"])->name("description.save");
Route::put("/description/new", [CourseController::class, "descSave"])->name("description.save");
Route::get("/description/{id}", [CourseController::class, "descIndex"])->name("description.index");
Route::delete("/description/{id}", [CourseController::class, "descDestroy"])->name("description.destroy");
Route::get("/description/{id}/edit", [CourseController::class, "descEdit"])->name("description.edit");

//Contract
Route::post('/contract/new', [ContractsController::class, "guestRequest"])->name('contract.joinCourse');

// Employee
Route::get("/employee", [EmployeeController::class, "list"])->name("employee.list");
Route::put("/employee/save", [EmployeeController::class, "save"])->name("employee.save");
Route::get("/employee/{id}", [EmployeeController::class, "index"])->name("employee.index");
Route::delete("/employee/{id}", [EmployeeController::class, "destroy"])->name("employee.destroy");
Route::get("/employee/{id}/edit", [EmployeeController::class, "edit"])->name("employee.edit");