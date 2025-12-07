<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Documentación del proyecto (PDF)
            $table->string('documentation_path')->nullable()->after('repository_url');
            // Imagen/Logo del proyecto
            $table->string('image_path')->nullable()->after('documentation_path');
            // Video demostrativo (URL de YouTube/Vimeo)
            $table->string('video_url')->nullable()->after('image_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['documentation_path', 'image_path', 'video_url']);
        });
    }
};
