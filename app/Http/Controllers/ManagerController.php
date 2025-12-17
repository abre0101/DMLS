<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\LetterTemplate;
use App\Models\ActivityLog;
use App\Models\Letter;
use App\Models\Task;
use App\Models\ApprovalRequest;
use Illuminate\Support\Facades\Response; 
use Barryvdh\DomPDF\Facade\Pdf; 

class ManagerController extends Controller
{
   public function index(Request $request)
{
    $manager = Auth::user();
    $department = $manager->department;
    $departmentId = $department?->id;

    $baseQuery = Document::with('uploadedBy')
        ->whereHas('uploadedBy', fn($q) => $q->where('department_id', $departmentId));

    $search = $request->input('search');

    // Document status trends for dashboard visualization
    $trends = (clone $baseQuery)
        ->selectRaw("
            CASE
                WHEN manager_approval IS NULL THEN 'pending'
                WHEN manager_approval = 1 THEN 'approved'
                WHEN manager_approval = 0 THEN 'rejected'
            END as status,
            COUNT(*) as count
        ")
        ->groupByRaw("
            CASE
                WHEN manager_approval IS NULL THEN 'pending'
                WHEN manager_approval = 1 THEN 'approved'
                WHEN manager_approval = 0 THEN 'rejected'
            END
        ")
        ->get()
        ->pluck('count', 'status');
 $managerId = auth()->id();

    // Example: tasks assigned by director to this manager
    $tasks = Task::where('assigned_to', $managerId)
                 ->where('status', 'pending')
                 ->orderBy('due_date', 'asc')
                 ->get();
    $pendingDocuments = (clone $baseQuery)
        ->whereNull('manager_approval')
        ->when($search, fn($q) => $q->where('title', 'like', "%{$search}%"))
        ->paginate(10, ['*'], 'pending');

    $approvedDocuments = (clone $baseQuery)
        ->where('manager_approval', 1)
        ->when($search, fn($q) => $q->where('title', 'like', "%{$search}%"))
        ->paginate(10, ['*'], 'approved');

    $rejectedDocuments = (clone $baseQuery)
        ->where('manager_approval', 0)
        ->when($search, fn($q) => $q->where('title', 'like', "%{$search}%"))
        ->paginate(10, ['*'], 'rejected');

    // Get only the pending approval requests (full collection for listing)
    $pendingApprovalRequests = ApprovalRequest::where('status', 'pending')->get();

    // Just count of pending approval requests
    $PendingApprovalRequestscount = ApprovalRequest::where('status', 'pending')->count();

    // Counts for summary cards
    $pendingDocumentsCount = (clone $baseQuery)->whereNull('manager_approval')->count();
    $totalDocumentsCount = (clone $baseQuery)->count();
    $lettersToManagerCount = Letter::where('receiver_id', $manager->id)->count();

    // Recent activity log for the department
    $recentActivities = ActivityLog::where('department_id', $departmentId)
        ->latest()
        ->take(5)
        ->get();

    $notifications = []; // Replace with real notifications query if needed

    return view('manager.dashboard', compact(
        'pendingDocuments',
        'approvedDocuments',
        'rejectedDocuments',
        'pendingDocumentsCount',
        'totalDocumentsCount',
        'lettersToManagerCount',
        'PendingApprovalRequestscount',
        'recentActivities',
        'pendingApprovalRequests',
        'trends',
        'tasks',
        'department',
        'notifications'
    ));
}

public function export(Request $request)
{
    $format = $request->get('format', 'csv'); // default to csv

    $manager = Auth::user();
    $department = $manager->department;
    $departmentName = $department?->name ?? 'Not Assigned';
    $departmentId = $department?->id;

    $baseQuery = Document::whereHas('uploadedBy', fn($q) => $q->where('department_id', $departmentId));

    $pendingDocuments = (clone $baseQuery)->whereNull('manager_approval')->count();
    $totalDocuments = (clone $baseQuery)->count();
    $lettersReceived = Letter::where('receiver_id', $manager->id)->count();
    $pendingApprovals = ApprovalRequest::where('status', 'pending')->count();

    if ($format === 'pdf') {
        // Pass variables individually matching the Blade template keys
        $pdf = Pdf::loadView('manager.reports.pdf', [
            'department' => $departmentName,
            'pendingDocuments' => $pendingDocuments,
            'totalDocuments' => $totalDocuments,
            'lettersReceived' => $lettersReceived,
            'pendingApprovals' => $pendingApprovals,
        ]);

        $filename = 'manager_dashboard_report_' . now()->format('Ymd_His') . '.pdf';
        return $pdf->download($filename);
    }

    // CSV export
    $reportData = [
        'Department' => $departmentName,
        'Pending Documents' => $pendingDocuments,
        'Total Documents' => $totalDocuments,
        'Letters Received' => $lettersReceived,
        'Pending Approval Requests' => $pendingApprovals,
    ];

    $filename = 'manager_dashboard_report_' . now()->format('Ymd_His') . '.csv';
    $handle = fopen('php://temp', 'r+');
    fputcsv($handle, ['Metric', 'Value']);
    foreach ($reportData as $label => $value) {
        fputcsv($handle, [$label, $value]);
    }
    rewind($handle);
    $csv = stream_get_contents($handle);
    fclose($handle);

    return Response::make($csv, 200, [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"$filename\"",
    ]);
}



    public function approve(Document $document)
    {
        $manager = Auth::user();

        if ($document->uploadedBy->department_id !== $manager->department_id) {
            abort(403, 'Unauthorized action.');
        }

        if (!is_null($document->manager_approval)) {
            return redirect()->route('manager.dashboard')->with('info', 'This document has already been processed.');
        }

        $document->manager_approval = 1;
        $document->director_approval = null;

        $director = User::where('role_id', 2)  // assuming role_id 2 = director
            ->where('department_id', $manager->department_id)
            ->first();

        if ($director) {
            $document->assigned_to = $director->id;
            // Optional: notify director here
        }

        $document->save();

        return redirect()->route('manager.dashboard')->with('success', 'Document approved and sent to director for review.');
    }

    // Reject document and return to uploader
    public function reject(Document $document)
    {
        $manager = Auth::user();

        if ($document->uploadedBy->department_id !== $manager->department_id) {
            abort(403, 'Unauthorized action.');
        }

        if (!is_null($document->manager_approval)) {
            return redirect()->route('manager.dashboard')->with('info', 'This document has already been processed.');
        }

        $document->manager_approval = 0;
        $document->director_approval = null;
        $document->assigned_to = $document->uploadedBy->id;

        $document->save();

        return redirect()->route('manager.dashboard')->with('error', 'Document rejected and sent back to employee.');
    }

    // Show form to create letter (you might want to rename this to createLetter if used)
    public function createLetter()
    {
        $templates = LetterTemplate::all();
        return view('manager.letters.create', compact('templates'));
    }

    // Store letter
    public function storeLetter(Request $request)
    {
        $request->validate([
            'template_id' => 'nullable|exists:letter_templates,id',
            'content' => 'required|string',
            'receiver_id' => 'required|exists:users,id',
            'parent_id' => 'nullable|exists:letters,id',
            'direction' => 'required|in:incoming,outgoing',
            'status' => 'required|string',
        ]);

        Letter::create([
            'template_id' => $request->template_id ?? null,
            'content' => $request->content,
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'parent_id' => $request->parent_id ?? null,
            'direction' => $request->direction,
            'status' => $request->status,
        ]);

        return redirect()->route('manager.letters.index')->with('success', 'Letter created successfully.');
    }

    // Get template content as JSON (for AJAX)
    public function getContent($id)
    {
        $template = LetterTemplate::findOrFail($id);
        return response()->json(['content' => $template->content]);
    }
}
