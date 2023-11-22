<?php

use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\BioTimeController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\CohortController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VacationController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\PlatformController;



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



// *protected routes 
Route::group(['middleware' => ['auth:sanctum']], function () {
    // Route::group(['middleware' => ['web']], function () {

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
    // student's progress API
    Route::get('/students-progress', [StatisticsController::class, 'studentsProgress']);
    // Vacation API
    Route::Resource('/vacations', VacationController::class);
    // Roles API
    Route::Resource('/roles', RoleController::class);
    // All Applicants API
    Route::post('/applicants', [ApplicantController::class, 'applicants']);
    // applicants in date formate
    Route::post('/applicants-sync', [ApplicantController::class, 'syncApplicants']);
    // update applicants
    Route::post('/applicants-update', [ApplicantController::class, 'updateApplicantsStatus']);
    // number of people in registrations
    Route::post('/applicants-check-in', [ApplicantController::class, 'checkInCount']);
    // number of people in selection pool
    Route::post('/applicants-sp', [ApplicantController::class, 'selectionPool']);
    //  selection pool candidates
    Route::get('/selection-pool', [ApplicantController::class, 'selectionPoolApplicants']);
    // enable to sync student data with multiple variables such as attendance/ platform activity/ personal information
    Route::get('/students-sync', [StudentsController::class, 'syncStudents']);
    // selection pool candidate
    Route::get('/sp-applicant', [ApplicantController::class, 'selectionPoolApplicant']);
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
    // birthdays
    Route::get('/birthdays', [StudentsController::class, 'birthdays']);
});
