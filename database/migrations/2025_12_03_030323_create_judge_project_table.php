<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('judge_project', function (Blueprint $table) {
            $table->id();
            $table->foreignId('judge_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->timestamp('assigned_at')->useCurrent();
            $table->boolean('is_completed')->default(false);
            $table->timestamps();

            $table->unique(['judge_id', 'project_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('judge_project');
    }
};
