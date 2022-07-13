<?php

use Illuminate\Support\Facades\Route;

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

use App\Http\Controllers\HomeController;

Route::get('/about', function () {
	return view('welcome');
});

Route::get("/", [HomeController::class, "index"]);

Route::get("/review", [HomeController::class, "review"]);
Route::get("/delete/{uid}", [HomeController::class, "delete"]);
Route::get("/detail/{uid}", [HomeController::class, "detail"]);

Route::get("/report", [HomeController::class, "report"]);
