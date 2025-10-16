@extends('layouts.app')

@section('content')
    <h1>Edit User</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Name:</label>
            <input type="text" class="form-control" value="{{ $user->name }}" disabled>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" value="{{ $user->email }}" disabled>
        </div>

        <div class="mb-3">
            <label for="role_id" class="form-label">Role:</label>
            <select name="role_id" class="form-select" required>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                        {{ ucfirst($role->name) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status:</label>
            <select name="status" class="form-select" required>
                <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Active</option>
                <option value="blocked" {{ old('status', $user->status) === 'blocked' ? 'selected' : '' }}>BLOCK</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update User</button>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection
