<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin default (laundry.com)
        User::updateOrCreate(
            ['email' => 'admin@laundry.com'],
            [
                'name' => 'Administrator Laundry',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Admin alias (gmail.com) agar user bisa login dengan admin@gmail.com
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Administrator Laundry',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Kasir default (laundry.com)
        User::updateOrCreate(
            ['email' => 'kasir@laundry.com'],
            [
                'name' => 'Kasir Laundry',
                'password' => Hash::make('password'),
                'role' => 'kasir',
            ]
        );

        // Kasir alias (gmail.com)
        User::updateOrCreate(
            ['email' => 'kasir@gmail.com'],
            [
                'name' => 'Kasir Laundry',
                'password' => Hash::make('password'),
                'role' => 'kasir',
            ]
        );
    }
}
