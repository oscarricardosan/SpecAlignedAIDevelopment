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

        return redirect()->to("/install/storage");
    }

    public function showStorageForm()
    {
        return view("install.storage");
    }

    public function saveStorage(Request $request)
    {
        $data = $request->validate([
            "s3_endpoint"  => "required|string",
            "s3_key"       => "required|string",
            "s3_secret"    => "required|string",
            "s3_bucket"    => "required|string",
            "s3_region"    => "nullable|string",
            "said_root"    => "required|string",
        ]);

        // Write to Laravel .env
        $this->writeEnv([
            "S3_ENDPOINT"  => $data["s3_endpoint"],
            "S3_KEY"       => $data["s3_key"],
            "S3_SECRET"    => $data["s3_secret"],
            "S3_BUCKET"    => $data["s3_bucket"],
            "S3_REGION"    => $data["s3_region"] ?? "us-east-1",
        ]);

        // Remove SAID_ROOT from Laravel .env — it's injected by Docker at runtime
        $this->removeEnvKey("SAID_ROOT");

        // Also write SAID_ROOT to /said-setup/.env so docker-compose can read it on restart
        $this->writeDockerComposeEnv([
            "SAID_ROOT" => $data["said_root"],
        ]);

        session(["said_root_expected" => $data["said_root"]]);

        return redirect()->to("/install/restart");
    }

    public function showRestart()
    {
        if (!session("said_root_expected")) {
            return redirect("/install/storage");
        }

        $saidRoot   = env("SAID_ROOT", "");
        $isPlaceholder = $saidRoot === "/choose-your-projects-path" || $saidRoot === "";
        $mounted    = !$isPlaceholder && is_dir("/said-projects") && is_readable("/said-projects");

        return view("install.restart", [
            "said_root" => session("said_root_expected"),
            "mounted"   => $mounted,
        ]);
    }

    public function saveRestart()
    {
        $saidRoot      = env("SAID_ROOT", "");
        $isPlaceholder = $saidRoot === "/choose-your-projects-path" || $saidRoot === "";

        if ($isPlaceholder || !is_dir("/said-projects") || !is_readable("/said-projects")) {
            return back()->withErrors(["restart" => "The projects folder is still not mounted. Please restart Docker containers first."]);
        }

        session()->forget("said_root_expected");

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

        $this->writeEnv(["APP_INSTALLED" => "true", "SESSION_DRIVER" => "database"]);

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

    private function removeEnvKey(string $key): void
    {
        $path = base_path(".env");
        $content = file_get_contents($path);
        $content = preg_replace("/^" . preg_quote($key, "/") . "=.*\n?/m", "", $content);
        file_put_contents($path, $content);
    }

    private function writeDockerComposeEnv(array $values): void
    {
        $dir  = "/var/docker";
        $path = "{$dir}/.env";

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        // Docker may have created .env as a directory if the host file didn't exist
        if (is_dir($path)) {
            rmdir($path);
        }

        if (!file_exists($path)) {
            file_put_contents($path, "");
        }

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
