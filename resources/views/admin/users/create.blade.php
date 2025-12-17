@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Create New User</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <h5 class="mb-2">Whoops! There were some problems with your input:</h5>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.store') }}" method="POST" class="card card-body shadow-sm">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" placeholder="Enter full name" value="{{ old('name') }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
            <input type="email" name="email" class="form-control" placeholder="Enter email" value="{{ old('email') }}" required>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                <input type="password" name="password" class="form-control" placeholder="Enter password" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="roles" class="form-label">Assign Role(s) <span class="text-danger">*</span></label>
            <select name="roles[]" class="form-select" multiple required>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}" {{ collect(old('roles'))->contains($role->name) ? 'selected' : '' }}>
                        {{ ucfirst($role->name) }}
                    </option>
                @endforeach
            </select>
            <small class="form-text text-muted">Hold Ctrl (or Cmd) to select multiple roles.</small>
        </div>

        <div class="mb-3">
            <label for="department_id" class="form-label">Department <span class="text-danger">*</span></label>
            <select name="department_id" class="form-select" required>
                <option value="">-- Select Department --</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                        {{ $department->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="status" class="form-label">Account Status <span class="text-danger">*</span></label>
            <select name="status" class="form-select" required>
                <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="blocked" {{ old('status') === 'blocked' ? 'selected' : '' }}>Blocked</option>
            </select>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Create User</button>
        </div>
    </form>
</div>
@endsection
