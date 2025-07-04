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
        DB::table('teams')->insert([
            [
                'id' => 1,
                'name' => 'Frontend Team',
                'description' => 'UI/UX development and frontend implementation',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Backend Team',
                'description' => 'Server-side logic and API development',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'QA Team',
                'description' => 'Quality assurance and testing',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Obtener IDs de usuarios
        $john = DB::table('users')->where('email', 'john@taskflow.com')->first();
        $jane = DB::table('users')->where('email', 'jane@taskflow.com')->first();
        $bob = DB::table('users')->where('email', 'bob@taskflow.com')->first();
        $sarah = DB::table('users')->where('email', 'sarah@taskflow.com')->first();

        // Asignar usuarios a teams
        DB::table('team_user')->insert([
            [
                'team_id' => 1, // Frontend Team
                'user_id' => $jane->id,
                'role' => 'LEAD',
                'joined_at' => now()->subMonths(2),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'team_id' => 1, // Frontend Team
                'user_id' => $sarah->id,
                'role' => 'DESIGNER',
                'joined_at' => now()->subMonth(),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'team_id' => 2, // Backend Team
                'user_id' => $john->id,
                'role' => 'LEAD',
                'joined_at' => now()->subMonths(3),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'team_id' => 2, // Backend Team
                'user_id' => $sarah->id,
                'role' => 'DEVELOPER',
                'joined_at' => now()->subWeeks(3),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'team_id' => 3, // QA Team
                'user_id' => $bob->id,
                'role' => 'LEAD',
                'joined_at' => now()->subMonths(2),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
