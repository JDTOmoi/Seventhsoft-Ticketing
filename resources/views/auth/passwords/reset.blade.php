@extends('layouts.form')
@section('header-title', 'Reset Password')
@section('form-title', 'Reset Password')

@section('form-content')
<form method="POST" action="{{ route('password.update') }}" id="form-form">
    @csrf

    <input type="hidden" name="token" value="{{ $token }}">
    <input type="hidden" name="email" value="{{ request('email') }}">

    <div class="form-group">
        <label for="password">Password Baru</label>
        <div class="input-group w-100">
            <input id="password" type="password" name="password" required
                class="form-control @error('password') is-invalid @enderror">
            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password">
                <i class="fa fa-eye" id="eye-icon-password"></i>
            </button>
        </div>
        @error('password')
            <span style="color: red; font-size: 13px;">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="password-confirm">Konfirmasi Password</label>
        <input id="password-confirm" type="password" name="password_confirmation" required
            class="form-control">
    </div>
    <div class="button-wrapper">
        <button type="submit" class="submit-button" onclick="duplicationPrevention(event, 'form-form')">Reset Password</button>
    </div>
</form>
@endsection

@include('partials.password')