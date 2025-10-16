@if ($documents->isEmpty())
    <p class="text-muted">No documents found.</p>
@else
    <ul class="list-group mb-4">
        @foreach ($documents as $document)
            <li class="list-group-item">
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <strong>{{ $document->title ?? 'Untitled' }}</strong><br>
                        <small class="text-muted">Author: {{ $document->getAuthorFirstName() }}</small>
                    </div>

                    <div class="col-md-3 mb-2">
                        <small class="text-muted d-block">Department: {{ $document->department->name ?? 'N/A' }}</small>
                        <small class="text-muted d-block">Category: {{ $document->category->name ?? 'N/A' }}</small>
                    </div>

                    <div class="col-md-2 mb-2">
                        <small class="text-muted d-block">Uploaded on: 
                            {{ $document->created_at ? $document->created_at->format('M d, Y') : 'N/A' }}
                        </small>
                    </div>

                    <div class="col-md-2 mb-2">
                        @php
                            $status = strtolower($document->status);
                            $statusClass = match ($status) {
                                'approved' => 'text-success',
                                'rejected' => 'text-danger',
                                'pending' => 'text-warning-custom',
                                default => 'text-secondary',
                            };
                        @endphp
                        <strong>Status:</strong> 
                        <span class="{{ $statusClass }}">{{ ucfirst($status) }}</span>
                    </div>

                    <div class="col-md-2 text-md-end">
                        <a href="{{ route('employee.documents.show', $document) }}" class="btn btn-sm btn-outline-primary">
                            View Document
                        </a>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
@endif
