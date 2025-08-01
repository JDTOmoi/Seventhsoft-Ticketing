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