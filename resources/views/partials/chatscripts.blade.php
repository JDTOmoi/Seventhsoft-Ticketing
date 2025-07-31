<script>
    document.addEventListener('DOMContentLoaded', function () {
        const chatContainer = document.getElementById('chat-scroll-container');
        if (chatContainer) {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }
    });

    function closePopupMenusOnMobile() {
        const closeForm = document.getElementById('close-chat-form');
        const attachMenu = document.getElementById('attachment-menu');

        if (window.innerWidth <= 720) {
            if (closeForm) closeForm.style.display = 'none';
            if (attachMenu) attachMenu.style.display = 'none';
        }
    }
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const chevronBtn = document.getElementById('chevron-toggle');
        const icon = chevronBtn.querySelector('i');
        const chatBar = document.getElementById('chat-bar');
        const isNotShown = !sidebar.classList.contains('show');

        sidebar.classList.toggle('show', isNotShown);
        icon.classList.toggle('fa-chevron-left', isNotShown);
        icon.classList.toggle('fa-chevron-right', !isNotShown);
        chevronBtn.classList.toggle('chevron-selected', isNotShown);
        chevronBtn.classList.toggle('chevron-deselected', !isNotShown);

        updateChatBarPosition();
        closePopupMenusOnMobile();
    }

    function updateChatBarPosition() {
        const sidebar = document.getElementById('sidebar');
        const chatBar = document.getElementById('chat-bar');
        const isShown = sidebar.classList.contains('show');
        const height = chatBar.offsetHeight; // height should be good by now

        if (window.innerWidth <= 720) {
            chatBar.style.setProperty('bottom', isShown ? `-${height}px` : '0', 'important');
        } else {
            chatBar.style.bottom = '0';
        }
    }

    window.addEventListener('resize', () => { //update the chat bar, and close the pop ups when resizing from desktop to mobile.
        updateChatBarPosition();
        closePopupMenusOnMobile();
    });

    //Timezone

    const userTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;

    fetch('/set-timezone', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        body: JSON.stringify({ timezone: userTimezone })
    });


</script>

<script> //Close chat and attachment forms
let selectedFiles = []; //Made into global so that it can be sent in the post request.

function validateInputs() {
    const messageInput = document.getElementById('chat-message');
    const hasText = messageInput.value.trim().length > 0;
    const submitBtn = document.getElementById('chat-submit');
    const allowedExts = ['png', 'jpg', 'jpeg', 'pdf', 'docx', 'webp'];
    const maxSize = 2 * 1024 * 1024;
    const maxFiles = 4;
    let validFiles = true;

    if (selectedFiles.length > 0) {
        if (selectedFiles.length > maxFiles) {
            validFiles = false;
        }

        for (const file of selectedFiles) {
            const ext = file.name.split('.').pop().toLowerCase();
            if (!allowedExts.includes(ext) || file.size > maxSize) {
                validFiles = false;
                break;
            }
        }
    }

    // Enable if there's text OR (valid files AND not empty)
    submitBtn.disabled = !(hasText || (selectedFiles.length > 0 && validFiles));
    submitBtn.classList.toggle('disabled', submitBtn.disabled);
}

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
    });
});

function positionPopupForms() { //Move popup forms
    const chatBar = document.getElementById('chat-bar');
    const closeForm = document.getElementById('close-chat-form');
    const attachMenu = document.getElementById('attachment-menu');

    if (!chatBar) return;

    const chatBarHeight = chatBar.offsetHeight;
    const offsetFromBar = 8; // distance from top of chat bar

    const bottomOffset = chatBarHeight + offsetFromBar + 'px';

    if (closeForm && closeForm.style.display === 'block') {
        closeForm.style.bottom = bottomOffset;
    }

    if (attachMenu && attachMenu.style.display === 'block') {
        attachMenu.style.bottom = bottomOffset;
    }
}
</script>
<script> // HYPERLINK MAKER
document.addEventListener('DOMContentLoaded', function () {
    const chatContents = document.getElementsByClassName("chat-content")

    function escapeHTML(str) { //XSS PROTECTION
        return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
    }

    function linkify(text) {
        const urlRegex = /((https?:\/\/)[^\s]+)/g;
        return text.replace(urlRegex, '<a href="$1" target="_blank" rel="noopener noreferrer" onmouseover="text-decoration: underline;">$1</a>');
    }

    for (let i = 0; i < chatContents.length; i++) {
        const div = chatContents[i];
        const rawText = div.textContent || ''; //Add an empty string if null, innerHTML adds duplicate <br>
        const escapedText = escapeHTML(rawText);
        const linkifiedText = linkify(escapedText);
        div.innerHTML = linkifiedText;
    }
});
</script>
<script>
// Init values
const chatBar = document.getElementById('chat-bar');
const sidebar = document.getElementById('sidebar');
const textarea = document.getElementById('chat-message');

function autoResizeTextArea(elem) {
    elem.style.height = '48px';
    const newHeight = Math.min(elem.scrollHeight, 120);
    elem.style.overflowY = newHeight >= 120 ? 'auto' : 'hidden';
    elem.style.height = newHeight + 'px';
    //delay frontend until height is updated.
    requestAnimationFrame(() => {
        updateChatBarLayout();
        positionPopupForms();
    });
}

// Responsive chat bar
function updateChatBarLayout() {
    if (!chatBar || !sidebar) return;

    const isShown = sidebar.classList.contains('show');

    // Make sure it updates the value of autoresizetextarea.
    requestAnimationFrame(() => {
        const isShown = sidebar.classList.contains('show');

        if (window.innerWidth <= 720) {
            const height = chatBar.offsetHeight; // height should be good by now
            chatBar.style.setProperty('bottom', isShown ? `-${height}px` : '0', 'important');
        } else {
            chatBar.style.bottom = '0';
        }

        positionPopupForms();
    });
}

function handleResize() {
    updateChatBarLayout();
    if (textarea) autoResizeTextArea(textarea);
}

// Init Function
document.addEventListener('DOMContentLoaded', () => {
    if (textarea) autoResizeTextArea(textarea);
    updateChatBarLayout();
    window.addEventListener('resize', handleResize);
});
</script>