<?php

use Illuminate\Support\Facades\Route;
use App\Models\post;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\StudentLogsController;
use App\Http\Controllers\UserController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// students endpoint, 
// These have to be registered in the web for it to work.
// speciall the edit and show and update apis
Route::resource('/students', StudentsController::class);
Route::resource('/logs', StudentLogsController::class);;
Route::resource('/users', UserController::class);;

