<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['roles', 'department']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(10);
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $departments = Department::all();
        $roles = Role::all();
        return view('admin.users.create', compact('departments', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|max:255|unique:users',
            'password'      => 'required|string|min:8|confirmed',
            'department_id' => 'required|exists:departments,id',
            'status'        => 'required|in:active,blocked',
            'role'          => 'required|string|exists:roles,name',
        ]);

        $user = User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'department_id' => $request->department_id,
            'status'        => $request->status,
        ]);

        $user->assignRole($request->role);

        return redirect()->route('admin.users')->with('success', 'User created successfully.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $departments = Department::all();
        $roles = Role::all();

        return view('admin.users.edit', compact('user', 'departments', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|max:255|unique:users,email,' . $id,
            'department_id' => 'required|exists:departments,id',
            'status'        => 'required|in:active,blocked',
            'role'          => 'required|string|exists:roles,name',
        ]);

        $user->update([
            'name'          => $request->name,
            'email'         => $request->email,
            'department_id' => $request->department_id,
            'status'        => $request->status,
        ]);

        $user->syncRoles([$request->role]);

        return redirect()->route('admin.users')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
    }
}
