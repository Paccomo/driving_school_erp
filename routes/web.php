<?php

use App\Http\Controllers\Auth\PwController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Branch\BranchController;
use App\Http\Controllers\Clients\ClientsController;
use App\Http\Controllers\Contract\ContractsController;
use App\Http\Controllers\Course\CourseController;
use App\Http\Controllers\Course\PricingController;
use App\Http\Controllers\Documents\DocumentController;
use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\Employee\InstructorController;
use App\Http\Controllers\Lessons\LessonController;
use App\Http\Controllers\References\LinksController;
use App\Http\Controllers\References\SlidesController;
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
Route::get('/contracts/termination', [ContractsController::class, "clientRequest"])->name('contract.termination');
Route::get('/contracts/extension', [ContractsController::class, "clientRequest"])->name('contract.extension');
Route::post('/contracts/save', [ContractsController::class, "clientReqSave"])->name('contract.client.save');
Route::get('/contract', [ContractsController::class, "list"])->name('contract.list');
Route::get('/contract/download/{id}', [ContractsController::class, "download"])->name('contract.download');
Route::get('/contract/accepted', [ContractsController::class, "list"])->name('contract.accepted');
Route::get('/contract/denied', [ContractsController::class, "list"])->name('contract.denied');
Route::get('/contract/all', [ContractsController::class, "list"])->name('contract.all');
Route::get('/add/contract', [ContractsController::class, "addWithoutRequest"])->name('contract.requestless');
Route::post('/add/contract', [ContractsController::class, "saveWithoutRequest"])->name('contract.saveRequestless');
Route::get('/contract/{id}', [ContractsController::class, "index"])->name('contract.index');
Route::get('/contract/{id}/approve', [ContractsController::class, "approve"])->name('contract.approve');
Route::get('/contract/{id}/deny', [ContractsController::class, "deny"])->name('contract.deny');
Route::get('/contract/{id}/addContract', [ContractsController::class, "add"])->name('contract.add');
Route::post('/contract/save', [ContractsController::class, "save"])->name('contract.save');
Route::get('/contracts', [ContractsController::class, "clientContracts"])->name('contract.client');

// Employee
Route::get("/employee", [EmployeeController::class, "list"])->name("employee.list");
Route::put("/employee/save", [EmployeeController::class, "save"])->name("employee.save");
Route::put("/employee/timetable", [EmployeeController::class, "timetableSave"])->name("employee.timetable.save");
Route::get("/employee/{id}", [EmployeeController::class, "index"])->name("employee.index");
Route::delete("/employee/{id}", [EmployeeController::class, "destroy"])->name("employee.destroy");
Route::get("/employee/{id}/edit", [EmployeeController::class, "edit"])->name("employee.edit");
Route::get("/employee/{id}/timetable", [EmployeeController::class, "timetableForm"])->name("employee.timetable.form");

// Slides
Route::get("/slide", [SlidesController::class, "list"])->name("slides.list");
Route::get("/slide/new", [SlidesController::class, "add"])->name("slides.add");
Route::post("/slide/new", [SlidesController::class, "save"])->name("video.save");
Route::put("/slide/new", [SlidesController::class, "save"])->name("slides.save");
Route::delete("/slide/{id}", [SlidesController::class, "destroy"])->name("slides.destroy");
Route::get("/slide/{id}", [SlidesController::class, "index"])->name("slides.index");
Route::get("/slide/{id}/edit", [SlidesController::class, "edit"])->name("slides.edit");

// Clients
Route::get("/client/all", [ClientsController::class, "list"])->name("client.all");
Route::get("/client/find", [ClientsController::class, "search"])->name("client.find");
Route::get("/client/endStudies/{id}", [ClientsController::class, "endStudy"])->name("client.end");
Route::get("/client/practice/{id}", [ClientsController::class, "togglePracticalLessons"])->name("client.practice");
Route::get("/client/grade/{id}", [ClientsController::class, "grade"])->name("client.insert.grade");
Route::post("/client/grade", [ClientsController::class, "saveGrade"])->name("client.grade");
Route::get("/client/payment/{id}", [ClientsController::class, "payment"])->name("client.insert.payment");
Route::post("/client/payment", [ClientsController::class, "savePayment"])->name("client.payment");
Route::get("/client", [ClientsController::class, "list"])->name("client.list");
Route::put("/client/save", [ClientsController::class, "save"])->name("client.save");
Route::get("/client/view/{id}", [ClientsController::class, "index"])->name("client.index");
Route::get("/client/{id}/edit", [ClientsController::class, "edit"])->name("client.edit");
Route::post('/client/downloadReciept', [ClientsController::class, "receipt"])->name("client.receipt");
Route::get('/client/driveDocument/{id}', [ClientsController::class, "driveDocForm"])->name("client.driveForm");
Route::post('/client/driveDocument', [ClientsController::class, "driveDoc"])->name("client.drive");
Route::post('/client/instructor', [ClientsController::class, "instructor"])->name("client.instructor");

// Documents
Route::get('/documents', [DocumentController::class, 'document'])->name('documents');
Route::get('/documents/addMed', [DocumentController::class, 'add'])->name('documents.addMed');
Route::get('/documents/addTheory', [DocumentController::class, 'add'])->name('documents.addTheory');
Route::get('/documents/download/{id}', [DocumentController::class, "download"])->name('documents.download');
Route::get('/documents/destroy/{id}', [DocumentController::class, "destroy"])->name('documents.destroy');
Route::post('/documents/save', [DocumentController::class, 'save'])->name('documents.save');

// Lessons
Route::get('/lessons', [LessonController::class, 'clientLessons'])->name('lesson');
Route::get('/lessons/upcoming', [LessonController::class, 'instLessons'])->name('lesson.upcoming');
Route::get('/lessons/grades', [LessonController::class, 'gradeForm'])->name('lesson.grades');
Route::post('/lessons/grades', [LessonController::class, 'grade'])->name('lesson.grades.save');
Route::get('/lessons/reservation', [LessonController::class, 'reservation'])->name('lesson.reservation');
Route::post('/lessons/reservation', [LessonController::class, 'reservationSave'])->name('lesson.reservation.save');
Route::post('/lessons/instructor', [LessonController::class, 'assignInstructor'])->name('lesson.instructor');
Route::get('/lessons/cancel/{id}', [LessonController::class, 'cancel'])->name('lesson.cancel');