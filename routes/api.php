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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// APIs
// students APIs
Route::get('/students',[StudentsController::class,'index']);

// Logs API
Route::get('/studentLogs',[StudentsController::class,'index']);

// Register API
Route::post('/register',[UserController::class, 'register']);
 
// Login API
Route::post('/login',[UserController::class, 'login']);
