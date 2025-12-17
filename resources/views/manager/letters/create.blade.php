@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create New Letter</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops! There were some problems with your input:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('manager.storeLetter') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="template_id" class="form-label">Template</label>
            <select id="template_id" name="template_id" class="form-control" onchange="loadTemplateContent()">
                <option value="">-- Select Template --</option>
                @foreach($templates as $template)
                    <option value="{{ $template->id }}">{{ $template->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="receiver_id" class="form-label">Receiver</label>
            <select name="receiver_id" id="receiver_id" class="form-select" required>
                <option value="">Select Receiver</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ old('receiver_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="parent_id" class="form-label">Parent Letter (optional)</label>
            <input type="number" name="parent_id" id="parent_id" class="form-control" value="{{ old('parent_id') }}">
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea name="content" id="content" rows="5" class="form-control" required>{{ old('content') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="direction" class="form-label">Direction</label>
            <select name="direction" id="direction" class="form-select" required>
                <option value="">Select Direction</option>
                <option value="incoming" {{ old('direction') == 'incoming' ? 'selected' : '' }}>Incoming</option>
                <option value="outgoing" {{ old('direction') == 'outgoing' ? 'selected' : '' }}>Outgoing</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Create Letter</button>
        <a href="{{ route('director.letters.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<script>
    function loadTemplateContent() {
        const templateId = document.getElementById('template_id').value;
        const contentBox = document.getElementById('content');

        if (!templateId) {
            contentBox.value = '';
            return;
        }

        fetch(`/template/${templateId}/content`)
            .then(response => {
                if (!response.ok) throw new Error("Template fetch failed.");
                return response.json();
            })
            .then(data => {
                contentBox.value = data.content;
            })
            .catch(error => {
                console.error("Error loading template content:", error);
                alert('Could not load template content.');
            });
    }
</script>

@endsection
