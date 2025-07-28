<style>
    #chat-scroll-container {
        height: calc(100vh - 64px - 64px); /* navbar height + chat bar height */
        overflow-y: auto;
        padding-bottom: 24px;
        scroll-behavior: smooth;
    }

    .chat-bubble {
        max-width: 440px;
        width: 80%;
        padding: 16px;
        border-radius: 16px;
        box-shadow: 0 4px 4px rgba(0, 0, 0, 0.5);
        word-wrap: break-word;
        overflow-wrap: break-word;
        text-align: left;
    }

    @media (max-width: 760px) {
        .chat-bubble {
            max-width: 300px;
            width: 60%;
        }
    }

    @media (max-width: 720px) {
        .chat-bubble {
            max-width: 440px;
            width: 80%;
        }
    }

    @media (max-width: 400px) {
        .chat-bubble {
            max-width: 240px;
            width: 60%;
        }
    }

    .chat-bubble h4 {
        margin: 0 0 8px 0;
        font-size: 16px;
    }

    .chat-bubble p {
        margin: 0;
        font-size: 14px;
        line-height: 1.5;
        white-space: pre-wrap;
    }

    .chat-bubble.client {
        background-color: #4D2EE7;
        color: #F0F0F0;
    }

    .chat-bubble.client h4 {
        color: #ECECEC;
    }

    .chat-bubble.support {
        background-color: #E5E5E5;
        color: #111111;
    }

    .chat-bubble.support h4 {
        color: #202020;
    }
    
    .chat-bubble.client h4, /* !important overrides everything */
    .chat-bubble.support h4 {
        font-size: 18px !important;
        font-weight: 600 !important;
    }

    .chat-bubble.client p,
    .chat-bubble.support p {
        font-size: 13px !important;
        font-weight: 300 !important;
    }

    .chat-bubble.client a {
        font-weight: 500;
        color: #F0F0F0;
        text-decoration: none;
    }

    .chat-bubble.support a {
        font-weight: 500;
        color: #111111;
        text-decoration: none;
    }

    .chat-bubble.client a:hover,
    .chat-bubble.support a:hover {
        text-decoration: underline;
        text-decoration-thickness: 1.5px;
    }


    .chat-time {
        font-size: 12px;
        font-weight: 400;
        color: #525252;
        white-space: nowrap;
    }

    .chat-day-label {
        color: #2D2D2D;
        font-weight: 800;
        font-size: 15px;
        background-color: #EFEFEF;
        border-radius: 27px;
        padding: 6px 24px;
        box-shadow: inset 0 0 0 2px #2D2D2D;
        text-align: center;
        margin: 16px 0;
    }

    .chat-bubble-row {
        display: flex;
        align-items: flex-end;
        margin-bottom: 16px;
    }

    .chat-status i {
        margin-left: 8px;
        font-size: 15px;
        color: #2D2D2D;
    }

    .chat-status i.read {
        color: #2C91BD;
    }

    .profile-pic {
        width: 32px;
        height: 32px;
        border-radius: 50%;
    }
</style>