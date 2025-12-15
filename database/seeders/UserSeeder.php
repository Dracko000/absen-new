<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Superadmin
        $superadmin = User::create([
            'name' => 'Superadmin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password'),
        ]);
        $superadmin->assignRole('Superadmin');

        // Create Admin (Teacher)
        $admin = User::create([
            'name' => 'John Teacher',
            'email' => 'teacher@example.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('Admin');

        // Create User (Student)
        $user = User::create([
            'name' => 'Jane Student',
            'email' => 'student@example.com',
            'password' => Hash::make('password'),
        ]);
        $user->assignRole('User');
    }
}
