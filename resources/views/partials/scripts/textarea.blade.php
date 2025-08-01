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
        positionPopupForms(); //Global function
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