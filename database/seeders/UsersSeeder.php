<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'role' => 'super_admin',
                'name' => 'Super Admin',
                'email' => 'superadmin@minipos.test',
                'password' => Hash::make('123456'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role' => 'admin',
                'name' => 'Admin',
                'email' => 'admin@minipos.test',
                'password' => Hash::make('123456'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role' => 'user',
                'name' => 'User',
                'email' => 'user@minipos.test',
                'password' => Hash::make('123456'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        User::insert($users);
    }
}
