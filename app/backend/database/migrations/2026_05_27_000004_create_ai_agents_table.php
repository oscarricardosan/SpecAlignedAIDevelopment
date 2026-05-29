<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_agents', function (Blueprint $table) {
            $table->id();
            $table->string('type')->unique();
            $table->string('name')->nullable();
            $table->foreignId('provider_id_low')->nullable()->constrained('ai_providers')->nullOnDelete();
            $table->string('model_low')->nullable();
            $table->foreignId('provider_id_medium')->nullable()->constrained('ai_providers')->nullOnDelete();
            $table->string('model_medium')->nullable();
            $table->foreignId('provider_id_high')->nullable()->constrained('ai_providers')->nullOnDelete();
            $table->string('model_high')->nullable();
            $table->foreignId('user_who_created_id')->nullable()->constrained('users');
            $table->foreignId('user_who_updated_id')->nullable()->constrained('users');
            $table->foreignId('user_who_deleted_id')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });

        $types = [
            ['type' => 'assistant',    'name' => 'System Assistant'],
            ['type' => 'programmer',   'name' => 'Programmer'],
            ['type' => 'quality_code', 'name' => 'Quality Auditor — Code'],
            ['type' => 'quality_ui',   'name' => 'Quality Auditor — UI/UX'],
        ];

        foreach ($types as $agent) {
            DB::table('ai_agents')->insert(array_merge($agent, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_agents');
    }
};
