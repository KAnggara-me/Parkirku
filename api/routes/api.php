<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIController;

Route::get("/", APIController::class . "@index");
Route::get("/", [APIController::class, "index"]);

Route::get("/uid", [APIController::class, "get"]);
Route::get("/uid/{id}", [APIController::class, "getById"]);

Route::get("/login",  [APIController::class, "index"]);
Route::post("/login",  [APIController::class, "login"]);

Route::get("/logout",  [APIController::class, "index"]);
Route::post("/logout",  [APIController::class, "logout"]);
