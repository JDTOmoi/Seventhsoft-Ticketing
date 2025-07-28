@if (isset($ct))
    <div id="chat-bar"> <!-- Keep the style parameter for Javascript functions.-->
        <div class="chat-bar-inner" @if ($ct->status === 'DITUTUP') style="height: 48px;"@endif> <!-- Kinda hacky but hey, it just works.-->
            @if ($ct->status !== 'DITUTUP')
                <button type="button" id="attachment-trigger" title="Tambah file" class="attachment-button">
                    <i class="fa fa-file fa-sm"></i>
                </button>
                <button type="button" id="close-chat-trigger" title="Tutup Chat" class="close-chat-button">
                    ✕
                </button>

                <form class="close-chat-form" id="close-chat-form" style="display: none;" method="POST" action="{{ route('closeChat', ['t' => $ct->id]) }}">
                    @csrf
                    @method('PUT')
                    <div class="close-chat-message">Tutup chat? Tindakan ini tidak dapat dibatalkan.</div>
                    <div class="close-chat-actions">
                        <button type="button" id="close-chat-cancel" class="btn-cancel-close">Batal</button>
                        <button type="submit" class="btn-confirm-close" onclick="duplicationPrevention(event, 'close-chat-form')">Tutup</button>
                    </div>
                </form>
                
                <form method="POST" class="chat-form" id="chat-form" enctype="multipart/form-data" action="{{ route('chatPost', ['t' => $ct->id]) }}">
                    @csrf
                    <textarea type="text" name="response" id="chat-message" oninput="autoResizeTextArea(this)" placeholder="Ketik pesan..." @if($ct->status === 'DITUTUP') disabled @endif></textarea>
                    <div class="attachment-menu" id="attachment-menu" style="display: none;">
                        <p>Upload Attachment yang terkait (opsional)</p>
                        <div id="drop-area" class="drop-area">
                            <p>Seret & jatuhkan file di sini atau klik untuk menelusuri</p>
                            <input type="file" id="chat-attachments" name="attachments[]" multiple accept=".png,.jpg,.jpeg,.webp,.pdf,.docx" hidden>
                        </div>
                        <ul id="attachment-preview" class="attachment-preview"></ul>
                        <p class="attachment-condition">Max. 4 file (PNG, JPG, PDF, DOCX, atau WEBP), ukuran tiap file max. 2MB</p>
                    </div>
                    <button onclick="duplicationPrevention(event, 'chat-form')" type="submit" class="submitter disabled" id="chat-submit" disabled>
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            @else
                <span class="chat-closed-msg">Anda tidak dapat chat pada tiket yang sudah ditutup.</span>
            @endif
        </div>
    </div>
@endif