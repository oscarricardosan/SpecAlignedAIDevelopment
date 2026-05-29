<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_providers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('provider_code');
            $table->text('api_key');
            $table->string('api_endpoint')->nullable();
            $table->foreignId('user_who_created_id')->nullable()->constrained('users');
            $table->foreignId('user_who_updated_id')->nullable()->constrained('users');
            $table->foreignId('user_who_deleted_id')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_providers');
    }
};
