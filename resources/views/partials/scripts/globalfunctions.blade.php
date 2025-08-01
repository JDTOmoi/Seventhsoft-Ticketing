<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    @if(isset($ct)) // Null failsafe, used when opening app for first time
        window.lastChatLocalDate = @json($lastChatLocalDate); //See ClientTicketController viewChat() for more details.
    @endif

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