@if ($docs->count())
    <div class="chat-attachments-wrapper {{ $isClient ? 'start' : 'end' }}">
        <div class="chat-docs-bubble">
            @foreach ($docs as $doc)
                <div class="chat-doc-row">
                    <div class="chat-doc-icon">
                        @if ($doc->extension === 'pdf')
                            <i class="fas fa-file-pdf" style="color: #d32f2f; font-size: 16px;"></i>
                        @elseif ($doc->extension === 'docx')
                            <i class="fas fa-file-word" style="color: #1976d2; font-size: 16px;"></i>
                        @endif
                    </div>
                    <div class="chat-doc-name">
                        <a href="{{ asset('storage/client_attachments/' . $doc->name) }}" download style="color: #333; text-decoration: underline;">
                            {{ Str::replaceFirst($chat->id . '_', '', pathinfo($doc->name, PATHINFO_FILENAME)) }}.{{ $doc->extension }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif