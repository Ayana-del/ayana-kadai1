@extends('layouts.common')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
<link rel="stylesheet" href="{{ asset('css/contact.css') }}">
@endsection

@section('content')
<div class="auth-card">
    <h2 class="form-subtitle">Login</h2>

    <form method="POST" action="{{ route('login') }}" class="contact-form">
        @csrf

        <div class="form-group">
            <label for="email" class="form-label">メールアドレス</label>
            <div class="form-input-wrapper">
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="test@exanple.com">
            </div>
        </div>
        @error('email')
        <div class="validation-error">{{ $message }}</div>
        @enderror

        <div class="form-group">
            <label for="password" class="form-label">パスワード</label>
            <div class="form-input-wrapper">
                <input id="password" type="password" name="password" required autocomplete="current-password"placeholder="coachtech1106">
            </div>
        </div>
        @error('password')
        <div class="validation-error">{{ $message }}</div>
        @enderror

        <div class="form-actions">
            <button type="submit" class="submit-button">
                ログイン
            </button>
        </div>
    </form>
</div>
@endsection