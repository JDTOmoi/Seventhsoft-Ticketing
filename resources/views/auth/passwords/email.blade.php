@extends('layouts.form')
@section('header-title', 'Lupa Password')
@section('form-title', 'Lupa Password')

@section('form-content')
    @if (session('status'))
        <div style="color: green; font-size: 14px; margin-bottom: 16px;">
            {{session('status')}}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" id="form-form">
        @csrf

        <div class="form-group">
            <label for="email">Alamat Email</label>
            <input id="email" type="email"
                   class="form-control @error('email') is-invalid @enderror"
                   name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

            @error('email')
                <span style="color: red; font-size: 13px;">
                    {{ $message }}
                </span>
            @enderror
        </div>
        <div class="button-wrapper">
            <button type="submit" class="submit-button" onclick="duplicationPrevention(event, 'form-form')" >Kirim Link Reset Password</button>
        </div>
    </form>
@endsection

