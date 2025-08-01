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
  const user = @json(Auth::user());

  const monthMap = {
    0: 'January', 1: 'February', 2: 'March', 3: 'April', 4: 'May', 5: 'June',
    6: 'July', 7: 'August', 8: 'September', 9: 'October', 10: 'November', 11: 'December'
  };

  function formatDateLabel(date) {
    const now = new Date();
    const yesterday = new Date();
    yesterday.setDate(now.getDate() - 1);

    const dateOnly = date.toDateString();
    const nowOnly = now.toDateString();
    const yestOnly = yesterday.toDateString();

    if (dateOnly === nowOnly) return 'Hari ini';
    if (dateOnly === yestOnly) return 'Kemarin';

    return `${date.getDate()} ${monthMap[date.getMonth()]} ${date.getFullYear()}`;
  }

  function createImageGrid(images) { //converted from partials.images
    //TODO: Make the one for display for the ones in SUPPORT. Theoretically cannot be properly done until SupportToDeveloper UI is finished.
    const count = images.length;
    let html = '';

    if (count === 1) {
      html = `<img src="/storage/client_attachments/${images[0].name}" class="image-1">`;
    } else if (count === 2) {
      html = `<div class="image-grid">${images.map(i => `<img src="/storage/client_attachments/${i.name}" class="image-2">`).join('')}</div>`;
    } else if (count === 3) {
      html = `
        <div class="image-grid">
            <img src="/storage/client_attachments/${images[0].name}" class="image-2">
            <div class="image-grid" style="flex-direction: column;">
                <img src="/storage/client_attachments/${images[1].name}" class="image-3-vert">
                <img src="/storage/client_attachments/${images[2].name}" class="image-3-vert">
            </div>
        </div>
      `;
    } else {
      html = `<div class="image-grid-wrap">${images.slice(0, 4).map(i => `<img src="/storage/client_attachments/${i.name}" class="image-3-vert">`).join('')}</div>`;
    }

    return `
      <div class="chat-attachments-wrapper start">
        <div class="chat-image-wrapper">${html}</div>
      </div>
    `;
  }

  function createDocGrid(docs, chatId) { //converted from partials.docs
    //TODO: Make the one for display for the ones in SUPPORT. Theoretically cannot be properly done until SupportToDeveloper UI is finished.
    const docHtml = docs.map(doc => {
      const name = doc.name.replace(`${chatId}_`, '').replace(/\.\w+$/, '');
      const icon = doc.extension === 'pdf'
        ? `<i class="fas fa-file-pdf" style="color: #d32f2f; font-size: 16px;"></i>`
        : `<i class="fas fa-file-word" style="color: #1976d2; font-size: 16px;"></i>`;
      return `
        <div class="chat-doc-row">
          <div class="chat-doc-icon">${icon}</div>
          <div class="chat-doc-name">
            <a href="/storage/client_attachments/${doc.name}" download style="color: #333; text-decoration: underline;">
              ${name}.${doc.extension}
            </a>
          </div>
        </div>
      `;
    }).join('');

    return `
      <div class="chat-attachments-wrapper start">
        <div class="chat-docs-bubble">${docHtml}</div>
      </div>
    `;
  }

  function renderChat(chat) {
    //Time at UTC
    const utcDate = new Date(chat.created_at);

    //Convert time to the timezone of wherever the user is now.
    const localDate = new Date(utcDate.getTime() + (utcDate.getTimezoneOffset() * 60000 * -1));
    
    //Get time from local day.
    const time = localDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    const pad = (n) => n.toString().padStart(2, '0');
    const dateKey = `${localDate.getFullYear()}-${pad(localDate.getMonth() + 1)}-${pad(localDate.getDate())}`;
    const dateLabel = formatDateLabel(localDate);
    //Generate one unique ID corresponding to the day, should be fine unless someone wants to be put on hold for over 48 hours nonstop.
    const dayWrapperId = `chat-day-${dateKey}`;
    let wrapper = document.getElementById(dayWrapperId);
    //console.log(window.lastChatLocalDate);
    //console.log(dateKey);
    const shouldAddDayWrapper = dateKey != window.lastChatLocalDate
    //console.log(shouldAddDayWrapper);
    const images = chat.attachments?.filter(att => ['jpg', 'jpeg', 'png', 'webp'].includes(att.extension)) || [];
    const docs = chat.attachments?.filter(att => ['pdf', 'docx'].includes(att.extension)) || [];

    const responseHtml = chat.response
      ? `<div class="chat-bubble client"><h4>${user.username}</h4><p class="chat-content">${chat.response}</p></div>`
      : '';

    const imageHtml = images.length ? createImageGrid(images) : '';
    const docHtml = docs.length ? createDocGrid(docs, chat.id) : '';

    let chatRow = '';

    let hasText = !!chat.response?.trim();
    let hasImages = images.length > 0;
    let hasDocs = docs.length > 0;

    if (hasText || hasImages || hasDocs) {
      chatRow = '';

      if (hasText && hasImages) chatRow += imageHtml;
      if (hasText && hasDocs) chatRow += docHtml;
      if (!hasText && hasImages && hasDocs) chatRow += imageHtml;

      chatRow += `
        <div class="chat-bubble-row" style="justify-content: flex-start;">
          <div style="display: flex; align-items: flex-end;">
            <img src="/storage/users/${user.profile_picture}" class="profile-pic" style="margin-right: 12px;">
            ${hasText ? responseHtml : ''}
            ${!hasText && hasImages && !hasDocs ? imageHtml : ''}
            ${!hasText && !hasImages && hasDocs ? docHtml : ''}
            ${!hasText && hasImages && hasDocs ? docHtml : ''}
            <span class="chat-time" style="margin-left: 12px;">${time}</span>
            <span class="chat-status"><i class="fa-solid fa-clock"></i></span>
          </div>
        </div>
      `;
    }


    // TODO: Restructure the chatRow html when images and docs are present. Checklist for properly implemented rows. 
    // TXT only     V
    // IMG only     V
    // DOC only     V
    // TXT + IMG    V
    // TXT + DOC    V
    // IMG + DOC    V
    // ALL THREE    V

    // TODO: Same as here but do it again for the support. Theoretically cannot be properly done until other group finishes.
    // TXT only     X
    // IMG only     X
    // DOC only     X
    // TXT + IMG    X
    // TXT + DOC    X
    // IMG + DOC    X
    // ALL THREE    X

    //console.log("Date key:", dateKey, "Exists:", !!wrapper);

    if (shouldAddDayWrapper) {
        wrapper = document.createElement('div');
        wrapper.classList.add('chat-day-wrapper');
        wrapper.id = dayWrapperId;
        wrapper.innerHTML = `
            <div style="display: flex; justify-content: center;">
                <div class="chat-day-label">${dateLabel}</div>
            </div>
            ${chatRow}
        `;
        document.getElementById('chat-scroll-container').appendChild(wrapper);
    } else {
        if (!wrapper) {
            wrapper = document.createElement('div');
            wrapper.classList.add('chat-day-wrapper');
            wrapper.id = dayWrapperId;
            document.getElementById('chat-scroll-container').appendChild(wrapper);
        }
        wrapper.insertAdjacentHTML('beforeend', chatRow);
    }
    
    window.lastChatLocalDate = dateKey; // Update this variable since you just made a new chat.

    // Auto-scroll to bottom
    const container = document.getElementById('chat-scroll-container');
    container.scrollTop = container.scrollHeight;
  }

  @if(isset($ct))
    const ticketID = "{{ $ct->id }}";

    const chatRef = db.ref('tickets/' + ticketID + '/');
    let initialLoad = true;

    chatRef.once('value')
      .then(() => {
          initialLoad = false;
      });
    
    chatRef.on('child_added', (snapshot) => {
      if (initialLoad) return;
      const message = snapshot.val();
      renderChat(message);
    });
  @endif
</script>