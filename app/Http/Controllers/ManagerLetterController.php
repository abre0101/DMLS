<?php
namespace App\Http\Controllers;

use App\Models\LetterTemplate;
use App\Models\Letter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf; // for PDF export

class ManagerLetterController extends Controller
{
    // Show form to create a new letter
    public function create()
    {
        $manager = Auth::user();

        $users = User::where('id', '!=', $manager->id)
            ->whereNotNull('role_id')
            ->with('role') // eager load role relationship if needed
            ->get();

        $templates = LetterTemplate::where('department_id', $manager->department_id)->get();

        return view('manager.letters.create', compact('users', 'templates'));
    }
public function index()
{
    $manager = Auth::user();

    // Example: show all letters related to this manager (sent or received)
    $letters = Letter::where('sender_id', $manager->id)
                     ->orWhere('receiver_id', $manager->id)
                     ->with(['sender.role', 'receiver.role'])
                     ->latest()
                     ->paginate(10);

    return view('manager.letters.index', compact('letters'));
}

    // Show list of templates for manager's department
    public function templates()
    {
        $manager = Auth::user();
        $templates = LetterTemplate::where('department_id', $manager->department_id)->paginate(10);
        return view('manager.letters.templates.index', compact('templates'));
    }

    // Show a single letter template details
    public function showTemplate(LetterTemplate $template)
    {
        $this->authorizeDepartment($template->department_id);
        return view('manager.letters.templates.show', compact('template'));
    }

    // Show form to create a new template
    public function createTemplate()
    {
        return view('manager.letters.templates.create');
    }

    // Store new template
    public function storeTemplate(Request $request)
    {
        $manager = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        LetterTemplate::create([
            'department_id' => $manager->department_id,
            'name' => $request->name,
            'content' => $request->content,
        ]);

        return redirect()->route('manager.letters.templates.index')->with('success', 'Template created.');
    }

    // Edit template form
    public function editTemplate(LetterTemplate $template)
    {
        $this->authorizeDepartment($template->department_id);
        return view('manager.letters.templates.edit', compact('template'));
    }

    // Update template
    public function updateTemplate(Request $request, LetterTemplate $template)
    {
        $this->authorizeDepartment($template->department_id);

        $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $template->update($request->only('name', 'content'));

        return redirect()->route('manager.letters.templates.index')->with('success', 'Template updated.');
    }

    // Delete template
    public function deleteTemplate(LetterTemplate $template)
    {
        $this->authorizeDepartment($template->department_id);
        $template->delete();

        return redirect()->route('manager.letters.templates.index')->with('success', 'Template deleted.');
    }

  

public function storeByEmail(Request $request)
{
    $request->validate([
        'recipient_name' => 'required|string|max:255',
        'recipient_email' => 'required|email',
        'template_id' => 'required|exists:letter_templates,id',
        'fields' => 'required|array',
    ]);

    $template = LetterTemplate::findOrFail($request->template_id);
    $content = $template->content;

    foreach ($request->fields as $key => $value) {
        $pattern = '/\{' . preg_quote($key, '/') . '\}/';
        $content = preg_replace($pattern, $value, $content);
    }

    if (preg_match('/\{.+?\}/', $content)) {
        return back()->withErrors(['fields' => 'Please fill in all placeholder fields properly.'])->withInput();
    }

    $receiver = User::where('email', $request->recipient_email)->first();

    if (!$receiver) {
        return back()->withErrors(['recipient_email' => 'Recipient email not found in the system.'])->withInput();
    }

    Letter::create([
        'sender_id' => Auth::id(),
        'receiver_id' => $receiver->id,
        'template_id' => $template->id,
        'content' => $content,
        'status' => 'sent',
    ]);

    return redirect()->route('manager.letters.index')->with('success', 'Letter saved successfully.');
}


    // Show form to send letter using templates
    public function showSendForm ()
    {  
        $templates = LetterTemplate::all();

    // Get all users
        $users = User::all();
        $manager = Auth::user();
        if (!$manager) {
            abort(403, 'Unauthorized.');
        }
      

        return view('manager.letters.send', compact('templates', 'users'));
    }
public function send(Request $request)
{
    $validated = $request->validate([
        'template_id' => 'required|exists:templates,id',
        'receiver_id' => 'required|exists:users,id',    
        'content'     => 'required|string',
        'status'      => 'nullable|in:draft,sent',
    ]);

    $letter = Letter::create([
        'template_id' => $validated['template_id'],
        'receiver_id' => $validated['receiver_id'],
        'sender_id'   => auth()->id(),
        'content'     => $validated['content'],
        'status'      => $validated['status'] ?? 'sent',
    ]);

    return redirect()->route('manager.letters.sent')->with('success', 'Letter ' . ($letter->status === 'draft' ? 'saved as draft' : 'sent') . ' successfully.');
}
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
 
]);



    return redirect()->route('manager.letters.index')->with('success', 'Letter created successfully.');
}



public function archive(Letter $letter)
{
    $this->authorize('update', $letter);

    $letter->update(['status' => 'archived']);

    return back()->with('success', 'Letter archived successfully.');
}
public function show(Letter $letter)
{
    $manager = Auth::user();

    // Ensure manager is sender or receiver
    if ($letter->sender_id !== $manager->id && $letter->receiver_id !== $manager->id) {
        abort(403, 'Unauthorized access.');
    }

    // ✅ Mark as read if the current user is the receiver and the letter is unread
    if ($letter->receiver_id === $manager->id && !$letter->is_read) {
        $letter->is_read = true;
        $letter->save();
    }

    $letter->load(['sender.role', 'receiver.role']);

    return view('manager.letters.show', compact('letter'));
}

    // Store/send a letter
    public function sendLetter(Request $request)
    {
        $manager = Auth::user();

        $request->validate([
            'template_id' => 'required|exists:letter_templates,id',
            'receiver_id' => 'required|exists:users,id',
          
            'content' => 'required|string',
        ]);

        $template = LetterTemplate::findOrFail($request->template_id);
        $this->authorizeDepartment($template->department_id);

        Letter::create([
            'template_id' => $template->id,
            'sender_id' => $manager->id,
            'receiver_id' => $request->receiver_id,
           
            'content' => $request->content,
            'status' => 'sent',
        ]);

        // TODO: Notify recipient

        return redirect()->route('manager.letters.sent')->with('success', 'Letter sent successfully.');
    }

    // List incoming letters to manager
    public function incomingLetters()
    {
        $manager = Auth::user();

        $letters = Letter::where('receiver_id', $manager->id)
            ->with(['sender.role', 'receiver.role'])
            ->latest()
            ->paginate(10);

        return view('manager.letters.incoming', compact('letters'));
    }

    // List letters sent by manager
    public function sentLetters()
    {
        $manager = Auth::user();

        $letters = Letter::where('sender_id', $manager->id)
            ->with(['receiver.role', 'sender.role'])
            ->latest()
            ->paginate(10);

        return view('manager.letters.sent', compact('letters'));
    }

    // Export letter as PDF
    public function exportPDF(Letter $letter)
    {
        $manager = Auth::user();

        if ($letter->sender_id !== $manager->id && $letter->receiver_id !== $manager->id) {
            abort(403, 'Unauthorized');
        }

        $letter->load(['sender', 'receiver']);

        $pdf = Pdf::loadView('manager.letters.pdf', ['letter' => $letter, 'manager' => $manager]);
        return $pdf->download("Letter_{$letter->id}.pdf");
    }

    // Inbox view
    public function inbox()
    {
        $user = Auth::user();

        $letters = Letter::where('receiver_id', $user->id)
            ->with(['sender.role', 'receiver.role'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('manager.letters.inbox.index', compact('letters'));
    }

    // Check if manager belongs to given department
   protected function authorizeDepartment($departmentId)
{
    $manager = Auth::user();
    
    if ($manager->department_id !== $departmentId) {
        abort(403, 'Unauthorized access to this department.');
    }
}




  

}
