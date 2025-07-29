@extends('layouts.form')

@section('form-title', 'Register')

@section('header-title', 'Register')

@section('form-content')
<form method="POST" enctype="multipart/form-data" id="form-form" action="{{ route('register') }}">
    @csrf

    <div class="form-group">
        <label for="username">Username <span style="color: #E53B3B;">*</span></label>
        <input id="username" type="text" name="username" value="{{ old('username') }}" class="form-control @error('username') is-invalid @enderror" autofocus>
        @error('username')
            <span style="color: red; font-size: 13px;">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="name">Nama Asli <span style="color: #E53B3B;">*</span></label>
        <input id="name" type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror">
    </div>

    <div class="form-group">
        <label for="phone_number">Nomor Telepon <span style="color: #E53B3B;">*</span></label>
        <input id="phone_number" type="text" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror" value="{{ old('phone_number') }}">
        @error('phone_number')
            <span style="color: red; font-size: 13px;">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="email">Email <span style="color: #E53B3B;">*</span></label>
        <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}">
        @error('email')
            <span style="color: red; font-size: 13px;">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="password">Password <span style="color: #E53B3B;">*</span></label>

        <div class="input-group w-100">
            <input id="password" type="password" name="password" class="form-control" class="form-control @error('password') is-invalid @enderror">
            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password">
                <i class="fa fa-eye" id="eye-icon-password"></i>
            </button>
        </div>
        @error('password')
            <span style="color: red; font-size: 13px;">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="password_confirmation">Konfirmasi Password <span style="color: #E53B3B;">*</span></label>
        <input id="password_confirmation" type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror">
        @error('password_confirmation')
            <span style="color: red; font-size: 13px;">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-attachment" id="drop-area">
        <div class="py-2">Upload Profile Picture (optional)</div>
        <div class="form-attachment-button" id="form-attachment">
            <i class="fa-solid fa-paperclip px-2"></i>Pilih File
            <input type="file" id="filebutton" name="profile_picture" accept="image/png,image/jpeg,image/webp,image/jpg" hidden>
        </div>
        <div class="attachment-condition py-2">PNG, JPG, or WEBP, besaran sampai 2MB</div>
        <div id="attachment-preview"></div>
    </div>

    <div class="button-wrapper">
        <button type="submit" onclick="duplicationPrevention(event, 'form-form')" class="submit-button">Register User</button>
    </div>
</form>
@endsection

@section('foot-info')
    Sudah punya akun? Login <a href="{{ route('login') }}">disini</a>.
@endsection

@include('partials.password')
@include('partials.formpictureattachment')