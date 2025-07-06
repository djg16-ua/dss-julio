<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuleSeeder extends Seeder
{
    public function run()
    {
        // Obtener proyectos
        $projects = DB::table('projects')->get()->keyBy('id');

        // Definir módulos por proyecto de manera realista
        $modulesByProject = [
            // E-commerce Platform (Proyecto 1) - Muchos módulos
            1 => [
                ['name' => 'Authentication', 'description' => 'User authentication and authorization system', 'priority' => 'HIGH', 'category' => 'DEVELOPMENT', 'status' => 'DONE', 'is_core' => true],
                ['name' => 'Payment System', 'description' => 'Payment processing and gateway integration', 'priority' => 'HIGH', 'category' => 'INTEGRATION', 'status' => 'ACTIVE', 'is_core' => true],
                ['name' => 'User Interface', 'description' => 'Frontend components and user experience', 'priority' => 'MEDIUM', 'category' => 'DESIGN', 'status' => 'ACTIVE', 'is_core' => false],
                ['name' => 'Product Catalog', 'description' => 'Product management and catalog system', 'priority' => 'HIGH', 'category' => 'DEVELOPMENT', 'status' => 'ACTIVE', 'is_core' => true],
                ['name' => 'Shopping Cart', 'description' => 'Shopping cart and checkout functionality', 'priority' => 'HIGH', 'category' => 'DEVELOPMENT', 'status' => 'ACTIVE', 'is_core' => true],
                ['name' => 'Order Management', 'description' => 'Order processing and fulfillment', 'priority' => 'HIGH', 'category' => 'DEVELOPMENT', 'status' => 'PENDING', 'is_core' => true],
                ['name' => 'Inventory System', 'description' => 'Stock management and tracking', 'priority' => 'MEDIUM', 'category' => 'DEVELOPMENT', 'status' => 'PENDING', 'is_core' => false],
                ['name' => 'Search Engine', 'description' => 'Product search and filtering', 'priority' => 'MEDIUM', 'category' => 'DEVELOPMENT', 'status' => 'ACTIVE', 'is_core' => false],
                ['name' => 'Reviews System', 'description' => 'Customer reviews and ratings', 'priority' => 'LOW', 'category' => 'DEVELOPMENT', 'status' => 'PENDING', 'is_core' => false],
                ['name' => 'Recommendation Engine', 'description' => 'AI-powered product recommendations', 'priority' => 'LOW', 'category' => 'INTEGRATION', 'status' => 'PENDING', 'is_core' => false],
                ['name' => 'Analytics Module', 'description' => 'Sales and user behavior analytics', 'priority' => 'MEDIUM', 'category' => 'DEVELOPMENT', 'status' => 'ACTIVE', 'is_core' => false],
                ['name' => 'Admin Dashboard', 'description' => 'Administrative interface and controls', 'priority' => 'HIGH', 'category' => 'DESIGN', 'status' => 'ACTIVE', 'is_core' => true],
            ],

            // CRM System (Proyecto 2)
            2 => [
                ['name' => 'Authentication', 'description' => 'CRM user authentication system', 'priority' => 'HIGH', 'category' => 'DEVELOPMENT', 'status' => 'ACTIVE', 'is_core' => true],
                ['name' => 'Contact Management', 'description' => 'Customer and lead contact management', 'priority' => 'HIGH', 'category' => 'DEVELOPMENT', 'status' => 'ACTIVE', 'is_core' => true],
                ['name' => 'Sales Pipeline', 'description' => 'Sales process and pipeline management', 'priority' => 'HIGH', 'category' => 'DEVELOPMENT', 'status' => 'PENDING', 'is_core' => true],
                ['name' => 'Task Management', 'description' => 'Task and activity tracking', 'priority' => 'MEDIUM', 'category' => 'DEVELOPMENT', 'status' => 'PENDING', 'is_core' => false],
                ['name' => 'Reporting Module', 'description' => 'Sales reports and analytics', 'priority' => 'MEDIUM', 'category' => 'DEVELOPMENT', 'status' => 'PENDING', 'is_core' => false],
                ['name' => 'Email Integration', 'description' => 'Email communication and templates', 'priority' => 'MEDIUM', 'category' => 'INTEGRATION', 'status' => 'PENDING', 'is_core' => false],
                ['name' => 'Calendar Integration', 'description' => 'Appointment and meeting scheduling', 'priority' => 'LOW', 'category' => 'INTEGRATION', 'status' => 'PENDING', 'is_core' => false],
            ],

            // Mobile Banking App (Proyecto 3)
            3 => [
                ['name' => 'Biometric Authentication', 'description' => 'Fingerprint and face recognition login', 'priority' => 'HIGH', 'category' => 'DEVELOPMENT', 'status' => 'DONE', 'is_core' => true],
                ['name' => 'Account Dashboard', 'description' => 'Account overview and balance display', 'priority' => 'HIGH', 'category' => 'DESIGN', 'status' => 'DONE', 'is_core' => true],
                ['name' => 'Transaction Engine', 'description' => 'Money transfer and payment processing', 'priority' => 'HIGH', 'category' => 'DEVELOPMENT', 'status' => 'ACTIVE', 'is_core' => true],
                ['name' => 'Security Module', 'description' => 'Advanced security and fraud detection', 'priority' => 'HIGH', 'category' => 'DEVELOPMENT', 'status' => 'ACTIVE', 'is_core' => true],
                ['name' => 'Bill Payment', 'description' => 'Utility and service bill payments', 'priority' => 'MEDIUM', 'category' => 'DEVELOPMENT', 'status' => 'ACTIVE', 'is_core' => false],
                ['name' => 'ATM Locator', 'description' => 'ATM and branch location finder', 'priority' => 'LOW', 'category' => 'INTEGRATION', 'status' => 'PENDING', 'is_core' => false],
                ['name' => 'Investment Portal', 'description' => 'Investment tracking and management', 'priority' => 'MEDIUM', 'category' => 'DEVELOPMENT', 'status' => 'PENDING', 'is_core' => false],
                ['name' => 'Customer Support', 'description' => 'In-app customer service chat', 'priority' => 'MEDIUM', 'category' => 'INTEGRATION', 'status' => 'PENDING', 'is_core' => false],
            ],

            // Healthcare Portal (Proyecto 4)
            4 => [
                ['name' => 'Patient Registration', 'description' => 'Patient onboarding and profile management', 'priority' => 'HIGH', 'category' => 'DEVELOPMENT', 'status' => 'ACTIVE', 'is_core' => true],
                ['name' => 'Appointment Scheduler', 'description' => 'Medical appointment booking system', 'priority' => 'HIGH', 'category' => 'DEVELOPMENT', 'status' => 'ACTIVE', 'is_core' => true],
                ['name' => 'Medical Records', 'description' => 'Electronic health records management', 'priority' => 'HIGH', 'category' => 'DEVELOPMENT', 'status' => 'PENDING', 'is_core' => true],
                ['name' => 'Doctor Portal', 'description' => 'Healthcare provider interface', 'priority' => 'HIGH', 'category' => 'DESIGN', 'status' => 'PENDING', 'is_core' => true],
                ['name' => 'Prescription System', 'description' => 'Digital prescription management', 'priority' => 'MEDIUM', 'category' => 'DEVELOPMENT', 'status' => 'PENDING', 'is_core' => false],
                ['name' => 'Billing Module', 'description' => 'Medical billing and insurance claims', 'priority' => 'MEDIUM', 'category' => 'DEVELOPMENT', 'status' => 'PENDING', 'is_core' => false],
                ['name' => 'Telemedicine', 'description' => 'Video consultation platform', 'priority' => 'LOW', 'category' => 'INTEGRATION', 'status' => 'PENDING', 'is_core' => false],
            ],

            // Analytics Dashboard (Proyecto 5)
            5 => [
                ['name' => 'Data Ingestion', 'description' => 'Data collection and processing pipeline', 'priority' => 'HIGH', 'category' => 'DEVELOPMENT', 'status' => 'ACTIVE', 'is_core' => true],
                ['name' => 'Visualization Engine', 'description' => 'Charts and graphs rendering', 'priority' => 'HIGH', 'category' => 'DEVELOPMENT', 'status' => 'ACTIVE', 'is_core' => true],
                ['name' => 'Report Builder', 'description' => 'Custom report creation tool', 'priority' => 'MEDIUM', 'category' => 'DEVELOPMENT', 'status' => 'PENDING', 'is_core' => false],
                ['name' => 'Real-time Monitoring', 'description' => 'Live data streaming and alerts', 'priority' => 'MEDIUM', 'category' => 'DEVELOPMENT', 'status' => 'ACTIVE', 'is_core' => false],
                ['name' => 'Data Export', 'description' => 'Data export in various formats', 'priority' => 'LOW', 'category' => 'DEVELOPMENT', 'status' => 'PENDING', 'is_core' => false],
            ],

            // AI Chatbot Platform (Proyecto 6)
            6 => [
                ['name' => 'NLP Engine', 'description' => 'Natural language processing core', 'priority' => 'HIGH', 'category' => 'DEVELOPMENT', 'status' => 'PENDING', 'is_core' => true],
                ['name' => 'Conversation Manager', 'description' => 'Dialog flow and context management', 'priority' => 'HIGH', 'category' => 'DEVELOPMENT', 'status' => 'PENDING', 'is_core' => true],
                ['name' => 'Training Interface', 'description' => 'Bot training and configuration', 'priority' => 'MEDIUM', 'category' => 'DESIGN', 'status' => 'PENDING', 'is_core' => false],
                ['name' => 'Integration APIs', 'description' => 'Third-party platform integrations', 'priority' => 'MEDIUM', 'category' => 'INTEGRATION', 'status' => 'PENDING', 'is_core' => false],
            ],

            // Inventory Management (Proyecto 7)
            7 => [
                ['name' => 'Stock Tracking', 'description' => 'Real-time inventory tracking', 'priority' => 'HIGH', 'category' => 'DEVELOPMENT', 'status' => 'PENDING', 'is_core' => true],
                ['name' => 'Warehouse Management', 'description' => 'Warehouse operations and layout', 'priority' => 'HIGH', 'category' => 'DEVELOPMENT', 'status' => 'PENDING', 'is_core' => true],
                ['name' => 'Barcode Scanner', 'description' => 'Mobile barcode scanning integration', 'priority' => 'MEDIUM', 'category' => 'INTEGRATION', 'status' => 'PENDING', 'is_core' => false],
                ['name' => 'Supplier Management', 'description' => 'Vendor and supplier tracking', 'priority' => 'MEDIUM', 'category' => 'DEVELOPMENT', 'status' => 'PENDING', 'is_core' => false],
            ],

            // Proyectos más pequeños con menos módulos
            8 => [
                ['name' => 'Social Media Connector', 'description' => 'Multi-platform social media integration', 'priority' => 'HIGH', 'category' => 'INTEGRATION', 'status' => 'PENDING', 'is_core' => true],
                ['name' => 'Content Scheduler', 'description' => 'Social media post scheduling', 'priority' => 'MEDIUM', 'category' => 'DEVELOPMENT', 'status' => 'PENDING', 'is_core' => false],
                ['name' => 'Analytics Aggregator', 'description' => 'Social media metrics collection', 'priority' => 'MEDIUM', 'category' => 'DEVELOPMENT', 'status' => 'PENDING', 'is_core' => false],
            ],

            9 => [
                ['name' => 'Course Builder', 'description' => 'Online course creation tools', 'priority' => 'HIGH', 'category' => 'DEVELOPMENT', 'status' => 'PENDING', 'is_core' => true],
                ['name' => 'Student Portal', 'description' => 'Student learning interface', 'priority' => 'HIGH', 'category' => 'DESIGN', 'status' => 'PENDING', 'is_core' => true],
                ['name' => 'Assessment Engine', 'description' => 'Quiz and exam system', 'priority' => 'MEDIUM', 'category' => 'DEVELOPMENT', 'status' => 'PENDING', 'is_core' => false],
                ['name' => 'Grade Management', 'description' => 'Grading and progress tracking', 'priority' => 'MEDIUM', 'category' => 'DEVELOPMENT', 'status' => 'PENDING', 'is_core' => false],
            ],

            10 => [
                ['name' => 'Device Registry', 'description' => 'IoT device registration and management', 'priority' => 'HIGH', 'category' => 'DEVELOPMENT', 'status' => 'PENDING', 'is_core' => true],
                ['name' => 'Data Collector', 'description' => 'IoT sensor data aggregation', 'priority' => 'HIGH', 'category' => 'DEVELOPMENT', 'status' => 'PENDING', 'is_core' => true],
                ['name' => 'Control Interface', 'description' => 'Remote device control panel', 'priority' => 'MEDIUM', 'category' => 'DESIGN', 'status' => 'PENDING', 'is_core' => false],
            ],

            // Proyectos completados
            11 => [
                ['name' => 'Migration Tools', 'description' => 'Data migration utilities', 'priority' => 'HIGH', 'category' => 'DEVELOPMENT', 'status' => 'DONE', 'is_core' => true],
                ['name' => 'Legacy Adapter', 'description' => 'Legacy system compatibility layer', 'priority' => 'HIGH', 'category' => 'INTEGRATION', 'status' => 'DONE', 'is_core' => true],
                ['name' => 'Validation Suite', 'description' => 'Data validation and verification', 'priority' => 'MEDIUM', 'category' => 'DEVELOPMENT', 'status' => 'DONE', 'is_core' => false],
            ],

            12 => [
                ['name' => 'Vulnerability Scanner', 'description' => 'Automated security scanning', 'priority' => 'HIGH', 'category' => 'DEVELOPMENT', 'status' => 'DONE', 'is_core' => true],
                ['name' => 'Audit Logger', 'description' => 'Security event logging and analysis', 'priority' => 'HIGH', 'category' => 'DEVELOPMENT', 'status' => 'DONE', 'is_core' => true],
                ['name' => 'Report Generator', 'description' => 'Security audit report creation', 'priority' => 'MEDIUM', 'category' => 'DEVELOPMENT', 'status' => 'DONE', 'is_core' => false],
            ],

            // Payment Gateway Integration (Proyecto 13) - Completado
            13 => [
                ['name' => 'Gateway Abstraction Layer', 'description' => 'Universal payment gateway interface', 'priority' => 'HIGH', 'category' => 'DEVELOPMENT', 'status' => 'DONE', 'is_core' => true],
                ['name' => 'Multi-Gateway Support', 'description' => 'Support for Stripe, PayPal, Square, and others', 'priority' => 'HIGH', 'category' => 'INTEGRATION', 'status' => 'DONE', 'is_core' => true],
                ['name' => 'Fraud Detection Engine', 'description' => 'AI-powered fraud detection and prevention', 'priority' => 'HIGH', 'category' => 'DEVELOPMENT', 'status' => 'DONE', 'is_core' => true],
                ['name' => 'Transaction Logging', 'description' => 'Comprehensive transaction audit trail', 'priority' => 'MEDIUM', 'category' => 'DEVELOPMENT', 'status' => 'DONE', 'is_core' => false],
                ['name' => 'Webhook Management', 'description' => 'Payment webhook handling and verification', 'priority' => 'MEDIUM', 'category' => 'INTEGRATION', 'status' => 'DONE', 'is_core' => false],
                ['name' => 'PCI Compliance Tools', 'description' => 'PCI DSS compliance verification and monitoring', 'priority' => 'HIGH', 'category' => 'DEVELOPMENT', 'status' => 'DONE', 'is_core' => true],
                ['name' => 'Payment Analytics', 'description' => 'Payment performance and failure analytics', 'priority' => 'LOW', 'category' => 'DEVELOPMENT', 'status' => 'DONE', 'is_core' => false],
            ],

            // Proyectos internos más pequeños
            14 => [
                ['name' => 'Performance Profiler', 'description' => 'Application performance monitoring', 'priority' => 'HIGH', 'category' => 'DEVELOPMENT', 'status' => 'ACTIVE', 'is_core' => true],
                ['name' => 'Optimization Engine', 'description' => 'Automated performance optimization', 'priority' => 'MEDIUM', 'category' => 'DEVELOPMENT', 'status' => 'PENDING', 'is_core' => false],
            ],

            15 => [
                ['name' => 'API Documentation', 'description' => 'Automated API documentation generation', 'priority' => 'HIGH', 'category' => 'DEVELOPMENT', 'status' => 'ACTIVE', 'is_core' => true],
                ['name' => 'Standard Templates', 'description' => 'Reusable API templates and patterns', 'priority' => 'MEDIUM', 'category' => 'DEVELOPMENT', 'status' => 'PENDING', 'is_core' => false],
            ],

            16 => [
                ['name' => 'Service Registry', 'description' => 'Microservice discovery and registration', 'priority' => 'HIGH', 'category' => 'DEVELOPMENT', 'status' => 'ACTIVE', 'is_core' => true],
                ['name' => 'API Gateway', 'description' => 'Centralized API routing and management', 'priority' => 'HIGH', 'category' => 'DEVELOPMENT', 'status' => 'ACTIVE', 'is_core' => true],
            ],

            17 => [
                ['name' => 'Theme Engine', 'description' => 'Dynamic theme switching system', 'priority' => 'HIGH', 'category' => 'DEVELOPMENT', 'status' => 'ACTIVE', 'is_core' => true],
                ['name' => 'Color Palette', 'description' => 'Dark mode color schemes', 'priority' => 'MEDIUM', 'category' => 'DESIGN', 'status' => 'ACTIVE', 'is_core' => false],
            ],

            19 => [
                ['name' => 'Query Optimizer', 'description' => 'Database query performance optimization', 'priority' => 'HIGH', 'category' => 'DEVELOPMENT', 'status' => 'ACTIVE', 'is_core' => true],
                ['name' => 'Index Manager', 'description' => 'Database index optimization', 'priority' => 'MEDIUM', 'category' => 'DEVELOPMENT', 'status' => 'PENDING', 'is_core' => false],
            ],

            20 => [
                ['name' => 'Documentation Portal', 'description' => 'Centralized documentation website', 'priority' => 'HIGH', 'category' => 'DESIGN', 'status' => 'ACTIVE', 'is_core' => true],
                ['name' => 'Content Management', 'description' => 'Documentation content management system', 'priority' => 'MEDIUM', 'category' => 'DEVELOPMENT', 'status' => 'PENDING', 'is_core' => false],
            ],
        ];

        // Crear módulos
        $moduleInserts = [];
        $moduleId = 1;

        foreach ($modulesByProject as $projectId => $modules) {
            foreach ($modules as $moduleData) {
                $createdAt = $projects[$projectId]->created_at;
                $moduleInserts[] = [
                    'id' => $moduleId++,
                    'name' => $moduleData['name'],
                    'description' => $moduleData['description'],
                    'priority' => $moduleData['priority'],
                    'category' => $moduleData['category'],
                    'status' => $moduleData['status'],
                    'project_id' => $projectId,
                    'is_core' => $moduleData['is_core'],
                    'created_at' => $createdAt,
                    'updated_at' => now()->subDays(rand(0, 30)),
                ];
            }
        }

        // Insertar módulos en batches
        $chunks = array_chunk($moduleInserts, 25);
        foreach ($chunks as $chunk) {
            DB::table('modules')->insert($chunk);
        }

        // Asignar teams a módulos de manera realista
        $moduleTeamAssignments = [
            // E-commerce Platform modules
            ['module_name' => 'Authentication', 'project_id' => 1, 'teams' => [2, 8]], // Backend, Security
            ['module_name' => 'Payment System', 'project_id' => 1, 'teams' => [2, 8, 11]], // Backend, Security, Integration
            ['module_name' => 'User Interface', 'project_id' => 1, 'teams' => [1, 9]], // Frontend, Design
            ['module_name' => 'Product Catalog', 'project_id' => 1, 'teams' => [1, 2]], // Frontend, Backend
            ['module_name' => 'Shopping Cart', 'project_id' => 1, 'teams' => [1, 2]], // Frontend, Backend
            ['module_name' => 'Order Management', 'project_id' => 1, 'teams' => [2, 3]], // Backend, QA
            ['module_name' => 'Inventory System', 'project_id' => 1, 'teams' => [2]], // Backend
            ['module_name' => 'Search Engine', 'project_id' => 1, 'teams' => [2, 7]], // Backend, Data
            ['module_name' => 'Reviews System', 'project_id' => 1, 'teams' => [1, 2]], // Frontend, Backend
            ['module_name' => 'Recommendation Engine', 'project_id' => 1, 'teams' => [7, 10]], // Data, Research
            ['module_name' => 'Analytics Module', 'project_id' => 1, 'teams' => [7, 1]], // Data, Frontend
            ['module_name' => 'Admin Dashboard', 'project_id' => 1, 'teams' => [1, 9]], // Frontend, Design

            // CRM System modules
            ['module_name' => 'Authentication', 'project_id' => 2, 'teams' => [2]], // Backend
            ['module_name' => 'Contact Management', 'project_id' => 2, 'teams' => [1, 2]], // Frontend, Backend
            ['module_name' => 'Sales Pipeline', 'project_id' => 2, 'teams' => [1, 2, 5]], // Frontend, Backend, Product
            ['module_name' => 'Task Management', 'project_id' => 2, 'teams' => [1, 2]], // Frontend, Backend
            ['module_name' => 'Reporting Module', 'project_id' => 2, 'teams' => [7, 1]], // Data, Frontend
            ['module_name' => 'Email Integration', 'project_id' => 2, 'teams' => [11, 2]], // Integration, Backend
            ['module_name' => 'Calendar Integration', 'project_id' => 2, 'teams' => [11]], // Integration

            // Mobile Banking App modules
            ['module_name' => 'Biometric Authentication', 'project_id' => 3, 'teams' => [6, 8]], // Mobile, Security
            ['module_name' => 'Account Dashboard', 'project_id' => 3, 'teams' => [6, 9]], // Mobile, Design
            ['module_name' => 'Transaction Engine', 'project_id' => 3, 'teams' => [6, 2, 8]], // Mobile, Backend, Security
            ['module_name' => 'Security Module', 'project_id' => 3, 'teams' => [8, 2]], // Security, Backend
            ['module_name' => 'Bill Payment', 'project_id' => 3, 'teams' => [6, 11]], // Mobile, Integration
            ['module_name' => 'ATM Locator', 'project_id' => 3, 'teams' => [6, 11]], // Mobile, Integration
            ['module_name' => 'Investment Portal', 'project_id' => 3, 'teams' => [6, 2]], // Mobile, Backend
            ['module_name' => 'Customer Support', 'project_id' => 3, 'teams' => [6, 11]], // Mobile, Integration

            // Payment Gateway Integration modules
            ['module_name' => 'Gateway Abstraction Layer', 'project_id' => 13, 'teams' => [11, 2]], // Integration, Backend
            ['module_name' => 'Multi-Gateway Support', 'project_id' => 13, 'teams' => [11, 2, 8]], // Integration, Backend, Security
            ['module_name' => 'Fraud Detection Engine', 'project_id' => 13, 'teams' => [7, 8, 2]], // Data, Security, Backend
            ['module_name' => 'Transaction Logging', 'project_id' => 13, 'teams' => [2, 8]], // Backend, Security
            ['module_name' => 'Webhook Management', 'project_id' => 13, 'teams' => [11, 2]], // Integration, Backend
            ['module_name' => 'PCI Compliance Tools', 'project_id' => 13, 'teams' => [8, 2]], // Security, Backend
            ['module_name' => 'Payment Analytics', 'project_id' => 13, 'teams' => [7, 1]], // Data, Frontend
        ];

        // Insertar asignaciones de teams a módulos
        $moduleTeamInserts = [];
        foreach ($moduleTeamAssignments as $assignment) {
            $module = DB::table('modules')
                ->where('name', $assignment['module_name'])
                ->where('project_id', $assignment['project_id'])
                ->first();

            if ($module) {
                foreach ($assignment['teams'] as $teamId) {
                    $moduleTeamInserts[] = [
                        'module_id' => $module->id,
                        'team_id' => $teamId,
                        'assigned_at' => now()->subDays(rand(7, 60)),
                        'created_at' => now()->subDays(rand(7, 60)),
                        'updated_at' => now()->subDays(rand(0, 30)),
                    ];
                }
            }
        }

        // Insertar asignaciones
        if (!empty($moduleTeamInserts)) {
            $chunks = array_chunk($moduleTeamInserts, 25);
            foreach ($chunks as $chunk) {
                DB::table('module_team')->insert($chunk);
            }
        }
    }
}
