<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuleSeeder extends Seeder
{
    public function run()
    {
        // Obtener usuarios y proyectos
        $john = DB::table('users')->where('email', 'john@taskflow.com')->first();
        $jane = DB::table('users')->where('email', 'jane@taskflow.com')->first();
        
        $ecommerce = DB::table('projects')->where('title', 'E-commerce Platform')->first();
        $crm = DB::table('projects')->where('title', 'CRM System')->first();

        // Crear módulos
        DB::table('modules')->insert([
            [
                'id' => 1,
                'name' => 'Authentication',
                'description' => 'User authentication system for e-commerce',
                'priority' => 'HIGH',
                'category' => 'DEVELOPMENT',
                'status' => 'ACTIVE',
                'project_id' => $ecommerce->id,
                'is_core' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Payment System',
                'description' => 'Payment processing integration',
                'priority' => 'HIGH',
                'category' => 'INTEGRATION',
                'status' => 'PENDING',
                'project_id' => $ecommerce->id,
                'is_core' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'User Interface',
                'description' => 'Frontend components and layouts',
                'priority' => 'MEDIUM',
                'category' => 'DESIGN',
                'status' => 'ACTIVE',
                'project_id' => $ecommerce->id,
                'is_core' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'Authentication', // MISMO NOMBRE, proyecto diferente
                'description' => 'CRM user authentication system',
                'priority' => 'HIGH',
                'category' => 'DEVELOPMENT',
                'status' => 'PENDING',
                'project_id' => $crm->id,
                'is_core' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Asignar teams a módulos
        DB::table('module_team')->insert([
            [
                'module_id' => 1, // Auth E-commerce
                'team_id' => 2, // Backend Team
                'assigned_at' => now()->subMonth(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => 1, // Auth E-commerce
                'team_id' => 3, // QA Team
                'assigned_at' => now()->subWeeks(3),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => 2, // Payment System
                'team_id' => 2, // Backend Team
                'assigned_at' => now()->subWeek(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => 3, // User Interface
                'team_id' => 1, // Frontend Team
                'assigned_at' => now()->subMonth(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => 3, // User Interface
                'team_id' => 3, // QA Team
                'assigned_at' => now()->subWeeks(2),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => 4, // Auth CRM
                'team_id' => 2, // Backend Team
                'assigned_at' => now()->subWeek(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
