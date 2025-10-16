@extends('layouts.app')

@section('title', 'Admin Settings')

@section('content')
<div class="container">
    <h1>Admin Settings</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card mb-4">
            <div class="card-header">System Preferences</div>
            <div class="card-body">

                @php
                    $selectedTheme = old('theme', $settings['theme'] ?? 'light');
                @endphp

                <div class="mb-3">
                    <label for="theme" class="form-label">Dashboard Theme</label>
                    <select class="form-select" name="theme" id="theme">
                        <option value="light" {{ $selectedTheme == 'light' ? 'selected' : '' }}>Light Mode</option>
                        <option value="dark" {{ $selectedTheme == 'dark' ? 'selected' : '' }}>Dark Mode</option>
                    </select>
                </div>

                {{-- Add more settings fields as needed --}}
                {{-- Example:
                <div class="mb-3">
                    <label for="email_notification" class="form-label">Email Notifications</label>
                    <select class="form-select" name="email_notification" id="email_notification">
                        <option value="1" {{ old('email_notification', $settings['email_notification'] ?? false) == true ? 'selected' : '' }}>Enabled</option>
                        <option value="0" {{ old('email_notification', $settings['email_notification'] ?? false) == false ? 'selected' : '' }}>Disabled</option>
                    </select>
                </div>
                --}}

            </div>
        </div>

        <button type="submit" class="btn btn-primary">Save All Settings</button>
    </form>
</div>
@endsection
