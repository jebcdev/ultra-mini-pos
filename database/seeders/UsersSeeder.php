<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //minipos.test

        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@minipos.test',
            'password' => Hash::make('123456'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Admin',
            'email' => 'admin@minipos.test',
            'password' => Hash::make('123456'),
            'email_verified_at' => now(),
        ]);
    }
}
