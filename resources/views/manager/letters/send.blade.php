@extends('layouts.app')

@section('content')
    <h1 class="mb-4">Send a Letter</h1>

    <form action="{{ route('manager.letters.storeByEmail') }}" method="POST" id="sendForm" class="p-4 border rounded bg-light">
        @csrf

        <div class="mb-3">
            <label for="recipient_email" class="form-label">Recipient Email:</label>
            <select name="recipient_email" id="recipient_email" class="form-select" required>
                <option value="">-- Select Recipient --</option>
                @foreach($users as $user)
                    @if($user->email !== auth()->user()->email)
                        <option value="{{ $user->email }}" data-name="{{ $user->name }}">
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endif
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="recipient_name" class="form-label">Recipient Name:</label>
            <input type="text" name="recipient_name" id="recipient_name" class="form-control" readonly required>
        </div>

        <div class="mb-3">
            <label for="template_id" class="form-label">Select Template:</label>
            <select name="template_id" id="template_id" class="form-select" required>
                <option value="">-- Select a Template --</option>
                @foreach($templates as $template)
                    <option value="{{ $template->id }}" data-content="{{ htmlentities($template->content) }}">
                        {{ $template->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div id="dynamic-fields" class="mb-3">
            <!-- Placeholder fields will be injected here -->
        </div>

        <button type="submit" class="btn btn-primary">Send</button>
    </form>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const templateSelect = document.getElementById('template_id');
    const dynamicFieldsDiv = document.getElementById('dynamic-fields');
    const recipientSelect = document.getElementById('recipient_email');
    const recipientNameInput = document.getElementById('recipient_name');

    // Autofill recipient name based on selected email
    recipientSelect.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        recipientNameInput.value = selectedOption.dataset.name || '';
    });

    // Generate dynamic input fields for template placeholders
    templateSelect.addEventListener('change', function () {
        dynamicFieldsDiv.innerHTML = ''; // Clear previous fields

        const selectedOption = this.options[this.selectedIndex];
        const content = selectedOption.dataset.content;

        if (!content) return;

        // Extract unique placeholders like {EmployeeName}, {Date}, etc.
        const placeholders = [...new Set(content.match(/\{(.*?)\}/g))];

        placeholders.forEach(placeholder => {
            const fieldName = placeholder.replace(/[{}]/g, '');

            const wrapper = document.createElement('div');
            wrapper.className = 'mb-2';

            const label = document.createElement('label');
            label.className = 'form-label';
            label.setAttribute('for', `field-${fieldName}`);
            label.innerText = fieldName;

            const input = document.createElement('input');
            input.type = 'text';
            input.name = `fields[${fieldName}]`;
            input.id = `field-${fieldName}`;
            input.className = 'form-control';
            input.required = true;

            wrapper.appendChild(label);
            wrapper.appendChild(input);
            dynamicFieldsDiv.appendChild(wrapper);
        });
    });
});
</script>
@endsection
