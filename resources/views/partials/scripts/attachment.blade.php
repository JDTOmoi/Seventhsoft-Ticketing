<script> //Close chat and attachment forms
document.addEventListener('DOMContentLoaded', function () {
    const dropArea = document.getElementById('drop-area');
    const hiddenFileInput = document.getElementById('chat-attachments');
    const previewList = document.getElementById('attachment-preview');
    const submitBtn = document.getElementById('chat-submit');
    const messageInput = document.getElementById('chat-message');
    const chatForm = document.getElementById('chat-form');

    const closeTrigger = document.getElementById('close-chat-trigger');
    const confirmBox = document.getElementById('close-chat-form');
    const cancelBtn = document.getElementById('close-chat-cancel');
    const attachmentTrigger = document.getElementById('attachment-trigger');
    const attachmentMenu = document.getElementById('attachment-menu');

    const allowedExts = ['png', 'jpg', 'jpeg', 'pdf', 'docx', 'webp'];
    const maxSize = 2 * 1024 * 1024;
    const maxFiles = 4;
    

    // Close chat toggle
    if (closeTrigger && confirmBox && cancelBtn) {
        closeTrigger.addEventListener('click', () => {
            attachmentMenu.style.display = 'none';
            confirmBox.style.display = confirmBox.style.display === 'block' ? 'none' : 'block';
            positionPopupForms();
        });

        cancelBtn.addEventListener('click', () => {
            confirmBox.style.display = 'none';
        });
    }

    // Attachment menu toggle
    if (attachmentTrigger && attachmentMenu) {
        attachmentTrigger.addEventListener('click', () => {
            confirmBox.style.display = 'none';
            attachmentMenu.style.display = attachmentMenu.style.display === 'block' ? 'none' : 'block';
            positionPopupForms();
        });
    }

    function updatePreview() {
        previewList.innerHTML = '';
        selectedFiles.forEach((file, index) => {
            const li = document.createElement('li');
            li.innerHTML = `
                <span><strong>[${file.name.split('.').pop().toUpperCase()}]</strong> ${file.name}</span>
                <button type="button" data-index="${index}">&times;</button>
            `;
            previewList.appendChild(li);
        });

        validateInputs();
    }

    previewList.addEventListener('click', function (e) {
        if (e.target.tagName === 'BUTTON') {
            const index = parseInt(e.target.dataset.index);
            selectedFiles.splice(index, 1);
            updatePreview();
        }
    });

    function isAllowed(file) {
        const ext = file.name.split('.').pop().toLowerCase();
        return allowedExts.includes(ext) && file.size <= maxSize;
    }

    hiddenFileInput.addEventListener('change', function (e) {
        Array.from(e.target.files).forEach(file => {
            if (selectedFiles.length < maxFiles && isAllowed(file)) {
                selectedFiles.push(file);
            }
        });
        updatePreview();
        hiddenFileInput.value = '';
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
        files.forEach(file => {
            if (selectedFiles.length < maxFiles && isAllowed(file)) {
                selectedFiles.push(file);
            }
        });
        updatePreview();
    });

    dropArea.addEventListener('click', () => {
        hiddenFileInput.click();
    });

    messageInput.addEventListener('input', validateInputs);

    chatForm.addEventListener('submit', function () {
        const dataTransfer = new DataTransfer();
        selectedFiles.forEach(file => dataTransfer.items.add(file));
        hiddenFileInput.files = dataTransfer.files;
        hiddenFileInput.value = ''; //fixes bug where it is not attaching previously sent files.
    });
});
</script>