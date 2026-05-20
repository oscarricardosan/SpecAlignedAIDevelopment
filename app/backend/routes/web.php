<?php

use App\Http\Controllers\InstallController;
use Illuminate\Support\Facades\Route;

Route::prefix("install")->group(function () {
    Route::get("/", [InstallController::class, "showDatabaseForm"]);
    Route::get("/database", fn () => redirect("/install"));
    Route::post("/database", [InstallController::class, "saveDatabase"]);
    Route::get("/user", [InstallController::class, "showUserForm"]);
    Route::post("/user", [InstallController::class, "saveUser"]);
    Route::get("/complete", [InstallController::class, "showComplete"]);
});

Route::get("/", function () {
    return view("welcome");
});
