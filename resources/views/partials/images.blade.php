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