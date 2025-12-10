@extends('layouts.common')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/contact.css') }}">
<link rel="stylesheet" href="{{ asset('css/confirm.css') }}">
{{-- 確認画面固有のスタイルがあればここに追加 --}}
@endsection

@section('content')

@php
function getGenderText($value) {

return match ((int)$value) {
1 => '男性',
2 => '女性',
3 => 'その他',
default => '未選択',
};
}
@endphp

<h2 class="form-subtitle">Confirm</h2>

<form action="{{ route('contact.confirm') }}" method="POST" class="contact-form">
    @csrf

    @foreach($contact as $key => $value)
    @if(!is_array($value))
    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
    @endif
    @endforeach

    <div class="confirm-table">

        <div class="confirm-row">
            <div class="confirm-label">お名前</div>
            <div class="confirm-value">{{ $contact['last_name'] }}&nbsp;{{ $contact['first_name'] }}</div>
        </div>

        <div class="confirm-row">
            <div class="confirm-label">性別</div>
            <div class="confirm-value">{{ getGenderText($contact['gender']) }}</div>
        </div>

        <div class="confirm-row">
            <div class="confirm-label">メールアドレス</div>
            <div class="confirm-value">{{ $contact['email'] }}</div>
        </div>

        <div class="confirm-row">
            <div class="confirm-label">電話番号</div>
            <div class="confirm-value">{{ str_replace(['-', 'ー', ' '], '', $contact['tel']) }}</div>
        </div>

        <div class="confirm-row">
            <div class="confirm-label">住所</div>
            <div class="confirm-value">{{ $contact['address'] }}</div>
        </div>

        <div class="confirm-row">
            <div class="confirm-label">建物名</div>
            <div class="confirm-value">{{ $contact['building'] ?? '' }}</div>
        </div>

        <div class="confirm-row">
            <div class="confirm-label">お問い合わせの種類</div>
            <div class="confirm-value">{{ $category_name ?? 'データなし' }}</div>
        </div>

        <div class="confirm-row">
            <div class="confirm-label">お問い合わせ内容</div>
            <div class="confirm-value detail-value">{!! nl2br(e($contact['detail'])) !!}</div>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" name="action" value="send" class="submit-button">送信</button>
        <button type="submit" name="back" value="1" class="back-button">修正</button>
    </div>
</form>
@endsection