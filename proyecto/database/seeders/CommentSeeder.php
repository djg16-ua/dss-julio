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
        $john = DB::table('users')->where('email', 'john@taskflow.com')->first();
        $jane = DB::table('users')->where('email', 'jane@taskflow.com')->first();
        $bob = DB::table('users')->where('email', 'bob@taskflow.com')->first();
        $admin = DB::table('users')->where('email', 'admin@taskflow.com')->first();

        // Obtener tareas específicas
        $ecommerce = DB::table('projects')->where('title', 'E-commerce Platform')->first();
        $authModuleEcommerce = DB::table('modules')
            ->where('project_id', $ecommerce->id)
            ->where('name', 'Authentication')
            ->first();
        
        $loginTaskEcommerce = DB::table('tasks')
            ->where('module_id', $authModuleEcommerce->id)
            ->where('title', 'Create login form')
            ->first();
        
        $registerTaskEcommerce = DB::table('tasks')
            ->where('module_id', $authModuleEcommerce->id)
            ->where('title', 'User registration')
            ->first();

        // Crear comentarios
        DB::table('comments')->insert([
            [
                'content' => 'Remember to include password strength validation with clear feedback to users.',
                'user_id' => $john->id,
                'task_id' => $loginTaskEcommerce->id,
                'created_at' => now()->subHours(2),
                'updated_at' => now()->subHours(2),
            ],
            [
                'content' => 'I will add the password visibility toggle and ensure mobile compatibility.',
                'user_id' => $jane->id,
                'task_id' => $loginTaskEcommerce->id,
                'created_at' => now()->subHour(),
                'updated_at' => now()->subHour(),
            ],
            [
                'content' => 'We need to implement email verification for new user registrations.',
                'user_id' => $bob->id,
                'task_id' => $registerTaskEcommerce->id,
                'created_at' => now()->subMinutes(30),
                'updated_at' => now()->subMinutes(30),
            ],
            [
                'content' => 'Great progress on the authentication module! Keep up the good work.',
                'user_id' => $admin->id,
                'task_id' => $registerTaskEcommerce->id,
                'created_at' => now()->subMinutes(15),
                'updated_at' => now()->subMinutes(15),
            ],
        ]);
    }
}
