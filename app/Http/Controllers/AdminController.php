<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\User;
use App\Models\Role;
use App\Models\AccessLog;
use App\Models\Letter;
use App\Models\Department;
use App\Models\ApprovalRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function dashboard()
    {
        // Summary metrics
        $totalDocuments = Document::count();
        $pendingApprovals = Document::whereNull('manager_approval')->count();
        $activeUsers = User::where('last_active_at', '>=', now()->subDays(30))->count();
        $lettersSent = Letter::where('direction', 'outgoing')->count();
        $departmentsCount = Department::count();
        $storageUsed = $this->getStorageUsed();

        
       $documentsByStatus = [
    'pending' => Document::whereNull('director_approval')->count(),
    'approved' => Document::where('director_approval', 1)->count(),
    'rejected' => Document::where('director_approval', 0)->count(),
];


        // Recent documents for display
        $recentDocuments = Document::with('uploadedBy')
            ->latest()
            ->take(5)
            ->get();

        // Pending approval requests details
        $pendingApprovalRequests = ApprovalRequest::where('status', 'pending')->get();

        // Other useful stats if needed
        $lettersByStatus = Letter::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $userRolesCount = User::selectRaw('role_id, COUNT(*) as count')
            ->groupBy('role_id')
            ->pluck('count', 'role_id')
            ->toArray();
$unauthorizedAccessAttempts = AccessLog::where('status', 'unauthorized')
    ->where('created_at', '>=', now()->subDays(7))
    ->count();

        $newUsers = User::whereDate('created_at', '>=', now()->subDays(7))->get();


        // Fetch paginated users for user management table
        $users = User::with(['role', 'department'])->paginate(10);

        return view('admin.dashboard', compact(
            'totalDocuments',
            'pendingApprovals',
            'activeUsers',
            'lettersSent',
            'departmentsCount',
            'storageUsed',
            'unauthorizedAccessAttempts',
            'documentsByStatus',
            'recentDocuments',
            'pendingApprovalRequests',
            'lettersByStatus',
            'userRolesCount',
            'newUsers',
            'users' 
        ));
    }

   
    public function createUser()
    {
        $roles = Role::all(); 
        $departments = Department::all();

        return view('admin.users.create', compact('roles', 'departments'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role_id' => 'required|exists:roles,id',
            'department_id' => 'nullable|exists:departments,id',
      'status' => 'required|in:active,inactive',

        ]);

        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);
        $user->role_id = $validated['role_id'];
        $user->department_id = $validated['department_id'] ?? null;
        $user->status = $validated['status'];
        $user->save();

        return redirect()->route('admin.dashboard')->with('success', 'User created successfully.');
    }

   
    public function editUser(User $user)
    {
        $roles = Role::all();
        $departments = Department::all();

        return view('admin.users.edit', compact('user', 'roles', 'departments'));
    }

    /**
     * Update the specified user in storage.
     */
  public function updateUser(Request $request, User $user)
{
    $validated = $request->validate([

        'role_id' => 'required|exists:roles,id',
        'status' => 'required|in:active,blocked',
    ]);


    $user->role_id = $validated['role_id'];
    $user->status = $validated['status'];

    // Only update password if one is provided
    if (!empty($validated['password'])) {
        $user->password = Hash::make($validated['password']);
    }

    $user->save();

    return redirect()->route('admin.dashboard')->with('success', 'User updated successfully.');
}


    /**
     * Remove the specified user from storage.
     */
    public function destroyUser(User $user)
    {
        // Prevent deleting self
        if (Auth::id() === $user->id) {
            return redirect()->route('admin.dashboard')->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.dashboard')->with('success', 'User deleted successfully.');
    }

    /**
     * Calculate storage used by documents (example uses local storage disk).
     */
    private function getStorageUsed()
    {
        $files = Storage::disk('local')->files('documents');
        $totalBytes = 0;

        foreach ($files as $file) {
            $totalBytes += Storage::disk('local')->size($file);
        }

        return round($totalBytes / (1024 ** 3), 2); // GB with 2 decimals
    }
}
