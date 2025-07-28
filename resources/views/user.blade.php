@extends('layouts.form')

@section('form-title', 'Update Info User')

@section('form-content')
<form method="POST" action="{{ route('updateUserInfoPost') }}" id="form-form" enctype="multipart/form-data">
    @method('PUT')
    @csrf

    <div class="form-group">
        <label for="name">Nama Asli <span style="color: #E53B3B;">*</span></label>
        <input id="name" type="text" name="name" value="{{ old('name', $u->name) }}" required autofocus>
        @error('name')
            <span style="color: red; font-size: 13px;">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-attachment" id="drop-area">
        <div class="py-2">Replace Profile Picture</div>
        <div class="form-attachment-button" id="form-attachment">
            <i class="fa-solid fa-paperclip px-2"></i>Pilih File
            <input type="file" id="filebutton" name="profile_picture" accept="image/png,image/jpeg,image/webp,image/jpg" hidden>
        </div>
        <div class="attachment-condition py-2">PNG, JPG, or WEBP up to 2MB</div>
        <div id="attachment-preview"></div>
    </div>
    <div class="button-wrapper">
        <button type="submit" class="submit-button" onclick="duplicationPrevention(event, 'form-form')">Update Info</button>
    </div>
</form>

@include('partials.formpictureattachment')
@endsection