<div id="sidebar">
    <a href="{{ route('ticketsCreate') }}" class="ticket-button">
        +Buat Ticket Baru
    </a>

    <hr class="sidebar-divider">
    <div class="ticket-card-wrapper">
        @foreach ($tickets as $ticket)
            @php
                $firstChat = $chats->flatten()->where('ticketID', $ticket->id)->sortBy('created_at')->first();
                $selected = isset($ct) && $ct->id === $ticket->id;
                $stText = 'Blm diproses';
                $stClass = '';
                if ($ticket->status === 'SEDANG DIPROSES') {
                    $stClass = 'chuu';
                    $stText = 'Sdg diproses';
                }
                elseif($ticket->status === 'SELESAI') {
                    $stClass = 'shuu';
                    $stText = 'Selesai';
                }
                elseif($ticket->status === 'DITUTUP') { //DITUTUP
                    $stClass = 'tei';
                    $stText = 'Ditutup';
                }
            @endphp

            <div class="ticket-card {{ $selected ? 'selected' : '' }}">
                <div class="bothend-flexbox">
                    <a href="{{ route('chat', ['t' => $ticket->id]) }}" style="text-decoration: none; color: inherit;">
                        <h4>{{ $ticket->title }}</h4>
                    </a>
                    <div class="status {{ $stClass }}">
                        {{ $stText }}
                    </div>
                </div>
                <p>{{ $firstChat->response ?? 'Tidak ada chat.' }}</p>
            </div>
        @endforeach
    </div>
</div>