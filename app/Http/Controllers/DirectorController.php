<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

use App\Models\{
    ApprovalRequest,
    Document,
    DocumentComment,
    Letter,
    LetterTemplate,
    Notification,
    User,
    Task,
    AccessLog,
    IntegrationStatus,
    Department,
    Role
};

class DirectorController extends Controller
{
    /**
     * Show the director dashboard with various stats and charts.
     */
    public function dashboard(): \Illuminate\View\View
    {
        $user = Auth::user();

        // Count of documents pending director approval
        $executivePendingApprovals = Document::where('manager_approval', 1)
            ->whereNull('director_approval')
            ->whereNull('e_signed_at')
            ->count();

        // Count of approval requests pending >3 days at director level
        $bottlenecks = ApprovalRequest::where('status', 'pending')
            ->where('level', 'director')
            ->where('created_at', '<', now()->subDays(3))
            ->count();

        // Total signed documents
        $signedDocumentsCount = Document::whereNotNull('e_signed_at')->count();

$docUploadTrends = Document::selectRaw('departments.name as department, DATE_FORMAT(documents.created_at, "%Y-%m-%d") as day, COUNT(*) as uploads')
    ->join('departments', 'documents.department_id', '=', 'departments.id')
    ->where('documents.created_at', '>=', now()->subWeek())
    ->groupBy('departments.name', 'day')
    ->orderBy('day')
    ->orderBy('department')
    ->get();


        // Top 5 letter templates used
        $topTemplates = Letter::select('template_id', DB::raw('COUNT(*) as usage_count'))
            ->whereNotNull('template_id')
            ->groupBy('template_id')
            ->orderByDesc('usage_count')
            ->with('template')
            ->take(5)
            ->get();

        // Most active correspondents by sender and receiver
        $activeCorrespondents = Letter::selectRaw('sender_id, receiver_id, COUNT(*) as count')
            ->groupBy('sender_id', 'receiver_id')
            ->orderByDesc('count')
            ->with(['sender', 'receiver'])
            ->take(5)
            ->get();

        // Monthly letter volume
        $monthlyLetterVolume = Letter::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Documents count by department
        $departmentDocsCount = Document::selectRaw('departments.name as department, COUNT(*) as total_docs')
            ->join('departments', 'documents.department_id', '=', 'departments.id')
            ->groupBy('departments.name')
            ->get();

        // Letters count by department
        $departmentLettersCount = Letter::selectRaw('department, COUNT(*) as total_letters')
            ->groupBy('department')
            ->get();

        // Average approval times by department (in hours)
        $departmentApprovalTimes = Document::selectRaw('departments.name as department, AVG(TIMESTAMPDIFF(HOUR, documents.created_at, documents.updated_at)) as avg_approval_time')
            ->join('departments', 'documents.department_id', '=', 'departments.id')
            ->groupBy('departments.name')
            ->get();

        // Departments with lagging approvals (>72 hours)
        $departmentsLagging = $departmentApprovalTimes->filter(fn($dept) => $dept->avg_approval_time > 72);

        // Roles and their permissions
        $rolePermissions = Role::with('permissions')->get();

        // Unauthorized access attempts last 7 days
       

        // Data retention status counts
        $dataRetentionStatus = [
            'documents_pending_review' => Document::where('status', 'approved')
                ->where('updated_at', '<', now()->subYears(3))
                ->count(),
            'letters_pending_archive' => Letter::where('created_at', '<', now()->subYears(2))->count(),
        ];

        // Critical notifications count
 
        // Storage usage info (dummy method)
        $storageUsage = $this->getStorageUsage();

        // User counts
        $totalDocuments = Document::count();
        $activeUsersCount = User::where('status', 'active')->count();
        $inactiveUsersCount = User::where('status', 'blocked')->count();

        // API usage stats (dummy method)
        $apiUsageStats = $this->getApiUsageStats();

        // Integration statuses
        $integrationStatuses = IntegrationStatus::all();

        return view('director.dashboard', compact(
            'executivePendingApprovals',
            'bottlenecks',
            'totalDocuments',
            'signedDocumentsCount',
            'docUploadTrends',
            'topTemplates',
            'activeCorrespondents',
            'monthlyLetterVolume',
            'departmentDocsCount',
            'departmentLettersCount',
            'departmentApprovalTimes',
            'departmentsLagging',
            'rolePermissions',
           
            'dataRetentionStatus',
           
            'storageUsage',
            'activeUsersCount',
            'inactiveUsersCount',
            'apiUsageStats',
            'integrationStatuses',
        ));
    }

    /**
     * Approve a document with a director's signature.
     */
   public function approve(Request $request, Document $document)
{
    $request->validate([
        'signature' => 'required|string',
        'note' => 'nullable|string|max:1000',
    ]);

    $signatureData = $request->input('signature');

    if (preg_match('/^data:image\/png;base64,/', $signatureData)) {
        $signatureData = substr($signatureData, strpos($signatureData, ',') + 1);
        $signatureData = base64_decode($signatureData);

        $fileName = 'signatures/' . uniqid() . '.png';
        Storage::disk('public')->put($fileName, $signatureData);
    } else {
        return back()->withErrors(['signature' => 'Invalid signature format.']);
    }

    $document->update([
        'status' => 'approved',
        'approved_by' => auth()->id(),
        'approved_at' => now(),
        'director_signature' => $fileName,
        'director_approval' => 1,
        'e_signed_at' => now(),
    ]);

    if ($request->filled('note')) {
        DocumentComment::create([
            'document_id' => $document->id,
            'user_id' => auth()->id(),
            'comment' => $request->note,
        ]);
    }

    return redirect()->route('director.documents.index')->with('success', 'Document successfully approved!');
}

    /**
     * Show overdue tasks assigned to managers.
     */
    public function overdueTasks()
    {
        $managerRoleId = 3; // Assuming 3 is manager role ID
        $managerIds = User::where('role_id', $managerRoleId)->pluck('id');

        $tasks = collect();
        if ($managerIds->isNotEmpty()) {
            $tasks = Task::with('assignedToUser')
                ->whereIn('assigned_to', $managerIds)
                ->where('status', 'pending')
                ->where('due_date', '<', now())
                ->orderBy('due_date', 'asc')
                ->get();
        }

        return view('director.tasks.overdue', compact('tasks'));
    }

    /**
     * Reject a document with an optional note.
     */
    public function reject(Request $request, Document $document)
    {
        $request->validate([
            'note' => 'nullable|string|max:1000',
        ]);

        $document->update([
            'manager_approval' => 0,
            'status' => 'rejected',
            'director_approval' => 0,
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        if ($request->filled('note')) {
            DocumentComment::create([
                'document_id' => $document->id,
                'user_id' => auth()->id(),
                'comment' => $request->note,
            ]);
        }

        return redirect()->route('director.documents.index')
            ->with('status', 'Document rejected successfully.');
    }

    /**
     * Show activity summary for departments.
     */
    public function departmentActivity()
    {
        $departments = Department::withCount(['documents', 'letters', 'employees as employees_count'])->get();

        return view('director.departments.activity', compact('departments'));
    }

    /**
     * Show a document, only if manager approval is given.
     */
    public function showDocument(Document $document)
    {
        if (!$document->manager_approval) {
            abort(403, 'Document must be approved by manager before director review.');
        }

        return view('director.documents.show', compact('document'));
    }

    /**
     * Show form to create a task.
     */
    public function createTask()
    {
        $managerRoleId = 3;
        $managers = User::where('role_id', $managerRoleId)->get();

        return view('director.tasks.create', compact('managers'));
    }

    /**
     * Store a newly created task.
     */
    public function storeTask(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'required|exists:users,id',
            'due_date' => 'required|date|after:today',
        ]);

        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'assigned_to' => $request->assigned_to,
            'status' => Task::STATUS_PENDING,
            'due_date' => $request->due_date,
        ]);

        return redirect()->route('director.tasks.overdue')->with('success', 'Task created successfully.');
    }

    /**
     * Expedite an approval request.
     */
    public function expediteApproval(ApprovalRequest $approval)
    {
        $approval->updated_at = now();
        $approval->save();

        Notification::create([
            'user_id' => $approval->approver_id,
            'message' => "Approval for document #{$approval->document_id} has been expedited by director.",
            'status' => 'new',
        ]);

        return redirect()->back()->with('success', 'Approval expedited.');
    }

    /**
     * Override and approve an approval request.
     */
    public function overrideApproval(ApprovalRequest $approval)
    {
        $approval->update([
            'status' => 'approved',
            'approver_id' => Auth::id(),
            'updated_at' => now(),
        ]);

        $document = $approval->document;
        $document->update([
            'status' => 'approved',
            'director_approval' => true,
        ]);

        Notification::create([
            'user_id' => $document->creator_id,
            'message' => "Approval for document #{$approval->document_id} has been overridden and approved by director.",
            'status' => 'new',
        ]);

        return redirect()->back()->with('success', 'Approval overridden and document approved.');
    }

    /**
     * Dummy method to get storage usage stats.
     */
    protected function getStorageUsage(): array
    {
        return [
            'used_gb' => 120,
            'total_gb' => 500,
            'percent_used' => 24,
        ];
    }

   
    public function showLetter(Letter $letter)
    {
        $this->authorize('view', $letter);
        return view('director.letters.show', compact('letter'));
    }

   
    protected function getApiUsageStats(): array
    {
        return [
            'last_24h_calls' => 3500,
            'monthly_calls' => 102000,
            'error_rate_percent' => 0.3,
        ];
    }

 
    public function index()
    {
        $userId = Auth::id();

        $incomingLetters = Letter::where('receiver_id', $userId)
            ->where('direction', 'incoming')
            ->with('sender')
            ->latest()
            ->get();

        $outgoingLetters = Letter::where('sender_id', $userId)
            ->where('direction', 'outgoing')
            ->latest()
            ->get();

        return view('director.letters.index', compact('incomingLetters', 'outgoingLetters'));
    }

    /**
     * List documents approved by manager.
     */
public function listDocuments(Request $request)
{
    $query = Document::query()->where('manager_approval', 1);

    if ($request->filled('search')) {
        $query->where('title', 'like', '%' . $request->search . '%');
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    $documents = $query->latest()->paginate(10);

    return view('director.documents.index', compact('documents'));
}

    /**
     * Show form to create a letter.
     */
    public function createLetter()
    {
        $users = User::all();
        $templates = LetterTemplate::all();

        return view('director.letters.create', compact('users', 'templates'));
    }

    /**
     * Store a new letter based on template and fields.
     */
    public function storeLetter(Request $request)
    {
        $request->validate([
            'recipient_email' => 'required|email|exists:users,email',
            'recipient_name' => 'required|string|max:255',
            'template_id' => 'required|exists:letter_templates,id',
            'fields' => 'required|array',
            'fields.*' => 'required|string',
        ]);

        $template = LetterTemplate::findOrFail($request->template_id);
        $content = $template->content;

        // Replace placeholders in template content
        foreach ($request->fields as $key => $value) {
            $content = str_replace('{' . $key . '}', $value, $content);
        }

        $receiver = User::where('email', $request->recipient_email)->firstOrFail();

        $letter = Letter::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $receiver->id,
            'template_id' => $template->id,
            'content' => $content,
            'status' => 'sent',
            'direction' => 'outgoing',
        ]);

        return redirect()->route('director.letters.index')->with('success', 'Letter sent successfully.');
    }

    /**
     * Show form to edit a letter.
     */
    public function editLetter(Letter $letter)
    {
        $this->authorize('update', $letter);

        $users = User::all();
        $templates = LetterTemplate::all();

        return view('director.letters.edit', compact('letter', 'users', 'templates'));
    }

    /**
     * Update a letter.
     */
    public function updateLetter(Request $request, Letter $letter)
    {
        $this->authorize('update', $letter);

        $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'template_id' => 'nullable|exists:letter_templates,id',
        ]);

        $letter->update($request->only(['subject', 'content', 'template_id']));

        return redirect()->route('director.letters.index')->with('success', 'Letter updated successfully.');
    }

    /**
     * Delete a letter.
     */
    public function deleteLetter(Letter $letter)
    {
        $this->authorize('delete', $letter);
        $letter->delete();

        return redirect()->route('director.letters.index')->with('success', 'Letter deleted.');
    }

    /**
     * Generate PDF of a letter.
     */
    public function generatePdf(Letter $letter)
    {
        $pdf = Pdf::loadView('director.letters.pdf', compact('letter'));
        return $pdf->stream('letter_' . $letter->id . '.pdf');
    }
}
