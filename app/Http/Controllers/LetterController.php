<?php

namespace App\Http\Controllers;

use App\Models\Letter;
use App\Models\LetterTemplate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Facades\Mail;



class LetterController extends Controller
{
    /**
     * Show form to create a new letter.
     */
    public function create()
    {
        $user = Auth::user();
        $templates = LetterTemplate::where('department_id', $user->department_id)->get(); // restrict templates by user department if needed
        $users = User::all(); 
        
        return view('manager.letters.create', compact('users', 'templates'));
    }

    /**
     * Fetch the content of a letter template by ID (AJAX).
     */
    public function getContent($id)
    {
        $template = LetterTemplate::findOrFail($id);
        return response()->json(['content' => $template->content]);
    }


    public function sendLetterEmail($id)
    {
        $letter = Letter::findOrFail($id);
        $user = auth()->user();
    
        Mail::to($letter->receiver->email)->send(new \App\Mail\LetterMail($letter, $user));
    
        return back()->with('success', 'Letter sent via email.');
    }
    
    /**
     * Store a new letter based on user input and a selected template.
     */

    public function exportDocx($id)
    {
        $user = auth()->user();
        if (!in_array($user->role_id, [1, 2, 5])) {
            abort(403, 'Unauthorized action.');
        }
    
        $letter = Letter::with(['receiver', 'sender'])->findOrFail($id);
        $brandingUser = $user;
    
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
    
        // Add logo
        $section->addImage(public_path('images/logo.png'), ['width' => 120]);
    
        // Add branding, header, content, footer
        $section->addText($brandingUser->department->name . " Department", ['bold' => true, 'size' => 20, 'color' => '004085']);
        $section->addText('Official Letter', ['size' => 12, 'color' => '666666']);
        $section->addTextBreak(1);
    
        $section->addText("To: " . $letter->receiver->name);
        $section->addText("From: " . $letter->sender->name);
        $section->addText("Subject: " . $letter->subject);
        $section->addTextBreak(1);
    
        $section->addText(strip_tags($letter->content), ['size' => 14]);
        $section->addTextBreak(1);
    
        $section->addText("Generated on " . now()->format('Y-m-d H:i'), ['size' => 10, 'color' => '999999'], ['alignment' => 'center']);
    
        $filename = 'letter_'.$letter->id.'.docx';
    
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header("Content-Disposition: attachment; filename={$filename}");
        header('Cache-Control: max-age=0');
    
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save('php://output');
        exit;
    }
    
    public function exportPdf($id)
    {
        $user = auth()->user();

        // Check if user's role_id is admin (1), manager (2), or director (5)
        if (! in_array($user->role_id, [1, 2, 5])) {
            abort(403, 'Unauthorized action.');
        }

        // Fetch the letter and its relations
        $letter = Letter::with(['receiver', 'sender'])->findOrFail($id);

        // Pass $user as $manager for branding
        $manager = $user;

        $pdf = PDF::loadView('letters.pdf', compact('letter', 'manager'));

        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('letter_'.$letter->id.'.pdf');
    }


    public function store(Request $request)
    {
        $request->validate([
            'recipient_name' => 'required|string|max:255',
            'recipient_email' => 'required|email',
            'template_id' => 'required|exists:letter_templates,id',
            'fields' => 'required|array',
        ]);

        $template = LetterTemplate::findOrFail($request->template_id);
        $content = $template->content;

        // Replace placeholders with submitted field values
        // Assumes placeholders like {name}, {date}, etc. (curly braces without spaces)
        foreach ($request->fields as $key => $value) {
            // Use preg_quote in case $key contains special regex chars
            $pattern = '/\{' . preg_quote($key, '/') . '\}/';
            $content = preg_replace($pattern, $value, $content);
        }

        // Optional: Check if all placeholders have been replaced, else fail
        if (preg_match('/\{.+?\}/', $content)) {
            return back()->withErrors(['fields' => 'Please fill in all placeholder fields properly.'])->withInput();
        }

        // Find the receiver by email
        $receiver = User::where('email', $request->recipient_email)->firstOrFail();

        $letter = new Letter();
        $letter->sender_id = Auth::id();
        $letter->receiver_id = $receiver->id;
        $letter->content = $content;
        $letter->save();

        return redirect()->route('manager.letters.index') // Make sure this route exists or update accordingly
                         ->with('success', 'Letter sent successfully.');
    }

    /**
     * Mark a letter as read and redirect to its detail view.
     */
    public function markAsRead(Letter $letter)
    {
        if (!$letter->is_read) {
            $letter->is_read = true;
            $letter->save();
        }

        return redirect()->route('manager.letters.show', $letter->id); // Adjust route as needed
    }

    /**
     * Show a specific letter with sender and receiver data.
     */
    public function show($id)
    {
        $user = Auth::user();
        $letter = Letter::with(['sender', 'receiver'])->findOrFail($id);

        if ($letter->sender_id !== $user->id && $letter->receiver_id !== $user->id) {
            abort(403, 'Unauthorized access to this letter.');
        }

        return view('manager.letters.show', compact('letter'));
    }

    /**
     * List all letters related to the current user.
     */
    public function index()
    {
        $user = Auth::user();

        $letters = Letter::where('sender_id', $user->id)
                         ->orWhere('receiver_id', $user->id)
                         ->latest()
                         ->paginate(10); // paginate instead of get for scalability

        return view('manager.letters.index', compact('letters'));
    }
}
