@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Workflow Steps</h2>

    @if($steps->isEmpty())
        <p>No steps available.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Workflow</th>
                    <th>Approver</th>
                    <th>Status</th>
                    <th>Step Order</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($steps as $step)
                    <tr>
                        <td>{{ $step->id }}</td>
                        <td>{{ $step->workflow->name }}</td>
                        <td>{{ $step->approver->name ?? 'N/A' }}</td>
                        <td>{{ ucfirst($step->status) }}</td>
                        <td>{{ $step->step_order }}</td>
                        <td>
                            @if($step->status === 'pending' && auth()->id() === $step->approver_id)
                                <a href="{{ route('workflow.step.form', $step->id) }}" class="btn btn-sm btn-primary">
                                    Approve
                                </a>
                            @else
                                <span class="text-muted">No Action</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
