<style>
    .ticket-card {
        border-radius: 24px;
        width: 100%;
        margin: 16px 0;
        padding: 16px;
        box-shadow: 0 4px 0px rgba(240, 240, 240, 0.5);
        text-align: left;
        overflow: hidden;
        border: 2px solid #ECECEC; 
        background-color: #202020;
        color: #ECECEC;
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }

    .ticket-card-wrapper {
        width: 100%;
        flex-grow: 1;
        overflow-y: auto;
        padding: 0 24px;
    }

    .bothend-flexbox {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .ticket-card .status { /* BELUM DIPROSES */
        color: #3A3A3A;
        font-weight: 800;
        font-size: 10px;
        background-color: #C0C0C0;
        border-radius: 16px;
        padding: 6px 12px;
        box-shadow: inset 0 0 0 2px #3A3A3A;
        text-align: right;
        align-items: center;
        line-height: 1;

        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .ticket-card .status.chuu { /* SEDANG DIPROSES */
        color: #7A4314;
        background-color: #FFE8C4;
        box-shadow: inset 0 0 0 2px #7A4314;
    }

    .ticket-card .status.shuu { /* SELESAI */
        color: #12630C;
        background-color: #CCFFC4;
        box-shadow: inset 0 0 0 2px #12630C;
    }

    .ticket-card .status.tei { /* DITUTUP */
        color: #7A1414;
        background-color: #FFC4C4;
        box-shadow: inset 0 0 0 2px #7A1414;
    }

    .ticket-card.selected {
        background-color: #ECECEC;
        border-color: #606060;
        color: #202020;
        border: 2px solid #606060; 
    }

    .ticket-card.selected h4 {
        color: #202020;
    }

    .ticket-card.selected p {
        color: #525252;
    }

    .ticket-card h4 {
        font-size: 16px;
        font-weight: 600;
        margin: 0;
        color: #ECECEC;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        text-overflow: ellipsis;
        hyphens: auto;
        word-break: break-word;
        line-height: 1.25;
        max-height: calc(1.25em * 2);
    }

    .ticket-card h4:hover {
        text-decoration: underline
    }

    .ticket-card p {
        font-size: 12px;
        color: #C8C8C8;
        line-height: 1.5;
        max-height: calc(1.5em * 3);
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        text-overflow: ellipsis;
        hyphens: auto;
        word-break: break-word;
        margin-top: 0;
        margin-bottom: 0;
    }

    @media (max-width: 360px) {
        .ticket-card {
            width: calc(100% - 48px);
            margin-left: 24px;
            margin-right: 24px;
        }
    }
</style>