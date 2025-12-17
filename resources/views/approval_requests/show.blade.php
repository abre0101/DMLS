{{-- resources/views/approval_requests/show.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Approve Document: {{ $approval->document->title ?? 'Untitled' }}</h1>

    {{-- Display document or approval details here --}}
    <p>Status: {{ ucfirst($approval->status) }}</p>

    {{-- Signature capture UI (e.g., a canvas or third-party signature pad) --}}
    <div id="signature-pad-container">
        <!-- Your signature pad implementation here -->
        <canvas id="signature-pad" width="400" height="200" style="border:1px solid #ccc;"></canvas>
        <button id="clear-signature">Clear</button>
    </div>

    <form action="{{ route('approval-requests.approve', $approval->id) }}" method="POST" id="approvalForm">
        @csrf
        <input type="hidden" name="signature_data" id="signatureDataInput">
        <button type="submit" class="btn btn-primary">Approve</button>
    </form>
</div>

{{-- Include JavaScript for signature capture and setting hidden input --}}
<script>
    const canvas = document.getElementById('signature-pad');
    const signatureDataInput = document.getElementById('signatureDataInput');
    const form = document.getElementById('approvalForm');
    const clearButton = document.getElementById('clear-signature');
    
    const signaturePad = new SignaturePad(canvas);

    clearButton.addEventListener('click', () => {
        signaturePad.clear();
    });

    form.addEventListener('submit', function(e) {
        if (signaturePad.isEmpty()) {
            alert('Please provide your signature before approving.');
            e.preventDefault();
        } else {
            const dataURL = signaturePad.toDataURL();
            signatureDataInput.value = dataURL;
        }
    });
</script>
@endsection