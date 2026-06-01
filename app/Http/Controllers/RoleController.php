<?php
namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::orderBy('name')->get()->groupBy(function ($p) {
            $prefix = explode(' ', $p->name)[0];
            return match($prefix) {
                'kelola' => 'Manajemen',
                'hapus', 'restore' => 'Dokumen',
                'verifikasi', 'upload', 'edit', 'lihat' => 'Dokumen',
                default => 'Lainnya',
            };
        });
        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::create(['name' => $request->name]);
        $role->givePermissionTo($request->permissions);

        return redirect()->route('roles.index')->with('success', 'Role berhasil dibuat.');
    }

    public function edit(Role $role)
    {
        if ($role->name === 'Super Admin') {
            return back()->with('error', 'Super Admin tidak bisa diedit.');
        }

        $permissions = Permission::orderBy('name')->get()->groupBy(function ($p) {
            $prefix = explode(' ', $p->name)[0];
            return match($prefix) {
                'kelola' => 'Manajemen',
                'hapus', 'restore' => 'Dokumen',
                'verifikasi', 'upload', 'edit', 'lihat' => 'Dokumen',
                default => 'Lainnya',
            };
        });
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        if ($role->name === 'Super Admin') {
            return back()->with('error', 'Super Admin tidak bisa diedit.');
        }

        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        return redirect()->route('roles.index')->with('success', 'Role berhasil diperbarui.');
    }

    public function destroy(Role $role)
    {
        if ($role->name === 'Super Admin') {
            return back()->with('error', 'Super Admin tidak bisa dihapus.');
        }

        if ($role->users()->count() > 0) {
            return back()->with('error', 'Role masih digunakan oleh user.');
        }

        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role berhasil dihapus.');
    }
}
