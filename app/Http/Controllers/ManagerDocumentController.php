<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ManagerDocumentController extends Controller
{
  


    public function index(Request $request)
    {
        $manager = Auth::user();
        $departmentId = $manager->department_id;

        $documents = Document::with(['uploadedBy', 'tags', 'versions'])
            ->whereHas('uploadedBy', fn($q) => $q->where('department_id', $departmentId))
            ->whereNull('manager_approval')
            ->when($request->title, fn($q) => $q->where('title', 'like', "%{$request->title}%"))
            ->when($request->submitter, fn($q) => $q->whereHas('uploadedBy', fn($sub) => $sub->where('name', 'like', "%{$request->submitter}%")))
            ->when($request->upload_date, fn($q) => $q->whereDate('created_at', $request->upload_date))
            ->when($request->tag, fn($q) => $q->whereHas('tags', fn($q) => $q->where('name', $request->tag)))
            ->paginate(10);

        return view('manager.documents.index', compact('documents'));
    }

    public function show(Document $document)
    {
        $manager = Auth::user();

        abort_if($document->uploadedBy->department_id !== $manager->department_id, 403);

        $document->load(['uploadedBy', 'tags', 'comments', 'versions']);

        return view('manager.documents.show', compact('document'));
    }

    public function approve(Request $request, Document $document)
    {
        $manager = Auth::user();

        abort_if($document->uploadedBy->department_id !== $manager->department_id, 403);

        if (!is_null($document->manager_approval)) {
            return back()->with('info', 'This document has already been processed.');
        }

        $request->validate([
            'signature' => 'required|string',
            'note' => 'nullable|string|max:1000',
        ]);

        $signatureData = $request->input('signature');

        if (preg_match('/^data:image\/(\w+);base64,/', $signatureData, $type)) {
            $signatureData = substr($signatureData, strpos($signatureData, ',') + 1);
            $type = strtolower($type[1]);

            if (!in_array($type, ['jpg', 'jpeg', 'png', 'gif'])) {
                return back()->withErrors(['signature' => 'Invalid image type. Allowed types: jpg, jpeg, png, gif']);
            }
        } else {
            return back()->withErrors(['signature' => 'Invalid signature format.']);
        }

        $signatureDecoded = base64_decode($signatureData);

        if ($signatureDecoded === false) {
            return back()->withErrors(['signature' => 'Failed to decode signature.']);
        }

    
        $fileName = 'signatures/manager_signature_' . $document->id . '_' . time() . '.' . $type;
        Storage::disk('public')->put($fileName, $signatureDecoded);

       
        $document->manager_approval = 1; 
        $document->director_approval = null; 
        $document->manager_signature = $fileName; 
        $document->status = 'pending'; 
        $document->save();

        if ($request->filled('note')) {
            DocumentComment::create([
                'document_id' => $document->id,
                'user_id' => $manager->id,
                'comment' => $request->note,
            ]);
        }

        return redirect()->route('manager.documents.index')->with('success', 'Document approved and forwarded to director.');
    }

    public function reject(Request $request, Document $document)
    {
        $manager = Auth::user();

        abort_if($document->uploadedBy->department_id !== $manager->department_id, 403);

        if (!is_null($document->manager_approval)) {
            return back()->with('info', 'This document has already been processed.');
        }

        $request->validate([
            'note' => 'nullable|string|max:1000',
        ]);

        $document->manager_approval = 0; 
        $document->status = 'rejected';
        $document->director_approval = null;
        $document->manager_signature = null;
        $document->save();


        if ($request->filled('note')) {
            DocumentComment::create([
                'document_id' => $document->id,
                'user_id' => $manager->id,
                'comment' => $request->note,
            ]);
        }

        return redirect()->route('manager.documents.index')->with('error', 'Document rejected and sent back to employee.');
    }
     
}


