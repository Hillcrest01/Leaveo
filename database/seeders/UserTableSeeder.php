<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User::create([
        //     'name' => 'Admin',
        //     'email' => 'admin@example.com',
        //     'password' => bcrypt('password'),
        //     'employee_id' => 'EMP_001',
        // ]);

        // User::create([
        //     'name' => 'Ketray Safu',
        //     'email' => 'hsafu@dsl.ke',
        //     'password' => bcrypt('Ketray.Safu@2026'),
        //     'employee_id' => 'EMP_002',
        //     'role' => 'employee',
        // ]);

               User::create([
            'name' => 'Leaveo HR Manager',
            'email' => 'hr@leaveo.co.ke',
            'password' => bcrypt('Leaveo'),
            'employee_id' => 'EMP_001',
            'role' => 'hr',
        ]);

        //   User::create([
        //     'name' => 'Test User',
        //     'email' => 'test@dsl.ke',
        //     'password' => bcrypt('test@dsl.ke'),
        //     'employee_id' => 'EMP_003',
        //     'role' => 'employee',
        // ]);
    }
}
