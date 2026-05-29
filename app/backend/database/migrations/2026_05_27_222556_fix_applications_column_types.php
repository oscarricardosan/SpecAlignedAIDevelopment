<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Convert text → json for array columns
        DB::statement('ALTER TABLE applications ALTER COLUMN design_principles_codes TYPE json USING design_principles_codes::json');
        DB::statement('ALTER TABLE applications ALTER COLUMN databases_codes TYPE json USING databases_codes::json');
        DB::statement('ALTER TABLE applications ALTER COLUMN storage_codes TYPE json USING storage_codes::json');

        // Convert testing_frameworks_codes from text (json array) to single string
        DB::statement('ALTER TABLE applications ALTER COLUMN testing_frameworks_codes TYPE varchar(255) USING NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE applications ALTER COLUMN design_principles_codes TYPE text USING design_principles_codes::text');
        DB::statement('ALTER TABLE applications ALTER COLUMN databases_codes TYPE text USING databases_codes::text');
        DB::statement('ALTER TABLE applications ALTER COLUMN storage_codes TYPE text USING storage_codes::text');

        DB::statement('ALTER TABLE applications ALTER COLUMN testing_frameworks_codes TYPE text USING testing_frameworks_codes::text');
    }
};