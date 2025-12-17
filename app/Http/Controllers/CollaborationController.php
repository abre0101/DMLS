<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\ActivityLog;
use App\Models\User; // ✅ Correct placement
use App\Models\Department;
use App\Models\Category;
 // Make sure you have a Department model
class CollaborationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
    
        $documents = Document::where(function($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->orWhereHas('collaborators', function ($q) use ($user) {
                      $q->where('user_id', $user->id);
                  });
        })->with(['collaborators', 'approvedBy', 'rejectedBy'])->get();
    
        $documentIds = $documents->pluck('id');
    
        $activities = ActivityLog::whereIn('id', $documentIds)
            ->latest()
            ->limit(10)
            ->get();
    
        return view('collaboration.index', compact('documents', 'activities'));
    }
    

    public function create()
    {
        $users = User::all();
        $departments = Department::all();
        $categories = Category::all();  // Fetch all categories
    
        return view('collaboration.create', compact('users', 'departments', 'categories'));
    }
    

 public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'file' => 'required|file',
        'department' => 'required|exists:departments,id',  // Validate department id properly
        'category_id' => 'required|exists:categories,id',
        'collaborators' => 'array',
        'collaborators.*' => 'exists:users,id',
    ]);
    
    $path = $request->file('file')->store('documents');

    $document = Document::create([
        'title' => $validated['title'],
        'description' => $validated['description'],
        'file_path' => $path,
        'file_type' => $request->file('file')->getClientOriginalExtension(),
        'user_id' => auth()->id(),
        'status' => 'pending',
        'department_id' => $validated['department'], // store as department_id
        'category_id' => $validated['category_id'],
    ]);

    // Attach the creator as a collaborator
    $document->collaborators()->attach(auth()->id());

    // Attach other collaborators if any
    if (\Schema::hasTable('document_user') && $request->has('collaborators')) {
        $document->collaborators()->attach($validated['collaborators']);
    }

    return redirect()->route('collaboration.index')->with('success', 'Collaboration started.');
}

    
    
}
