<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Enums\UserRole;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Lindan Maulana',
            'email'=> 'lindanmaulana@gmail.com',
            'password' => bcrypt('lindan123'),
            'role' => UserRole::Admin
        ]);

        User::create([
            'name' => 'Aditya Ramadhan',
            'email' => 'aditya@gmail.com',
            'password' => bcrypt('aditya123'),
            'role' => UserRole::Customer
        ]);
    }
}
