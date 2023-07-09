<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\BioTimeController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\CohortController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\UserController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// APIs
// There is public routes and protetcted routes

//!
//!
//! Add Sanctum

// *protected routes 
Route::group(['middleware' => ['auth:sanctum']], function () {
    // students APIs
    Route::Resource('/students', StudentsController::class);
    // users APIs
    Route::Resource('/users', UserController::class);
    // cohort API
    Route::resource('/cohorts', CohortController::class);
    // update password
    Route::post('/password', [UserController::class, 'updatePassword']);
    // Register user
    Route::post('/register', [UserController::class, 'register']);
    // search Bio time API
    Route::post('/search', [SearchController::class, 'searchStudents']);
    // Logout API
    Route::post('/logout', [UserController::class, 'logout']);
});


// *Public routes 
Route::group(['middleware' => ['web']], function () {
    // Login API
    Route::post('/login', [UserController::class, 'login']);
    // TODO: Remove later
    // TODO: Make sure these are used
    Route::resource('/attendance-students', BioTimeController::class);
    // attendance API
    // only get a specific student work
    Route::resource('/attendance', AttendanceController::class);
    // bio time user API
    Route::resource('/candidate-info', CandidateController::class);
});
