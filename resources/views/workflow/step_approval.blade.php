@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Approve Workflow Step</h2>

    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">{{ $step->workflow->name }}</h5>
            <p class="card-text">Step Order: {{ $step->step_order }}</p>
            <p class="card-text">Status: <strong>{{ ucfirst($step->status) }}</strong></p>
        </div>
    </div>

    @if($step->status === 'pending' && auth()->id() === $step->approver_id)
        <form method="POST" action="{{ route('workflow.step.approve', $step->id) }}">
            @csrf
            <div class="form-group mb-3">
                <label for="signature">e-Signature</label>
                <input type="text" class="form-control" name="signature" required placeholder="Enter your name or signature">
            </div>
            <button type="submit" class="btn btn-success">Approve</button>
        </form>
    @else
        <p class="text-muted">You are not authorized to approve this step.</p>
    @endif
</div>
@endsection
