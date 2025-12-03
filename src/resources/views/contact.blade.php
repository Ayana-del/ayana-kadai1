@extends('layouts.common')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/contact.css') }}">
@endsection

@section('content')

<h2 class="form-subtitle">Contact</h2>

<form action="{{ route('contact.confirm') }}" method="POST" class="contact-form">
    @csrf
    <div class="form-group required">
        <label class="form-label">お名前</label>
        <div class="name-group">
            <div class="form-input-half">
                <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" placeholder="山田" required>
                <span class="input-example">姓</span>
            </div>
            <div class="form-input-half">
                <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" placeholder="太郎" required>
                <span class="input-example">名</span>
            </div>
        </div>
    </div>

    <div class="form-group required">
        <label class="form-label">性別</label>
        <div class="form-input-wrapper gender-radios">
            <label>
                <input type="radio" name="gender" value="1" {{ old('gender') == '1' ? 'checked' : '' }} required>
                男性
            </label>
            <label>
                <input type="radio" name="gender" value="2" {{ old('gender') == '2' ? 'checked' : '' }}>
                女性
            </label>
            <label>
                <input type="radio" name="gender" value="3" {{ old('gender') == '3' ? 'checked' : '' }}>
                その他
            </label>
        </div>
    </div>

    <div class="form-group required">
        <label for="email" class="form-label">メールアドレス</label>
        <div class="form-input-wrapper">
            <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="test@example.com" required>
        </div>
    </div>

    <div class="form-group required">
        <label for="tel" class="form-label">電話番号</label>
        <div class="form-input-wrapper">
            <input type="tel" id="tel" name="tel" value="{{ old('tel') }}" placeholder="09012345678" required>
        </div>
    </div>

    <div class="form-group required">
        <label for="address" class="form-label">住所</label>
        <div class="form-input-wrapper">
            <input type="text" id="address" name="address" value="{{ old('address') }}" placeholder="東京都渋谷区元宮町1-2-3" required>
        </div>
    </div>

    <div class="form-group">
        <label for="building" class="form-label">建物名</label>
        <div class="form-input-wrapper">
            <input type="text" id="building" name="building" value="{{ old('building') }}" placeholder="〇〇マンション101号室">
        </div>
    </div>

    <div class="form-group required">
        <label for="category_id" class="form-label">お問い合わせの種類</label>
        <div class="form-input-wrapper">
            <select id="category_id" name="category_id" required>
                <option value="" selected disabled>選択してください</option>
                @isset($categories)
                @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->content }}
                </option>
                @endforeach
                @endisset
            </select>
        </div>
    </div>

    <div class="form-group required">
        <label for="detail" class="form-label">お問い合わせ内容</label>
        <div class="form-input-wrapper">
            <textarea id="detail" name="detail" rows="6" required>{{ old('detail') }}</textarea>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="submit-button">確認画面</button>
    </div>
</form>
@endsection