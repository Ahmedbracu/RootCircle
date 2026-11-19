<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'ahmedchad27@gmail.com'],
            [
                'name'     => 'Admin',
                'password' => Hash::make('Z123456@a'),
                'role'     => 'admin',
            ]
        );
    }
}
