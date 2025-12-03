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
        // 1. Agregar columnas a TEAMS
        Schema::table('teams', function (Blueprint $table) {
            $table->foreignId('advisor_id')->nullable()->after('leader_id')->constrained('users');
            $table->string('advisor_status')->default('pending')->after('advisor_id'); // pending, accepted, rejected
        });

        // 2. Eliminar columnas de PROJECTS (Limpieza)
        Schema::table('projects', function (Blueprint $table) {
            // Primero botamos la llave foránea (el nombre suele ser projects_advisor_id_foreign)
            $table->dropForeign(['advisor_id']); 
            $table->dropColumn(['advisor_id', 'advisor_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir cambios (por si acaso)
        Schema::table('projects', function (Blueprint $table) {
            $table->foreignId('advisor_id')->nullable()->constrained('users');
            $table->string('advisor_status')->default('pending');
        });

        Schema::table('teams', function (Blueprint $table) {
            $table->dropForeign(['advisor_id']);
            $table->dropColumn(['advisor_id', 'advisor_status']);
        });
    }
};
