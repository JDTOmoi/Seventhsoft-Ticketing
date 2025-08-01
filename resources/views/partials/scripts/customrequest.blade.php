<script> //Custom POST request to prevent browser refreshes.
  @if(isset($ct)) // Null failsafe, used when opening app for first time
    document.getElementById('chat-form').addEventListener('submit', async function(e) {
        e.preventDefault(); // Prevent page reload

        const messageInput = document.getElementById('chat-message');
        const submitBtn = document.getElementById('chat-submit');
        const previewList = document.getElementById('attachment-preview');
        const attachmentMenu = document.getElementById('attachment-menu');

        const message = messageInput.value.trim(); //Transferred to another variable because apparently, doing it all in one go poofs the text to nonexistence before reaching the request.
        if (!message && selectedFiles.length === 0) return;

        messageInput.value = '';
        submitBtn.classList.toggle('disabled', true);
        submitBtn.disabled = true;

        const formData = new FormData();
        formData.append('ticket_id', "{{ $ct->id }}");
        formData.append('response', message);

        selectedFiles.forEach(file => {
            formData.append('attachments[]', file);
        });

        const response = await fetch("{{ route('chatPost', ['t' => $ct->id]) }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        });

        if (response.ok) {
            // Reset UI only if successful
            messageInput.value = '';
            selectedFiles = [];
            previewList.innerHTML = '';
            attachmentMenu.style.display = 'none';
        } else {
            console.error('Upload failed:', await response.text());
        }
    });
  @endif
</script>