<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Resetear caché
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Definir los "Módulos" del sistema
        $modules = [
            'users',      // Usuarios
            'events',     // Eventos
            'teams',      // Equipos
            'projects',   // Proyectos
            'awards',     // Premios
        ];

        // 3. Definir las acciones estándar (CRUD)
        $actions = [
            'view',   // Ver / Listar
            'create', // Crear
            'edit',   // Editar / Modificar
            'delete', // Eliminar
        ];

        // 4. Generación Automática de Permisos (Ej: users.view, events.create)
        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => "$module.$action"]);
            }
        }

        // 5. Permisos Especiales (Acciones que no son CRUD normal)
        $specialPermissions = [
            'projects.grade',       // Calificar proyectos
            'events.publish',       // Publicar eventos (cerrar convocatoria)
            'dashboard.view',       // Ver panel principal
            'reports.download',     // Descargar diplomas/constancias
        ];

        foreach ($specialPermissions as $sp) {
            Permission::firstOrCreate(['name' => $sp]);
        }

        // =================================================================
        // ASIGNACIÓN DE ROLES (Aquí definimos quién hace qué)
        // =================================================================

        // A) STUDENT (Estudiante)
        // El estudiante casi no necesita permisos explícitos, su lógica es "owner-based"
        // (Solo edita SU equipo). Pero le damos permiso de ver.
        $roleStudent = Role::firstOrCreate(['name' => 'student']);
        $roleStudent->givePermissionTo([
            'events.view',
            'projects.view',
            'teams.create', // Puede registrar su equipo
            'dashboard.view'
        ]);

        // B) JUDGE (Juez)
        $roleJudge = Role::firstOrCreate(['name' => 'judge']);
        $roleJudge->givePermissionTo([
            'dashboard.view',
            'events.view',
            'projects.view',
            'projects.grade', // ¡Vital!
        ]);

        // C) STAFF (Organizador)
        // Puede gestionar eventos y equipos, pero NO borrar usuarios ni tocar configuraciones
        $roleStaff = Role::firstOrCreate(['name' => 'staff']);
        $roleStaff->givePermissionTo([
            'dashboard.view',
            'events.view', 'events.create', 'events.edit', // Puede gestionar eventos
            'teams.view', 'teams.edit',                     // Puede moderar equipos
            'projects.view',
            'reports.download',
            'events.publish'
        ]);

        // D) ADVISOR (Asesor)
        $roleAdvisor = Role::firstOrCreate(['name' => 'advisor']);
        $roleAdvisor->givePermissionTo([
            'dashboard.view',
            'projects.view',
            // El asesor podría ver calificaciones pero no editar
        ]);

        // E) ADMIN (Dios)
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $roleAdmin->givePermissionTo(Permission::all());
    }
}