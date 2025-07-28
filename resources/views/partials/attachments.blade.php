@php
    $isClient = $chat->type === 'client';
    $images = $chat->attachments->whereIn('extension', ['jpg', 'jpeg', 'png', 'webp'])->sortBy('id')->values();
    $docs = $chat->attachments->whereIn('extension', ['pdf', 'docx'])->sortBy('id')->values();
@endphp

@if ($images->count())
    <div class="chat-attachments-wrapper {{ $isClient ? 'start' : 'end' }}">
        <div class="chat-image-wrapper">
            @if ($images->count() === 1)
                <img src="{{ asset('storage/client_attachments/' . $images[0]->name) }}" class="image-1">
            @elseif ($images->count() === 2)
                <div class="image-grid">
                    @foreach ($images as $img)
                        <img src="{{ asset('storage/client_attachments/' . $img->name) }}" class="image-2">
                    @endforeach
                </div>
            @elseif ($images->count() === 3)
                <div class="image-grid">
                    <img src="{{ asset('storage/client_attachments/' . $images[0]->name) }}" class="image-2">
                    <div class="image-grid" style="flex-direction: column;">
                        <img src="{{ asset('storage/client_attachments/' . $images[1]->name) }}" class="image-3-vert">
                        <img src="{{ asset('storage/client_attachments/' . $images[2]->name) }}" class="image-3-vert">
                    </div>
                </div>
            @elseif ($images->count() >= 4)
                <div class="image-grid-wrap">
                    @foreach ($images->take(4) as $img)
                        <img src="{{ asset('storage/client_attachments/' . $img->name) }}" class="image-3-vert">
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endif

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