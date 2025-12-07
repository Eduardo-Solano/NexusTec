<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Nuevos estados del evento:
     * - registration: Período de inscripciones (equipos pueden registrarse)
     * - active: Evento en curso (proyectos, evaluaciones de jueces)
     * - closed: Evento cerrado (ganadores, diplomas, sin más acciones)
     */
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Agregar el nuevo campo de estado
            $table->enum('status', ['registration', 'active', 'closed'])
                  ->default('registration')
                  ->after('end_date');
        });

        // Migrar datos existentes basándose en is_active y fechas
        // Si is_active = false -> closed
        // Si is_active = true y end_date pasada -> closed
        // Si is_active = true y start_date futura -> registration
        // Si is_active = true y en curso -> active
        DB::statement("
            UPDATE events SET status = CASE
                WHEN is_active = 0 THEN 'closed'
                WHEN is_active = 1 AND end_date < NOW() THEN 'closed'
                WHEN is_active = 1 AND start_date > NOW() THEN 'registration'
                ELSE 'active'
            END
        ");

        // Eliminar el campo is_active ya que será reemplazado por status
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('end_date');
        });

        // Restaurar datos: closed -> is_active = false, otros -> is_active = true
        DB::statement("
            UPDATE events SET is_active = CASE
                WHEN status = 'closed' THEN 0
                ELSE 1
            END
        ");

        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
