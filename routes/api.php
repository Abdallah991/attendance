<?php

use App\Http\Controllers\CohortController;
use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentLogsController;
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
    // Logs API
    Route::resource('/logs', StudentLogsController::class);
    // Events API
    Route::resource('/events', EventController::class);
    // cohort API
    Route::resource('/cohorts', CohortController::class);
});
