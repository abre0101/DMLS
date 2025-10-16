<?php
namespace App\Http\Livewire;


use Livewire\Component;
use App\Models\Document;
use App\Models\Category;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;

class DocumentSearch extends Component

{
    public $query = '';
    public $status = '';
    public $fileType = '';
    public $categoryId = '';
    public $departmentId = '';
    public $sortBy = 'created_at_desc';

    public $bookmarkedDocuments = [];

    public function mount()
    {
        // Load bookmarked documents for the logged-in user
        $this->bookmarkedDocuments = Auth::check()
            ? Auth::user()->bookmarks()->pluck('document_id')->toArray()
            : [];
    }

    public function toggleBookmark($documentId)
    {
        if (!Auth::check()) {
            $this->dispatchBrowserEvent('notify', ['message' => 'Please log in to bookmark documents.', 'type' => 'error']);
            return;
        }

        if (in_array($documentId, $this->bookmarkedDocuments)) {
            Auth::user()->bookmarks()->detach($documentId);
            $this->bookmarkedDocuments = array_diff($this->bookmarkedDocuments, [$documentId]);
        } else {
            Auth::user()->bookmarks()->attach($documentId);
            $this->bookmarkedDocuments[] = $documentId;
        }
    }
public function render()
{
    logger()->info('Current Filters:', [
        'query' => $this->query,
        'status' => $this->status,
        'fileType' => $this->fileType,
        'categoryId' => $this->categoryId,
        'departmentId' => $this->departmentId,
        'sortBy' => $this->sortBy,
    ]);

    $results = [];

    if (strlen($this->query) >= 2 || $this->hasActiveFilters()) {
        $results = Document::with('category')
            ->when($this->query, function ($q) {
                $q->where(function ($sub) {
                    
                    $sub->where('title', 'Like','%'.$this->query.'%')
                        ->orWhere('description', 'like', '%' . $this->query . '%')
                        ->orWhere('author', 'like', '%' . $this->query . '%');
                });
            })
            ->when($this->status, fn($q) => $q->where('status', $this->status))
            ->when($this->fileType, fn($q) => $q->where('file_type', $this->fileType))
            ->when($this->categoryId, fn($q) => $q->where('category_id', $this->categoryId))
            ->when($this->departmentId, fn($q) => $q->where('department_id', $this->departmentId))
            ->when($this->sortBy === 'created_at_asc', fn($q) => $q->orderBy('created_at', 'asc'))
            ->when($this->sortBy === 'created_at_desc', fn($q) => $q->orderBy('created_at', 'desc'))
            ->when($this->sortBy === 'title_asc', fn($q) => $q->orderBy('title', 'asc'))
            ->when($this->sortBy === 'title_desc', fn($q) => $q->orderBy('title', 'desc'))
            ->limit(50)
            ->get();
    }

    return view('livewire.document-search', [
        'results' => $results,
        'categories' => Category::all(),
        'departments' => Department::all(),
       
        'currentFilters' => [
            'query' => $this->query,
            'status' => $this->status,
            'fileType' => $this->fileType,
            'categoryId' => $this->categoryId,
            'departmentId' => $this->departmentId,
            'sortBy' => $this->sortBy,
        ],
    ]);
}

    protected function hasActiveFilters()
    {
        return $this->status || $this->fileType || $this->categoryId || $this->departmentId;
    }
}
