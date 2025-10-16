<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;


class RolesController extends Controller
{
    public function assignRole(Request $request)
    {
        // Validate the request
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|string|in:employee,manager',
        ]);

        // Find the user
        $user = User::find($request->user_id);

        // Assign the role
        $user->assignRole($request->role);

        return response()->json(['message' => 'Role assigned successfully.']);
    }
}
