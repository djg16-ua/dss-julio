<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Usuarios principales (los que ya tenías)
        $mainUsers = [
            [
                'name' => 'Admin User',
                'email' => 'admin@taskflow.com',
                'password' => Hash::make('password'),
                'role' => 'ADMIN',
                'email_verified_at' => now(),
                'created_at' => now()->subYears(2),
                'updated_at' => now()->subYears(2),
            ],
            [
                'name' => 'John Developer',
                'email' => 'john@taskflow.com',
                'password' => Hash::make('password'),
                'role' => 'USER',
                'email_verified_at' => now(),
                'created_at' => now()->subYears(1),
                'updated_at' => now()->subMonths(3),
            ],
            [
                'name' => 'Jane Designer',
                'email' => 'jane@taskflow.com',
                'password' => Hash::make('password'),
                'role' => 'USER',
                'email_verified_at' => now(),
                'created_at' => now()->subYears(1),
                'updated_at' => now()->subWeeks(2),
            ],
            [
                'name' => 'Bob Tester',
                'email' => 'bob@taskflow.com',
                'password' => Hash::make('password'),
                'role' => 'USER',
                'email_verified_at' => now(),
                'created_at' => now()->subMonths(8),
                'updated_at' => now()->subWeeks(1),
            ],
            [
                'name' => 'Sarah Lead',
                'email' => 'sarah@taskflow.com',
                'password' => Hash::make('password'),
                'role' => 'USER',
                'email_verified_at' => now(),
                'created_at' => now()->subMonths(10),
                'updated_at' => now()->subDays(5),
            ],
        ];

        $additionalUsers = [
            ['name' => 'Michael Rodriguez', 'email' => 'michael.rodriguez@taskflow.com', 'role' => 'ADMIN'],
            ['name' => 'Lisa Chen', 'email' => 'lisa.chen@taskflow.com', 'role' => 'USER'],
            ['name' => 'David Wilson', 'email' => 'david.wilson@taskflow.com', 'role' => 'USER'],
            ['name' => 'Emma Thompson', 'email' => 'emma.thompson@taskflow.com', 'role' => 'USER'],

            ['name' => 'Alex Johnson', 'email' => 'alex.johnson@taskflow.com', 'role' => 'USER'],
            ['name' => 'Maria Garcia', 'email' => 'maria.garcia@taskflow.com', 'role' => 'USER'],
            ['name' => 'Chris Lee', 'email' => 'chris.lee@taskflow.com', 'role' => 'USER'],
            ['name' => 'Sophie Anderson', 'email' => 'sophie.anderson@taskflow.com', 'role' => 'USER'],
            ['name' => 'Ryan Parker', 'email' => 'ryan.parker@taskflow.com', 'role' => 'USER'],
            ['name' => 'Nina Patel', 'email' => 'nina.patel@taskflow.com', 'role' => 'USER'],

            ['name' => 'Kevin Brown', 'email' => 'kevin.brown@taskflow.com', 'role' => 'USER'],
            ['name' => 'Amanda Taylor', 'email' => 'amanda.taylor@taskflow.com', 'role' => 'USER'],
            ['name' => 'Daniel Kim', 'email' => 'daniel.kim@taskflow.com', 'role' => 'USER'],
            ['name' => 'Jessica White', 'email' => 'jessica.white@taskflow.com', 'role' => 'USER'],
            ['name' => 'Marcus Johnson', 'email' => 'marcus.johnson@taskflow.com', 'role' => 'USER'],
            ['name' => 'Rachel Green', 'email' => 'rachel.green@taskflow.com', 'role' => 'USER'],
            ['name' => 'Brian Davis', 'email' => 'brian.davis@taskflow.com', 'role' => 'USER'],

            ['name' => 'Ashley Miller', 'email' => 'ashley.miller@taskflow.com', 'role' => 'USER'],
            ['name' => 'Tyler Wilson', 'email' => 'tyler.wilson@taskflow.com', 'role' => 'USER'],
            ['name' => 'Megan Clark', 'email' => 'megan.clark@taskflow.com', 'role' => 'USER'],
            ['name' => 'Jason Lopez', 'email' => 'jason.lopez@taskflow.com', 'role' => 'USER'],
            ['name' => 'Samantha Hill', 'email' => 'samantha.hill@taskflow.com', 'role' => 'USER'],

            ['name' => 'Andrew Scott', 'email' => 'andrew.scott@taskflow.com', 'role' => 'USER'],
            ['name' => 'Lauren Adams', 'email' => 'lauren.adams@taskflow.com', 'role' => 'USER'],
            ['name' => 'James Wright', 'email' => 'james.wright@taskflow.com', 'role' => 'USER'],
            ['name' => 'Stephanie Turner', 'email' => 'stephanie.turner@taskflow.com', 'role' => 'USER'],

            ['name' => 'Robert Martinez', 'email' => 'robert.martinez@taskflow.com', 'role' => 'USER'],
            ['name' => 'Jennifer Hall', 'email' => 'jennifer.hall@taskflow.com', 'role' => 'USER'],
            ['name' => 'Matthew Young', 'email' => 'matthew.young@taskflow.com', 'role' => 'USER'],
            ['name' => 'Olivia King', 'email' => 'olivia.king@taskflow.com', 'role' => 'USER'],

            ['name' => 'Ethan Cooper', 'email' => 'ethan.cooper@taskflow.com', 'role' => 'USER'],
            ['name' => 'Zoe Richardson', 'email' => 'zoe.richardson@taskflow.com', 'role' => 'USER'],
            ['name' => 'Lucas Bailey', 'email' => 'lucas.bailey@taskflow.com', 'role' => 'USER'],
            ['name' => 'Chloe Ward', 'email' => 'chloe.ward@taskflow.com', 'role' => 'USER'],
            ['name' => 'Noah Foster', 'email' => 'noah.foster@taskflow.com', 'role' => 'USER'],
            ['name' => 'Ava Torres', 'email' => 'ava.torres@taskflow.com', 'role' => 'USER'],

            ['name' => 'Liam Roberts', 'email' => 'liam.roberts@taskflow.com', 'role' => 'USER'],
            ['name' => 'Maya Singh', 'email' => 'maya.singh@taskflow.com', 'role' => 'USER'],
            ['name' => 'Owen Murphy', 'email' => 'owen.murphy@taskflow.com', 'role' => 'USER'],
            ['name' => 'Aria Collins', 'email' => 'aria.collins@taskflow.com', 'role' => 'USER'],
            ['name' => 'Caleb Rivera', 'email' => 'caleb.rivera@taskflow.com', 'role' => 'USER'],
            ['name' => 'Layla Stewart', 'email' => 'layla.stewart@taskflow.com', 'role' => 'USER'],
            ['name' => 'Hunter Brooks', 'email' => 'hunter.brooks@taskflow.com', 'role' => 'USER'],
            ['name' => 'Skylar Reed', 'email' => 'skylar.reed@taskflow.com', 'role' => 'USER'],

            ['name' => 'Gabriel Price', 'email' => 'gabriel.price@taskflow.com', 'role' => 'USER'],
            ['name' => 'Violet Hughes', 'email' => 'violet.hughes@taskflow.com', 'role' => 'USER'],
            ['name' => 'Isaac Watson', 'email' => 'isaac.watson@taskflow.com', 'role' => 'USER'],
            ['name' => 'Hazel Morgan', 'email' => 'hazel.morgan@taskflow.com', 'role' => 'USER'],
            ['name' => 'Julian Gray', 'email' => 'julian.gray@taskflow.com', 'role' => 'USER'],
            ['name' => 'Aurora James', 'email' => 'aurora.james@taskflow.com', 'role' => 'USER'],

            ['name' => 'Ezra Phillips', 'email' => 'ezra.phillips@taskflow.com', 'role' => 'USER'],
            ['name' => 'Luna Carter', 'email' => 'luna.carter@taskflow.com', 'role' => 'USER'],
            ['name' => 'Axel Evans', 'email' => 'axel.evans@taskflow.com', 'role' => 'USER'],
            ['name' => 'Sage Mitchell', 'email' => 'sage.mitchell@taskflow.com', 'role' => 'USER'],
            ['name' => 'Felix Ross', 'email' => 'felix.ross@taskflow.com', 'role' => 'USER'],
            ['name' => 'Iris Powell', 'email' => 'iris.powell@taskflow.com', 'role' => 'USER'],

            ['name' => 'Dante Bell', 'email' => 'dante.bell@taskflow.com', 'role' => 'USER'],
            ['name' => 'Willow Cox', 'email' => 'willow.cox@taskflow.com', 'role' => 'USER'],
            ['name' => 'Atlas Ward', 'email' => 'atlas.ward@taskflow.com', 'role' => 'USER'],
            ['name' => 'Ember Wood', 'email' => 'ember.wood@taskflow.com', 'role' => 'USER'],
            ['name' => 'Phoenix Hayes', 'email' => 'phoenix.hayes@taskflow.com', 'role' => 'USER'],
            ['name' => 'River Stone', 'email' => 'river.stone@taskflow.com', 'role' => 'USER'],

            ['name' => 'Orion Blake', 'email' => 'orion.blake@taskflow.com', 'role' => 'USER'],
            ['name' => 'Nova Cruz', 'email' => 'nova.cruz@taskflow.com', 'role' => 'USER'],
            ['name' => 'Kai Sullivan', 'email' => 'kai.sullivan@taskflow.com', 'role' => 'USER'],
            ['name' => 'Wren Fisher', 'email' => 'wren.fisher@taskflow.com', 'role' => 'USER'],
            ['name' => 'Rowan Cole', 'email' => 'rowan.cole@taskflow.com', 'role' => 'USER'],
            ['name' => 'Sage Harper', 'email' => 'sage.harper@taskflow.com', 'role' => 'USER'],

            ['name' => 'Zara Webb', 'email' => 'zara.webb@taskflow.com', 'role' => 'USER'],
            ['name' => 'Finn Palmer', 'email' => 'finn.palmer@taskflow.com', 'role' => 'USER'],
            ['name' => 'Ivy Russell', 'email' => 'ivy.russell@taskflow.com', 'role' => 'USER'],
            ['name' => 'Jude Knight', 'email' => 'jude.knight@taskflow.com', 'role' => 'USER'],
            ['name' => 'Evelyn Fox', 'email' => 'evelyn.fox@taskflow.com', 'role' => 'USER'],
            ['name' => 'Milo Barnes', 'email' => 'milo.barnes@taskflow.com', 'role' => 'USER'],
            ['name' => 'Vera Santos', 'email' => 'vera.santos@taskflow.com', 'role' => 'USER'],
            ['name' => 'Theo Reid', 'email' => 'theo.reid@taskflow.com', 'role' => 'USER'],
            ['name' => 'Jade Duncan', 'email' => 'jade.duncan@taskflow.com', 'role' => 'USER'],
            ['name' => 'Leo Walsh', 'email' => 'leo.walsh@taskflow.com', 'role' => 'USER'],

            ['name' => 'Ruby Hayes', 'email' => 'ruby.hayes@taskflow.com', 'role' => 'USER'],
            ['name' => 'Oscar Lane', 'email' => 'oscar.lane@taskflow.com', 'role' => 'USER'],
            ['name' => 'Nora Pierce', 'email' => 'nora.pierce@taskflow.com', 'role' => 'USER'],
            ['name' => 'Dean Wells', 'email' => 'dean.wells@taskflow.com', 'role' => 'USER'],
            ['name' => 'Leah Stone', 'email' => 'leah.stone@taskflow.com', 'role' => 'USER'],
            ['name' => 'Max Cross', 'email' => 'max.cross@taskflow.com', 'role' => 'USER'],
            ['name' => 'Cora Flynn', 'email' => 'cora.flynn@taskflow.com', 'role' => 'USER'],
            ['name' => 'Jax Burns', 'email' => 'jax.burns@taskflow.com', 'role' => 'USER'],
            ['name' => 'Tessa Wade', 'email' => 'tessa.wade@taskflow.com', 'role' => 'USER'],
            ['name' => 'Kane Sharp', 'email' => 'kane.sharp@taskflow.com', 'role' => 'USER'],
            ['name' => 'Bria Moon', 'email' => 'bria.moon@taskflow.com', 'role' => 'USER'],
        ];

        DB::table('users')->insert($mainUsers);

        $usersToInsert = [];
        foreach ($additionalUsers as $index => $user) {
            $createdAt = now()->subDays(rand(1, 730));
            $updatedAt = $createdAt->addDays(rand(0, 30));

            $usersToInsert[] = [
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make('password'),
                'role' => $user['role'],
                'email_verified_at' => $createdAt->addMinutes(rand(5, 60)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ];
        }

        $chunks = array_chunk($usersToInsert, 20);
        foreach ($chunks as $chunk) {
            DB::table('users')->insert($chunk);
        }
    }
}
