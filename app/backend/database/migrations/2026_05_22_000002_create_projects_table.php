<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('path');
            $table->text('description')->nullable();
            $table->string('criticality');
            $table->string('business_sector');
            // compliance moved to project_compliances table
            $table->string('target_audience');
            $table->foreignId('user_who_created_id')->nullable()->constrained('users');
            $table->foreignId('user_who_updated_id')->nullable()->constrained('users');
            $table->foreignId('user_who_deleted_id')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
