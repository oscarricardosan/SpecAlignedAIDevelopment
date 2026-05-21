<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use PDO;

class InstallController extends Controller
{
    public function showDatabaseForm()
    {
        return view("install.database");
    }

    public function saveDatabase(Request $request)
    {
        $data = $request->validate([
            "db_connection" => "required|in:pgsql",
            "db_host"       => "required|string",
            "db_port"       => "required|string",
            "db_database"   => "required|string",
            "db_username"   => "required|string",
            "db_password"   => "nullable|string",
        ]);

        $dsn = sprintf("pgsql:host=%s;port=%s;dbname=%s", $data["db_host"], $data["db_port"], $data["db_database"]);

        try {
            new PDO($dsn, $data["db_username"], $data["db_password"], [
                PDO::ATTR_TIMEOUT => 5,
            ]);
        } catch (\PDOException $e) {
            return back()->withErrors(["db_connection" => "Could not connect to PostgreSQL. Please check your credentials."])->withInput();
        }

        $this->writeEnv([
            "DB_CONNECTION" => $data["db_connection"],
            "DB_HOST"       => $data["db_host"],
            "DB_PORT"       => $data["db_port"],
            "DB_DATABASE"   => $data["db_database"],
            "DB_USERNAME"   => $data["db_username"],
            "DB_PASSWORD"   => $data["db_password"],
        ]);

        config([
            "database.default"                                          => $data["db_connection"],
            "database.connections.{$data["db_connection"]}.host"     => $data["db_host"],
            "database.connections.{$data["db_connection"]}.port"     => $data["db_port"],
            "database.connections.{$data["db_connection"]}.database" => $data["db_database"],
            "database.connections.{$data["db_connection"]}.username" => $data["db_username"],
            "database.connections.{$data["db_connection"]}.password" => $data["db_password"],
        ]);

        DB::purge($data["db_connection"]);
        DB::reconnect($data["db_connection"]);

        sleep(1);

        return redirect()->to("/install/user");
    }

    public function showUserForm()
    {
        return view("install.user");
    }

    public function saveUser(Request $request)
    {
        $data = $request->validate([
            "name"     => "required|string|max:255",
            "email"    => "required|email|max:255",
            "password" => "required|string|min:8|confirmed",
        ]);

        try {
            Artisan::call("migrate", ["--force" => true]);
        } catch (\Exception $e) {
            return back()->withErrors(["migration" => "Migration error: " . $e->getMessage()]);
        }

        User::create([
            "name"     => $data["name"],
            "email"    => $data["email"],
            "password" => $data["password"],
        ]);

        $this->writeEnv(["APP_INSTALLED" => "true"]);

        return redirect()->to("/install/complete");
    }

    public function showComplete()
    {
        return view("install.complete");
    }

    private function writeEnv(array $values): void
    {
        $path = base_path(".env");
        $content = file_get_contents($path);

        foreach ($values as $key => $value) {
            if (str_contains($content, $key . "=")) {
                $content = preg_replace(
                    "/^" . preg_quote($key, "/") . "=.*/m",
                    $key . "=" . $value,
                    $content
                );
            } else {
                $content .= "\n" . $key . "=" . $value;
            }
        }

        file_put_contents($path, $content);
    }
}
