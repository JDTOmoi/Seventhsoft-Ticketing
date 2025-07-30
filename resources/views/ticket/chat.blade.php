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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@include('partials.chatscripts')

<script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-database.js"></script>
<script>
  const firebaseConfig = {
        apiKey: "AIzaSyBV2hBUPLMvp-H-tVT913G4dxfGhACsYA0",
        authDomain: "seventhsoft-ticketing.firebaseapp.com",
        projectId: "seventhsoft-ticketing",
        storageBucket: "seventhsoft-ticketing.firebasestorage.app",
        databaseURL: "https://seventhsoft-ticketing-default-rtdb.asia-southeast1.firebasedatabase.app",
        messagingSenderId: "969424041134",
        appId: "1:969424041134:web:6745ba3e6ab06d7786a93f"
    };
  firebase.initializeApp(firebaseConfig);
  const db = firebase.database();

  @if(isset($ct))
    const ticketID = "{{ $ct->id }}";
    db.ref('tickets/' + ticketID + '/').on('child_added', (snapshot) => {
      const message = snapshot.val();
      console.log("New message:", message);
    });
  @endif
</script>

@endsection
