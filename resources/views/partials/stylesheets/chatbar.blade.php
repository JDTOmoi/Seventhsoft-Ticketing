<style>
    #chat-bar {
        position: fixed;
        bottom: 0;
        left: 360px;
        right: 0;
        background-color: #F3F3F3;
        border-top: 2px solid #ECECEC;
        display: flex;
        justify-content: center;
        align-items: flex-end; /* makes everything go down */
        padding: 8px 16px;
        z-index: 1030;
        transition: bottom 0.3s ease;
        box-shadow: 0 -4px 4px rgba(0, 0, 0, 0.25);
    }

    #chat-bar form {
        display: flex;
        width: 100%;
        max-width: 720px;
    }

    #chat-bar textarea {
        flex: 1;
        padding: 12px 16px;
        border-radius: 16px;
        border: 1px solid #D4D4D4;
        font-size: 14px;
        margin-right: 8px;
        line-height: 24px; /* makes sure that the line height remains consistent when resizing. */
        height: 48px;
        max-height: 120px;
        resize: none;
        overflow-y: hidden;
        overflow-x: hidden;
        align-self: flex-end;
    }

    #chat-bar button.submitter{
        background-color: #4D2EE7;
        border: none;
        padding: 12px 16px;
        border-radius: 32px;
        color: #F3F3F3;
        font-weight: bold;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        width: 48px;
        height: 48px;
    }

    #chat-bar button.submitter.disabled {
        cursor: default;
        background-color: #D2D2D2;
        color: #7F7F7F;
        box-shadow: none;
    }

    #chat-bar button.submitter i {
        font-size: 16px;
    }

    @media (max-width: 720px) {
        #chat-bar {
            left: 0;
        }

        #sidebar.show ~ #chat-bar {
            box-shadow: none;
            bottom: -100px !important;
        }
    }

    .chat-bar-inner {
        display: flex; /* make horizontal arrangement */
        width: 100%;
        max-width: 720px;
        align-items: center;
        position: relative;
        gap: 8px; /* Keeps spacing */
    }

    .close-chat-form,
    .attachment-menu {
        position: absolute;
        bottom: 72px;
        background: #FFFFFF;
        border-radius: 16px;
        padding: 16px;
        box-shadow: 0 4px 4px rgba(0,0,0,0.5);
        z-index: 200;
    }

    .close-chat-message {
        margin: 0 0 8px 0;
        font-size: 14px;
    }

    .close-chat-actions {
        display: flex;
        gap: 8px;
        justify-content: flex-end;
    }

    .btn-cancel-close {
        padding: 4px 8px;
        border: none;
        background-color: #CCCCCC;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .btn-confirm-close {
        padding: 4px 8px;
        border: none;
        background-color: #cc0000;
        color: white;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .btn-cancel-close:hover {
        background-color: #9B9B9B;
    }

    .btn-confirm-close:hover {
        background-color: #810505;
    }

    .chat-form {
        display: flex;
        flex: 1;
        align-items: flex-end;
        gap: 8px;
    }

    .chat-closed-msg {
        color: #525252;
        font-size: 14px;
        margin: auto;
    }

    .close-chat-button,
    .attachment-button {
        background: none;
        border: none;
        cursor: pointer;
        color: #525252;
        width: 48px;
        height: 48px;
        font-size: 30px;
        text-decoration: none;
        display: flex;
        align-items: center;
        align-self: flex-end;
        transition: color 0.3s ease;
    }

    .close-chat-button:hover {
        font-weight: 700;
        color: #830000
    }

    .attachment-button:hover {
        font-weight: 700;
        color: #197604
    }

    .drop-area {
        border: 3px dotted gray;
        padding: 15px;
        text-align: center;
        cursor: pointer;
        border-radius: 8px;
        background: #ffffff;
        transition: border-color 0.3s ease;
    }

    .drop-area.dragover {
        border-color: #0b234a;
        background: #eef5ff;
    }

    .attachment-preview {
        list-style: none;
        padding-left: 0;
        margin-top: 10px;
    }

    .attachment-preview li {
        display: flex;
        align-items: flex-start; /* make it wrapped text */
        justify-content: space-between;
        background: #f1f1f1;
        padding: 6px 10px;
        margin-bottom: 5px;
        border-radius: 6px;
        font-size: 14px;
        gap: 8px;
        flex-wrap: nowrap;
    }

    .attachment-preview li span {
        flex: 1;
        white-space: normal; /* wrapper */
        word-break: break-word; /* Break words */
        overflow-wrap: anywhere; /* long strings */
    }

    .attachment-preview li button {
        flex-shrink: 0; /* shrinkproofer */
        background: transparent;
        border: none;
        color: red;
        font-size: 16px;
        cursor: pointer;
    }
    </style>