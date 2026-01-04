<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [];

        // 3 user utama dengan role berbeda
        $users[] = [
            'name' => 'Admin',
            'email' => 'admin@persil.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $users[] = [
            'name' => 'Super Admin',
            'email' => 'superadmin@persil.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $users[] = [
            'name' => 'User Biasa',
            'email' => 'user@persil.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // 97 user tambahan dengan role user
        for ($i = 4; $i <= 100; $i++) {
            $users[] = [
                'name' => 'User ' . $i,
                'email' => 'user' . $i . '@persil.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'created_at' => now()->subDays(mt_rand(1, 365)),
                'updated_at' => now(),
            ];
        }

        DB::table('users')->insert($users);
    }
}
