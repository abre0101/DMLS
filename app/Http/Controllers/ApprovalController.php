<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\ApprovalRequest;
use App\Models\Document;
use App\Models\User;


class ApprovalController extends Controller
{
 
public function index()
{
    $user = Auth::user();


    // Common logic
    $approvals = ApprovalRequest::with('document', 'user')
        ->where('approver_id', $user->id)
        ->latest()
        ->paginate(10);

    $pendingApprovals = ApprovalRequest::with('document')
        ->where('approver_id', $user->id)
        ->where('status', 'pending')
        ->latest()
        ->get();

    // Role-specific document filters
    if ($user->role_id === '5') {
        $pendingDocuments = Document::where('status', 'manager_approved') // director sees manager-approved docs
            ->with('user', 'category')
            ->latest()
            ->get();

        return view('director.approvals.index', compact('approvals', 'pendingApprovals', 'pendingDocuments'));
    }

    if ($user->role_id=== '3') {
        $pendingDocuments = Document::where('status', 'pending') // manager sees pending docs
            ->with('user', 'category')
            ->latest()
            ->get();

        return view('manager.approvals.index', compact('approvals', 'pendingApprovals', 'pendingDocuments'));
    }

    abort(403, 'Unauthorized role.');
}

    /**
     * Store a new approval request.
     */
    public function store(Request $request)
{
    $request->validate([
        'document_id' => 'required|exists:documents,id',
        'notes' => 'nullable|string',
    ]);

    $user = Auth::user();

 
$manager = $user->department?->manager;



    if (!$manager) {
        return back()->with('error', 'No manager found for your department.');
    }

    // Create a new approval request
    $approvalRequest = new ApprovalRequest();
    $approvalRequest->document_id = $request->document_id;
    $approvalRequest->user_id = $user->id; 
    $approvalRequest->approver_id = $manager->id; 
    $approvalRequest->escalated_to_id = null; 
    $approvalRequest->level = 1;
    $approvalRequest->status = 'pending'; 
    $approvalRequest->approved_at = null; 
    $approvalRequest->notes = $request->notes ?? null; 
    $approvalRequest->save();

    return redirect()->route('employee.dashboard')->with('success', 'Approval request submitted to your department manager.');
}
/*

    public function sign(Request $request, $approvalId)
{
    $request->validate([
        'signature' => 'required|string',
    ]);

    $approval = Approval::findOrFail($approvalId);

    // Decode base64 image
    $signatureData = $request->input('signature');
    list($type, $data) = explode(';', $signatureData);
    list(, $data) = explode(',', $data);
    $data = base64_decode($data);

    $fileName = 'signatures/approval_' . $approvalId . '_' . time() . '.png';
    Storage::disk('public')->put($fileName, $data);

    $approval->signature_path = $fileName;
    $approval->signed_at = now();
    $approval->save();

    return redirect()->route('dashboard')->with('success', 'Document signed successfully.');

    /**
     * Approve the document.
     */
  

    public function reject($id)
    {
        $approvalRequest = ApprovalRequest::findOrFail($id);
        $approvalRequest->status = 'rejected';
        $approvalRequest->approved_at = now(); // You can set this to null if you want
        $approvalRequest->save();

        // Optionally, you can notify the employee about the rejection here
        $document = Document::findOrFail($approvalRequest->document_id);
        // Notify the employee (you can send an email or any other notification)
        Mail::raw("Your document titled '{$document->title}' has been rejected by your manager.", function ($message) use ($document) {
            $message->to($document->user->email)
                    ->subject('Document Rejection Notification');
        });

       return redirect()->route('manager.approvals.index')->with('success', 'Document rejected and notification sent to the employee.');
    }

    /**
     * Send a reminder email to the approver/manager.
     */
    public function sendReminder(ApprovalRequest $approval)
    {
        // Ensure it's still pending and older than 1 day (adjust as needed)
        if ($approval->status !== 'pending' || $approval->created_at->gt(now()->subDays(1))) {
            return back()->with('error', 'Reminder cannot be sent at this time.');
        }

        // Get the approver
        $approver = $approval->approver;

        if (!$approver || !$approver->email) {
            return back()->with('error', 'Approver email not available.');
        }

        // Send the reminder email
        Mail::raw("Reminder: Please approve or reject the document titled '{$approval->document->name}'.", function ($message) use ($approver) {
            $message->to($approver->email)
                    ->subject('Document Approval Reminder');
        });

        return back()->with('success', 'Reminder sent successfully to the approver.');
    }

    /**
     * Show the form to create a new approval request.
     */
    public function create()
    {
        // Fetch documents created by the current user
        $documents = Document::where('user_id', Auth::id())->latest()->get();

        return view('approval_requests.create', compact('documents'));
    }
 public function approve($id)
{
    $approvalRequest = ApprovalRequest::findOrFail($id);
    $document = $approvalRequest->document;

    // First level: Manager approves
    if ($approvalRequest->level == 1) {
        $director = User::where('role_id', '5')->first();

        if (!$director) {
            return back()->with('error', 'No director found for approval.');
        }

        $approvalRequest->level = 2;
        $approvalRequest->approver_id = $director->id; // send to director
        $approvalRequest->status = 'pending';
        $approvalRequest->approved_at = now(); // record manager approval
        $approvalRequest->save();

        // Update document status to reflect intermediate stage
        $document->status = 'manager_approved';
        $document->save();

        return redirect()->route('manager.approvals.index')
                         ->with('success', 'Document approved by manager and sent to director for final approval.');
    }

    // Second level: Director approves
    if ($approvalRequest->level == 2) {
        $approvalRequest->status = 'approved';
        $approvalRequest->approved_at = now();
        $approvalRequest->save();

        // Update document status to final approval
        $document->status = 'approved';
        $document->approved_at = now();
        $document->approved_by = Auth::id();
        $document->save();

        return redirect()->route('director.approvals.index')
                         ->with('success', 'Document fully approved by director.');
    }

    return redirect()->route('manager.approvals.index')->with('error', 'Invalid approval level.');
}

public function sign(Request $request, $approvalId)
{
    $request->validate([
        'signature' => [
            'required',
            'string',
            'regex:/^data:image\/png;base64,/',
            'max:65535',
        ],
    ], [
        'signature.required' => 'A signature is required to approve this document.',
        'signature.regex' => 'The signature format is invalid. Please try again.',
        'signature.max' => 'Signature image is too large.',
    ]);

    $approval = ApprovalRequest::findOrFail($approvalId);

    // Decode base64 image
    $signatureData = $request->input('signature');
    list($type, $data) = explode(';', $signatureData);
    list(, $data) = explode(',', $data);
    $data = base64_decode($data);

    $fileName = 'signatures/approval_' . $approvalId . '_' . time() . '.png';
    Storage::disk('public')->put($fileName, $data);

    $approval->signature_path = $fileName;
    $approval->signed_at = now();
    $approval->save();

    return redirect()->route('dashboard')->with('success', 'Document signed successfully.');
}

public function show($id)
{
    $approval = ApprovalRequest::with(['document', 'user', 'approver'])->findOrFail($id);

    if ($approval->status !== 'pending') {
        return redirect()->route('manager.approvals.index')
                         ->with('info', 'This approval request has already been processed.');
    }

    $document = $approval->document;

    return view('manager.approvals.show', compact('approval', 'document'));
}


}