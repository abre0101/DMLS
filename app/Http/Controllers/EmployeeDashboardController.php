<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\{
    Document,
    ApprovalRequest,
    Letter,
    Category,
    Task,
    Department,
    Version,
    Collaboration,
    Reply
};

class EmployeeDashboardController extends Controller
{
  
    public function index(Request $request)
{
    $user = Auth::user();

    // Start the document query
    $documentQuery = Document::with(['category', 'workflow'])
        ->where('user_id', $user->id);

    // Apply filters
    if ($request->filled('title')) {
        $documentQuery->where('title', 'like', '%' . $request->title . '%');
    }

    if ($request->filled('submitter')) {
        $documentQuery->whereHas('user', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->submitter . '%');
        });
    }

    if ($request->filled('upload_date')) {
        $documentQuery->whereDate('created_at', $request->upload_date);
    }

    if ($request->filled('tag')) {
        $documentQuery->where('metadata', 'like', '%' . $request->tag . '%'); // Adjust if tag data is stored elsewhere
    }

    $documents = $documentQuery->latest()->paginate(7); // changed from `take(7)->get()` to `paginate()`

    // ...unchanged code
    $sentLetters = Letter::with(['receiver', 'template'])
        ->where('sender_id', $user->id)->latest()->take(5)->get();

    $receivedLetters = Letter::with(['sender', 'template'])
        ->where('receiver_id', $user->id)->latest()->take(5)->get();

    $notifications = $user->unreadNotifications()->get();
    $notificationsGroupedByDocument = $notifications->groupBy(fn($n) => $n->data['document_title'] ?? 'General');

    $pendingApprovals = ApprovalRequest::with('user')
        ->where('status', ApprovalRequest::STATUS_PENDING)
        ->where('user_id', $user->id)->latest()->get();

    $pendingDocuments = Document::with(['user', 'category'])
        ->where('status', Document::STATUS_PENDING)
        ->where('user_id', $user->id)->latest()->get();

    $tasks = Task::where('assigned_to', $user->id)->orderBy('due_date')->get();

    $activeCollaborationsCount = Document::where(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->orWhereHas('collaborators', fn($q) => $q->where('user_id', $user->id));
        })
        ->whereHas('collaborators')
        ->whereIn('status', ['active', 'pending'])
        ->count();

    $stats = [
        'total_documents' => Document::where('user_id', $user->id)->count(),
        'letters_sent' => $sentLetters->count(),
        'pending_approvals' => $pendingDocuments->count(),
        'active_collaborations' => $activeCollaborationsCount,
    ];

    $integrationStatuses = DB::table('integration_statuses')->get();
    $categories = Category::all();

    return view('employee.dashboard', compact(
        'documents',
        'tasks',
        'pendingApprovals',
        'pendingDocuments',
        'sentLetters',
        'receivedLetters',
        'categories',
        'stats',
        'notificationsGroupedByDocument',
        'integrationStatuses'
    ));
}


  public function showLetter(Letter $letter)
{
    $this->authorize('view', $letter);

  \Log::info('Before update:', ['is_seen' => $letter->is_seen, 'seen_at' => $letter->seen_at]);

if (!$letter->is_seen) {
    $letter->update([
        'is_seen' => true,
        'seen_at' => now(),
    ]);
}

$letter->refresh();

\Log::info('After update:', ['is_seen' => $letter->is_seen, 'seen_at' => $letter->seen_at]);


    return view('employee.letters.show', compact('letter'));
}


    public function employeeIndex()
    {
        $user = Auth::user();

        $documents = Document::with('category')
            ->where('user_id', $user->id)
            ->latest()->paginate(10);

        return view('employee.documents.index', compact('documents', 'user'));
    }

    
    public function employeeShow(int $id)
    {
        $document = Document::with(['category', 'workflow', 'versions'])->findOrFail($id);
        $this->authorize('view', $document);

        return view('employee.documents.show', compact('document'));
    }

  
    public function downloadVersion(int $versionId)
    {
        $version = Version::findOrFail($versionId);

        if (!Storage::exists($version->file_path)) {
            return back()->with('error', 'File not found.');
        }

        $fileName = $version->original_name ?? "version_{$version->id}.pdf";
        return Storage::download($version->file_path, $fileName);
    }

    public function restoreVersion(int $versionId)
    {
        $version = Version::findOrFail($versionId);
        $document = $version->document;

        $this->authorize('update', $document);

        $document->update([
            'file_path' => $version->file_path,
            'watermark' => $version->watermark ?? $document->watermark,
            'version' => $version->version,
        ]);

        return redirect()->route('employee.documents.show', $document->id)
            ->with('success', 'Version restored successfully.');
    }

   
    public function exportPDF(Letter $letter)
    {
        $user = Auth::user();

        if ($letter->sender_id !== $user->id && $letter->receiver_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $letter->load(['sender', 'receiver']);

        $pdf = Pdf::loadView('employee.letters.download', [
            'letter' => $letter,
            'employee' => $user,
        ]);

        return $pdf->download("Letter_{$letter->id}.pdf");
    }

    public function replyForm(Letter $letter)
    {
        $this->authorize('reply', $letter);
        return view('employee.letters.reply', compact('letter'));
    }

 public function sendReply(Request $request, Letter $letter)
{
    $this->authorize('reply', $letter);

    $request->validate([
        'body' => 'required|string|max:5000',
    ]);

    $action = $request->input('action'); // 'send', 'draft', or 'archive'

    if ($action === 'archive') {
        $letter->update(['status' => 'archived']);
        return redirect()->route('employee.dashboard')->with('success', 'Letter archived.');
    }

    // Create the reply
    Letter::create([
        'sender_id' => Auth::id(),
        'receiver_id' => $letter->sender_id,
        'subject' => 'RE: ' . $letter->subject,
        'content' => $request->input('body'),
        'parent_id' => $letter->id,
        'letter_template_id' => $letter->letter_template_id,
        'status' => $action === 'draft' ? 'draft' : 'sent',
    ]);

   
    $letter->update(['as_seen' => 1]);

    $message = $action === 'draft' ? 'Reply saved as draft.' : 'Reply sent.';
    return redirect()->route('employee.dashboard')->with('success', $message);
}

    public function editReply(Letter $letter, Reply $reply)
    {
        $this->authorize('update', $reply);
        return view('employee.letters.edit-reply', compact('letter', 'reply'));
    }


    public function updateReply(Request $request, Letter $letter, Reply $reply)
    {
        $this->authorize('update', $reply);

        $request->validate([
            'reply' => 'required|string|max:5000',
        ]);

        $reply->content = $request->input('reply');
        $reply->save();

        return redirect()->route('employee.letters.show', $letter->id)
            ->with('success', 'Reply updated successfully.');
    }

    public function listLetters()
    {
        $letters = Auth::user()->letters()->paginate(10);
        return view('employee.letters.index', compact('letters'));
    }

  
    public function create()
    {
        $user = Auth::user();

        if (!$user->hasRole('employee')) {
            abort(403, 'Unauthorized action.');
        }

        $categories = Category::all();
        $departments = Department::all();

        return view('employee.documents.create', compact('categories', 'departments'));
    }
}
