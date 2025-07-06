<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectSeeder extends Seeder
{
    public function run()
    {
        // Obtener usuarios clave
        $admin = DB::table('users')->where('email', 'admin@taskflow.com')->first();
        $john = DB::table('users')->where('email', 'john@taskflow.com')->first();
        $michael = DB::table('users')->where('email', 'michael.rodriguez@taskflow.com')->first();
        $robert = DB::table('users')->where('email', 'robert.martinez@taskflow.com')->first();
        $lisa = DB::table('users')->where('email', 'lisa.chen@taskflow.com')->first();

        // Crear proyectos variados
        $projects = [
            // Proyectos principales activos
            [
                'id' => 1,
                'title' => 'E-commerce Platform',
                'description' => 'Complete online shopping platform with payment integration, inventory management, and customer analytics',
                'status' => 'ACTIVE',
                'start_date' => now()->subMonths(8),
                'end_date' => now()->addMonths(4),
                'public' => true,
                'created_by' => $admin->id,
                'created_at' => now()->subMonths(8),
                'updated_at' => now()->subDays(2),
            ],
            [
                'id' => 2,
                'title' => 'CRM System',
                'description' => 'Customer relationship management system with lead tracking and sales automation',
                'status' => 'ACTIVE',
                'start_date' => now()->subMonths(6),
                'end_date' => now()->addMonths(6),
                'public' => false,
                'created_by' => $john->id,
                'created_at' => now()->subMonths(6),
                'updated_at' => now()->subDays(1),
            ],
            [
                'id' => 3,
                'title' => 'Mobile Banking App',
                'description' => 'Secure mobile banking application with biometric authentication and real-time transactions',
                'status' => 'ACTIVE',
                'start_date' => now()->subMonths(10),
                'end_date' => now()->addMonths(2),
                'public' => false,
                'created_by' => $michael->id,
                'created_at' => now()->subMonths(10),
                'updated_at' => now()->subDays(3),
            ],
            [
                'id' => 4,
                'title' => 'Healthcare Portal',
                'description' => 'Patient management system with appointment scheduling and medical records',
                'status' => 'ACTIVE',
                'start_date' => now()->subMonths(5),
                'end_date' => now()->addMonths(8),
                'public' => true,
                'created_by' => $robert->id,
                'created_at' => now()->subMonths(5),
                'updated_at' => now()->subDays(4),
            ],
            [
                'id' => 5,
                'title' => 'Analytics Dashboard',
                'description' => 'Real-time business intelligence and analytics platform with custom reporting',
                'status' => 'ACTIVE',
                'start_date' => now()->subMonths(4),
                'end_date' => now()->addMonths(5),
                'public' => true,
                'created_by' => $admin->id,
                'created_at' => now()->subMonths(4),
                'updated_at' => now()->subWeeks(1),
            ],

            // Proyectos en planning/pendientes
            [
                'id' => 6,
                'title' => 'AI Chatbot Platform',
                'description' => 'Intelligent chatbot platform with natural language processing and machine learning',
                'status' => 'PENDING',
                'start_date' => now()->addWeeks(2),
                'end_date' => now()->addMonths(12),
                'public' => false,
                'created_by' => $lisa->id,
                'created_at' => now()->subWeeks(3),
                'updated_at' => now()->subDays(5),
            ],
            [
                'id' => 7,
                'title' => 'Inventory Management System',
                'description' => 'Comprehensive inventory tracking and warehouse management solution',
                'status' => 'PENDING',
                'start_date' => now()->addMonth(),
                'end_date' => now()->addMonths(7),
                'public' => true,
                'created_by' => $john->id,
                'created_at' => now()->subWeeks(2),
                'updated_at' => now()->subDays(6),
            ],
            [
                'id' => 8,
                'title' => 'Social Media Integration',
                'description' => 'Social media management and analytics tool for businesses',
                'status' => 'PENDING',
                'start_date' => now()->addWeeks(3),
                'end_date' => now()->addMonths(5),
                'public' => true,
                'created_by' => $michael->id,
                'created_at' => now()->subWeeks(1),
                'updated_at' => now()->subDays(2),
            ],
            [
                'id' => 9,
                'title' => 'Learning Management System',
                'description' => 'Online education platform with course management and student tracking',
                'status' => 'PENDING',
                'start_date' => now()->addWeeks(6),
                'end_date' => now()->addMonths(10),
                'public' => true,
                'created_by' => $robert->id,
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(3),
            ],
            [
                'id' => 10,
                'title' => 'IoT Device Management',
                'description' => 'Internet of Things device monitoring and control platform',
                'status' => 'PENDING',
                'start_date' => now()->addMonths(2),
                'end_date' => now()->addMonths(14),
                'public' => false,
                'created_by' => $admin->id,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(1),
            ],

            // Proyectos completados recientemente
            [
                'id' => 11,
                'title' => 'Legacy System Migration',
                'description' => 'Migration of legacy systems to modern cloud infrastructure',
                'status' => 'DONE',
                'start_date' => now()->subMonths(12),
                'end_date' => now()->subMonths(2),
                'public' => false,
                'created_by' => $john->id,
                'created_at' => now()->subMonths(12),
                'updated_at' => now()->subMonths(2),
            ],
            [
                'id' => 12,
                'title' => 'Security Audit Platform',
                'description' => 'Comprehensive security auditing and vulnerability assessment tool',
                'status' => 'DONE',
                'start_date' => now()->subMonths(14),
                'end_date' => now()->subMonths(3),
                'public' => true,
                'created_by' => $michael->id,
                'created_at' => now()->subMonths(14),
                'updated_at' => now()->subMonths(3),
            ],
            [
                'id' => 13,
                'title' => 'Payment Gateway Integration',
                'description' => 'Multi-payment gateway integration with fraud detection',
                'status' => 'DONE',
                'start_date' => now()->subMonths(10),
                'end_date' => now()->subMonths(1),
                'public' => false,
                'created_by' => $lisa->id,
                'created_at' => now()->subMonths(10),
                'updated_at' => now()->subMonths(1),
            ],

            // Proyectos internos/de investigación
            [
                'id' => 14,
                'title' => 'Performance Optimization Study',
                'description' => 'Research project to optimize application performance across all platforms',
                'status' => 'ACTIVE',
                'start_date' => now()->subMonths(3),
                'end_date' => now()->addMonths(3),
                'public' => false,
                'created_by' => $robert->id,
                'created_at' => now()->subMonths(3),
                'updated_at' => now()->subWeeks(1),
            ],
            [
                'id' => 15,
                'title' => 'API Standardization Initiative',
                'description' => 'Standardizing API design patterns and documentation across all services',
                'status' => 'ACTIVE',
                'start_date' => now()->subMonths(2),
                'end_date' => now()->addMonths(4),
                'public' => true,
                'created_by' => $admin->id,
                'created_at' => now()->subMonths(2),
                'updated_at' => now()->subDays(7),
            ],

            // Proyectos más pequeños/experimentales
            [
                'id' => 16,
                'title' => 'Microservices POC',
                'description' => 'Proof of concept for microservices architecture implementation',
                'status' => 'ACTIVE',
                'start_date' => now()->subWeeks(6),
                'end_date' => now()->addWeeks(6),
                'public' => false,
                'created_by' => $john->id,
                'created_at' => now()->subWeeks(6),
                'updated_at' => now()->subDays(4),
            ],
            [
                'id' => 17,
                'title' => 'Dark Mode Implementation',
                'description' => 'Adding dark mode support across all web and mobile applications',
                'status' => 'ACTIVE',
                'start_date' => now()->subWeeks(4),
                'end_date' => now()->addWeeks(8),
                'public' => true,
                'created_by' => $lisa->id,
                'created_at' => now()->subWeeks(4),
                'updated_at' => now()->subDays(2),
            ],
            [
                'id' => 18,
                'title' => 'Accessibility Compliance',
                'description' => 'Ensuring WCAG 2.1 AA compliance across all user interfaces',
                'status' => 'PENDING',
                'start_date' => now()->addWeeks(4),
                'end_date' => now()->addMonths(6),
                'public' => true,
                'created_by' => $michael->id,
                'created_at' => now()->subWeeks(1),
                'updated_at' => now()->subDays(1),
            ],

            // Proyectos de mantenimiento
            [
                'id' => 19,
                'title' => 'Database Optimization',
                'description' => 'Ongoing database performance optimization and maintenance',
                'status' => 'ACTIVE',
                'start_date' => now()->subMonths(1),
                'end_date' => now()->addMonths(6),
                'public' => false,
                'created_by' => $robert->id,
                'created_at' => now()->subMonths(1),
                'updated_at' => now()->subDays(3),
            ],
            [
                'id' => 20,
                'title' => 'Documentation Overhaul',
                'description' => 'Complete review and update of technical documentation',
                'status' => 'ACTIVE',
                'start_date' => now()->subWeeks(3),
                'end_date' => now()->addMonths(2),
                'public' => true,
                'created_by' => $admin->id,
                'created_at' => now()->subWeeks(3),
                'updated_at' => now()->subDays(1),
            ],
        ];

        DB::table('projects')->insert($projects);

        // Asignar teams a proyectos de manera realista
        $projectTeamAssignments = [
            // E-commerce Platform (Proyecto grande - múltiples teams)
            [
                'project_id' => 1,
                'team_assignments' => [
                    ['team_id' => 1, 'assigned_at' => now()->subMonths(8)], // Frontend
                    ['team_id' => 2, 'assigned_at' => now()->subMonths(8)], // Backend
                    ['team_id' => 3, 'assigned_at' => now()->subMonths(7)], // QA
                    ['team_id' => 4, 'assigned_at' => now()->subMonths(6)], // DevOps
                    ['team_id' => 5, 'assigned_at' => now()->subMonths(8)], // Product
                    ['team_id' => 9, 'assigned_at' => now()->subMonths(7)], // Design
                    ['team_id' => 8, 'assigned_at' => now()->subMonths(5)], // Security
                ]
            ],
            // CRM System
            [
                'project_id' => 2,
                'team_assignments' => [
                    ['team_id' => 2, 'assigned_at' => now()->subMonths(6)], // Backend
                    ['team_id' => 1, 'assigned_at' => now()->subMonths(5)], // Frontend
                    ['team_id' => 3, 'assigned_at' => now()->subMonths(4)], // QA
                    ['team_id' => 5, 'assigned_at' => now()->subMonths(6)], // Product
                    ['team_id' => 9, 'assigned_at' => now()->subMonths(5)], // Design
                ]
            ],
            // Mobile Banking App
            [
                'project_id' => 3,
                'team_assignments' => [
                    ['team_id' => 6, 'assigned_at' => now()->subMonths(10)], // Mobile
                    ['team_id' => 2, 'assigned_at' => now()->subMonths(9)], // Backend
                    ['team_id' => 8, 'assigned_at' => now()->subMonths(10)], // Security
                    ['team_id' => 3, 'assigned_at' => now()->subMonths(8)], // QA
                    ['team_id' => 4, 'assigned_at' => now()->subMonths(7)], // DevOps
                    ['team_id' => 5, 'assigned_at' => now()->subMonths(9)], // Product
                ]
            ],
            // Healthcare Portal
            [
                'project_id' => 4,
                'team_assignments' => [
                    ['team_id' => 1, 'assigned_at' => now()->subMonths(5)], // Frontend
                    ['team_id' => 2, 'assigned_at' => now()->subMonths(5)], // Backend
                    ['team_id' => 8, 'assigned_at' => now()->subMonths(4)], // Security
                    ['team_id' => 3, 'assigned_at' => now()->subMonths(3)], // QA
                    ['team_id' => 9, 'assigned_at' => now()->subMonths(4)], // Design
                ]
            ],
            // Analytics Dashboard
            [
                'project_id' => 5,
                'team_assignments' => [
                    ['team_id' => 7, 'assigned_at' => now()->subMonths(4)], // Data
                    ['team_id' => 1, 'assigned_at' => now()->subMonths(3)], // Frontend
                    ['team_id' => 2, 'assigned_at' => now()->subMonths(4)], // Backend
                    ['team_id' => 9, 'assigned_at' => now()->subMonths(3)], // Design
                ]
            ],
            // AI Chatbot Platform
            [
                'project_id' => 6,
                'team_assignments' => [
                    ['team_id' => 7, 'assigned_at' => now()->subWeeks(2)], // Data
                    ['team_id' => 10, 'assigned_at' => now()->subWeeks(2)], // Research
                    ['team_id' => 2, 'assigned_at' => now()->subWeeks(1)], // Backend
                ]
            ],
            // Inventory Management System
            [
                'project_id' => 7,
                'team_assignments' => [
                    ['team_id' => 2, 'assigned_at' => now()->subWeeks(2)], // Backend
                    ['team_id' => 1, 'assigned_at' => now()->subWeeks(1)], // Frontend
                    ['team_id' => 5, 'assigned_at' => now()->subWeeks(2)], // Product
                ]
            ],
            // Social Media Integration
            [
                'project_id' => 8,
                'team_assignments' => [
                    ['team_id' => 11, 'assigned_at' => now()->subWeek()], // Integration
                    ['team_id' => 1, 'assigned_at' => now()->subWeek()], // Frontend
                    ['team_id' => 7, 'assigned_at' => now()->subDays(5)], // Data
                ]
            ],
            // Learning Management System
            [
                'project_id' => 9,
                'team_assignments' => [
                    ['team_id' => 1, 'assigned_at' => now()->subDays(8)], // Frontend
                    ['team_id' => 2, 'assigned_at' => now()->subDays(8)], // Backend
                    ['team_id' => 9, 'assigned_at' => now()->subDays(6)], // Design
                    ['team_id' => 5, 'assigned_at' => now()->subDays(8)], // Product
                ]
            ],
            // IoT Device Management
            [
                'project_id' => 10,
                'team_assignments' => [
                    ['team_id' => 12, 'assigned_at' => now()->subDays(3)], // Platform
                    ['team_id' => 2, 'assigned_at' => now()->subDays(3)], // Backend
                    ['team_id' => 8, 'assigned_at' => now()->subDays(2)], // Security
                ]
            ],
            // Legacy System Migration (Completado)
            [
                'project_id' => 11,
                'team_assignments' => [
                    ['team_id' => 4, 'assigned_at' => now()->subMonths(12)], // DevOps
                    ['team_id' => 2, 'assigned_at' => now()->subMonths(11)], // Backend
                    ['team_id' => 12, 'assigned_at' => now()->subMonths(10)], // Platform
                ]
            ],
            // Security Audit Platform (Completado)
            [
                'project_id' => 12,
                'team_assignments' => [
                    ['team_id' => 8, 'assigned_at' => now()->subMonths(14)], // Security
                    ['team_id' => 2, 'assigned_at' => now()->subMonths(13)], // Backend
                    ['team_id' => 3, 'assigned_at' => now()->subMonths(10)], // QA
                ]
            ],
            // Payment Gateway Integration (Completado)
            [
                'project_id' => 13,
                'team_assignments' => [
                    ['team_id' => 11, 'assigned_at' => now()->subMonths(10)], // Integration
                    ['team_id' => 2, 'assigned_at' => now()->subMonths(9)], // Backend
                    ['team_id' => 8, 'assigned_at' => now()->subMonths(8)], // Security
                ]
            ],
            // Performance Optimization Study
            [
                'project_id' => 14,
                'team_assignments' => [
                    ['team_id' => 10, 'assigned_at' => now()->subMonths(3)], // Research
                    ['team_id' => 2, 'assigned_at' => now()->subMonths(2)], // Backend
                    ['team_id' => 4, 'assigned_at' => now()->subMonths(2)], // DevOps
                ]
            ],
            // API Standardization Initiative
            [
                'project_id' => 15,
                'team_assignments' => [
                    ['team_id' => 2, 'assigned_at' => now()->subMonths(2)], // Backend
                    ['team_id' => 12, 'assigned_at' => now()->subMonths(2)], // Platform
                    ['team_id' => 11, 'assigned_at' => now()->subMonths(1)], // Integration
                ]
            ],
            // Microservices POC
            [
                'project_id' => 16,
                'team_assignments' => [
                    ['team_id' => 2, 'assigned_at' => now()->subWeeks(6)], // Backend
                    ['team_id' => 4, 'assigned_at' => now()->subWeeks(5)], // DevOps
                ]
            ],
            // Dark Mode Implementation
            [
                'project_id' => 17,
                'team_assignments' => [
                    ['team_id' => 1, 'assigned_at' => now()->subWeeks(4)], // Frontend
                    ['team_id' => 9, 'assigned_at' => now()->subWeeks(4)], // Design
                    ['team_id' => 6, 'assigned_at' => now()->subWeeks(3)], // Mobile
                ]
            ],
            // Accessibility Compliance
            [
                'project_id' => 18,
                'team_assignments' => [
                    ['team_id' => 9, 'assigned_at' => now()->subDays(5)], // Design
                    ['team_id' => 1, 'assigned_at' => now()->subDays(3)], // Frontend
                ]
            ],
            // Database Optimization
            [
                'project_id' => 19,
                'team_assignments' => [
                    ['team_id' => 2, 'assigned_at' => now()->subMonths(1)], // Backend
                    ['team_id' => 4, 'assigned_at' => now()->subWeeks(3)], // DevOps
                ]
            ],
            // Documentation Overhaul
            [
                'project_id' => 20,
                'team_assignments' => [
                    ['team_id' => 1, 'assigned_at' => now()->subWeeks(3)], // Frontend
                    ['team_id' => 2, 'assigned_at' => now()->subWeeks(3)], // Backend
                    ['team_id' => 6, 'assigned_at' => now()->subWeeks(2)], // Mobile
                    ['team_id' => 5, 'assigned_at' => now()->subWeeks(2)], // Product
                ]
            ],
        ];

        // Insertar asignaciones de teams a proyectos
        $projectTeamInserts = [];
        foreach ($projectTeamAssignments as $projectData) {
            foreach ($projectData['team_assignments'] as $assignment) {
                $projectTeamInserts[] = [
                    'project_id' => $projectData['project_id'],
                    'team_id' => $assignment['team_id'],
                    'assigned_at' => $assignment['assigned_at'],
                    'created_at' => $assignment['assigned_at'],
                    'updated_at' => $assignment['assigned_at']->addDays(rand(0, 30)),
                ];
            }
        }

        // Insertar todas las asignaciones
        $chunks = array_chunk($projectTeamInserts, 25);
        foreach ($chunks as $chunk) {
            DB::table('project_team')->insert($chunk);
        }
    }
}
