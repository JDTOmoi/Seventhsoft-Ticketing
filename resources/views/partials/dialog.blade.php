@if (isset($ct) && isset($cc))
    @foreach ($cc as $day => $group)
        <div class="chat-day-wrapper">
            <div style="display: flex; justify-content: center;">
                <div class="chat-day-label">
                    @php
                        $parsedDate = ''; //Null failsafe.

                        $carbonDate = \Carbon\Carbon::parse($day);

                        if ($carbonDate->isToday()) {
                            $parsedDate = "Hari ini";
                        } elseif ($carbonDate->isYesterday()) {
                            $parsedDate = "Kemarin";
                        } else {
                            $parsedDate = $carbonDate->translatedFormat('d F Y');
                        }
                    @endphp
                    {{ $parsedDate }}
                </div>
            </div>

            @foreach ($group as $chat)
                @php
                    $isClient = $chat->type === 'client';
                    $timezone = session('user_timezone', config('app.timezone'));
                    $time = \Carbon\Carbon::parse($chat->created_at)->setTimezone($timezone)->format('h:i A');
                    $check = $chat->check;
                    $images = $chat->attachments->whereIn('extension', ['jpg', 'jpeg', 'png', 'webp'])->sortBy('id')->values();
                    $docs = $chat->attachments->whereIn('extension', ['pdf', 'docx'])->sortBy('id')->values();
                @endphp

                @if($chat->response)
                    @include('partials.images')
                    @include('partials.docs')
                @elseif($images->count() && $docs->count())
                    @include('partials.images')
                @endif

                <div class="chat-bubble-row" style="justify-content: {{ $isClient ? 'flex-start' : 'flex-end' }};">
                    @if ($isClient)
                        <div style="display: flex; align-items: flex-end;">
                            <img src="{{ Auth::user()->profile_picture ? asset('storage/users/' . Auth::user()->profile_picture) : asset('storage/users/def.jpg') }}" class="profile-pic" style="margin-right: 12px;">
                            @if($chat->response)
                                <div class="chat-bubble client">
                                    <h4>{{ Auth::user()->username }}</h4>
                                    <p class="chat-content">{{ $chat->response }}</p>
                                </div>
                            @elseif($images->count() && $docs->count())
                                @include('partials.docs')
                            @elseif($images->count() && !$docs->count())
                                @include('partials.images')
                            @elseif(!$images->count() && $docs->count())
                                @include('partials.docs')
                            @endif
                            <span class="chat-time" style="margin-left: 12px;">{{ $time }}</span>
                            <span class="chat-status">
                                <i class="fa-solid
                                @if ($check === "sending")
                                fa-clock
                                @elseif ($check === "sent")
                                fa-check
                                @elseif ($check === "reached")
                                fa-check-double
                                @elseif ($check === "read")
                                fa-check-double read
                                @endif
                                ">
                                </i>
                            </span>
                        </div>
                    @else <!-- REPLACE AUTH::USER() WITH $SUPPORT WHEN REYHAN AND ADIT'S PART ARE COMBINED-->
                        <div style="display: flex; align-items: flex-end;">
                            <span class="chat-time" style="margin-right: 12px;">{{ $time }}</span>
                            @if($chat->response)
                                <div class="chat-bubble support">
                                    <h4>{{ Auth::user()->username }}</h4>
                                    <p class="chat-content">{{ $chat->response }}</p>
                                </div>
                            @elseif($images->count() && $docs->count())
                                @include('partials.docs')
                            @elseif($images->count() && !$docs->count())
                                @include('partials.images')
                            @elseif(!$images->count() && $docs->count())
                                @include('partials.docs')
                            @endif
                            <img src="{{ Auth::user()->profile_picture ? asset('storage/users/' . Auth::user()->profile_picture) : asset('storage/users/def.jpg') }}" class="profile-pic" style="margin-left: 12px;">
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endforeach
@endif
