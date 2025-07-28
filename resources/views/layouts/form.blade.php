@extends('layouts.app')

@section('style')
<style>
    .form-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 32px;
        min-height: calc(100vh - 64px);
        box-sizing: border-box;
        font-weight: 400;
    }

    .form-title {
        font-size: 32px;
        font-weight: 400;
        margin: 32px 0;
        color: #171717;
    }

    .form-box {
        background-color: #ffffff;
        color: #171717;
        padding: 32px;
        border-radius: 8px;
        max-width: 676px;
        width: 100%;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .form-group {
        display: flex;
        flex-direction: column;
        margin-bottom: 24px;
    }

    .form-attachment {
        display: flex;
        flex-direction: column;
        align-items:center;
        border: dotted 3px gray;
        padding: 24px;
        border-radius: 8px;
    }

    .form-attachment p .attachment-condition{
        font-size: 12px;
    }

    .form-attachment-button {
        background-color: #FFFFFF;
        border-radius: 6px;
        padding: 12px 12px;
        box-shadow: inset 0 0 0 2px #D4D4D4;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .toggle-password {
        border: 1px solid #0b234a;
        background-color: transparent;
        color: #0b234a;
        transition: background-color 0.2s ease, color 0.2s ease;
    }

    .toggle-password:hover {
        background-color: #0b234a;
        color: white;
    }

    .toggle-password:hover i {
        color: white; 
    }

    .toggle-password i {
        color: #0b234a;
    }

    label {
        margin-bottom: 8px;
        font-weight: 400;
        color: #404040;
    }

    input, textarea, select, option {
        padding: 10px 12px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 6px;
    }

    .input-group {
    width: 100%;
}

    .input-group input,
    .input-group button {
        flex-shrink: 0;
    }

    .forgot-password a{
        font-size: 13px;
        margin-bottom: 24px;
        color: #0b234a;
        font-weight: 600;
        text-decoration: none;
    }
    .forgot-password a:hover{
        font-size: 13px;
        text-decoration: underline;
    }

    .button-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 24px;
    }

    .submit-button {
        padding: 12px;
        background-color: #0b234a;
        width: 35%;
        color: white;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .submit-button:hover {
        background-color: #8B0404;
        text-decoration: underline;
        text-decoration-thickness: 2px;
    }

    .submit-button:active {
        background-color: #660707;
    }

    @media (max-width: 650px) { /* When tablet is in portrait mode*/
        .submit-button {
            width: 55%;
        }
    }

    @media (max-width: 500px) { /* When phone is in portrait mode*/
        .submit-button {
            width: 80%;
        }
    }

    @media (max-width: 373px) { /* When phone is in portrait mode*/
        .submit-button {
            width: 90%;
        }
    }

    .foot-info {
        margin-top: 24px;
        text-align: center;
        font-size: 14px;
    }

    .foot-info a {
        font-weight: bold;
        color: #0b234a;
        text-decoration: none;
    }

    .foot-info a:hover {
        text-decoration: underline;
        text-decoration-thickness: 2px;
        cursor: pointer;
    }

    .inline-form-link {
        font-weight: bold;
        color: #0b234a;
        cursor: pointer;
    }
    .inline-form-link:hover {
        text-decoration: underline;
    }

</style>
@endsection

@section('content')
<div class="form-wrapper">
    <div class="form-title">
        @yield('form-title')
    </div>

    <div class="form-box">
        @yield('form-content')
    </div>

    <div class="foot-info">
        @yield('foot-info')
    </div>
</div>

@yield('script')
@endsection