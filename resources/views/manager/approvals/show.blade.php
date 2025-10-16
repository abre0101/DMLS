@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Approve Document: <strong>{{ $document->title }}</strong></h3>

    <div class="card mb-4">
        <div class="card-body">
            <p><strong>Submitted By:</strong> {{ $document->uploadedBy->name ?? 'N/A' }}</p>
            <p><strong>Uploaded At:</strong> {{ $document->created_at->format('Y-m-d H:i') }}</p>
            <p><strong>Status:</strong> 
                <span class="badge bg-{{ $document->status === 'pending' ? 'warning' : ($document->status === 'approved' ? 'success' : 'danger') }}">
                    {{ ucfirst($document->status) }}
                </span>
            </p>
            <p><strong>File:</strong> 
                <a href="{{ Storage::url($document->file_path) }}" target="_blank" class="btn btn-sm btn-primary">View</a>
                <a href="{{ Storage::url($document->file_path) }}" download class="btn btn-sm btn-outline-secondary">Download</a>
            </p>
        </div>
    </div>

 
    <form action="{{ route('manager.documents.approve', $document->id) }}" method="POST" id="approvalForm">
        @csrf
        @method('POST') 

   
        <div class="mb-3">
            <label for="signature-pad" class="form-label">Please provide your signature:</label>
            <canvas id="signature-pad" class="border rounded" style="width:100%; height:200px;"></canvas>
            <input type="hidden" name="signature" id="signatureInput">
            @error('signature')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

       
        <div class="mb-3">
            <label for="note" class="form-label">Optional Note</label>
            <textarea name="note" id="note" class="form-control" rows="3" placeholder="Add a note (optional)">{{ old('note') }}</textarea>
            @error('note')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- Buttons --}}
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-secondary" id="clear-signature">Clear Signature</button>
            <button type="submit" class="btn btn-success">Approve & Forward</button>
        </div>
    </form>
    <hr class="my-5">

<h4 class="text-danger">Reject Document</h4>
<form action="{{ route('manager.documents.reject', $document->id) }}" method="POST">
    @csrf
    {{-- no @method needed if route is POST --}}
    <div class="mb-3">
        <label for="note_reject" class="form-label">Rejection Note (optional)</label>
        <textarea name="note" id="note_reject" class="form-control" rows="3" placeholder="Why are you rejecting this document?">{{ old('note') }}</textarea>
        @error('note')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-danger">Reject Document</button>
</form>


</div>

{{-- Signature Pad --}}
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const canvas = document.getElementById('signature-pad');
        const signaturePad = new SignaturePad(canvas);

        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext('2d').scale(ratio, ratio);
            signaturePad.clear();
        }
        window.addEventListener('resize', resizeCanvas);
        resizeCanvas();

        document.getElementById('clear-signature').addEventListener('click', () => {
            signaturePad.clear();
        });

        document.getElementById('approvalForm').addEventListener('submit', function (e) {
            if (signaturePad.isEmpty()) {
                alert('Please provide a signature before submitting.');
                e.preventDefault();
                return false;
            }

            const dataUrl = signaturePad.toDataURL('image/png');
            document.getElementById('signatureInput').value = dataUrl;
        });
    });
</script>
@endsection
