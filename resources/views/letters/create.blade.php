@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Create Letter</h1>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('letters.store') }}" method="POST" id="letter-form">
        @csrf

        <div class="form-group mb-3">
            <label for="template">Select Template</label>
            <select name="template_id" id="template" class="form-control" required>
                <option value="">-- Choose Template --</option>
                @foreach($templates as $template)
                    <option value="{{ $template->id }}" data-content="{{ $template->content }}">{{ $template->name }}</option>
                @endforeach
            </select>
        </div>

        <div id="dynamic-fields">
            {{-- Dynamic inputs will be added here based on selected template --}}
        </div>

        <button type="submit" class="btn btn-primary">Save as Draft</button>
        <button type="button" id="send-btn" class="btn btn-success">Send Letter</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const templateSelect = document.getElementById('template');
        const dynamicFields = document.getElementById('dynamic-fields');
        const letterForm = document.getElementById('letter-form');
        const sendBtn = document.getElementById('send-btn');

        // Parse placeholders from template content and create input fields
        function renderFields(content) {
            dynamicFields.innerHTML = '';
            const placeholders = [...new Set(content.match(/{\s*[\w\.]+\s*}/g))]; // Corrected regex

            placeholders.forEach(ph => {
                const name = ph.replace(/[{\s}]/g, ''); // Corrected regex
                const labelText = name.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());

                const div = document.createElement('div');
                div.className = 'form-group mb-3';

                const label = document.createElement('label');
                label.htmlFor = 'field_' + name;
                label.textContent = labelText;

                const input = document.createElement('input');
                input.type = 'text';
                input.name = 'data[' + name + ']';
                input.id = 'field_' + name;
                input.className = 'form-control';
                input.required = true;

                div.appendChild(label);
                div.appendChild(input);
                dynamicFields.appendChild(div);
            });

            if (placeholders.length === 0) {
                dynamicFields.innerHTML = '<p>No placeholders detected in this template.</p>';
            }
        }

        // When template changes, render dynamic field++s
        templateSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const content = selectedOption.getAttribute('data-content') || '';
            renderFields(content);
        });

        // On clicking Send button, add a hidden input to signify send action, then submit
        sendBtn.addEventListener('click', function() {
            if (!templateSelect.value) {
                alert('Please select a template and fill all fields first.');
                return;
            }
            // Validate all generated inputs
            const inputs = dynamicFields.querySelectorAll('input');
            for (let input of inputs) {
                if (!input.value.trim()) {
                    alert('Please fill all required fields.');

                    input.focus();
                    return;
                }
            }

            // Add hidden input and submit
            let sendInput = letterForm.querySelector('input[name="send"]');
            if(!sendInput) {
                sendInput = document.createElement('input');
                sendInput.type = 'hidden';
                sendInput.name = 'send';
                sendInput.value = '1';
                letterForm.appendChild(sendInput);
            }
            letterForm.submit();
        });
    });
</script>
@endsection