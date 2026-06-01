<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'kelola user', 'kelola dokumen', 'hapus dokumen', 'restore dokumen',
            'kelola kategori', 'kelola bidang', 'verifikasi dokumen', 'edit metadata',
            'lihat log', 'upload dokumen', 'edit dokumen', 'lihat dokumen',
            'kelola role',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $superAdmin = Role::create(['name' => 'Super Admin']);
        $superAdmin->givePermissionTo(Permission::all());

        $admin = Role::create(['name' => 'Admin']);
        $admin->givePermissionTo([
            'kelola user', 'verifikasi dokumen', 'edit metadata', 'lihat log',
            'upload dokumen', 'edit dokumen', 'lihat dokumen',
            'kelola kategori', 'kelola bidang',
        ]);

        $user = Role::create(['name' => 'User']);
        $user->givePermissionTo([
            'upload dokumen', 'lihat dokumen',
        ]);
    }
}
