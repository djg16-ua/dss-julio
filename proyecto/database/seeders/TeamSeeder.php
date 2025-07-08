<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TeamSeeder extends Seeder
{
    public function run()
    {
        // Verificar que existan proyectos
        $projects = Project::all();
        if ($projects->isEmpty()) {
            $this->command->warn('No hay proyectos en la base de datos. Ejecuta ProjectSeeder primero.');
            return;
        }

        // Verificar que existan usuarios
        $users = User::all();
        if ($users->isEmpty()) {
            $this->command->warn('No hay usuarios en la base de datos. Ejecuta UserSeeder primero.');
            return;
        }

        $this->command->info('Creando equipos adicionales y asignando usuarios...');

        DB::beginTransaction();
        
        try {
            // Crear índice de usuarios por email para búsquedas rápidas
            $usersByEmail = $users->keyBy('email');

            foreach ($projects as $project) {
                $this->command->info("Procesando proyecto: {$project->title}");
                
                // Obtener el equipo general que ya fue creado automáticamente
                $generalTeam = $project->getGeneralTeam();
                
                if (!$generalTeam) {
                    $this->command->warn("No se encontró equipo general para proyecto {$project->id}");
                    continue;
                }

                // 1. Añadir el creador del proyecto al equipo general como LEAD
                $creator = User::find($project->created_by);
                if ($creator) {
                    $generalTeam->users()->syncWithoutDetaching([
                        $creator->id => [
                            'is_active' => true,
                            'role' => 'LEAD',
                            'joined_at' => $project->created_at,
                            'created_at' => $project->created_at,
                            'updated_at' => $project->created_at,
                        ]
                    ]);
                }

                // 2. Crear equipos específicos según el proyecto
                $this->createSpecificTeams($project);

                // 3. Asignar usuarios a equipos
                $this->assignUsersToProjectTeams($project, $usersByEmail);
            }

            DB::commit();
            $this->command->info('Equipos y asignaciones creados exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            $this->command->error('Error en TeamSeeder: ' . $e->getMessage());
            throw $e;
        }
    }

    private function createSpecificTeams(Project $project)
    {
        $teams = [];
        
        switch ($project->id) {
            case 1: // E-commerce Platform - proyecto grande
                $teams = [
                    ['name' => 'Frontend', 'description' => 'Desarrollo de interfaz de usuario'],
                    ['name' => 'Backend', 'description' => 'Desarrollo del servidor y APIs'],
                    ['name' => 'QA', 'description' => 'Control de calidad y testing'],
                    ['name' => 'DevOps', 'description' => 'Infraestructura y despliegue'],
                ];
                break;

            case 2: // CRM System
            case 4: // Healthcare Portal
            case 8: // Learning Management System
                $teams = [
                    ['name' => 'Development', 'description' => 'Equipo de desarrollo principal'],
                    ['name' => 'Testing', 'description' => 'Equipo de pruebas y QA'],
                ];
                break;

            case 3: // Mobile Banking App
                $teams = [
                    ['name' => 'Mobile Development', 'description' => 'Desarrollo de aplicación móvil'],
                    ['name' => 'Backend', 'description' => 'APIs y servicios backend'],
                    ['name' => 'Security', 'description' => 'Seguridad y compliance bancario'],
                ];
                break;

            case 5: // Analytics Dashboard
                $teams = [
                    ['name' => 'Data Team', 'description' => 'Análisis de datos y machine learning'],
                    ['name' => 'Frontend', 'description' => 'Dashboard y visualizaciones'],
                ];
                break;

            default:
                // Proyectos más pequeños solo tienen el equipo general
                break;
        }

        foreach ($teams as $teamData) {
            Team::create([
                'name' => $teamData['name'],
                'description' => $teamData['description'],
                'project_id' => $project->id,
                'is_general' => false,
                'created_at' => $project->created_at->addDays(rand(1, 7)),
                'updated_at' => now()->subDays(rand(1, 30)),
            ]);
        }
    }

    private function assignUsersToProjectTeams(Project $project, $usersByEmail)
    {
        // Definir asignaciones por proyecto
        $projectAssignments = [
            1 => [ // E-commerce Platform
                'General' => [
                    'admin@taskflow.com', 'john@taskflow.com', 'jane@taskflow.com', 
                    'bob@taskflow.com', 'sarah@taskflow.com', 'alex.johnson@taskflow.com',
                    'maria.garcia@taskflow.com', 'kevin.brown@taskflow.com'
                ],
                'Frontend' => ['jane@taskflow.com', 'alex.johnson@taskflow.com', 'maria.garcia@taskflow.com'],
                'Backend' => ['john@taskflow.com', 'kevin.brown@taskflow.com', 'sarah@taskflow.com'],
                'QA' => ['bob@taskflow.com'],
                'DevOps' => ['admin@taskflow.com'],
            ],
            2 => [ // CRM System
                'General' => [
                    'jane@taskflow.com', 'michael.rodriguez@taskflow.com', 
                    'lisa.chen@taskflow.com', 'david.wilson@taskflow.com'
                ],
                'Development' => ['jane@taskflow.com', 'michael.rodriguez@taskflow.com'],
                'Testing' => ['lisa.chen@taskflow.com'],
            ],
            3 => [ // Mobile Banking App
                'General' => [
                    'michael.rodriguez@taskflow.com', 'lisa.chen@taskflow.com',
                    'david.wilson@taskflow.com', 'emma.thompson@taskflow.com'
                ],
                'Mobile Development' => ['michael.rodriguez@taskflow.com', 'david.wilson@taskflow.com'],
                'Backend' => ['lisa.chen@taskflow.com'],
                'Security' => ['emma.thompson@taskflow.com'],
            ],
            4 => [ // Healthcare Portal
                'General' => [
                    'admin@taskflow.com', 'sarah@taskflow.com', 'alex.johnson@taskflow.com',
                    'robert.martinez@taskflow.com'
                ],
                'Development' => ['sarah@taskflow.com', 'alex.johnson@taskflow.com'],
                'Testing' => ['robert.martinez@taskflow.com'],
            ],
            5 => [ // Analytics Dashboard
                'General' => [
                    'jane@taskflow.com', 'maria.garcia@taskflow.com',
                    'kevin.brown@taskflow.com', 'emma.thompson@taskflow.com'
                ],
                'Data Team' => ['maria.garcia@taskflow.com', 'kevin.brown@taskflow.com'],
                'Frontend' => ['jane@taskflow.com', 'emma.thompson@taskflow.com'],
            ],
            8 => [ // Learning Management System
                'General' => [
                    'admin@taskflow.com', 'john@taskflow.com', 'bob@taskflow.com',
                    'robert.martinez@taskflow.com'
                ],
                'Development' => ['john@taskflow.com', 'robert.martinez@taskflow.com'],
                'Testing' => ['bob@taskflow.com'],
            ],
            9 => [ // AI Chatbot Platform
                'General' => [
                    'lisa.chen@taskflow.com', 'john@taskflow.com', 'kevin.brown@taskflow.com',
                    'maria.garcia@taskflow.com', 'emma.thompson@taskflow.com'
                ],
            ],
            10 => [ // IoT Device Management
                'General' => [
                    'admin@taskflow.com', 'david.wilson@taskflow.com', 'robert.martinez@taskflow.com',
                    'alex.johnson@taskflow.com'
                ],
            ],
            11 => [ // Legacy System Migration
                'General' => [
                    'john@taskflow.com', 'sarah@taskflow.com', 'emma.thompson@taskflow.com',
                    'michael.rodriguez@taskflow.com'
                ],
            ],
            12 => [ // Security Audit Platform
                'General' => [
                    'michael.rodriguez@taskflow.com', 'bob@taskflow.com', 'jane@taskflow.com',
                    'robert.martinez@taskflow.com'
                ],
            ],
            13 => [ // Payment Gateway Integration
                'General' => [
                    'lisa.chen@taskflow.com', 'alex.johnson@taskflow.com', 'kevin.brown@taskflow.com',
                    'maria.garcia@taskflow.com'
                ],
            ],
            14 => [ // Performance Optimization Study
                'General' => [
                    'robert.martinez@taskflow.com', 'sarah@taskflow.com', 'maria.garcia@taskflow.com',
                    'david.wilson@taskflow.com'
                ],
            ],
            15 => [ // API Standardization Initiative
                'General' => [
                    'admin@taskflow.com', 'jane@taskflow.com', 'david.wilson@taskflow.com',
                    'kevin.brown@taskflow.com'
                ],
            ],
            16 => [ // Microservices POC
                'General' => [
                    'john@taskflow.com', 'emma.thompson@taskflow.com', 'kevin.brown@taskflow.com',
                    'bob@taskflow.com'
                ],
            ],
            17 => [ // Dark Mode Implementation
                'General' => [
                    'lisa.chen@taskflow.com', 'alex.johnson@taskflow.com', 'bob@taskflow.com',
                    'maria.garcia@taskflow.com'
                ],
            ],
            18 => [ // Accessibility Compliance
                'General' => [
                    'michael.rodriguez@taskflow.com', 'maria.garcia@taskflow.com', 'sarah@taskflow.com',
                    'emma.thompson@taskflow.com'
                ],
            ],
            19 => [ // Database Optimization
                'General' => [
                    'robert.martinez@taskflow.com', 'jane@taskflow.com', 'david.wilson@taskflow.com',
                    'admin@taskflow.com'
                ],
            ],
            20 => [ // Documentation Overhaul
                'General' => [
                    'admin@taskflow.com', 'alex.johnson@taskflow.com', 'kevin.brown@taskflow.com',
                    'jane@taskflow.com'
                ],
            ],
        ];

        if (!isset($projectAssignments[$project->id])) {
            // Para proyectos sin asignaciones específicas, solo añadir algunos usuarios al general
            $generalTeam = $project->getGeneralTeam();
            $someUsers = $usersByEmail->take(3);
            
            foreach ($someUsers as $user) {
                if ($user->id !== $project->created_by) { // No duplicar al creador
                    $generalTeam->users()->syncWithoutDetaching([
                        $user->id => [
                            'is_active' => true,
                            'role' => 'DEVELOPER',
                            'joined_at' => $project->created_at->addDays(rand(1, 10)),
                            'created_at' => $project->created_at,
                            'updated_at' => $project->created_at,
                        ]
                    ]);
                }
            }
            return;
        }

        $assignments = $projectAssignments[$project->id];
        $projectTeams = $project->teams->keyBy('name');

        foreach ($assignments as $teamName => $userEmails) {
            $team = $projectTeams->get($teamName);
            
            if (!$team) {
                $this->command->warn("Equipo '{$teamName}' no encontrado para proyecto {$project->title}");
                continue;
            }

            foreach ($userEmails as $email) {
                $user = $usersByEmail->get($email);
                if (!$user) {
                    $this->command->warn("Usuario '{$email}' no encontrado");
                    continue;
                }

                $team->users()->syncWithoutDetaching([
                    $user->id => [
                        'is_active' => true,
                        'role' => $this->getUserRole($email, $teamName),
                        'joined_at' => $team->created_at->addDays(rand(0, 7)),
                        'created_at' => $team->created_at,
                        'updated_at' => $team->created_at,
                    ]
                ]);
            }
        }
    }

    private function getUserRole($email, $teamName): string
    {
        // Asignar roles según el usuario
        $roleMap = [
            'admin@taskflow.com' => 'LEAD',
            'john@taskflow.com' => 'LEAD',
            'jane@taskflow.com' => 'LEAD',
            'michael.rodriguez@taskflow.com' => 'LEAD',
            'bob@taskflow.com' => 'SENIOR_DEV',
            'lisa.chen@taskflow.com' => 'SENIOR_DEV',
            'david.wilson@taskflow.com' => 'SENIOR_DEV',
            'alex.johnson@taskflow.com' => 'SENIOR_DEV',
            'kevin.brown@taskflow.com' => 'SENIOR_DEV',
            'robert.martinez@taskflow.com' => 'SENIOR_DEV',
            'emma.thompson@taskflow.com' => 'SENIOR_DEV',
            'maria.garcia@taskflow.com' => 'SENIOR_DEV',
        ];


        return $roleMap[$email] ?? 'DEVELOPER';
    }
}