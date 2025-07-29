@extends('layouts.mail')
@section('header-title', 'Verifikasi Email')
@section('form-title', 'Verifikasi Email')

@section('form-content')
    <h4>Selamat datang ke Seventhsoft Ticketing, {{$name}}</h4>

    <p>Kode OTP Anda adalah: <b>{{ $otp }}</b></p>
    <p>OTP akan hangus pada 3 menit, jadi segera masukkan OTP ke aplikasi Anda.</p>
    <br />
    <p>Dilarang memberikan OTP kepada orang lain.</p>
    <br />
    <p>Jika bukan Anda yang mendaftar melalui email tersebut, segera kontak kami untuk mengatas masalah tersebut.</p>
@endsection

@section('foot-info')
    <img src="{{asset('storage/email-logo - Copy.png')}}" style="width: 7.5vw; min-width: 64px;" alt="logo"/>
@endsection