<?php

namespace App\Http\Controllers;

use App\Models\ApprovalRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovalRequestController extends Controller
{
    /**
     * Display a listing of the approval requests.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch pending approval requests assigned to the logged-in user
        $pendingApprovals = ApprovalRequest::where('status', ApprovalRequest::STATUS_PENDING)
                                           ->where('approver_id', Auth::id()) // Only show approvals relevant to user
                                           ->get();

        return view('approval_requests.index', compact('pendingApprovals'));
    }

    /**
     * Approve an approval request with e-signature data.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\ApprovalRequest $approval
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve(Request $request, ApprovalRequest $approval)
    {
        if (Auth::id() !== $approval->approver_id) {
            return redirect()->back()->with('error', 'Unauthorized approval attempt.');
        }
    
        $approval->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);
    
        return redirect()->back()->with('success', 'Approval completed successfully.');
    }
    
     
    /**
     * Reject an approval request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\ApprovalRequest $approval
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Request $request, ApprovalRequest $approval)
    {
        // Ensure the logged-in user is authorized to reject
        if (Auth::id() !== $approval->approver_id) {
            return redirect()->back()->with('error', 'Unauthorized rejection attempt.');
        }

        $approval->reject();

        return redirect()->back()->with('success', 'Approval request rejected successfully.');
    }

    /**
     * Display the specified approval request.
     *
     * @param \App\Models\ApprovalRequest $approval
     * @return \Illuminate\View\View
     */
    public function show(ApprovalRequest $approval)
    {
        // Ensure the logged-in user is authorized to view the approval request
        if (Auth::id() !== $approval->approver_id && Auth::id() !== $approval->user_id) {
            return redirect()->route('approval_requests.index')->with('error', 'Unauthorized access.');
        }

        return view('approval_requests.show', compact('approval'));
    }
}
