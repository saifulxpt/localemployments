<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['phone' => '01700000000'],
            [
                'name'           => 'Admin',
                'phone'          => '01700000000',
                'email'          => 'admin@localemployments.com',
                'password'       => Hash::make('Admin@12345'),
                'role'           => 'admin',
                'phone_verified' => true,
                'status'         => 'active',
            ]
        );
    }
}
