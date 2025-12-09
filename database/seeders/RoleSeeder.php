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
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $modules = [
            'users',
            'students',
            'staff',
            'judges',
            'events',
            'teams',
            'projects',
            'criteria',
            'awards',
        ];

        $actions = [
            'view',
            'create',
            'edit',
            'delete',
        ];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => "$module.$action"]);
            }
        }

        $specialPermissions = [
            'projects.grade',
            'projects.deliver',
            'events.publish',
            'events.join',
            'teams.join',
            'teams.lead',
            'teams.advise',
            'dashboard.view',
            'dashboard.stats',
            'reports.download',
        ];

        foreach ($specialPermissions as $sp) {
            Permission::firstOrCreate(['name' => $sp]);
        }

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

        $roleJudge = Role::firstOrCreate(['name' => 'judge']);
        $roleJudge->syncPermissions([
            'dashboard.view',
            'events.view',
            'teams.view',
            'projects.view',
            'projects.grade',
        ]);

        $roleAdvisor = Role::firstOrCreate(['name' => 'advisor']);
        $roleAdvisor->syncPermissions([
            'dashboard.view',
            'events.view',
            'teams.view',
            'teams.advise',
            'projects.view',
        ]);

        $roleStaff = Role::firstOrCreate(['name' => 'staff']);
        $roleStaff->syncPermissions([
            'dashboard.view',
            'dashboard.stats',
            'events.view',
            'events.create',
            'events.edit',
            'events.delete',
            'events.publish',
            'teams.view',
            'teams.edit',
            'teams.delete',
            'projects.view',
            'projects.edit',
            'criteria.view',
            'criteria.create',
            'criteria.edit',
            'criteria.delete',
            'awards.view',
            'awards.create',
            'awards.edit',
            'awards.delete',
            'reports.download',
        ]);

        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $roleAdmin->syncPermissions([
            'dashboard.view',
            'dashboard.stats',
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'students.view',
            'students.create',
            'students.edit',
            'students.delete',
            'staff.view',
            'staff.create',
            'staff.edit',
            'staff.delete',
            'judges.view',
            'judges.create',
            'judges.edit',
            'judges.delete',
            'events.view',
            'events.create',
            'events.edit',
            'events.delete',
            'events.publish',
            'teams.view',
            'teams.create',
            'teams.edit',
            'teams.delete',
            'projects.view',
            'projects.create',
            'projects.edit',
            'projects.delete',
            'criteria.view',
            'criteria.create',
            'criteria.edit',
            'criteria.delete',
            'awards.view',
            'awards.create',
            'awards.edit',
            'awards.delete',
            'reports.download',
        ]);
    }
}