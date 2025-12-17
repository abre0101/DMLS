@php use Illuminate\Support\Facades\Storage; @endphp

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="row g-3 align-items-center mb-3">
            <div class="col-md-4">
                <input
                    type="text"
                    class="form-control"
                    placeholder="🔍 Search by title, description, or author..."
                    wire:model.debounce.300ms="query"
                    wire:loading.class="opacity-50"
                >
            </div>

            <div class="col-md-2">
                <select class="form-select" wire:model="status">
                    <option value="">All Statuses</option>
                    <option value="approved">✅ Approved</option>
                    <option value="rejected">❌ Rejected</option>
                    <option value="pending">🕒 Pending</option>
                </select>
            </div>

            <div class="col-md-2">
                <select class="form-select" wire:model="fileType">
                    <option value="">All File Types</option>
                    <option value="pdf">PDF</option>
                    <option value="docx">DOCX</option>
                    <option value="xlsx">XLSX</option>
                    <option value="pptx">PPTX</option>
                </select>
            </div>

       
            <div class="col-md-3 mt-2">
                <select class="form-select" wire:model="sortBy">
                    <option value="created_at_desc">🆕 Newest First</option>
                    <option value="created_at_asc">📅 Oldest First</option>
                    <option value="title_asc">🔤 Title A–Z</option>
                    <option value="title_desc">🔡 Title Z–A</option>
                </select>
            </div>
        </div>

        <div wire:loading class="text-muted mb-3">🔄 Searching documents...</div>

        @if ($results && $results->count())
            <ul class="list-group shadow-sm">
                @foreach ($results as $document)
                    @php
                        $ext = pathinfo($document->file_path, PATHINFO_EXTENSION);
                        $icons = [
                            'pdf' => '📄', 'doc' => '📝', 'docx' => '📝',
                            'xls' => '📊', 'xlsx' => '📊', 'ppt' => '📽', 'pptx' => '📽',
                        ];
                        $icon = $icons[strtolower($ext)] ?? '📁';
                    @endphp

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $icon }} {{ \Illuminate\Support\Str::limit($document->title, 60) }}</strong><br>
                            <small class="text-muted">
                                {{ $document->category->name ?? 'Uncategorized' }} | 
                                {{ ucfirst($document->status) }} | 
                                {{ $document->file_type }}
                            </small><br>
                            <small>👤 {{ $document->author }} — 🏢 {{ optional($document->department)->name }}</small>
                        </div>

                        @if (Storage::exists($document->file_path))
                            <a
                                href="{{ Storage::url($document->file_path) }}"
                                target="_blank"
                                class="btn btn-sm btn-outline-primary"
                            >
                                View
                            </a>
                        @else
                            <span class="badge bg-secondary">File Missing</span>
                        @endif
                    </li>
                @endforeach
            </ul>
        @elseif(strlen($query) >= 2 || $status || $fileType || $categoryId || $departmentId)
            <p class="text-muted mt-3">No documents found.</p>
        @endif
    </div>
</div>
