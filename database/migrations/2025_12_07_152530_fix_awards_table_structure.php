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
        Schema::table('awards', function (Blueprint $table) {
            // Renombrar 'postion' a 'position' (corregir typo) si existe
            if (Schema::hasColumn('awards', 'postion')) {
                $table->renameColumn('postion', 'position');
            }
            
            // Agregar columna position si no existe
            if (!Schema::hasColumn('awards', 'position') && !Schema::hasColumn('awards', 'postion')) {
                $table->string('position')->after('team_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('awards', function (Blueprint $table) {
            if (Schema::hasColumn('awards', 'position')) {
                $table->renameColumn('position', 'postion');
            }
        });
    }
};
