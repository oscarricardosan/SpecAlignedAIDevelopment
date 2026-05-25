<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\FilesystemController;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::prefix("install")->name("install.")->group(function () {
    Route::get("/", [InstallController::class, "showDatabaseForm"])->name("database");
    Route::post("/database", [InstallController::class, "saveDatabase"])->name("database.save");
    Route::get("/storage", [InstallController::class, "showStorageForm"])->name("storage");
    Route::post("/storage", [InstallController::class, "saveStorage"])->name("storage.save");
    Route::get("/restart", [InstallController::class, "showRestart"])->name("restart");
    Route::post("/restart", [InstallController::class, "saveRestart"])->name("restart.save");

    Route::middleware("projects.mounted")->group(function () {
        Route::get("/user", [InstallController::class, "showUserForm"])->name("user");
        Route::post("/user", [InstallController::class, "saveUser"])->name("user.save");
        Route::get("/complete", [InstallController::class, "showComplete"])->name("complete");
    });
});

Route::get("/login", [LoginController::class, "showForm"])->name("login");
Route::post("/login", [LoginController::class, "login"])->name("login.post");
Route::post("/logout", [LoginController::class, "logout"])->name("logout");

Route::middleware(["auth", "projects.mounted"])->group(function () {
    Route::get("/dashboard", function () {
        $recentProjects = \App\Models\Project::with('compliances')
            ->orderBy('updated_at', 'desc')
            ->take(3)
            ->get();
        return view("dashboard", compact('recentProjects'));
    })->name("dashboard");

    Route::get("/projects", [ProjectController::class, "index"])->name("projects.index");
    Route::get("/projects/create", [ProjectController::class, "create"])->name("projects.create");
    Route::post("/projects", [ProjectController::class, "store"])->name("projects.store");
    Route::get("/projects/{project}/edit", [ProjectController::class, "edit"])->name("projects.edit");
    Route::put("/projects/{project}", [ProjectController::class, "update"])->name("projects.update");

    Route::get("/apps", [AppController::class, "index"])->name("apps.index");
    Route::get("/apps/create", [AppController::class, "create"])->name("apps.create");
    Route::post("/apps", [AppController::class, "store"])->name("apps.store");

    Route::prefix("api/filesystem")->name("api.filesystem.")->group(function () {
        Route::get("/browse", [FilesystemController::class, "browse"])->name("browse");
        Route::post("/directory", [FilesystemController::class, "createDirectory"])->name("directory.create");
    });
});

Route::get("/", function () {
    if (env("APP_INSTALLED", false)) {
        return redirect()->route("login");
    }
    return view("welcome");
})->name("welcome");
