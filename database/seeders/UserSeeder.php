<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@hamora.test',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);
        $superAdmin->assignRole('Super Admin');

        $admin = User::create([
            'name' => 'Admin RSUD',
            'email' => 'admin@hamora.test',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);
        $admin->assignRole('Admin');

        $user = User::create([
            'name' => 'User Biasa',
            'email' => 'user@hamora.test',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);
        $user->assignRole('User');
    }
}
