<?php

use App\Http\Controllers\AiAgentController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\FilesystemController;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\StudioController;
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
    Route::get("/projects/{project}", [ProjectController::class, "show"])->name("projects.show");
    Route::get("/projects/{project}/edit", [ProjectController::class, "edit"])->name("projects.edit");
    Route::put("/projects/{project}", [ProjectController::class, "update"])->name("projects.update");

    Route::get("/projects/{project}/apps", [AppController::class, "index"])->name("apps.index");
    Route::get("/projects/{project}/apps/create", [AppController::class, "create"])->name("apps.create");
    Route::post("/projects/{project}/apps", [AppController::class, "store"])->name("apps.store");
    Route::get("/projects/{project}/apps/{application}", [AppController::class, "show"])->name("apps.show");
    Route::get("/projects/{project}/apps/{application}/edit", [AppController::class, "edit"])->name("apps.edit");
    Route::put("/projects/{project}/apps/{application}", [AppController::class, "update"])->name("apps.update");

    Route::prefix("api/filesystem")->name("api.filesystem.")->group(function () {
        Route::get("/browse", [FilesystemController::class, "browse"])->name("browse");
        Route::post("/directory", [FilesystemController::class, "createDirectory"])->name("directory.create");
    });

    // Providers
    Route::get("/providers", [ProviderController::class, "index"])->name("providers.index");
    Route::post("/providers", [ProviderController::class, "store"])->name("providers.store");
    Route::delete("/providers/{provider}", [ProviderController::class, "destroy"])->name("providers.destroy");

    // AI Agents
    Route::get("/ai-agents", [AiAgentController::class, "index"])->name("ai-agents.index");
    Route::patch("/ai-agents/{agent}", [AiAgentController::class, "update"])->name("ai-agents.update");

    // Studio
    Route::get("/studio", [StudioController::class, "index"])->name("studio.index");
});

Route::get("/", function () {
    if (env("APP_INSTALLED", false)) {
        return redirect()->route("login");
    }
    return view("welcome");
})->name("welcome");
