<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DemoUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Superadmin
        $superadmin = User::create([
            'name' => 'Superadmin',
            'email' => 'superadmin@attendance.test',
            'password' => Hash::make('password'),
        ]);
        $superadmin->assignRole('Superadmin');

        echo "Superadmin created:\n";
        echo "- Email: superadmin@attendance.test\n";
        echo "- Password: password\n\n";

        // Create Admin (Teacher)
        $admin = User::create([
            'name' => 'John Teacher',
            'email' => 'teacher@attendance.test',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('Admin');

        echo "Admin (Teacher) created:\n";
        echo "- Email: teacher@attendance.test\n";
        echo "- Password: password\n\n";

        // Create User (Student)
        $user = User::create([
            'name' => 'Jane Student',
            'email' => 'student@attendance.test',
            'password' => Hash::make('password'),
        ]);
        $user->assignRole('User');

        echo "User (Student) created:\n";
        echo "- Email: student@attendance.test\n";
        echo "- Password: password\n\n";

        // Create additional teacher
        $teacher2 = User::create([
            'name' => 'Sarah Teacher',
            'email' => 'sarah@attendance.test',
            'password' => Hash::make('password'),
        ]);
        $teacher2->assignRole('Admin');

        echo "Additional teacher created:\n";
        echo "- Email: sarah@attendance.test\n";
        echo "- Password: password\n\n";

        // Create additional students
        $student2 = User::create([
            'name' => 'Bob Student',
            'email' => 'bob@attendance.test',
            'password' => Hash::make('password'),
        ]);
        $student2->assignRole('User');

        echo "Additional student created:\n";
        echo "- Email: bob@attendance.test\n";
        echo "- Password: password\n\n";

        $student3 = User::create([
            'name' => 'Alice Student',
            'email' => 'alice@attendance.test',
            'password' => Hash::make('password'),
        ]);
        $student3->assignRole('User');

        echo "Additional student created:\n";
        echo "- Email: alice@attendance.test\n";
        echo "- Password: password\n\n";
    }
}
