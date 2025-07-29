@extends('layouts.form')
@section('header-title', 'Verifikasi Email')

@section('form-title', 'Verifikasi Email')

@section('form-content')

@if (session('resent'))
    <div style="margin-bottom: 16px; font-size: 14px; color: green;">
        {{ __('Kode OTP telah dikirimkan lagi ke email Anda.') }}
    </div>
@endif

<p>
    Kode OTP enam angka tersebut telah dikirimkan ke akun email Anda yang bernama <strong>{{ Auth::user()->email }}</strong>.
</p>

<p>
    Jika email belum masuk, klik <span onclick="duplicationPrevention(event, 'resend-form')" class="inline-form-link">disini</span> untuk mengirim kembali link verifikasi ke email tersebut.
</p>

<form id="resend-form" method="POST" action="{{ route('verification.resend') }}" style="display: none;">
    @csrf
</form>

<form method="POST" id="form-form" action="{{ route('verification.verify') }}">
    @csrf
    <div class="form-group">
        <label for="otp">Kode OTP</label>
        <input id="otp" type="number" name="otp" class="form-control @error('otp') is-invalid @enderror" style="width: 25%"  autofocus>
        @error('otp')
                <span style="color: red; font-size: 13px;">{{ $message }}</span>
        @enderror
    </div>

    <div class="button-wrapper">
        <button type="submit" class="submit-button" onclick="duplicationPrevention(event, 'form-form')">Verifikasi Email</button>
    </div>
</form>
@endsection

