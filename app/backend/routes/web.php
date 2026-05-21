<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::prefix("install")->group(function () {
    Route::get("/", [InstallController::class, "showDatabaseForm"]);
    Route::get("/database", fn () => redirect("/install"));
    Route::post("/database", [InstallController::class, "saveDatabase"]);
    Route::get("/storage", [InstallController::class, "showStorageForm"]);
    Route::post("/storage", [InstallController::class, "saveStorage"]);
    Route::get("/restart", [InstallController::class, "showRestart"]);
    Route::post("/restart", [InstallController::class, "saveRestart"]);

    Route::middleware("projects.mounted")->group(function () {
        Route::get("/user", [InstallController::class, "showUserForm"]);
        Route::post("/user", [InstallController::class, "saveUser"]);
        Route::get("/complete", [InstallController::class, "showComplete"]);
    });
});

Route::get("/login", [LoginController::class, "showForm"]);
Route::post("/login", [LoginController::class, "login"]);
Route::post("/logout", [LoginController::class, "logout"]);

Route::middleware(["auth", "projects.mounted"])->group(function () {
    Route::get("/dashboard", function () {
        return view("dashboard");
    });

    Route::get("/projects", [ProjectController::class, "index"]);
    Route::get("/projects/create", [ProjectController::class, "create"]);
    Route::post("/projects", [ProjectController::class, "store"]);

    Route::get("/apps", [AppController::class, "index"]);
    Route::get("/apps/create", [AppController::class, "create"]);
    Route::post("/apps", [AppController::class, "store"]);
});

Route::get("/", function () {
    if (env("APP_INSTALLED", false)) {
        return redirect("/login");
    }
    return view("welcome");
});
