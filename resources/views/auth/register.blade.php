@extends('layouts.app')

@section('content')
<div class="container register-container">
    <h1 class="register-title">Register</h1>

    <form action="{{ route('register') }}" method="POST" class="register-form" novalidate>
        @csrf

        <div class="form-group mb-3">
            <label for="name" class="form-label">Name</label>
            <input 
                type="text" 
                id="name" 
                name="name" 
                class="form-control @error('name') is-invalid @enderror" 
                placeholder="Enter your name" 
                required 
                value="{{ old('name') }}"
                autofocus
            >
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="email" class="form-label">Email</label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                class="form-control @error('email') is-invalid @enderror" 
                placeholder="Enter your email" 
                required 
                value="{{ old('email') }}"
            >
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="password" class="form-label">Password</label>
            <input 
                type="password" 
                id="password" 
                name="password" 
                class="form-control @error('password') is-invalid @enderror" 
                placeholder="Enter your password" 
                required
                autocomplete="new-password"
            >
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input 
                type="password" 
                id="password_confirmation" 
                name="password_confirmation" 
                class="form-control @error('password_confirmation') is-invalid @enderror" 
                placeholder="Confirm your password" 
                required
                autocomplete="new-password"
            >
            @error('password_confirmation')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-4">
            <label for="department_id" class="form-label">Department</label>
            <select 
                id="department_id" 
                name="department_id" 
                class="form-select @error('department_id') is-invalid @enderror" 
                required
            >
                <option value="" disabled {{ old('department_id') ? '' : 'selected' }}>
                    Select your department
                </option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}" 
                        {{ old('department_id') == $department->id ? 'selected' : '' }}>
                        {{ $department->name }}
                    </option>
                @endforeach
            </select>
            @error('department_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary btn-submit">Register</button>
    </form>
</div>
@endsection
