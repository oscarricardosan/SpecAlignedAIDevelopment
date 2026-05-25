<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_compliances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->string('compliance'); // none, gdpr, hipaa, pci_dss, soc2, iso_27001
            $table->foreignId('user_who_created_id')->nullable()->constrained('users');
            $table->foreignId('user_who_updated_id')->nullable()->constrained('users');
            $table->foreignId('user_who_deleted_id')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['project_id', 'compliance']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_compliances');
    }
};
