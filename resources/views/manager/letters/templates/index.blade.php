@extends('layouts.app')

@section('title', 'Letter Templates')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Letter Templates</h1>
        <a href="{{ route('manager.letters.templates.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Create New Template
        </a>
    </div>

    @if($templates->count())
        <div class="table-responsive shadow-sm">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Created At</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($templates as $template)
                        <tr>
                            <td>{{ $template->name }}</td>
                            <td>{{ $template->created_at->format('Y-m-d') }}</td>
                            <td class="text-center">
                                <a href="{{ route('manager.letters.templates.show', $template) }}" class="btn btn-sm btn-info me-1" title="View Template">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('manager.letters.templates.edit', $template) }}" class="btn btn-sm btn-warning me-1" title="Edit Template">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('manager.letters.templates.destroy', $template) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this template?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" title="Delete Template">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $templates->links() }}
        </div>
    @else
        <div class="alert alert-info">
            No letter templates found. Start by creating a new one!
        </div>
    @endif
</div>
@endsection
