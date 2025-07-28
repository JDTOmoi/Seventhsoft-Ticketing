@extends('layouts.form')

@section('form-title', 'Login')

@section('header-title', 'Login')

@section('form-content')
<form method="POST" action="{{ route('login') }}" id="form-form">
    @csrf

    <div class="form-group">
        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
        @error('email')
            <span style="color: red; font-size: 13px;">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="password">Password</label>

        <div class="input-group w-100">
            <input id="password" type="password" name="password" class="form-control" required>
            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password">
                <i class="fa fa-eye" id="eye-icon-password"></i>
            </button>
        </div>
        @error('password')
            <span style="color: red; font-size: 13px;">{{ $message }}</span>
        @enderror
    </div>

    <div class="forgot-password">
        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}">Lupa Password?</a>
        @endif
    </div>

    <div class="button-wrapper">
         <button type="submit" class="submit-button" onclick="duplicationPrevention(event, 'form-form')">Login</button>
    </div>
</form>
@endsection

@section('foot-info')
    Belum mempunyai akun account? Register <a href="{{ route('register') }}">disini</a>.
@endsection

@include('partials.password')
