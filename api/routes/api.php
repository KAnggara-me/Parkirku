<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIController;

Route::get("/", APIController::class . "@index");
Route::get("/", [APIController::class, "index"]);
Route::get("/uid", [APIController::class, "get"]);
Route::get("/last", [APIController::class, "getLast"]);
Route::get("/keluar", [APIController::class, "keluar"]);
Route::get("/uid/{id}", [APIController::class, "getById"]);

Route::post("/login",  [APIController::class, "login"]);
Route::post("/update", [APIController::class, "update"]);
Route::post("/logout",  [APIController::class, "logout"]);
Route::post("/update2", [APIController::class, "update2"]);

Route::delete("/delete", [APIController::class, "delete"]);
