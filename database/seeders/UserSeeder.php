<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultRoles = Role::defaultRoles();

        foreach ($defaultRoles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        $uper_admin = User::create([
            'username' => 'superadmin',
            'name' => 'Ikhwanul Akhmad. DLY',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('password'),
        ]);
        $uper_admin->assignRole('Super Admin');

        $admin = User::create([
            'username' => 'admin',
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('Admin');

        // User::factory(200)->create();
    }
}
