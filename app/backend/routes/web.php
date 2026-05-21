<?php

use App\Http\Controllers\InstallController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::prefix("install")->group(function () {
    Route::get("/", [InstallController::class, "showDatabaseForm"]);
    Route::get("/database", fn () => redirect("/install"));
    Route::post("/database", [InstallController::class, "saveDatabase"]);
    Route::get("/user", [InstallController::class, "showUserForm"]);
    Route::post("/user", [InstallController::class, "saveUser"]);
    Route::get("/complete", [InstallController::class, "showComplete"]);
});

Route::get("/login", [LoginController::class, "showForm"]);
Route::post("/login", [LoginController::class, "login"]);
Route::post("/logout", [LoginController::class, "logout"]);

Route::middleware("auth")->group(function () {
    Route::get("/dashboard", function () {
        return view("dashboard");
    });
});

Route::get("/", function () {
    if (env("APP_INSTALLED", false)) {
        return redirect("/login");
    }
    return view("welcome");
});
