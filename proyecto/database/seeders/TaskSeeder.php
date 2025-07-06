<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
    public function run()
    {
        // Obtener usuarios clave
        $users = DB::table('users')->get()->keyBy('email');
        $john = $users->get('john@taskflow.com');
        $jane = $users->get('jane@taskflow.com');
        $bob = $users->get('bob@taskflow.com');
        $admin = $users->get('admin@taskflow.com');

        // Obtener módulos organizados por proyecto
        $modules = DB::table('modules')
            ->join('projects', 'modules.project_id', '=', 'projects.id')
            ->select('modules.*', 'projects.title as project_title')
            ->get()
            ->groupBy('project_id');

        // Definir tareas para diferentes módulos
        $taskTemplates = [
            // Authentication Module Tasks
            'Authentication' => [
                ['title' => 'Create login form', 'description' => 'Design and implement user login interface with validation', 'priority' => 'HIGH'],
                ['title' => 'User registration system', 'description' => 'Build complete user registration flow with email verification', 'priority' => 'HIGH'],
                ['title' => 'JWT implementation', 'description' => 'Implement JWT token-based authentication system', 'priority' => 'MEDIUM'],
                ['title' => 'Password reset functionality', 'description' => 'Create secure password reset flow with email verification', 'priority' => 'MEDIUM'],
                ['title' => 'Two-factor authentication', 'description' => 'Implement 2FA using TOTP or SMS verification', 'priority' => 'LOW'],
                ['title' => 'Social login integration', 'description' => 'Add Google, Facebook, and GitHub OAuth login options', 'priority' => 'LOW'],
                ['title' => 'Session management', 'description' => 'Implement secure session handling and timeout logic', 'priority' => 'MEDIUM'],
                ['title' => 'User role management', 'description' => 'Create role-based access control system', 'priority' => 'MEDIUM'],
            ],

            // Payment System Tasks
            'Payment System' => [
                ['title' => 'Stripe integration', 'description' => 'Integrate Stripe payment gateway for card processing', 'priority' => 'HIGH'],
                ['title' => 'PayPal integration', 'description' => 'Add PayPal payment option for checkout', 'priority' => 'MEDIUM'],
                ['title' => 'Payment validation', 'description' => 'Implement payment verification and fraud detection', 'priority' => 'HIGH'],
                ['title' => 'Refund system', 'description' => 'Build automated refund processing system', 'priority' => 'MEDIUM'],
                ['title' => 'Payment analytics', 'description' => 'Create payment metrics and reporting dashboard', 'priority' => 'LOW'],
                ['title' => 'Currency conversion', 'description' => 'Add multi-currency support with real-time exchange rates', 'priority' => 'LOW'],
                ['title' => 'Subscription billing', 'description' => 'Implement recurring payment and subscription management', 'priority' => 'MEDIUM'],
            ],

            // User Interface Tasks
            'User Interface' => [
                ['title' => 'Homepage design', 'description' => 'Design and implement main landing page layout', 'priority' => 'HIGH'],
                ['title' => 'Navigation menu', 'description' => 'Create responsive navigation with mobile support', 'priority' => 'HIGH'],
                ['title' => 'Product page layout', 'description' => 'Design individual product display pages', 'priority' => 'MEDIUM'],
                ['title' => 'Shopping cart UI', 'description' => 'Create intuitive shopping cart interface', 'priority' => 'HIGH'],
                ['title' => 'Checkout flow design', 'description' => 'Design streamlined checkout process', 'priority' => 'HIGH'],
                ['title' => 'User profile pages', 'description' => 'Create user account and profile management pages', 'priority' => 'MEDIUM'],
                ['title' => 'Search interface', 'description' => 'Design advanced search and filter interface', 'priority' => 'MEDIUM'],
                ['title' => 'Mobile optimization', 'description' => 'Optimize all pages for mobile devices', 'priority' => 'MEDIUM'],
                ['title' => 'Loading animations', 'description' => 'Add smooth loading states and animations', 'priority' => 'LOW'],
                ['title' => 'Error page designs', 'description' => 'Create custom 404 and error page designs', 'priority' => 'LOW'],
            ],

            // Product Catalog Tasks
            'Product Catalog' => [
                ['title' => 'Product database schema', 'description' => 'Design and implement product data structure', 'priority' => 'HIGH'],
                ['title' => 'Category management', 'description' => 'Create hierarchical product category system', 'priority' => 'HIGH'],
                ['title' => 'Product image handling', 'description' => 'Implement multiple image upload and optimization', 'priority' => 'MEDIUM'],
                ['title' => 'Inventory tracking', 'description' => 'Add real-time stock level monitoring', 'priority' => 'HIGH'],
                ['title' => 'Product variants', 'description' => 'Support for size, color, and other product variants', 'priority' => 'MEDIUM'],
                ['title' => 'Bulk product import', 'description' => 'Create CSV/Excel product import functionality', 'priority' => 'LOW'],
                ['title' => 'Product SEO optimization', 'description' => 'Add meta tags and SEO-friendly URLs', 'priority' => 'LOW'],
            ],

            // Contact Management Tasks
            'Contact Management' => [
                ['title' => 'Contact database design', 'description' => 'Design comprehensive contact data structure', 'priority' => 'HIGH'],
                ['title' => 'Contact import/export', 'description' => 'Add CSV and vCard import/export functionality', 'priority' => 'MEDIUM'],
                ['title' => 'Contact search and filter', 'description' => 'Implement advanced contact search capabilities', 'priority' => 'MEDIUM'],
                ['title' => 'Contact history tracking', 'description' => 'Track all interactions and communication history', 'priority' => 'MEDIUM'],
                ['title' => 'Duplicate detection', 'description' => 'Automatic duplicate contact detection and merging', 'priority' => 'LOW'],
                ['title' => 'Contact segmentation', 'description' => 'Create dynamic contact groups and segments', 'priority' => 'LOW'],
            ],

            // Mobile-specific Tasks
            'Biometric Authentication' => [
                ['title' => 'Fingerprint integration', 'description' => 'Implement fingerprint authentication for iOS and Android', 'priority' => 'HIGH'],
                ['title' => 'Face ID integration', 'description' => 'Add Face ID support for compatible devices', 'priority' => 'HIGH'],
                ['title' => 'Biometric fallback', 'description' => 'Create secure fallback for non-biometric devices', 'priority' => 'MEDIUM'],
                ['title' => 'Security testing', 'description' => 'Comprehensive security testing of biometric features', 'priority' => 'HIGH'],
            ],

            // Data and Analytics Tasks
            'Data Ingestion' => [
                ['title' => 'API data connectors', 'description' => 'Create connectors for various data sources', 'priority' => 'HIGH'],
                ['title' => 'Real-time data streaming', 'description' => 'Implement real-time data processing pipeline', 'priority' => 'HIGH'],
                ['title' => 'Data validation rules', 'description' => 'Create comprehensive data quality validation', 'priority' => 'MEDIUM'],
                ['title' => 'Error handling system', 'description' => 'Build robust error handling for data ingestion', 'priority' => 'MEDIUM'],
                ['title' => 'Data transformation engine', 'description' => 'Implement data cleaning and transformation tools', 'priority' => 'LOW'],
            ],

            // DevOps and Infrastructure Tasks
            'Migration Tools' => [
                ['title' => 'Database migration scripts', 'description' => 'Create automated database migration tools', 'priority' => 'HIGH'],
                ['title' => 'Data validation checks', 'description' => 'Implement data integrity validation during migration', 'priority' => 'HIGH'],
                ['title' => 'Rollback mechanisms', 'description' => 'Create safe rollback procedures for failed migrations', 'priority' => 'MEDIUM'],
                ['title' => 'Performance monitoring', 'description' => 'Monitor migration performance and optimize bottlenecks', 'priority' => 'MEDIUM'],
            ],

            // Testing Tasks
            'Testing' => [
                ['title' => 'Unit test coverage', 'description' => 'Achieve 80%+ unit test coverage for core modules', 'priority' => 'HIGH'],
                ['title' => 'Integration testing', 'description' => 'Create comprehensive integration test suite', 'priority' => 'HIGH'],
                ['title' => 'End-to-end testing', 'description' => 'Implement automated E2E testing with Selenium/Cypress', 'priority' => 'MEDIUM'],
                ['title' => 'Performance testing', 'description' => 'Load testing and performance benchmarking', 'priority' => 'MEDIUM'],
                ['title' => 'Security testing', 'description' => 'Automated security vulnerability scanning', 'priority' => 'HIGH'],
                ['title' => 'Mobile testing', 'description' => 'Device-specific testing across iOS and Android', 'priority' => 'MEDIUM'],
                ['title' => 'API testing', 'description' => 'Comprehensive REST API testing suite', 'priority' => 'MEDIUM'],
            ],
        ];

        // Crear tareas para cada módulo
        $taskInserts = [];
        $taskId = 1;
        $allUsers = $users->values()->toArray();

        foreach ($modules as $projectId => $projectModules) {
            foreach ($projectModules as $module) {
                // Obtener templates de tareas para este módulo
                $templates = $taskTemplates[$module->name] ?? $taskTemplates['Testing'] ?? [];

                // Si no hay templates específicos, crear tareas genéricas
                if (empty($templates)) {
                    $templates = [
                        ['title' => 'Initial setup', 'description' => "Set up basic structure for {$module->name} module", 'priority' => 'HIGH'],
                        ['title' => 'Core implementation', 'description' => "Implement core functionality for {$module->name}", 'priority' => 'HIGH'],
                        ['title' => 'Testing and validation', 'description' => "Test and validate {$module->name} functionality", 'priority' => 'MEDIUM'],
                        ['title' => 'Documentation', 'description' => "Create documentation for {$module->name} module", 'priority' => 'LOW'],
                        ['title' => 'Code review', 'description' => "Code review and refactoring for {$module->name}", 'priority' => 'MEDIUM'],
                    ];
                }

                // Determinar cuántas tareas crear para este módulo (variable según status)
                $taskCount = match ($module->status) {
                    'DONE' => count($templates), // Todos las tareas
                    'ACTIVE' => min(rand(3, 7), count($templates)), // Algunas tareas
                    'PENDING' => min(rand(1, 4), count($templates)), // Pocas tareas
                    default => min(rand(2, 5), count($templates))
                };

                // Seleccionar tareas aleatoriamente de los templates
                $selectedTemplates = array_slice($templates, 0, $taskCount);

                foreach ($selectedTemplates as $index => $template) {
                    // Determinar estado de la tarea basado en el estado del módulo
                    $status = $this->determineTaskStatus($module->status, $index, $taskCount);

                    // Seleccionar usuario asignado (más probabilidad para usuarios principales)
                    $assignedUser = $this->selectAssignedUser($allUsers, $module->category);
                    $createdBy = rand(0, 10) > 7 ? $admin : $assignedUser; // 30% admin, 70% assigned user

                    // Calcular fechas
                    $createdAt = $this->calculateCreatedDate($module->created_at);
                    $endDate = $this->calculateEndDate($createdAt, $template['priority'], $status);
                    $completedAt = $status === 'DONE' ? clone $endDate : null;
                    if ($completedAt) {
                        $completedAt->sub(new \DateInterval('P' . rand(0, 5) . 'D'));
                    }

                    // Determinar dependencias (algunas tareas dependen de otras)
                    $dependsOn = null;
                    if ($index > 0 && rand(0, 100) < 30) { // 30% de probabilidad de dependencia
                        $dependsOn = $taskId - rand(1, min($index, 3)); // Depende de una tarea anterior
                    }

                    $taskInserts[] = [
                        'id' => $taskId++,
                        'title' => $template['title'],
                        'description' => $template['description'],
                        'status' => $status,
                        'priority' => $template['priority'],
                        'end_date' => $endDate,
                        'completed_at' => $completedAt,
                        'module_id' => $module->id,
                        'assigned_to' => $assignedUser->id,
                        'created_by' => $createdBy->id,
                        'depends_on' => $dependsOn,
                        'created_at' => $createdAt,
                        'updated_at' => now()->subDays(rand(0, 30)),
                    ];
                }
            }
        }

        // Insertar tareas en batches
        $chunks = array_chunk($taskInserts, 50);
        foreach ($chunks as $chunk) {
            DB::table('tasks')->insert($chunk);
        }
    }

    private function determineTaskStatus($moduleStatus, $taskIndex, $totalTasks)
    {
        switch ($moduleStatus) {
            case 'DONE':
                return 'DONE';
            case 'ACTIVE':
                $progress = $taskIndex / $totalTasks;
                if ($progress < 0.4) return 'DONE';
                if ($progress < 0.7) return 'ACTIVE';
                return 'PENDING';
            case 'PENDING':
                if ($taskIndex === 0) return rand(0, 100) < 60 ? 'ACTIVE' : 'PENDING';
                return 'PENDING';
            default:
                return ['PENDING', 'ACTIVE', 'DONE'][rand(0, 2)];
        }
    }

    private function selectAssignedUser($users, $moduleCategory)
    {
        // Usuarios principales por categoría
        $categoryUsers = [
            'DEVELOPMENT' => ['john@taskflow.com', 'kevin.brown@taskflow.com', 'amanda.taylor@taskflow.com', 'daniel.kim@taskflow.com'],
            'DESIGN' => ['jane@taskflow.com', 'felix.ross@taskflow.com', 'iris.powell@taskflow.com', 'dante.bell@taskflow.com'],
            'INTEGRATION' => ['kai.sullivan@taskflow.com', 'wren.fisher@taskflow.com', 'rowan.cole@taskflow.com'],
            'TESTING' => ['bob@taskflow.com', 'ashley.miller@taskflow.com', 'tyler.wilson@taskflow.com'],
        ];

        $preferredEmails = $categoryUsers[$moduleCategory] ?? ['john@taskflow.com', 'jane@taskflow.com', 'bob@taskflow.com'];

        // 70% probabilidad de asignar a usuario preferido, 30% aleatorio
        if (rand(0, 100) < 70) {
            $email = $preferredEmails[array_rand($preferredEmails)];
            $user = collect($users)->firstWhere('email', $email);
            if ($user) return $user;
        }

        return $users[array_rand($users)];
    }

    private function calculateCreatedDate($moduleCreatedAt)
    {
        $moduleDate = new \DateTime($moduleCreatedAt);
        $daysAfterModule = rand(1, 30);
        $moduleDate->add(new \DateInterval("P{$daysAfterModule}D"));
        return $moduleDate;
    }

    private function calculateEndDate($createdAt, $priority, $status)
    {
        $baseDays = match ($priority) {
            'HIGH' => rand(3, 10),
            'MEDIUM' => rand(7, 21),
            'LOW' => rand(14, 45),
            default => rand(7, 14)
        };

        // Tareas completadas tienen fechas en el pasado
        if ($status === 'DONE') {
            $baseDays = rand(5, 30); // Días positivos que luego restamos
            $endDate = clone $createdAt;
            $endDate->sub(new \DateInterval("P{$baseDays}D"));
            return $endDate;
        }
        // Tareas activas pueden estar cerca del deadline
        elseif ($status === 'ACTIVE') {
            $modifier = rand(0, 1) ? 'add' : 'sub';
            $days = rand(1, 15);
            $endDate = clone $createdAt;
            $endDate->$modifier(new \DateInterval("P{$days}D"));
            return $endDate;
        }

        // Tareas pendientes en el futuro
        $endDate = clone $createdAt;
        $endDate->add(new \DateInterval("P{$baseDays}D"));
        return $endDate;
    }
}
