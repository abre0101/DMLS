@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">📋 Dashboard — Welcome, {{ Auth::user()->name }}</h2>
        <a href="{{ route('employee.profile.edit') }}" class="btn btn-outline-secondary px-3 py-2">
            👤 Edit Profile
        </a>
    </div>

    <div class="mb-4 d-flex flex-wrap gap-3">
        <a href="{{ route('employee.documents.create') }}" class="btn btn-primary px-4 py-2 shadow-sm">📤 Upload Document</a>
        <a href="{{ route('approval_requests.create') }}" class="btn btn-warning px-4 py-2 shadow-sm text-white">✅ Start Approval Request</a>
        <a href="{{ route('collaboration.index') }}" class="btn btn-outline-success px-4 py-2">🤝 Collaborate</a>
        <a href="{{ route('employee.tasks.assigned_to_me') }}" class="btn btn-outline-info px-4 py-2">📋 My Assigned Tasks</a>
    </div>

    <div class="row mb-4">
          <div class="col-md-3 mb-2">
            <span class="badge bg-info text-black px-4 py-2 rounded-pill shadow">
                🔐 Role: {{ ucfirst(Auth::user()->role->name) }}
            </span>

    </div>

 
    <x-section-title icon="📥" title="Received Correspondence" />
    @include('partials.letters.inbox', ['letters' => $receivedLetters])

    <x-section-title icon="📂" title="My Documents" />
    <form method="GET" action="{{ route('employee.dashboard') }}" class="mb-4">
  <div class="row align-items-end g-2">
    <div class="col-md-3">
      <input type="text" name="title" placeholder="Title"
             value="{{ request('title') }}" class="form-control" />
    </div>
    <div class="col-md-3">
      <input type="date" name="upload_date"
             value="{{ request('upload_date') }}" class="form-control" />
    </div>
    <div class="col-md-2">
      <button class="btn btn-primary w-100" type="submit">Filter</button>
    </div>
  </div>

</form>
    @include('partials.documents.list', ['documents' => $documents])

    <x-section-title icon="📄" title="My Letters (Drafts & Sent)" />
    @include('partials.letters.sent', ['letters' => $sentLetters])

    <x-section-title icon="🔔" title="Pending Approval Requests" />
    @include('partials.approvals.pending', ['pendingApprovals' => $pendingApprovals])

    <x-section-title icon="💬" title="Notifications & Comments" />
    @include('partials.notifications.grouped', ['notificationsGroupedByDocument' => $notificationsGroupedByDocument])

    <x-section-title icon="📅" title="Tasks & Reminders" />
    @include('partials.tasks.list', ['tasks' => $tasks])
</div>


<style>
    .hover-shadow:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
        cursor: pointer;
        transition: box-shadow 0.2s ease;
    }

    .text-warning-custom {
        color: #ffc107 !important;
    }
</style>

@endsection
