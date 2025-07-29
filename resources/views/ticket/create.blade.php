@extends('layouts.form')

@section('header-title', 'Support Ticket')

@section('form-title', 'Submit Sebuah Support Ticket')
<!-- TODO: REMOVE ALL THE REQUIRED IN FORM AND REPLACE WITH VALIDATION AS SUBMIT BUTTON FREEZES WHEN DENIED -->
@section('form-content')
<form method="POST" enctype="multipart/form-data" id="form-form" action="{{ route('ticketsCreatePost') }}">
    @csrf
    @php
        use Illuminate\Support\Str;

        $attachmentErrors = collect($errors->getMessages())->keys()->filter(function ($key) {
            return $key === 'attachments' || Str::startsWith($key, 'attachments.');
        });
    @endphp

    @if ($attachmentErrors->isNotEmpty())
        <span style="color: red; font-size: 13px;">
            @if ($errors->has('attachments'))
                {{ $errors->first('attachments') }}
            @else
                Paling sedikit satu attachment tidak valid (bisa oleh karena tipe atau ukurannya)
            @endif
        </span>
    @endif

    <div class="form-group">
        <label for="title">Judul <span style="color: #E53B3B;">*</span></label>
        <input id="title" type="text" name="title" value="{{ old('title') }}" autofocus>
        @error('title')
            <span style="color: red; font-size: 13px;">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="app">Aplikasi <span style="color: #E53B3B;">*</span></label>
        <select id="app" name="app">
            <option value="">-- Pilih Aplikasi --</option>
            @foreach($apps as $app)
                <option value="{{ $app->id }}"
                    {{ old('app') == $app->id ? 'selected' : '' }}>
                    {{ $app->appName }}
                </option>
            @endforeach
        </select>
        @error('app')
            <span style="color: red; font-size: 13px;">{{ $message }}</span>
        @enderror
    </div>


    <div class="form-group">
        <label for="description">Deskripsi Permasalahan <span style="color: #E53B3B;">*</span></label>
        <textarea id="description" name="description" rows="4" style="resize: vertical;">{{ old('description') }}</textarea>
        @error('description')
            <span style="color: red; font-size: 13px;">{{ $message }}</span>
        @enderror
    </div>
    
    <div class="form-attachment" id="drop-area">
        <div class="py-2">Upload Attachment yang terkait oleh masalah (opsional)</div>
        <div class="form-attachment-button" id="form-attachment">
            <i class="fa-solid fa-paperclip px-2"></i>Pilih File
            <input type="file" id="filebutton" name="attachments[]" multiple accept=".png,.jpg,.jpeg,.webp,.pdf,.docx" hidden>
        </div>
        <div class="attachment-condition py-2">Max. 4 file (PNG, JPG, PDF, DOCX, atau WEBP), ukuran tiap file max. 2MB</div>
        <div id="attachment-preview"></div>
    </div>

    <div class="button-wrapper">
         <button type="submit" onclick="duplicationPrevention(event, 'form-form')" class="submit-button"><i class="fas fa-paper-plane px-2" style="color: white;"></i>Submit Ticket</button>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const dropArea = document.getElementById('drop-area');
    const hiddenFileInput = document.getElementById('filebutton');
    const previewArea = document.getElementById('attachment-preview');
    const formForm = document.getElementById('form-form');

    const allowedExts = ['png', 'jpg', 'jpeg', 'webp', 'pdf', 'docx'];
    const maxSize = 2 * 1024 * 1024; // 2MB
    const maxFiles = 4;
    let selectedFiles = [];

    function validateFile(file) {
        const ext = file.name.split('.').pop().toLowerCase();
        return allowedExts.includes(ext) && file.size <= maxSize;
    }

    function updatePreview() {
        previewArea.innerHTML = '';
        if (selectedFiles.length > 0) {
            selectedFiles.forEach((file, index) => {
                const preview = document.createElement('div');
                preview.innerHTML = `
                    <div style="margin-top: 10px; padding: 10px; border: 1px solid #ddd; border-radius: 5px; display: flex; justify-content: space-between; align-items: center;">
                        <span><strong>[${file.name.split('.').pop().toUpperCase()}]</strong> ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)</span>
                        <button type="button" class="remove-file" data-index="${index}" style="background: #e53b3b; color: white; border: none; border-radius: 3px; padding: 5px 10px; cursor: pointer;">&times;</button>
                    </div>
                `;
                previewArea.appendChild(preview);
            });

            // Add remove functionality for all remove buttons
            document.querySelectorAll('.remove-file').forEach(button => {
                button.addEventListener('click', function() {
                    const index = parseInt(this.dataset.index);
                    selectedFiles.splice(index, 1);
                    updatePreview();
                });
            });
        }
    }

    function handleFileSelection(files) {
        const filesToAdd = [];
        const rejectedFiles = [];

        Array.from(files).forEach(file => {
            if (selectedFiles.length + filesToAdd.length < maxFiles) {
                if (validateFile(file)) {
                    // Check if file already exists
                    const fileExists = selectedFiles.some(existingFile => 
                        existingFile.name === file.name && existingFile.size === file.size
                    );
                    
                    if (!fileExists) {
                        filesToAdd.push(file);
                    }
                } else {
                    rejectedFiles.push(file);
                }
            }
        });

        selectedFiles = selectedFiles.concat(filesToAdd);
        updatePreview();
    }

    // Handle manual file selection
    hiddenFileInput.addEventListener('change', function (e) {
        if (e.target.files.length > 0) {
            handleFileSelection(e.target.files);
        }
        // Reset input so same files can be re-selected
        e.target.value = '';
    });

    // Drag & drop handlers
    ['dragenter', 'dragover'].forEach(event => {
        dropArea.addEventListener(event, e => {
            e.preventDefault();
            dropArea.classList.add('dragover');
        });
    });

    ['dragleave', 'drop'].forEach(event => {
        dropArea.addEventListener(event, e => {
            e.preventDefault();
            dropArea.classList.remove('dragover');
        });
    });

    dropArea.addEventListener('drop', e => {
        const files = Array.from(e.dataTransfer.files);
        if (files.length > 0) {
            handleFileSelection(files);
        }
    });

    // Only clicking the file button opens file picker
    document.getElementById('form-attachment').addEventListener('click', () => {
        hiddenFileInput.click();
    });

    // Update hidden input before form submission
    formForm.addEventListener('submit', function () {
        if (selectedFiles.length > 0) {
            const dataTransfer = new DataTransfer();
            selectedFiles.forEach(file => dataTransfer.items.add(file));
            hiddenFileInput.files = dataTransfer.files;
        }
    });
});
</script>

@endsection

@section('foot-info')
    Perlu tolong segera? Lihat <b>FAQ</b> kami atau kontak <b>kami</b> secara langsung.
@endsection