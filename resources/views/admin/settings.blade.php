{{-- resources/views/admin/settings.blade.php --}}

@extends('layouts.app')

@section('content')
    <h1>Admin Settings</h1>
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Application Settings</h5>

                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf

                        {{-- Theme Setting --}}
                        <div class="form-group">
                            <label for="theme">Select Theme</label>
                            <select name="theme" id="theme" class="form-control" required>
                                <option value="light" {{ old('theme', $settings->where('key', 'theme')->first()->value) == 'light' ? 'selected' : '' }}>Light</option>
                                <option value="dark" {{ old('theme', $settings->where('key', 'theme')->first()->value) == 'dark' ? 'selected' : '' }}>Dark</option>
                            </select>
                        </div>

                        {{-- Email Notifications --}}
                        <div class="form-group mt-3">
                            <label for="email_notification">Enable Email Notifications</label>
                            <select name="email_notification" id="email_notification" class="form-control" required>
                                <option value="1" {{ old('email_notification', $settings->where('key', 'email_notification')->first()->value) == 1 ? 'selected' : '' }}>Enabled</option>
                                <option value="0" {{ old('email_notification', $settings->where('key', 'email_notification')->first()->value) == 0 ? 'selected' : '' }}>Disabled</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Save Settings</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
