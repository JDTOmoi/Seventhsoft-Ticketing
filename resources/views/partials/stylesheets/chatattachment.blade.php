<style>
    .chat-attachments-wrapper {
        display: flex;
        margin-bottom: 12px;
    }

    .chat-attachments-wrapper.start {
        justify-content: flex-start;
    }

    .chat-attachments-wrapper.end {
        justify-content: flex-end;
    }

    .chat-image-wrapper {
        width: 256px;
    }

    .chat-docs-bubble {
        max-width: 400px;
        background-color: #f4f4f4;
        box-shadow: 0 4px 4px rgba(0, 0, 0, 0.5);
        padding: 12px 16px;
        border-radius: 12px;
    }

    @media (max-width: 400px) {
        .chat-docs-bubble {
            max-width: 65vw;
        }
    }

    .chat-doc-row {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 8px;
    }

    .chat-doc-icon {
        flex-shrink: 0;
        background-color: #e0e0e0;
        border-radius: 50%;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .chat-doc-name {
        word-break: break-word;
        flex-grow: 1;
    }

    .image-1, .image-2, .image-3, .image-4 {
        object-fit: cover;
        border-radius: 8px;
        cursor: zoom-in;
    }

    .image-1 {
        width: 256px;
        height: 256px;
        border-radius: 16px;
    }

    .image-2 {
        width: 124px;
        height: 256px;
    }

    .image-3-vert {
        width: 124px;
        height: 124px;
        border-radius: 16px;
    }

    .image-grid {
        display: flex;
        gap: 8px;
    }

    .image-grid-wrap {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }    

    .chat-bubble-row .chat-attachments-wrapper {
        margin-bottom: 0;
    }
</style>