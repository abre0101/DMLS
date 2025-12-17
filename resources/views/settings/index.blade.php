@extends('layouts.app') {{-- Extend the main layout --}}

@section('title', 'Director Settings') {{-- Page Title --}}

@section('content') {{-- Define content section --}}
<div class="container">
    <h1>Director Settings</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Combined Settings Form -->
    <form action="{{ route('settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Notification Settings -->
        <div class="card mb-4">
            <div class="card-header">Notification Preferences</div>
            <div class="card-body">

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="email_notifications" id="email_notifications"
                        {{ old('email_notifications', $settings->email_notifications) ? 'checked' : '' }}>
                    <label class="form-check-label" for="email_notifications">Receive email notifications</label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="sms_notifications" id="sms_notifications"
                        {{ old('sms_notifications', $settings->sms_notifications) ? 'checked' : '' }}>
                    <label class="form-check-label" for="sms_notifications">Receive SMS notifications</label>
                </div>

            </div>
        </div>

        <!-- System Preferences -->
        <div class="card mb-4">
            <div class="card-header">System Preferences</div>
            <div class="card-body">

                <div class="mb-3">
                    <label for="theme" class="form-label">Dashboard Theme</label>
                    <select class="form-select" name="theme" id="theme">
                        <option value="light" {{ old('theme', $settings->theme) == 'light' ? 'selected' : '' }}>Light Mode</option>
                        <option value="dark" {{ old('theme', $settings->theme) == 'dark' ? 'selected' : '' }}>Dark Mode</option>
                    </select>
                </div>

            </div>
        </div>

        <button type="submit" class="btn btn-primary">Save All Settings</button>
    </form>
</div>
@endsection {{-- End content section --}}
