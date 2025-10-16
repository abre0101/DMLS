@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">📄 My Documents</h2>
         <a href="{{ route('employee.documents.create') }}" class="btn btn-primary">📤 Upload Document</a>
          
        </a>
    </div>


    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif


    @if($documents->isEmpty())
        <div class="alert alert-info">You haven’t uploaded any documents yet. Start by uploading one!</div>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Title</th>
                        <th scope="col">Category</th>
                        <th scope="col">Department</th>
                        <th scope="col">Status</th>
                        <th class="text-center" style="width: 160px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($documents as $doc)
                        <tr>
                            <td>{{ $doc->title }}</td>
                            <td>{{ $doc->category->name ?? 'N/A' }}</td>
                            <td>{{ $doc->department->name ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $statusClass = match($doc->status) {
                                        'approved' => 'badge bg-success',
                                        'pending' => 'badge bg-warning text-dark',
                                        'rejected' => 'badge bg-danger',
                                        default => 'badge bg-secondary',
                                    };
                                @endphp
                                <span class="{{ $statusClass }}">{{ ucfirst($doc->status) }}</span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('employee.documents.show', $doc) }}" 
                                   class="btn btn-sm btn-outline-primary me-1" 
                                   title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('employee.documents.edit', $doc) }}" 
                                   class="btn btn-sm btn-outline-secondary me-1" 
                                   title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('documents.destroy', $doc) }}" 
                                      method="POST" 
                                      class="d-inline" 
                                      onsubmit="return confirm('Are you sure you want to delete this document?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    
        <div class="d-flex justify-content-center mt-4">
            {{ $documents->links() }}
        </div>
    @endif
</div>
@endsection
