@extends('layouts.common')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
<link rel="stylesheet" href="{{ asset('css/contact.css') }}">
@endsection

@section('content')
<div class="register-form__content">
    <div class="register-form__heading">
        <h2>Register</h2>
    </div>

    <form class="form" method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">お名前</span>
            </div>
            <div class="name-group">
                <div class="form-input-wrapper">
                    <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" placeholder="山田" required>
                    @error('last_name') <div class="validation-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-input-wrapper">
                    <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" placeholder="太郎" required>
                    @error('first_name') <div class="validation-error">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">メールアドレス</span>
            </div>
            <div class="form-input-wrapper">
                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="test@example.com" required>
                @error('email') <div class="validation-error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">パスワード</span>
            </div>
            <div class="form-input-wrapper">
                <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="coachtech1106">
                @error('password') <div class="validation-error">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- 追加: パスワード確認 (Fortifyの必須項目) --}}
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">パスワード確認</span>
            </div>
            <div class="form-input-wrapper">
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="coachtech1106">
                @error('password_confirmation') <div class="validation-error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form__actions">
            <button type="submit" class="submit-button">登録</button>
        </div>
    </form>
</div>
@endsection