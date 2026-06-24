<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Bidang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('bidang', 'roles')->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $bidang = Bidang::all();
        $roles = Role::all();
        return view('users.create', compact('bidang', 'roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'password' => 'required|min:8|confirmed',
            'nip' => 'nullable|unique:users',
            'bidang_id' => 'nullable|exists:bidang,id',
            'jabatan' => 'nullable|max:255',
            'no_telp' => 'nullable|max:20',
            'role' => 'required|exists:roles,name',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);
        $user->assignRole($validated['role']);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $bidang = Bidang::all();
        $roles = Role::all();
        return view('users.edit', compact('user', 'bidang', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'nip' => 'nullable|unique:users,nip,' . $user->id,
            'bidang_id' => 'nullable|exists:bidang,id',
            'jabatan' => 'nullable|max:255',
            'no_telp' => 'nullable|max:20',
            'role' => 'required|exists:roles,name',
            'is_active' => 'boolean',
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);
        $user->syncRoles([$validated['role']]);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri.');
        }
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dinonaktifkan.');
    }
}
