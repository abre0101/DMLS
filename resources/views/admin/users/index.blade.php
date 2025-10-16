@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Manage Users</h1>
        <a href="{{ route('admin.users.create') }}" class="btn btn-success">+ Create New User</a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role(s)</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->getRoleNames()->isEmpty())
                                <span class="text-muted">No role assigned</span>
                            @else
                                {{ $user->getRoleNames()->join(', ') }}
                            @endif
                        </td>
                        <td>
                            @if($user->status === 'Blocked' || $user->status === 2)
                                <span class="badge bg-danger">Blocked</span>
                            @else
                                <span class="badge bg-success">Active</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning me-1">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>
                            <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
