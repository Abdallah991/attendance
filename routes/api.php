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
    // // students APIs
    // Route::Resource('/students', StudentsController::class);
    // // users APIs
    // Route::Resource('/users', UserController::class);
    // // Logs API
    // Route::resource('/logs', StudentLogsController::class);
    // // Events API
    // Route::resource('/events', EventController::class);
    // // cohort API
    // Route::resource('/cohorts', CohortController::class);
});


// *Public routes 
Route::group(['middleware' => ['web']], function () {
    // Login API
    Route::post('/login', [UserController::class, 'login']);

    // update password
    Route::get('/password', [UserController::class, 'updatePassword']);


    // Register API
    // TODO: make sure the API have captcha
    // TODO: make sure of the limit of the calls
    Route::post('/register', [UserController::class, 'register']);
    // Logout API
    Route::post('/logout', [UserController::class, 'logout']);

    // TODO: Remove later
    // students APIs
    Route::Resource('/students', StudentsController::class);
    // users APIs
    Route::Resource('/users', UserController::class);
    // transactions API
    // Route::resource('/logs', StudentLogsController::class);
    // cohort API
    Route::resource('/cohorts', CohortController::class);
    // attendance API
    Route::resource('/attendance', BioTimeController::class);
    // attendance API
    Route::resource('/candidate', AttendanceController::class);
    // bio time user API
    Route::resource('/candidate-info', CandidateController::class);
    // search Bio time API
    Route::post('/search', [SearchController::class, 'searchStudents']);
    // Route::post('/search', [SearchController::class, 'searchCandidates']);
});
