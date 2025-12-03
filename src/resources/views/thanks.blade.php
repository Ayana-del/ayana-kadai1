@extends('layouts.common')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/thanks.css') }}">
@endsection

@section('content')

<div class="thanks-container">
    <p class="thanks-message">お問い合わせありがとうございました</p>

    <a href="/" class="home-button">HOME</a>
</div>

@endsection