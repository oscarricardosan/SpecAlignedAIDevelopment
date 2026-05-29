<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();

            // Identity
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('path');
            $table->string('disk')->default('mnt-said-projects');
            $table->text('notes')->nullable();

            // Platform & stack
            $table->string('platform');
            $table->string('language');
            $table->boolean('language_is_custom')->default(false);
            $table->string('language_version')->nullable();
            $table->boolean('language_version_is_custom')->default(false);
            $table->string('technology');
            $table->boolean('technology_is_custom')->default(false);
            $table->string('framework_version')->nullable();
            $table->boolean('framework_version_is_custom')->default(false);
            $table->string('package_manager')->nullable();

            // Architecture
            $table->string('paradigm')->nullable();
            $table->string('architecture')->nullable();
            $table->text('design_principles_codes')->nullable();

            // Storage
            $table->text('databases_codes')->nullable();
            $table->string('database_access')->nullable();
            $table->text('storage_codes')->nullable();
            $table->string('code_repository')->nullable();
            $table->string('code_repository_url')->nullable();

            // Quality
            $table->text('testing_frameworks_codes')->nullable();
            $table->string('code_style')->nullable();
            $table->string('ci_cd')->nullable();

            // Execution
            $table->string('executor')->default('local');

            // Context for AI agents
            $table->text('context_stack')->nullable();
            $table->text('context_architecture')->nullable();
            $table->text('context_guidelines')->nullable();

            // Audit
            $table->foreignId('user_who_created_id')->nullable()->constrained('users');
            $table->foreignId('user_who_updated_id')->nullable()->constrained('users');
            $table->foreignId('user_who_deleted_id')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();

            // Unique app name per project
            $table->unique(['project_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
