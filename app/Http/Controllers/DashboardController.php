<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Letter;
use App\Models\ApprovalRequest;
use App\Models\Workflow;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class DashboardController extends Controller
{
    /**
     * Redirect users to their respective dashboards based on role.
     */
 public function index()
{
    $user = Auth::user();

    if (!$user || !$user->role_id) {
        return view('dashboard'); // fallback view for guests or misconfigured roles
    }

    $currentRoute = request()->route()->getName();

    return match ($user->role_id) {
        1 => $currentRoute !== 'admin.dashboard' ? redirect()->route('admin.dashboard') : view('admin.dashboard'),
        3 => $currentRoute !== 'manager.dashboard' ? redirect()->route('manager.dashboard') : view('manager.dashboard'),
        5 => $currentRoute !== 'director.dashboard' ? redirect()->route('director.dashboard') : view('director.dashboard'),
        default => $currentRoute !== 'employee.dashboard' ? redirect()->route('employee.dashboard') : view('employee.dashboard'),
    };
}


    /**
     * Show the dashboard view for employee role.
     */
    public function dashboard()
    {
        $user = Auth::user();

        return view('employee.dashboard', [
            'taskcount'            => $user->tasks()->count(),
            'notifications'        => $user->notifications,
            'documentCount'        => Document::where('user_id', $user->id)->count(),
            'letterCount'          => Letter::where('user_id', $user->id)->count(),
            'pendingApprovals'     => ApprovalRequest::where('status', 'pending')
                                                     ->whereNull('approved_at')
                                                     ->where('user_id', $user->id)
                                                     ->get(),
            'pendingWorkflowCount' => Workflow::where('status', 'pending')->count(),
            'tasks'                => Task::where('assigned_to', $user->id)->get(),
              'pendingDocumentsCount' => $pendingDocumentsCount,
        ]);
    }

    /**
     * Log out the authenticated user and invalidate session.
     */
    public function destroy(Request $request): \Illuminate\Http\RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Approve a document if authorized.
     */
    public function approveDocument(Request $request, int $documentId): \Illuminate\Http\RedirectResponse
    {
        if (!Gate::allows('approve', Document::class)) {
            return redirect()->back()->with('error', 'You do not have permission to approve this document.');
        }

        $document = Document::findOrFail($documentId);

        $document->update([
            'status'       => 'approved',
            'approved_at'  => now(),
            'approved_by'  => Auth::id(),
        ]);

        return redirect()->route('employee.dashboard')->with('success', 'Document approved successfully.');
    }
}
