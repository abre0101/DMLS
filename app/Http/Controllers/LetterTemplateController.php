<?php

namespace App\Http\Controllers;

use App\Models\LetterTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LetterTemplateController extends Controller
{
    public function __construct()
    {
        // Allow only Admin, HR, Manager, and Director roles
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            if (!in_array($user->role_id, ['1', '3', '5'])) {
                abort(403, 'Unauthorized');
            }
            return $next($request);
        });
    }

    // List templates for the user's department with pagination
    public function index()
    {
        $user = Auth::user();

        $templates = LetterTemplate::where('department_id', $user->department_id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('manager.letters.templates.index', compact('templates'));
    }

    // Show form to create a new template
    public function create()
    {
        return view('manager.letters.templates.create');
    }

    // Store a new letter template
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'title' => 'required|string|unique:letter_templates,title,NULL,id,department_id,' . $user->department_id,
            'content' => ['required', 'string', function ($attribute, $value, $fail) {
                if (!preg_match('/\{.+?\}/', $value)) {
                    $fail('The content must include at least one placeholder enclosed in curly braces, e.g. {name}.');
                }
            }],
        ]);

        LetterTemplate::create([
            'department_id' => $user->department_id,
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return redirect()->route('manager.letters.templates.index')->with('success', 'Template created successfully.');
    }

    // Show form to edit existing template
    public function edit(LetterTemplate $template)
    {
        $user = Auth::user();

        if ($template->department_id !== $user->department_id) {
            abort(403, 'Unauthorized');
        }

        return view('manager.letters.templates.edit', compact('template'));
    }

    // Update the existing template
    public function update(Request $request, LetterTemplate $template)
    {
        $user = Auth::user();

        if ($template->department_id !== $user->department_id) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'title' => 'required|string|unique:letter_templates,title,' . $template->id . ',id,department_id,' . $user->department_id,
            'content' => ['required', 'string', function ($attribute, $value, $fail) {
                if (!preg_match('/\{.+?\}/', $value)) {
                    $fail('The content must include at least one placeholder enclosed in curly braces, e.g. {name}.');
                }
            }],
        ]);

        $template->update([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return redirect()->route('manager.letters.templates.index')->with('success', 'Template updated successfully.');
    }

    // Delete a letter template
    public function destroy(LetterTemplate $template)
    {
        $user = Auth::user();

        if ($template->department_id !== $user->department_id) {
            abort(403, 'Unauthorized');
        }

        $template->delete();

        return redirect()->route('manager.letters.templates.index')->with('success', 'Template deleted successfully.');
    }

    // Fetch template content by ID (for AJAX or API)
    public function getContent($id)
    {
        $template = LetterTemplate::findOrFail($id);
        return response()->json(['content' => $template->content]);
    }
public function show($id)
{
    $letterTemplate = LetterTemplate::findOrFail($id);
    return view('letter_templates.show', compact('letterTemplate'));
}


}
