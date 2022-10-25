<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
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

// students APIs
Route::Resource('/students',StudentsController::class)
// ->middleware('auth:sanctum')
;

// users APIs
Route::Resource('/users',UserController::class)
// ->middleware('auth:sanctum')
;

// Logs API
Route::resource('/logs', StudentLogsController::class)
// ->middleware('auth:sanctum')
;

 // Register API
Route::post('/register',[UserController::class, 'register'])
// ->middleware('auth:sanctum')
;

// Login API
Route::post('/login',[UserController::class, 'login']);

// Logout API
Route::post('/logout', [UserController::class, 'logout'])
// ->middleware('auth:sanctum')
;



