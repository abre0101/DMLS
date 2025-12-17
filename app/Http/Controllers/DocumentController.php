<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Category;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use setasign\Fpdi\Fpdi;
use App\Helpers\PdfWithRotation;
use App\Jobs\SendApprovalRequestJob;
use Smalot\PdfParser\Parser;
use PhpOffice\PhpWord\IOFactory;

class DocumentController extends Controller
{
  
    public function index(Request $request)
    {
        $query = Document::query();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if (in_array(strtolower($request->status), ['pending', 'approved', 'rejected'])) {
            $query->where('status', strtolower($request->status));
        }

        $user = Auth::user();

        if ($user->hasRole('Admin')) {
            // Admin sees all documents
        } elseif ($user->hasRole('Director') || $user->hasRole('Manager')) {
            $query->where('department_id', $user->department_id);
        } else {
            $query->where('user_id', $user->id);
        }

        $documents = $query->with(['category', 'department'])
            ->latest()
            ->paginate(10);

        $categories = Category::all();
        $departments = Department::all();

        return view('employee.documents.index', compact('documents', 'categories', 'departments'));
    }

  
    public function create()
    {
        $categories = Category::all();
        $departments = Department::all();

        $documents = Document::where('user_id', Auth::id())
            ->with('category')
            ->latest()
            ->paginate(10);

        return view('employee.documents.create', compact('categories', 'departments', 'documents'));
    }


    public function edit($id)
    {
        $document = Document::findOrFail($id);
        $this->authorize('update', $document);

        $categories = Category::all();
        $departments = Department::all();

        $documents = Document::where('user_id', Auth::id())
            ->with('category')
            ->latest()
            ->paginate(10);

        return view('employee.documents.edit', compact('document', 'categories', 'departments', 'documents'));
    }

    // Store a new document with file and extracted metadata
    public function store(Request $request)
    {
        \Log::info('DocumentController@store called by user ID: ' . Auth::id());

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'department' => 'required|exists:departments,id',
            'category_id' => 'required|exists:categories,id',
            'document_file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png,tiff|max:5120',
            'watermark' => 'nullable|string|max:255',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        $watermark = $validated['watermark'] ?? 'Confidential';
        $file = $request->file('document_file');
        $fileType = strtolower($file->getClientOriginalExtension());

        $metadata = ['author' => null, 'title' => null, 'keywords' => null];

        // Extract metadata according to file type
        if ($fileType === 'pdf') {
            $metadata = $this->extractPdfMetadata($file->getRealPath());
        } elseif (in_array($fileType, ['doc', 'docx'])) {
            $metadata = $this->extractWordMetadata($file->getRealPath());
        }

        $author = $metadata['author'] ?? $request->author ?? '';
        $title = $metadata['title'] ?? $request->title ?? 'Untitled';

        $document = $this->createDocumentWithFile($request, $watermark, $validated['department'], $author, $title);

        if (!empty($validated['tags'])) {
            $document->tags()->sync($validated['tags']);
        }

      
        SendApprovalRequestJob::dispatch($document->id)->delay(now()->addDays(2));

        return redirect()->route('employee.documents.index')
            ->with('success', 'Document uploaded. Manager approval request will be sent after 2 days.');
    }

    
    public function download($id)
    {
        $document = Document::findOrFail($id);
        $filePath = storage_path('app/public/' . $document->file_path);

        if (!file_exists($filePath)) {
            \Log::error("File not found for download", ['file_path' => $filePath, 'document_id' => $id]);
            return back()->with('error', 'File not found.');
        }

        try {
            $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            $fileName = Str::slug($document->title ?? 'document') . '.' . $extension;
            $mimeType = mime_content_type($filePath) ?: 'application/octet-stream';

            \Log::info("Starting download", ['file_path' => $filePath, 'document_id' => $id]);

            return response()->download($filePath, $fileName, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            ]);
        } catch (\Exception $e) {
            \Log::error("Download error: " . $e->getMessage(), ['document_id' => $id]);
            return back()->with('error', 'Error occurred while downloading the file.');
        }
    }

    // Restore a soft-deleted document
    public function restore($id)
    {
        $document = Document::withTrashed()->findOrFail($id);
        $this->authorize('restore', $document);

        $document->restore();

        return redirect()->route('documents.index')->with('success', 'Document restored successfully.');
    }

    // Approve a document (authorization checked)
    public function approve(Request $request, $id)
    {
        $document = Document::withTrashed()->with('workflow')->findOrFail($id);

        \Log::info('Approving document', [
            'id' => $document->id,
            'department_id' => $document->department_id,
            'workflow' => $document->workflow,
        ]);

        if (!$this->isAuthorizedApprover($document)) {
            return back()->with('error', 'Unauthorized approval attempt.');
        }

        // Implement your approval logic here:
        // e.g., update status, log approval, notify users, etc.

        return back()->with('success', 'Document approved successfully.');
    }

    // Reject a document (authorization checked)
    public function reject(Request $request, Document $document)
    {
        if (!$this->isAuthorizedApprover($document)) {
            return back()->with('error', 'Unauthorized rejection attempt.');
        }

        $document->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejected_by' => Auth::id(),
        ]);

        $document->workflow?->update(['status' => 'rejected']);

        return back()->with('success', 'Document rejected successfully.');
    }

    // List pending approval documents for admins
    public function pendingApprovals()
    {
        $pendingDocuments = Document::where('status', 'pending')
            ->whereNotNull('category_id')
            ->latest()
            ->paginate(10);

        return view('admin.pending_approvals', compact('pendingDocuments'));
    }

    // ------------------ PRIVATE HELPER METHODS ------------------

    /**
     * Create document record with file handling and watermarking
     */
    private function createDocumentWithFile(Request $request, string $watermark, int $departmentId, string $author = '', string $title = ''): Document
    {
        \Log::info('Creating document with file for user ID: ' . Auth::id());

        $file = $request->file('document_file');
        $fileType = strtolower($file->getClientOriginalExtension());
        $user = Auth::user();

        $document = new Document();
        $document->title = $title ?: $request->title;
        $document->description = $request->description ?? '';
        $document->author = $author;
        $document->user_id = $user->id;
        $document->category_id = $request->category_id;
        $document->status = 'pending';
        $document->file_type = $fileType;
        $document->department_id = $departmentId;
        $document->watermark = $watermark;

        // Handle different file types with watermarking logic
        if (in_array($fileType, ['jpg', 'jpeg', 'png', 'tiff'])) {
            $image = Image::make($file->getRealPath());
            $watermarkImage = Image::canvas($image->width(), $image->height());

            $watermarkImage->text($watermark, $image->width() / 2, $image->height() / 2, function ($font) {
                $font->file(public_path('fonts/arial.ttf'));
                $font->size(60);
                $font->color('#ffffff');
                $font->align('center');
                $font->valign('middle');
                $font->angle(45);
            });

            $watermarkImage->opacity(30);
            $image->insert($watermarkImage, 'center');

            $path = 'documents/images/' . uniqid() . '.' . $fileType;
            Storage::disk('public')->put($path, (string)$image->encode());
            $document->file_path = $path;

        } elseif ($fileType === 'pdf') {
            $tempDir = storage_path('app/temp');
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            $tempFileName = uniqid() . '.pdf';
            $tempStoragePath = $tempDir . '/' . $tempFileName;
            $file->move($tempDir, $tempFileName);

            $path = $this->addPdfWatermark($tempStoragePath, $watermark);

            if (file_exists($tempStoragePath)) {
                unlink($tempStoragePath);
            }

            $document->file_path = $path;

        } else {
            // Default store for other file types (e.g. doc, docx)
            $path = $file->store('documents/files', 'public');
            $document->file_path = $path;
        }

        $document->save();

        return $document;
    }

    /**
     * Extract metadata from PDF file using smalot/pdfparser
     */
    private function extractPdfMetadata(string $filePath): array
    {
        $parser = new Parser();
        $pdf = $parser->parseFile($filePath);
        $details = $pdf->getDetails();
        $text = $pdf->getText();

        return [
            'author' => $details['Author'] ?? null,
            'keywords' => $details['Keywords'] ?? null,
            'title' => $details['Title'] ?? null,
            'text' => $text,
        ];
    }

    /**
     * Extract metadata from Word file using PhpOffice\PhpWord
     */
    private function extractWordMetadata(string $filePath): array
    {
        try {
            $phpWord = IOFactory::load($filePath);
            $props = $phpWord->getDocInfo();

            return [
                'author' => $props->getCreator() ?? null,
                'title' => $props->getTitle() ?? null,
                'keywords' => $props->getKeywords() ?? null,
            ];
        } catch (\Exception $e) {
            \Log::error('Error extracting Word metadata: ' . $e->getMessage());
            return [
                'author' => null,
                'title' => null,
                'keywords' => null,
            ];
        }
    }

    /**
     * Add watermark text to each page of a PDF file
     */
    private function addPdfWatermark(string $inputFilePath, string $watermark): string
    {
        $pdf = new PdfWithRotation();

        $pageCount = $pdf->setSourceFile($inputFilePath);

        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $tplIdx = $pdf->importPage($pageNo);
            $size = $pdf->getTemplateSize($tplIdx);

            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($tplIdx);

            $pdf->SetFont('Helvetica', 'B', 50);
            $pdf->SetTextColor(150, 150, 150);

            // Transparency workaround
            $pdf->_out('q'); // Save graphics state
            $pdf->_out('0.3 gs'); // Set transparency to 0.3

            $pdf->Rotate(45, $size['width'] / 2, $size['height'] / 2);
            $pdf->SetXY($size['width'] / 4, $size['height'] / 2);
            $pdf->Cell(0, 0, $watermark);
            $pdf->Rotate(0);

            $pdf->_out('Q'); // Restore graphics state
        }

        $outputPath = 'documents/pdfs/' . uniqid() . '.pdf';
        Storage::disk('public')->put($outputPath, $pdf->Output('', 'S'));

        return $outputPath;
    }

    /**
     * Check if current user is authorized to approve the document
     */
    private function isAuthorizedApprover(Document $document): bool
    {
        $user = Auth::user();

        if ($user->hasRole('Admin')) {
            return true;
        }

        if (!$document->workflow) {
            return false;
        }

        $currentApprover = $document->workflow->current_approver;

        return $currentApprover && $currentApprover->id === $user->id;
    }
}
