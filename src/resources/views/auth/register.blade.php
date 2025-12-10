@extends('layouts.common')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
<link rel="stylesheet" href="{{ asset('css/contact.css') }}">
@endsection

@section('content')
<div class="auth-container">
    <h2 class="form-subtitle">Register</h2>
    <div class="auth-card">
        <form class="form contact-form" method="POST" action="{{ route('register') }}">
            @csrf

            {{-- ★ 修正箇所 1: お名前フィールドをシンプルな form-group に変更 ★ --}}
            <div class="form-group">
                <label for="name" class="form-label">お名前</label>
                <div class="form-input-wrapper">
                    <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="山田 太郎" required autofocus>
                    @error('name')
                    <div class="validation-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- ★ 修正箇所 2: メールアドレスフィールドを変更 ★ --}}
            <div class="form-group">
                <label for="email" class="form-label">メールアドレス</label>
                <div class="form-input-wrapper">
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="test@example.com" required>
                    @error('email')
                    <div class="validation-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- ★ 修正箇所 3: パスワードフィールドを変更 ★ --}}
            <div class="form-group">
                <label for="password" class="form-label">パスワード</label>
                <div class="form-input-wrapper">
                    <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="coachtech1106">
                    @error('password')
                    <div class="validation-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- ★ 修正箇所 4: パスワード確認フィールドを変更 ★ --}}
            <div class="form-group">
                <label for="password_confirmation" class="form-label">パスワード確認</label>
                <div class="form-input-wrapper">
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="coachtech1106">
                    @error('password_confirmation') <div class="validation-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="submit-button">登録</button>
            </div>
        </form>
    </div>
</div>
@endsection