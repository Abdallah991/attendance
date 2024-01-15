<?php

use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\BioTimeController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\CodeWarsController;
use App\Http\Controllers\CohortController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VacationController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\SPController;


// *protected routes 
Route::group(['middleware' => ['auth:sanctum', 'throttle:api']], function () {
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
    // search students
    Route::post('/search', [SearchController::class, 'searchStudents']);
    // search applicants
    Route::post('/search-applicants', [SearchController::class, 'searchApplicants']);
    // Logout API
    Route::post('/logout', [UserController::class, 'logout']);
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

    // enable to sync student data with multiple variables such as attendance/ platform activity/ personal information
    Route::get('/students-sync', [StudentsController::class, 'syncStudents']);
    // selection pool candidate
    Route::get('/sp-applicant', [SPController::class, 'selectionPoolApplicant']);

    Route::resource('/attendance-students', BioTimeController::class);
    // only get a specific student work
    Route::resource('/attendance', AttendanceController::class);
    // bio time user API
    Route::resource('/candidate-info', CandidateController::class);
    // birthdays
    Route::get('/birthdays', [StudentsController::class, 'birthdays']);
    //  selection pool candidates
    Route::get('/selection-pool', [SPController::class, 'selectionPoolApplicants']);
    // Comment on Applicant
    Route::post('/sp-comment', [CommentController::class, 'CommentOnApplicant']);
    // 
    Route::get('/sp-applicant-comment', [CommentController::class, 'getComments']);
    Route::post('/sync-sp', [SPController::class, 'syncSelectionPoolApplicants']);
    Route::post('/sp-decision', [SPController::class, 'updateApplicantDecision']);
    Route::get('/user-image', [ImagesController::class, 'getImage']);
    Route::post('/upload-image', [ImagesController::class, 'upload']);
    // Add warrior api
    Route::post('/warrior', [CodeWarsController::class, 'createWarrior']);
    // get all warriors api
    Route::get('/warriors', [CodeWarsController::class, 'getAllWarriors']);
    // battles APIS
    Route::post('/create-battle', [CodeWarsController::class, 'createBattle']);
    // get battle
    Route::get('/battles', [CodeWarsController::class, 'getAllBattles']);
    Route::get('/battles/{id}', [CodeWarsController::class, 'getBattle']);
    // edit battle
    Route::put('/edit-battle/{id}', [CodeWarsController::class, 'editBattle']);
    // add warriors to battle
    Route::post('/add-warriors-battle', [CodeWarsController::class, 'addWarriorsToBattle']);
    // start battle by updating code wars id
    Route::post('/start-battle', [CodeWarsController::class, 'updateOldScores']);
    // student's progress API
    Route::get('/students-progress', [StatisticsController::class, 'studentsProgress']);
});
// *Public routes 
Route::group(['middleware' => ['web', 'throttle:api']], function () {
    // Login API
    Route::post('/login', [UserController::class, 'login']);
});
