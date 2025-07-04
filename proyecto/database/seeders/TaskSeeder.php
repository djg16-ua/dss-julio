<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
    public function run()
    {
        // Obtener usuarios
        $john = DB::table('users')->where('email', 'john@taskflow.com')->first();
        $jane = DB::table('users')->where('email', 'jane@taskflow.com')->first();
        $bob = DB::table('users')->where('email', 'bob@taskflow.com')->first();

        // Obtener módulos por proyecto
        $ecommerce = DB::table('projects')->where('title', 'E-commerce Platform')->first();
        $crm = DB::table('projects')->where('title', 'CRM System')->first();
        
        $authModuleEcommerce = DB::table('modules')
            ->where('project_id', $ecommerce->id)
            ->where('name', 'Authentication')
            ->first();
        
        $paymentModule = DB::table('modules')
            ->where('project_id', $ecommerce->id)
            ->where('name', 'Payment System')
            ->first();
        
        $uiModule = DB::table('modules')
            ->where('project_id', $ecommerce->id)
            ->where('name', 'User Interface')
            ->first();
        
        $authModuleCRM = DB::table('modules')
            ->where('project_id', $crm->id)
            ->where('name', 'Authentication')
            ->first();

        // Crear tareas
        DB::table('tasks')->insert([
            [
                'id' => 1,
                'title' => 'Create login form',
                'description' => 'E-commerce login form implementation with validation',
                'status' => 'ACTIVE',
                'priority' => 'HIGH',
                'end_date' => now()->addDays(3),
                'completed_at' => null,
                'module_id' => $authModuleEcommerce->id,
                'assigned_to' => $jane->id,
                'created_by' => $john->id,
                'depends_on' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'title' => 'User registration',
                'description' => 'E-commerce user registration system',
                'status' => 'PENDING',
                'priority' => 'HIGH',
                'end_date' => now()->addDays(5),
                'completed_at' => null,
                'module_id' => $authModuleEcommerce->id,
                'assigned_to' => $john->id,
                'created_by' => $john->id,
                'depends_on' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'title' => 'JWT implementation',
                'description' => 'JWT token system for E-commerce authentication',
                'status' => 'PENDING',
                'priority' => 'MEDIUM',
                'end_date' => now()->addDays(7),
                'completed_at' => null,
                'module_id' => $authModuleEcommerce->id,
                'assigned_to' => $john->id,
                'created_by' => $john->id,
                'depends_on' => 2, // Depende de User registration
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'title' => 'Stripe integration',
                'description' => 'Integrate Stripe payment gateway',
                'status' => 'PENDING',
                'priority' => 'HIGH',
                'end_date' => now()->addWeeks(2),
                'completed_at' => null,
                'module_id' => $paymentModule->id,
                'assigned_to' => $john->id,
                'created_by' => $john->id,
                'depends_on' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'title' => 'Homepage design',
                'description' => 'Design main homepage layout and components',
                'status' => 'ACTIVE',
                'priority' => 'MEDIUM',
                'end_date' => now()->addDays(4),
                'completed_at' => null,
                'module_id' => $uiModule->id,
                'assigned_to' => $jane->id,
                'created_by' => $jane->id,
                'depends_on' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'title' => 'Create login form', // MISMO NOMBRE, módulo diferente
                'description' => 'CRM login form implementation',
                'status' => 'PENDING',
                'priority' => 'MEDIUM',
                'end_date' => now()->addWeeks(2),
                'completed_at' => null,
                'module_id' => $authModuleCRM->id,
                'assigned_to' => $john->id,
                'created_by' => $john->id,
                'depends_on' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 7,
                'title' => 'Authentication testing',
                'description' => 'Test all authentication flows for e-commerce',
                'status' => 'PENDING',
                'priority' => 'HIGH',
                'end_date' => now()->addDays(10),
                'completed_at' => null,
                'module_id' => $authModuleEcommerce->id,
                'assigned_to' => $bob->id,
                'created_by' => $john->id,
                'depends_on' => 3, // Depende de JWT implementation
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
