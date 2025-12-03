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
            'users',      // Usuarios (gestión general)
            'students',   // Estudiantes
            'staff',      // Docentes/Organizadores
            'judges',     // Jueces
            'events',     // Eventos
            'teams',      // Equipos
            'projects',   // Proyectos
            'criteria',   // Criterios de evaluación
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
            'projects.deliver',     // Entregar proyectos (estudiantes)
            'events.publish',       // Publicar eventos (cerrar convocatoria)
            'events.join',          // Unirse a eventos (estudiantes)
            'teams.join',           // Unirse a equipos (estudiantes)
            'teams.lead',           // Liderar equipos (estudiantes)
            'teams.advise',         // Asesorar equipos (docentes)
            'dashboard.view',       // Ver panel principal
            'dashboard.stats',      // Ver estadísticas del dashboard
            'reports.download',     // Descargar diplomas/constancias
        ];

        foreach ($specialPermissions as $sp) {
            Permission::firstOrCreate(['name' => $sp]);
        }

        // =================================================================
        // ASIGNACIÓN DE ROLES
        // =================================================================

        // A) STUDENT (Estudiante)
        $roleStudent = Role::firstOrCreate(['name' => 'student']);
        $roleStudent->syncPermissions([
            'dashboard.view',
            'events.view',
            'events.join',
            'teams.view',
            'teams.create',
            'teams.join',
            'teams.lead',
            'projects.view',
            'projects.deliver',
        ]);

        // B) JUDGE (Juez)
        $roleJudge = Role::firstOrCreate(['name' => 'judge']);
        $roleJudge->syncPermissions([
            'dashboard.view',
            'events.view',
            'teams.view',
            'projects.view',
            'projects.grade',
        ]);

        // C) ADVISOR (Docente/Asesor)
        $roleAdvisor = Role::firstOrCreate(['name' => 'advisor']);
        $roleAdvisor->syncPermissions([
            'dashboard.view',
            'events.view',
            'teams.view',
            'teams.advise',
            'projects.view',
        ]);

        // D) STAFF (Organizador)
        $roleStaff = Role::firstOrCreate(['name' => 'staff']);
        $roleStaff->syncPermissions([
            'dashboard.view',
            'dashboard.stats',
            // Eventos - gestión completa
            'events.view',
            'events.create',
            'events.edit',
            'events.delete',
            'events.publish',
            // Equipos - gestión completa
            'teams.view',
            'teams.edit',
            'teams.delete',
            // Proyectos - ver y editar
            'projects.view',
            'projects.edit',
            // Criterios - gestión completa
            'criteria.view',
            'criteria.create',
            'criteria.edit',
            'criteria.delete',
            // Premios - gestión completa
            'awards.view',
            'awards.create',
            'awards.edit',
            'awards.delete',
            // Reportes
            'reports.download',
        ]);

        // E) ADMIN (Administrador Total)
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $roleAdmin->syncPermissions(Permission::all());
    }
}