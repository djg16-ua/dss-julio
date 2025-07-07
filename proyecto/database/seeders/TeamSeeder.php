<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeamSeeder extends Seeder
{
    public function run()
    {
        // Crear teams
        $teams = [
            [
                'id' => 1,
                'name' => 'Frontend Team',
                'description' => 'UI/UX development and frontend implementation',
                'created_at' => now()->subMonths(18),
                'updated_at' => now()->subWeeks(2),
            ],
            [
                'id' => 2,
                'name' => 'Backend Team',
                'description' => 'Server-side logic and API development',
                'created_at' => now()->subMonths(20),
                'updated_at' => now()->subWeeks(1),
            ],
            [
                'id' => 3,
                'name' => 'QA Team',
                'description' => 'Quality assurance and testing',
                'created_at' => now()->subMonths(15),
                'updated_at' => now()->subDays(5),
            ],
            [
                'id' => 4,
                'name' => 'DevOps Team',
                'description' => 'Infrastructure, deployment, and CI/CD',
                'created_at' => now()->subMonths(12),
                'updated_at' => now()->subDays(3),
            ],
            [
                'id' => 5,
                'name' => 'Product Team',
                'description' => 'Product management and strategy',
                'created_at' => now()->subMonths(14),
                'updated_at' => now()->subWeeks(1),
            ],
            [
                'id' => 6,
                'name' => 'Mobile Team',
                'description' => 'iOS and Android development',
                'created_at' => now()->subMonths(10),
                'updated_at' => now()->subDays(7),
            ],
            [
                'id' => 7,
                'name' => 'Data Team',
                'description' => 'Data analysis and machine learning',
                'created_at' => now()->subMonths(8),
                'updated_at' => now()->subWeeks(2),
            ],
            [
                'id' => 8,
                'name' => 'Security Team',
                'description' => 'Cybersecurity and compliance',
                'created_at' => now()->subMonths(9),
                'updated_at' => now()->subDays(10),
            ],
            [
                'id' => 9,
                'name' => 'Design Team',
                'description' => 'UI/UX design and user research',
                'created_at' => now()->subMonths(11),
                'updated_at' => now()->subWeeks(3),
            ],
            [
                'id' => 10,
                'name' => 'Research Team',
                'description' => 'Technical research and innovation',
                'created_at' => now()->subMonths(6),
                'updated_at' => now()->subDays(4),
            ],
            [
                'id' => 11,
                'name' => 'Integration Team',
                'description' => 'Third-party integrations and APIs',
                'created_at' => now()->subMonths(7),
                'updated_at' => now()->subDays(8),
            ],
            [
                'id' => 12,
                'name' => 'Platform Team',
                'description' => 'Core platform and infrastructure',
                'created_at' => now()->subMonths(13),
                'updated_at' => now()->subWeeks(1),
            ],
        ];

        DB::table('teams')->insert($teams);

        // Obtener todos los usuarios para asignar a teams
        $users = DB::table('users')->get();
        $usersByEmail = $users->keyBy('email');

        // Definir asignaciones de usuarios a teams con roles variados
        $teamAssignments = [
            // Frontend Team (Team 1) - Equipo grande
            [
                'team_id' => 1,
                'assignments' => [
                    ['email' => 'jane@taskflow.com', 'role' => 'LEAD', 'months_ago' => 12],
                    ['email' => 'alex.johnson@taskflow.com', 'role' => 'SENIOR_DEV', 'months_ago' => 10],
                    ['email' => 'maria.garcia@taskflow.com', 'role' => 'DEVELOPER', 'months_ago' => 8],
                    ['email' => 'chris.lee@taskflow.com', 'role' => 'DEVELOPER', 'months_ago' => 6],
                    ['email' => 'sophie.anderson@taskflow.com', 'role' => 'DEVELOPER', 'months_ago' => 4],
                    ['email' => 'ryan.parker@taskflow.com', 'role' => 'JUNIOR_DEV', 'months_ago' => 3],
                    ['email' => 'nina.patel@taskflow.com', 'role' => 'JUNIOR_DEV', 'months_ago' => 2],
                    ['email' => 'ethan.cooper@taskflow.com', 'role' => 'JUNIOR_DEV', 'months_ago' => 1],
                ]
            ],
            // Backend Team (Team 2) - Equipo muy activo
            [
                'team_id' => 2,
                'assignments' => [
                    ['email' => 'john@taskflow.com', 'role' => 'LEAD', 'months_ago' => 15],
                    ['email' => 'kevin.brown@taskflow.com', 'role' => 'SENIOR_DEV', 'months_ago' => 12],
                    ['email' => 'amanda.taylor@taskflow.com', 'role' => 'SENIOR_DEV', 'months_ago' => 10],
                    ['email' => 'daniel.kim@taskflow.com', 'role' => 'DEVELOPER', 'months_ago' => 8],
                    ['email' => 'jessica.white@taskflow.com', 'role' => 'DEVELOPER', 'months_ago' => 7],
                    ['email' => 'marcus.johnson@taskflow.com', 'role' => 'DEVELOPER', 'months_ago' => 5],
                    ['email' => 'rachel.green@taskflow.com', 'role' => 'DEVELOPER', 'months_ago' => 4],
                    ['email' => 'brian.davis@taskflow.com', 'role' => 'DEVELOPER', 'months_ago' => 3],
                    ['email' => 'sarah@taskflow.com', 'role' => 'DEVELOPER', 'months_ago' => 6],
                    ['email' => 'lucas.bailey@taskflow.com', 'role' => 'JUNIOR_DEV', 'months_ago' => 2],
                    ['email' => 'noah.foster@taskflow.com', 'role' => 'JUNIOR_DEV', 'months_ago' => 1],
                ]
            ],
            // QA Team (Team 3)
            [
                'team_id' => 3,
                'assignments' => [
                    ['email' => 'bob@taskflow.com', 'role' => 'LEAD', 'months_ago' => 14],
                    ['email' => 'ashley.miller@taskflow.com', 'role' => 'TESTER', 'months_ago' => 10],
                    ['email' => 'tyler.wilson@taskflow.com', 'role' => 'TESTER', 'months_ago' => 8],
                    ['email' => 'megan.clark@taskflow.com', 'role' => 'TESTER', 'months_ago' => 6],
                    ['email' => 'jason.lopez@taskflow.com', 'role' => 'TESTER', 'months_ago' => 4],
                    ['email' => 'samantha.hill@taskflow.com', 'role' => 'TESTER', 'months_ago' => 5],
                    ['email' => 'chloe.ward@taskflow.com', 'role' => 'TESTER', 'months_ago' => 2],
                ]
            ],
            // DevOps Team (Team 4)
            [
                'team_id' => 4,
                'assignments' => [
                    ['email' => 'andrew.scott@taskflow.com', 'role' => 'LEAD', 'months_ago' => 11],
                    ['email' => 'lauren.adams@taskflow.com', 'role' => 'SENIOR_DEV', 'months_ago' => 9],
                    ['email' => 'james.wright@taskflow.com', 'role' => 'DEVELOPER', 'months_ago' => 7],
                    ['email' => 'stephanie.turner@taskflow.com', 'role' => 'DEVELOPER', 'months_ago' => 5],
                    ['email' => 'gabriel.price@taskflow.com', 'role' => 'DEVELOPER', 'months_ago' => 3],
                ]
            ],
            // Product Team (Team 5)
            [
                'team_id' => 5,
                'assignments' => [
                    ['email' => 'robert.martinez@taskflow.com', 'role' => 'LEAD', 'months_ago' => 13],
                    ['email' => 'jennifer.hall@taskflow.com', 'role' => 'ANALYST', 'months_ago' => 10],
                    ['email' => 'matthew.young@taskflow.com', 'role' => 'ANALYST', 'months_ago' => 8],
                    ['email' => 'olivia.king@taskflow.com', 'role' => 'ANALYST', 'months_ago' => 6],
                    ['email' => 'ava.torres@taskflow.com', 'role' => 'ANALYST', 'months_ago' => 3],
                ]
            ],
            // Mobile Team (Team 6)
            [
                'team_id' => 6,
                'assignments' => [
                    ['email' => 'michael.rodriguez@taskflow.com', 'role' => 'LEAD', 'months_ago' => 9],
                    ['email' => 'lisa.chen@taskflow.com', 'role' => 'SENIOR_DEV', 'months_ago' => 8],
                    ['email' => 'david.wilson@taskflow.com', 'role' => 'SENIOR_DEV', 'months_ago' => 7],
                    ['email' => 'emma.thompson@taskflow.com', 'role' => 'DEVELOPER', 'months_ago' => 5],
                    ['email' => 'liam.roberts@taskflow.com', 'role' => 'JUNIOR_DEV', 'months_ago' => 2],
                    ['email' => 'maya.singh@taskflow.com', 'role' => 'JUNIOR_DEV', 'months_ago' => 1],
                ]
            ],
            // Data Team (Team 7)
            [
                'team_id' => 7,
                'assignments' => [
                    ['email' => 'violet.hughes@taskflow.com', 'role' => 'LEAD', 'months_ago' => 7],
                    ['email' => 'isaac.watson@taskflow.com', 'role' => 'SENIOR_DEV', 'months_ago' => 6],
                    ['email' => 'hazel.morgan@taskflow.com', 'role' => 'DEVELOPER', 'months_ago' => 5],
                    ['email' => 'julian.gray@taskflow.com', 'role' => 'DEVELOPER', 'months_ago' => 4],
                    ['email' => 'aurora.james@taskflow.com', 'role' => 'ANALYST', 'months_ago' => 3],
                ]
            ],
            // Security Team (Team 8)
            [
                'team_id' => 8,
                'assignments' => [
                    ['email' => 'ezra.phillips@taskflow.com', 'role' => 'LEAD', 'months_ago' => 8],
                    ['email' => 'luna.carter@taskflow.com', 'role' => 'SENIOR_DEV', 'months_ago' => 6],
                    ['email' => 'axel.evans@taskflow.com', 'role' => 'DEVELOPER', 'months_ago' => 4],
                    ['email' => 'sage.mitchell@taskflow.com', 'role' => 'ANALYST', 'months_ago' => 3],
                ]
            ],
            // Design Team (Team 9)
            [
                'team_id' => 9,
                'assignments' => [
                    ['email' => 'felix.ross@taskflow.com', 'role' => 'LEAD', 'months_ago' => 10],
                    ['email' => 'iris.powell@taskflow.com', 'role' => 'SENIOR_DEV', 'months_ago' => 8],
                    ['email' => 'dante.bell@taskflow.com', 'role' => 'DESIGNER', 'months_ago' => 6],
                    ['email' => 'willow.cox@taskflow.com', 'role' => 'DESIGNER', 'months_ago' => 5],
                    ['email' => 'atlas.ward@taskflow.com', 'role' => 'DESIGNER', 'months_ago' => 4],
                    ['email' => 'ember.wood@taskflow.com', 'role' => 'DESIGNER', 'months_ago' => 2],
                ]
            ],
            // Research Team (Team 10)
            [
                'team_id' => 10,
                'assignments' => [
                    ['email' => 'phoenix.hayes@taskflow.com', 'role' => 'LEAD', 'months_ago' => 5],
                    ['email' => 'river.stone@taskflow.com', 'role' => 'ANALYST', 'months_ago' => 4],
                    ['email' => 'orion.blake@taskflow.com', 'role' => 'ANALYST', 'months_ago' => 3],
                    ['email' => 'nova.cruz@taskflow.com', 'role' => 'ANALYST', 'months_ago' => 2],
                ]
            ],
            // Integration Team (Team 11)
            [
                'team_id' => 11,
                'assignments' => [
                    ['email' => 'kai.sullivan@taskflow.com', 'role' => 'LEAD', 'months_ago' => 6],
                    ['email' => 'wren.fisher@taskflow.com', 'role' => 'SENIOR_DEV', 'months_ago' => 5],
                    ['email' => 'rowan.cole@taskflow.com', 'role' => 'DEVELOPER', 'months_ago' => 4],
                    ['email' => 'sage.harper@taskflow.com', 'role' => 'DEVELOPER', 'months_ago' => 3],
                ]
            ],
            // Platform Team (Team 12)
            [
                'team_id' => 12,
                'assignments' => [
                    ['email' => 'zara.webb@taskflow.com', 'role' => 'LEAD', 'months_ago' => 12],
                    ['email' => 'finn.palmer@taskflow.com', 'role' => 'SENIOR_DEV', 'months_ago' => 10],
                    ['email' => 'ivy.russell@taskflow.com', 'role' => 'SENIOR_DEV', 'months_ago' => 8],
                    ['email' => 'jude.knight@taskflow.com', 'role' => 'DEVELOPER', 'months_ago' => 6],
                    ['email' => 'evelyn.fox@taskflow.com', 'role' => 'JUNIOR_DEV', 'months_ago' => 3],
                ]
            ],
        ];

        // Insertar asignaciones de usuarios a teams
        $teamUserInserts = [];
        foreach ($teamAssignments as $teamData) {
            foreach ($teamData['assignments'] as $assignment) {
                $user = $usersByEmail->get($assignment['email']);
                if ($user) {
                    $joinedAt = now()->subMonths($assignment['months_ago']);
                    $teamUserInserts[] = [
                        'team_id' => $teamData['team_id'],
                        'user_id' => $user->id,
                        'role' => $assignment['role'],
                        'joined_at' => $joinedAt,
                        'is_active' => rand(0, 100) > 5, // 95% activos
                        'created_at' => $joinedAt,
                        'updated_at' => $joinedAt->addDays(rand(0, 30)),
                    ];
                }
            }
        }

        // Agregar algunos usuarios que están en múltiples teams (cross-functional)
        $crossFunctionalAssignments = [
            // Sarah está en múltiples teams
            [
                'team_id' => 1, // Frontend
                'user_id' => $usersByEmail->get('sarah@taskflow.com')->id,
                'role' => 'OBSERVER',
                'joined_at' => now()->subMonths(4),
                'is_active' => true,
            ],
            [
                'team_id' => 5, // Product
                'user_id' => $usersByEmail->get('sarah@taskflow.com')->id,
                'role' => 'ANALYST',
                'joined_at' => now()->subMonths(2),
                'is_active' => true,
            ],
            // John también colabora con otros teams
            [
                'team_id' => 4, // DevOps
                'user_id' => $usersByEmail->get('john@taskflow.com')->id,
                'role' => 'OBSERVER',
                'joined_at' => now()->subMonths(6),
                'is_active' => true,
            ],
            [
                'team_id' => 8, // Security
                'user_id' => $usersByEmail->get('john@taskflow.com')->id,
                'role' => 'DEVELOPER',
                'joined_at' => now()->subMonths(3),
                'is_active' => true,
            ],
            // Algunos senior developers en múltiples proyectos
            [
                'team_id' => 9, // Design
                'user_id' => $usersByEmail->get('alex.johnson@taskflow.com')->id,
                'role' => 'OBSERVER',
                'joined_at' => now()->subMonths(2),
                'is_active' => true,
            ],
            [
                'team_id' => 6, // Mobile
                'user_id' => $usersByEmail->get('kevin.brown@taskflow.com')->id,
                'role' => 'DEVELOPER',
                'joined_at' => now()->subMonths(1),
                'is_active' => true,
            ],
        ];

        foreach ($crossFunctionalAssignments as $assignment) {
            $teamUserInserts[] = [
                'team_id' => $assignment['team_id'],
                'user_id' => $assignment['user_id'],
                'role' => $assignment['role'],
                'joined_at' => $assignment['joined_at'],
                'is_active' => $assignment['is_active'],
                'created_at' => $assignment['joined_at'],
                'updated_at' => $assignment['joined_at']->addDays(rand(0, 30)),
            ];
        }

        // Insertar todas las asignaciones
        $chunks = array_chunk($teamUserInserts, 30);
        foreach ($chunks as $chunk) {
            DB::table('team_user')->insert($chunk);
        }
    }
}
