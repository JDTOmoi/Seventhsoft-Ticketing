@extends('layouts.app')

@section('style')

@include('partials.stylesheets.sidebar')
@include('partials.stylesheets.chatbubble')
@include('partials.stylesheets.chatattachment')
@include('partials.stylesheets.chatbar')

<!-- DISABLES SCROLLBAR UI -->
<style> 
    .ticket-card-wrapper, 
    #chat-scroll-container {
        scrollbar-width: none; /* Firefox */
        -ms-overflow-style: none; /* IE 10+ */
    }

    .ticket-card-wrapper::-webkit-scrollbar,
    #chat-scroll-container::-webkit-scrollbar {
        display: none; /* Chrome, Safari, Opera */
    }

    .ticket-card-wrapper,
    #chat-scroll-container {
        overflow-y: auto;
        overflow-x: hidden;
    }
</style>
@endsection

@section('content')

@include('partials.sidebar')

<div id="main-wrapper" class="{{ isset($ct) ? 'chat-mode' : 'welcome-mode' }}">
    @if (isset($ct))
        <div id="chat-scroll-container">
            @include('partials.dialog')
        </div>
    @else
        <h2>Selamat datang di Help Center!</h2>
        <br />
        <p>Klik judul sebuah tiket yang Anda telah buat untuk melihat kembali chat.</p>
        <p>Klik Buat Ticket Baru untuk membuat tiket baru.</p>
    @endif
</div>

@include('partials.chatbar')

<button id="chevron-toggle" class="chevron-button chevron-deselected" onclick="toggleSidebar()">
    <i class="fas fa-chevron-right"></i>
</button>

@include('partials.scripts.globalfunctions')
@include('partials.scripts.attachment')
@include('partials.scripts.hyperlink')
@include('partials.scripts.textarea')
@include('partials.scripts.customrequest')
@include('partials.scripts.firebase')

@endsection
