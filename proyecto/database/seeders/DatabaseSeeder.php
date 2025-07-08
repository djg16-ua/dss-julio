<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,
            ProjectSeeder::class,
            TeamSeeder::class,
            ModuleSeeder::class,
            TaskSeeder::class,
            CommentSeeder::class,
        ]);
    }
}