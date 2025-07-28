<script>
document.addEventListener('DOMContentLoaded', function () {
    const dropArea = document.getElementById('drop-area');
    const hiddenFileInput = document.getElementById('filebutton');
    const previewArea = document.getElementById('attachment-preview');
    const formForm = document.getElementById('form-form');

    const allowedExts = ['png', 'jpg', 'jpeg', 'webp'];
    const maxSize = 2 * 1024 * 1024;
    let selectedFile = null;

    function validateFile(file) {
        const ext = file.name.split('.').pop().toLowerCase();
        return allowedExts.includes(ext) && file.size <= maxSize;
    }

    function updatePreview() {
        previewArea.innerHTML = '';
        if (selectedFile) {
            const preview = document.createElement('div');
            preview.innerHTML = `
                <div style="margin-top: 10px; padding: 10px; border: 1px solid #ddd; border-radius: 5px; display: flex; justify-content: space-between; align-items: center;">
                    <span><strong>[${selectedFile.name.split('.').pop().toUpperCase()}]</strong> ${selectedFile.name}</span>
                    <button type="button" id="remove-file" style="background: #e53b3b; color: white; border: none; border-radius: 3px; padding: 5px 10px; cursor: pointer;">&times;</button>
                </div>
            `;
            previewArea.appendChild(preview);

            document.getElementById('remove-file').addEventListener('click', function() {
                selectedFile = null;
                updatePreview();
                hiddenFileInput.value = '';
            });
        }
    }

    function handleFileSelection(file) {
        if (validateFile(file)) {
            selectedFile = file;
            updatePreview();
        } else {
            alert('Please select a valid image file (PNG, JPG, JPEG, or WEBP) under 2MB.');
        }
    }

    hiddenFileInput.addEventListener('change', function (e) {
        if (e.target.files.length > 0) {
            handleFileSelection(e.target.files[0]);
        }
    });

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
            handleFileSelection(files[0]); 
        }
    });

    document.getElementById('form-attachment').addEventListener('click', () => {
        hiddenFileInput.click();
    });

    formForm.addEventListener('submit', function () {
        if (selectedFile) {
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(selectedFile);
            hiddenFileInput.files = dataTransfer.files;
        }
    });
});
</script>