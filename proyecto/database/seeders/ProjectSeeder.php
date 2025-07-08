<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProjectSeeder extends Seeder
{
    public function run()
    {
        // Verificar que existan usuarios
        $users = User::all();
        if ($users->isEmpty()) {
            $this->command->warn('No hay usuarios en la base de datos. Ejecuta UserSeeder primero.');
            return;
        }

        $this->command->info('Creando proyectos...');

        // Obtener usuarios específicos para asignar como creadores
        $admin = User::where('email', 'admin@taskflow.com')->first();
        $john = User::where('email', 'john@taskflow.com')->first();
        $jane = User::where('email', 'jane@taskflow.com')->first();
        $michael = User::where('email', 'michael.rodriguez@taskflow.com')->first();
        $lisa = User::where('email', 'lisa@taskflow.com')->first();
        $robert = User::where('email', 'robert@taskflow.com')->first();

        // Usar el usuario admin como fallback si no existen los específicos
        $defaultCreator = $admin ?? $users->first();

        DB::beginTransaction();
        
        try {
            // 1. E-commerce Platform
            $project1 = Project::create([
                'title' => 'E-commerce Platform',
                'description' => 'Desarrollo de una plataforma de comercio electrónico completa con sistema de pagos, gestión de inventario y panel de administración.',
                'public' => true,
                'status' => 'ACTIVE',
                'start_date' => Carbon::now()->subMonths(3)->format('Y-m-d'),
                'end_date' => Carbon::now()->addMonths(6)->format('Y-m-d'),
                'created_by' => $john ? $john->id : $defaultCreator->id,
                'created_at' => Carbon::now()->subMonths(3),
                'updated_at' => Carbon::now()->subWeeks(1),
            ]);

            // 2. CRM System
            $project2 = Project::create([
                'title' => 'CRM System',
                'description' => 'Sistema de gestión de relaciones con clientes para automatizar procesos de ventas y marketing.',
                'public' => false,
                'status' => 'PENDING',
                'start_date' => Carbon::now()->addWeeks(2)->format('Y-m-d'),
                'end_date' => Carbon::now()->addMonths(8)->format('Y-m-d'),
                'created_by' => $jane ? $jane->id : $defaultCreator->id,
                'created_at' => Carbon::now()->subMonths(1),
                'updated_at' => Carbon::now()->subDays(5),
            ]);

            // 3. Mobile Banking App
            $project3 = Project::create([
                'title' => 'Mobile Banking App',
                'description' => 'Aplicación móvil para banca digital con autenticación biométrica y transferencias en tiempo real.',
                'public' => false,
                'status' => 'DONE',
                'start_date' => Carbon::now()->subMonths(8)->format('Y-m-d'),
                'end_date' => Carbon::now()->subMonths(1)->format('Y-m-d'),
                'created_by' => $michael ? $michael->id : $defaultCreator->id,
                'created_at' => Carbon::now()->subMonths(8),
                'updated_at' => Carbon::now()->subMonths(1),
            ]);

            // 4. Healthcare Portal
            $project4 = Project::create([
                'title' => 'Healthcare Portal',
                'description' => 'Portal web para gestión de citas médicas, historiales clínicos y telemedicina.',
                'public' => true,
                'status' => 'ACTIVE',
                'start_date' => Carbon::now()->subMonths(2)->format('Y-m-d'),
                'end_date' => Carbon::now()->addMonths(4)->format('Y-m-d'),
                'created_by' => $admin ? $admin->id : $defaultCreator->id,
                'created_at' => Carbon::now()->subMonths(2),
                'updated_at' => Carbon::now()->subDays(3),
            ]);

            // 5. Analytics Dashboard
            $project5 = Project::create([
                'title' => 'Analytics Dashboard',
                'description' => 'Dashboard de análisis de datos con visualizaciones interactivas y reportes automatizados.',
                'public' => true,
                'status' => 'PENDING',
                'start_date' => Carbon::now()->addMonths(1)->format('Y-m-d'),
                'end_date' => Carbon::now()->addMonths(7)->format('Y-m-d'),
                'created_by' => $jane ? $jane->id : $defaultCreator->id,
                'created_at' => Carbon::now()->subWeeks(3),
                'updated_at' => Carbon::now()->subWeeks(1),
            ]);

            // 6. Inventory Management
            $project6 = Project::create([
                'title' => 'Inventory Management System',
                'description' => 'Sistema de gestión de inventario con códigos de barras, alertas de stock y reportes de movimientos.',
                'public' => false,
                'status' => 'PAUSED',
                'start_date' => null,
                'end_date' => null,
                'created_by' => $michael ? $michael->id : $defaultCreator->id,
                'created_at' => Carbon::now()->subWeeks(6),
                'updated_at' => Carbon::now()->subWeeks(4),
            ]);

            // 7. Social Media Manager
            $project7 = Project::create([
                'title' => 'Social Media Manager',
                'description' => 'Herramienta para programar y gestionar publicaciones en múltiples redes sociales.',
                'public' => true,
                'status' => 'CANCELLED',
                'start_date' => Carbon::now()->subMonths(4)->format('Y-m-d'),
                'end_date' => Carbon::now()->subMonths(2)->format('Y-m-d'),
                'created_by' => $john ? $john->id : $defaultCreator->id,
                'created_at' => Carbon::now()->subMonths(5),
                'updated_at' => Carbon::now()->subMonths(2),
            ]);

            // 8. Learning Management System
            $project8 = Project::create([
                'title' => 'Learning Management System',
                'description' => 'Plataforma educativa online con cursos, evaluaciones y seguimiento del progreso.',
                'public' => true,
                'status' => 'ACTIVE',
                'start_date' => Carbon::now()->subWeeks(6)->format('Y-m-d'),
                'end_date' => Carbon::now()->addMonths(5)->format('Y-m-d'),
                'created_by' => $admin ? $admin->id : $defaultCreator->id,
                'created_at' => Carbon::now()->subWeeks(8),
                'updated_at' => Carbon::now()->subDays(2),
            ]);

            // 9. AI Chatbot Platform
            $project9 = Project::create([
                'title' => 'AI Chatbot Platform',
                'description' => 'Intelligent chatbot platform with natural language processing and machine learning',
                'public' => false,
                'status' => 'PENDING',
                'start_date' => Carbon::now()->addWeeks(2)->format('Y-m-d'),
                'end_date' => Carbon::now()->addMonths(12)->format('Y-m-d'),
                'created_by' => $lisa ? $lisa->id : $defaultCreator->id,
                'created_at' => Carbon::now()->subWeeks(3),
                'updated_at' => Carbon::now()->subDays(5),
            ]);

            // 10. IoT Device Management
            $project10 = Project::create([
                'title' => 'IoT Device Management',
                'description' => 'Internet of Things device monitoring and control platform',
                'public' => false,
                'status' => 'PENDING',
                'start_date' => Carbon::now()->addMonths(2)->format('Y-m-d'),
                'end_date' => Carbon::now()->addMonths(14)->format('Y-m-d'),
                'created_by' => $admin ? $admin->id : $defaultCreator->id,
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(1),
            ]);

            // 11. Legacy System Migration
            $project11 = Project::create([
                'title' => 'Legacy System Migration',
                'description' => 'Migration of legacy systems to modern cloud infrastructure',
                'public' => false,
                'status' => 'DONE',
                'start_date' => Carbon::now()->subMonths(12)->format('Y-m-d'),
                'end_date' => Carbon::now()->subMonths(2)->format('Y-m-d'),
                'created_by' => $john ? $john->id : $defaultCreator->id,
                'created_at' => Carbon::now()->subMonths(12),
                'updated_at' => Carbon::now()->subMonths(2),
            ]);

            // 12. Security Audit Platform
            $project12 = Project::create([
                'title' => 'Security Audit Platform',
                'description' => 'Comprehensive security auditing and vulnerability assessment tool',
                'public' => true,
                'status' => 'DONE',
                'start_date' => Carbon::now()->subMonths(14)->format('Y-m-d'),
                'end_date' => Carbon::now()->subMonths(3)->format('Y-m-d'),
                'created_by' => $michael ? $michael->id : $defaultCreator->id,
                'created_at' => Carbon::now()->subMonths(14),
                'updated_at' => Carbon::now()->subMonths(3),
            ]);

            // 13. Payment Gateway Integration
            $project13 = Project::create([
                'title' => 'Payment Gateway Integration',
                'description' => 'Multi-payment gateway integration with fraud detection',
                'public' => false,
                'status' => 'DONE',
                'start_date' => Carbon::now()->subMonths(10)->format('Y-m-d'),
                'end_date' => Carbon::now()->subMonth()->format('Y-m-d'),
                'created_by' => $lisa ? $lisa->id : $defaultCreator->id,
                'created_at' => Carbon::now()->subMonths(10),
                'updated_at' => Carbon::now()->subMonth(),
            ]);

            // 14. Performance Optimization Study
            $project14 = Project::create([
                'title' => 'Performance Optimization Study',
                'description' => 'Research project to optimize application performance across all platforms',
                'public' => false,
                'status' => 'ACTIVE',
                'start_date' => Carbon::now()->subMonths(3)->format('Y-m-d'),
                'end_date' => Carbon::now()->addMonths(3)->format('Y-m-d'),
                'created_by' => $robert ? $robert->id : $defaultCreator->id,
                'created_at' => Carbon::now()->subMonths(3),
                'updated_at' => Carbon::now()->subWeeks(1),
            ]);

            // 15. API Standardization Initiative
            $project15 = Project::create([
                'title' => 'API Standardization Initiative',
                'description' => 'Standardizing API design patterns and documentation across all services',
                'public' => true,
                'status' => 'ACTIVE',
                'start_date' => Carbon::now()->subMonths(2)->format('Y-m-d'),
                'end_date' => Carbon::now()->addMonths(4)->format('Y-m-d'),
                'created_by' => $admin ? $admin->id : $defaultCreator->id,
                'created_at' => Carbon::now()->subMonths(2),
                'updated_at' => Carbon::now()->subDays(7),
            ]);

            // 16. Microservices POC
            $project16 = Project::create([
                'title' => 'Microservices POC',
                'description' => 'Proof of concept for microservices architecture implementation',
                'public' => false,
                'status' => 'ACTIVE',
                'start_date' => Carbon::now()->subWeeks(6)->format('Y-m-d'),
                'end_date' => Carbon::now()->addWeeks(6)->format('Y-m-d'),
                'created_by' => $john ? $john->id : $defaultCreator->id,
                'created_at' => Carbon::now()->subWeeks(6),
                'updated_at' => Carbon::now()->subDays(4),
            ]);

            // 17. Dark Mode Implementation
            $project17 = Project::create([
                'title' => 'Dark Mode Implementation',
                'description' => 'Adding dark mode support across all web and mobile applications',
                'public' => true,
                'status' => 'ACTIVE',
                'start_date' => Carbon::now()->subWeeks(4)->format('Y-m-d'),
                'end_date' => Carbon::now()->addWeeks(8)->format('Y-m-d'),
                'created_by' => $lisa ? $lisa->id : $defaultCreator->id,
                'created_at' => Carbon::now()->subWeeks(4),
                'updated_at' => Carbon::now()->subDays(2),
            ]);

            // 18. Accessibility Compliance
            $project18 = Project::create([
                'title' => 'Accessibility Compliance',
                'description' => 'Ensuring WCAG 2.1 AA compliance across all user interfaces',
                'public' => true,
                'status' => 'PENDING',
                'start_date' => Carbon::now()->addWeeks(4)->format('Y-m-d'),
                'end_date' => Carbon::now()->addMonths(6)->format('Y-m-d'),
                'created_by' => $michael ? $michael->id : $defaultCreator->id,
                'created_at' => Carbon::now()->subWeeks(1),
                'updated_at' => Carbon::now()->subDays(1),
            ]);

            // 19. Database Optimization
            $project19 = Project::create([
                'title' => 'Database Optimization',
                'description' => 'Ongoing database performance optimization and maintenance',
                'public' => false,
                'status' => 'ACTIVE',
                'start_date' => Carbon::now()->subMonth()->format('Y-m-d'),
                'end_date' => Carbon::now()->addMonths(6)->format('Y-m-d'),
                'created_by' => $robert ? $robert->id : $defaultCreator->id,
                'created_at' => Carbon::now()->subMonth(),
                'updated_at' => Carbon::now()->subDays(3),
            ]);

            // 20. Documentation Overhaul
            $project20 = Project::create([
                'title' => 'Documentation Overhaul',
                'description' => 'Complete review and update of technical documentation',
                'public' => true,
                'status' => 'ACTIVE',
                'start_date' => Carbon::now()->subWeeks(3)->format('Y-m-d'),
                'end_date' => Carbon::now()->addMonths(2)->format('Y-m-d'),
                'created_by' => $admin ? $admin->id : $defaultCreator->id,
                'created_at' => Carbon::now()->subWeeks(3),
                'updated_at' => Carbon::now()->subDays(1),
            ]);


            DB::commit();
            
            $this->command->info('Proyectos creados exitosamente:');
            $this->command->info('- E-commerce Platform (ID: ' . $project1->id . ')');
            $this->command->info('- CRM System (ID: ' . $project2->id . ')');
            $this->command->info('- Mobile Banking App (ID: ' . $project3->id . ')');
            $this->command->info('- Healthcare Portal (ID: ' . $project4->id . ')');
            $this->command->info('- Analytics Dashboard (ID: ' . $project5->id . ')');
            $this->command->info('- Inventory Management System (ID: ' . $project6->id . ')');
            $this->command->info('- Social Media Manager (ID: ' . $project7->id . ')');
            $this->command->info('- Learning Management System (ID: ' . $project8->id . ')');
            $this->command->info('- AI Chatbot Platform (ID: ' . $project9->id . ')');
            $this->command->info('- IoT Device Management (ID: ' . $project10->id . ')');
            $this->command->info('- Legacy System Migration (ID: ' . $project11->id . ')');
            $this->command->info('- Security Audit Platform (ID: ' . $project12->id . ')');
            $this->command->info('- Payment Gateway Integration (ID: ' . $project13->id . ')');
            $this->command->info('- Performance Optimization Study (ID: ' . $project14->id . ')');
            $this->command->info('- API Standardization Initiative (ID: ' . $project15->id . ')');
            $this->command->info('- Microservices POC (ID: ' . $project16->id . ')');
            $this->command->info('- Dark Mode Implementation (ID: ' . $project17->id . ')');
            $this->command->info('- Accessibility Compliance (ID: ' . $project18->id . ')');
            $this->command->info('- Database Optimization (ID: ' . $project19->id . ')');
            $this->command->info('- Documentation Overhaul (ID: ' . $project20->id . ')');


        } catch (\Exception $e) {
            DB::rollback();
            $this->command->error('Error al crear proyectos: ' . $e->getMessage());
            throw $e;
        }
    }
}