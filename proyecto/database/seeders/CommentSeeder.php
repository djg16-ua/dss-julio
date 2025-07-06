<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommentSeeder extends Seeder
{
    public function run()
    {
        // Obtener usuarios
        $users = DB::table('users')->get()->keyBy('email');
        $admin = $users->get('admin@taskflow.com');
        $john = $users->get('john@taskflow.com');
        $jane = $users->get('jane@taskflow.com');
        $bob = $users->get('bob@taskflow.com');

        // Obtener tareas con información del módulo y proyecto
        $tasks = DB::table('tasks')
            ->join('modules', 'tasks.module_id', '=', 'modules.id')
            ->join('projects', 'modules.project_id', '=', 'projects.id')
            ->select('tasks.*', 'modules.name as module_name', 'projects.title as project_title')
            ->get();

        // Templates de comentarios por tipo
        $commentTemplates = [
            'progress_update' => [
                'Started working on this task. Initial setup is complete.',
                'Making good progress. About 30% done with the implementation.',
                'Hit a small roadblock but found a workaround. Back on track.',
                'This is taking longer than expected due to complexity.',
                'Almost finished with the main functionality. Just need testing.',
                'Completed the core features. Moving to edge case handling.',
                'Ready for code review. All requirements have been implemented.',
            ],
            'technical_discussion' => [
                'Should we use Redis for caching here or stick with database caching?',
                'I think we need to refactor this approach. The current implementation might not scale.',
                'What about adding TypeScript for this component? It might help catch errors early.',
                'We should consider using a design pattern here. Maybe Observer or Strategy?',
                'The API response time is slower than expected. Should we implement pagination?',
                'Security concern: we need to validate all user inputs before processing.',
                'Performance issue: this query is taking too long. Need to optimize.',
                'Code quality: this function is getting too complex. Should we break it down?',
            ],
            'requirements_clarification' => [
                'Need clarification on the exact user workflow for this feature.',
                'Should this work on mobile devices as well or just desktop?',
                'What should happen if the user cancels the operation midway?',
                'Do we need to support offline functionality for this?',
                'Should we add analytics tracking for this user action?',
                'What are the performance requirements? How many concurrent users?',
                'Are there any accessibility requirements we need to consider?',
                'Should this integrate with our existing notification system?',
            ],
            'feedback_and_review' => [
                'Great work on this! The implementation looks solid.',
                'Nice solution! This is much cleaner than the previous approach.',
                'Good job handling the edge cases. Very thorough implementation.',
                'The code looks good but needs some documentation comments.',
                'Impressive work! This will definitely improve user experience.',
                'The testing coverage is excellent. Well done!',
                'Love the attention to detail in the UI implementation.',
                'This solution is more elegant than what I had in mind. Great job!',
            ],
            'issues_and_blockers' => [
                'Blocked by the authentication service being down.',
                'Waiting for the API documentation to be updated.',
                'Dependencies are not resolving correctly. Need to investigate.',
                'The test environment is not reflecting production behavior.',
                'Third-party service integration is failing intermittently.',
                'Database migration is required before this can be completed.',
                'Need access to the staging environment to test this properly.',
                'Waiting for design approval before proceeding with implementation.',
            ],
            'suggestions' => [
                'Consider adding error handling for network timeouts.',
                'Maybe we should add loading states for better UX.',
                'What about adding keyboard shortcuts for power users?',
                'We could improve this by adding search functionality.',
                'Consider caching this data to improve performance.',
                'Adding unit tests would help prevent regressions.',
                'We should validate this input on both client and server side.',
                'Consider making this configurable through admin settings.',
            ],
            'testing_qa' => [
                'Tested on Chrome, Firefox, and Safari. All working correctly.',
                'Found an edge case where validation fails with special characters.',
                'Manual testing completed. All acceptance criteria met.',
                'Automated tests are passing. Ready for deployment.',
                'Testing on mobile devices - found a responsive design issue.',
                'Load testing shows good performance under expected load.',
                'Security testing reveals no major vulnerabilities.',
                'Cross-browser testing complete. Minor CSS fix needed for IE.',
            ],
        ];

        $commentInserts = [];

        // Crear comentarios para las tareas más importantes (alrededor del 40% de las tareas)
        $selectedTasks = $tasks->shuffle()->take(ceil($tasks->count() * 0.4));

        foreach ($selectedTasks as $task) {
            // Número de comentarios por tarea (1-8, con bias hacia menos comentarios)
            $commentCount = $this->getWeightedCommentCount();

            // Determinar qué usuarios van a comentar en esta tarea
            $commentingUsers = $this->selectCommentingUsers($users, $task, $commentCount);

            for ($i = 0; $i < $commentCount; $i++) {
                $user = $commentingUsers[$i % count($commentingUsers)];
                $commentType = $this->selectCommentType($task, $i, $commentCount);
                $content = $this->getCommentContent($commentTemplates, $commentType, $task, $user);

                // Calcular fecha del comentario
                $commentDate = $this->calculateCommentDate($task, $i, $commentCount);

                $commentInserts[] = [
                    'content' => $content,
                    'user_id' => $user->id,
                    'task_id' => $task->id,
                    'created_at' => $commentDate,
                    'updated_at' => $commentDate,
                ];
            }
        }

        // Agregar algunos comentarios específicos para tareas importantes
        $this->addSpecificComments($commentInserts, $tasks, $users);

        // Insertar comentarios en batches
        $chunks = array_chunk($commentInserts, 50);
        foreach ($chunks as $chunk) {
            DB::table('comments')->insert($chunk);
        }
    }

    private function getWeightedCommentCount()
    {
        $weights = [
            1 => 35, // 35% tareas con 1 comentario
            2 => 25, // 25% tareas con 2 comentarios
            3 => 20, // 20% tareas con 3 comentarios
            4 => 10, // 10% tareas con 4 comentarios
            5 => 5,  // 5% tareas con 5 comentarios
            6 => 3,  // 3% tareas con 6 comentarios
            7 => 1,  // 1% tareas con 7 comentarios
            8 => 1,  // 1% tareas con 8 comentarios
        ];

        $rand = rand(1, 100);
        $cumulative = 0;

        foreach ($weights as $count => $weight) {
            $cumulative += $weight;
            if ($rand <= $cumulative) {
                return $count;
            }
        }

        return 1;
    }

    private function selectCommentingUsers($users, $task, $commentCount)
    {
        $commentingUsers = [];
        $allUsers = $users->values()->toArray();

        // El usuario asignado siempre comenta
        $assignedUser = collect($allUsers)->firstWhere('id', $task->assigned_to);
        if ($assignedUser) {
            $commentingUsers[] = $assignedUser;
        }

        // El creador de la tarea también puede comentar (50% probabilidad)
        if ($task->created_by !== $task->assigned_to && rand(0, 100) < 50) {
            $creator = collect($allUsers)->firstWhere('id', $task->created_by);
            if ($creator) {
                $commentingUsers[] = $creator;
            }
        }

        // Agregar usuarios adicionales según el número de comentarios
        while (count($commentingUsers) < min($commentCount, 4)) {
            $randomUser = $allUsers[array_rand($allUsers)];
            if (!in_array($randomUser, $commentingUsers)) {
                $commentingUsers[] = $randomUser;
            }
        }

        return $commentingUsers;
    }

    private function selectCommentType($task, $commentIndex, $totalComments)
    {
        // Primer comentario: más probable que sea progress_update
        if ($commentIndex === 0) {
            $weights = [
                'progress_update' => 40,
                'technical_discussion' => 20,
                'requirements_clarification' => 20,
                'suggestions' => 20,
            ];
        }
        // Último comentario: más probable que sea feedback o testing
        elseif ($commentIndex === $totalComments - 1) {
            $weights = [
                'feedback_and_review' => 30,
                'testing_qa' => 25,
                'progress_update' => 25,
                'technical_discussion' => 20,
            ];
        }
        // Comentarios del medio
        else {
            $weights = [
                'technical_discussion' => 25,
                'progress_update' => 20,
                'suggestions' => 15,
                'requirements_clarification' => 15,
                'issues_and_blockers' => 10,
                'feedback_and_review' => 10,
                'testing_qa' => 5,
            ];
        }

        $rand = rand(1, array_sum($weights));
        $cumulative = 0;

        foreach ($weights as $type => $weight) {
            $cumulative += $weight;
            if ($rand <= $cumulative) {
                return $type;
            }
        }

        return 'progress_update';
    }

    private function getCommentContent($templates, $type, $task, $user)
    {
        $baseComments = $templates[$type] ?? $templates['progress_update'];
        $baseComment = $baseComments[array_rand($baseComments)];

        // Personalizar algunos comentarios según contexto
        $personalizations = [
            'authentication' => [
                'Remember to hash passwords securely.',
                'Don\'t forget to implement rate limiting for login attempts.',
                'Consider adding CAPTCHA for repeated failed attempts.',
                'Make sure we\'re compliant with password policies.',
            ],
            'payment' => [
                'PCI compliance is critical for this module.',
                'Let\'s make sure all transactions are logged properly.',
                'We need thorough testing with sandbox environments.',
                'Consider implementing webhook verification.',
            ],
            'ui' => [
                'Make sure this works well on mobile devices.',
                'Consider adding loading states for better UX.',
                'The color contrast should meet accessibility standards.',
                'Let\'s make sure this is responsive across screen sizes.',
            ],
            'api' => [
                'Don\'t forget to update the API documentation.',
                'Consider implementing proper error codes.',
                'Rate limiting should be implemented for this endpoint.',
                'Make sure we have proper request validation.',
            ],
        ];

        // Agregar personalización basada en el módulo
        $moduleName = strtolower($task->module_name);
        if (str_contains($moduleName, 'auth')) {
            $specific = $personalizations['authentication'];
        } elseif (str_contains($moduleName, 'payment')) {
            $specific = $personalizations['payment'];
        } elseif (str_contains($moduleName, 'ui') || str_contains($moduleName, 'interface')) {
            $specific = $personalizations['ui'];
        } else {
            $specific = $personalizations['api'];
        }

        // 30% de probabilidad de usar comentario específico
        if (rand(0, 100) < 30) {
            return $specific[array_rand($specific)];
        }

        return $baseComment;
    }

    private function calculateCommentDate($task, $commentIndex, $totalComments)
    {
        $taskCreated = new \DateTime($task->created_at);
        $taskEnd = new \DateTime($task->end_date);

        // Distribución de comentarios a lo largo del tiempo de vida de la tarea
        if ($totalComments === 1) {
            // Solo un comentario: en algún punto random
            $daysDiff = $taskCreated->diff($taskEnd)->days;
            $randomDays = rand(0, max(1, $daysDiff));
            return $taskCreated->add(new \DateInterval("P{$randomDays}D"));
        }

        // Múltiples comentarios: distribuir a lo largo del tiempo
        $daysDiff = $taskCreated->diff($taskEnd)->days;
        $intervalDays = max(1, intval($daysDiff / $totalComments));
        $baseDay = $intervalDays * $commentIndex;

        // Agregar algo de randomness
        $variance = rand(-$intervalDays + 1, $intervalDays - 1);
        $finalDay = max(0, $baseDay + $variance);

        return $taskCreated->add(new \DateInterval("P{$finalDay}D"));
    }

    private function addSpecificComments(&$commentInserts, $tasks, $users)
    {
        $admin = $users->get('admin@taskflow.com');
        $john = $users->get('john@taskflow.com');
        $jane = $users->get('jane@taskflow.com');
        $bob = $users->get('bob@taskflow.com');

        // Encontrar tareas específicas para comentarios especiales
        $loginTask = $tasks->where('title', 'Create login form')->first();
        $paymentTask = $tasks->where('title', 'Stripe integration')->first();
        $homepageTask = $tasks->where('title', 'Homepage design')->first();

        if ($loginTask) {
            $commentInserts[] = [
                'content' => 'Remember to include password strength validation with clear feedback to users.',
                'user_id' => $john->id,
                'task_id' => $loginTask->id,
                'created_at' => now()->subHours(rand(2, 48)),
                'updated_at' => now()->subHours(rand(2, 48)),
            ];

            $commentInserts[] = [
                'content' => 'I will add the password visibility toggle and ensure mobile compatibility.',
                'user_id' => $jane->id,
                'task_id' => $loginTask->id,
                'created_at' => now()->subHours(rand(1, 24)),
                'updated_at' => now()->subHours(rand(1, 24)),
            ];
        }

        if ($paymentTask) {
            $commentInserts[] = [
                'content' => 'Make sure to implement proper error handling for declined cards and network failures.',
                'user_id' => $admin->id,
                'task_id' => $paymentTask->id,
                'created_at' => now()->subHours(rand(6, 72)),
                'updated_at' => now()->subHours(rand(6, 72)),
            ];

            $commentInserts[] = [
                'content' => 'Testing with Stripe\'s test cards is going well. All major scenarios covered.',
                'user_id' => $bob->id,
                'task_id' => $paymentTask->id,
                'created_at' => now()->subHours(rand(2, 24)),
                'updated_at' => now()->subHours(rand(2, 24)),
            ];
        }

        if ($homepageTask) {
            $commentInserts[] = [
                'content' => 'The mockups look great! Should we add a hero video or stick with static images?',
                'user_id' => $jane->id,
                'task_id' => $homepageTask->id,
                'created_at' => now()->subHours(rand(12, 96)),
                'updated_at' => now()->subHours(rand(12, 96)),
            ];

            $commentInserts[] = [
                'content' => 'Let\'s go with static images for now to keep loading times fast. We can add video later.',
                'user_id' => $admin->id,
                'task_id' => $homepageTask->id,
                'created_at' => now()->subHours(rand(6, 48)),
                'updated_at' => now()->subHours(rand(6, 48)),
            ];

            $commentInserts[] = [
                'content' => 'Implemented the responsive design. Looks good on all device sizes!',
                'user_id' => $jane->id,
                'task_id' => $homepageTask->id,
                'created_at' => now()->subHours(rand(1, 12)),
                'updated_at' => now()->subHours(rand(1, 12)),
            ];
        }

        // Agregar algunos comentarios de seguimiento para tareas completadas
        $completedTasks = $tasks->where('status', 'DONE')->take(10);
        foreach ($completedTasks as $task) {
            if (rand(0, 100) < 60) { // 60% de probabilidad
                $commentInserts[] = [
                    'content' => 'Task completed successfully! All requirements met and tested.',
                    'user_id' => $task->assigned_to,
                    'task_id' => $task->id,
                    'created_at' => $task->completed_at ?? now()->subDays(rand(1, 30)),
                    'updated_at' => $task->completed_at ?? now()->subDays(rand(1, 30)),
                ];
            }
        }

        // Agregar comentarios de bloqueo para algunas tareas pendientes
        $pendingTasks = $tasks->where('status', 'PENDING')->take(5);
        foreach ($pendingTasks as $task) {
            if (rand(0, 100) < 40) { // 40% de probabilidad
                $blockers = [
                    'Waiting for API documentation to be finalized.',
                    'Blocked by dependency on another module completion.',
                    'Need design approval before proceeding.',
                    'Waiting for access to production environment.',
                    'Dependencies need to be resolved first.',
                ];

                $commentInserts[] = [
                    'content' => $blockers[array_rand($blockers)],
                    'user_id' => $task->assigned_to,
                    'task_id' => $task->id,
                    'created_at' => now()->subDays(rand(1, 7)),
                    'updated_at' => now()->subDays(rand(1, 7)),
                ];
            }
        }

        // Agregar comentarios de progreso para tareas activas
        $activeTasks = $tasks->where('status', 'ACTIVE')->take(15);
        foreach ($activeTasks as $task) {
            if (rand(0, 100) < 70) { // 70% de probabilidad
                $progressComments = [
                    'Making good progress on this. About 50% complete.',
                    'Running into some technical challenges but working through them.',
                    'Implementation is going smoothly. Should finish on time.',
                    'Need to refactor some parts but overall progress is good.',
                    'Almost done with the core functionality.',
                    'Testing phase has begun. Found a few minor issues to fix.',
                ];

                $commentInserts[] = [
                    'content' => $progressComments[array_rand($progressComments)],
                    'user_id' => $task->assigned_to,
                    'task_id' => $task->id,
                    'created_at' => now()->subDays(rand(0, 5)),
                    'updated_at' => now()->subDays(rand(0, 5)),
                ];
            }
        }
    }
}
