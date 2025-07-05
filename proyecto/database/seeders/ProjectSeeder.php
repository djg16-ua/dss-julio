<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectSeeder extends Seeder
{
    public function run()
    {
        // Obtener usuarios
        $admin = DB::table('users')->where('email', 'admin@taskflow.com')->first();
        $john = DB::table('users')->where('email', 'john@taskflow.com')->first();

        // Crear proyectos
        DB::table('projects')->insert([
            [
                'id' => 1,
                'title' => 'E-commerce Platform',
                'description' => 'Complete online shopping platform with payment integration',
                'status' => 'ACTIVE',
                'start_date' => now()->subMonth(),
                'end_date' => now()->addMonths(5),
                'public' => true,
                'created_by' => $admin->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'title' => 'CRM System',
                'description' => 'Customer relationship management system',
                'status' => 'PENDING',
                'start_date' => now()->addWeek(),
                'end_date' => now()->addMonths(3),
                'public' => false,
                'created_by' => $john->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Asignar teams a proyectos
        DB::table('project_team')->insert([
            [
                'project_id' => 1, // E-commerce
                'team_id' => 1, // Frontend Team
                'assigned_at' => now()->subMonth(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'project_id' => 1, // E-commerce
                'team_id' => 2, // Backend Team
                'assigned_at' => now()->subMonth(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'project_id' => 1, // E-commerce
                'team_id' => 3, // QA Team
                'assigned_at' => now()->subWeeks(3),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'project_id' => 2, // CRM
                'team_id' => 2, // Backend Team
                'assigned_at' => now()->subWeek(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'project_id' => 2, // CRM
                'team_id' => 3, // QA Team
                'assigned_at' => now()->subWeek(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
