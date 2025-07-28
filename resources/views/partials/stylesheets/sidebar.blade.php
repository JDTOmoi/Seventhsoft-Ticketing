<style>
    #sidebar {
        position: fixed;
        top: 64px; /* below navbar */
        left: 0;
        width: 360px;
        height: calc(100vh - 64px); /* avoid covering navbar */
        background-color: #0b234a; /* match navbar */
        color: white;
        z-index: 1040;
        transition: transform 0.3s ease-in-out;
        padding: 0px 20px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        align-items: center;

        box-shadow:
            inset -3px 0 0 0 #ECECEC,
            4px 0px 4px rgba(0, 0, 0, 0.25);
    }

    html, body {
        height: 100%;
        overflow: hidden;
    }

    #main-wrapper {
        position: fixed;
        top: 64px;
        left: 360px;
        right: 0;
        bottom: 0;
        overflow: hidden;
        padding: 20px;
        padding-top: 0;
        padding-bottom: 0;
    }

    #main-wrapper h2 {
        font-weight: 600;
        font-size: 48px;
    }

    #main-wrapper p {
        font-weight: 200;
        font-size: 24px;
    }

    .welcome-mode {
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
        flex-direction: column;
    }

    .chat-mode {
        display: block;
        text-align: left;
    }

    .ticket-button {
        width: 274px;
        height: 42px;
        background-color: #ececec;
        color: #202020;
        font-weight: 600;
        border-radius: 6px;
        border: none;
        margin-top: 6px;
        margin-bottom: 6px;
        text-align: center;
        line-height: 42px;
        text-decoration: none;
        display: block;
        transition: background-color 0.3s ease, color 0.3s ease, box-shadow 0.3s ease;
    }

    .ticket-button:hover {
        text-decoration: underline;
        text-decoration-thickness: 2px;
    }

    .ticket-button:active {
        background-color: #202020;
        color: #ececec;
        box-shadow: inset 0 0 0 2px #ECECEC;
    }

    .sidebar-divider {
        width: 100%;
        border: none;
        height: 5px;
        background-color: #f0f0f0;
        margin: 0px;
    }

    .chevron-button {
        display: none;
    }

    .chevron-selected {
        color: #0b234a;
        background-color: #ECECEC;
    }

    .chevron-deselected {
        background-color: #0b234a;
        color: #ECECEC;
    }

    #sidebar.show .chevron-button {
        left: 0;
        transform: translateX(-100%);
        border-radius: 0 6px 6px 0;
    }

    @media (max-width: 720px) {
        #sidebar {
            transform: translateX(-100%); /* Disable and reactivate box shadow when toggled on or off. */
            box-shadow: none;
        }

        #sidebar.show {
            transform: translateX(0);
            box-shadow:
            inset -3px 0 0 0 #ECECEC,
            4px 0px 4px rgba(0, 0, 0, 0.25);
        }

        #main-wrapper {
            left: 0;
        }

        .chevron-button {
            position: fixed;
            top: 74px;
            left: 0px;
            width: 36px;
            height: 42px;
            border: none;
            border-radius: 0 6px 6px 0;
            z-index: 1050;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: left 0.3s ease-in-out;
            box-shadow: 0 4px 4px rgba(0, 0, 0, 0.5);
        }

        #sidebar.show .chevron-button {
            left: 100%;
            transform: translateX(-100%);
        }
    }

    @media (max-width: 360px) {
        #sidebar {
            width: 100%;
        }

        .ticket-button {
            width: calc(100% - 48px);
            margin-left: 24px;
            margin-right: 24px;
        }
    }
</style>
@include('partials.stylesheets.ticketcard')