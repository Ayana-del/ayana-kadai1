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
            </div>
            <div class="form-input-half">
                <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" placeholder="太郎" required>
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

    <div class="form-row">
        <div class="form-label">電話番号</div>
        <div class="form-input-split">
            <input type="text" name="tel1" value="{{ old('tel1') }}" maxlength="5" placeholder="090" required>
            <span>-</span>
            <input type="text" name="tel2" value="{{ old('tel2') }}" maxlength="5" placeholder="1234" required>
            <span>-</span>
            <input type="text" name="tel3" value="{{ old('tel3') }}" maxlength="5" placeholder="5678" required>
        </div>

        @error('tel1') <div class="validation-error">{{ $message }}</div> @enderror
        @error('tel2') <div class="validation-error">{{ $message }}</div> @enderror
        @error('tel3') <div class="validation-error">{{ $message }}</div> @enderror
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
<div id="detailModal" class="modal">
    <div class="modal-content">
        <button class="modal-close" id="closeModal">&times;</button>
        <h3 class="modal-title">お問い合わせ詳細</h3>

        <div class="modal-detail-group">
            <p class="modal-detail-label">受付日時</p>
            <p class="modal-detail-value" id="modal-created-at"></p>
        </div>
        {{-- (中略：他の詳細データ表示要素) --}}
        <div class="modal-detail-group">
            <p class="modal-detail-label">お名前</p>
            <p class="modal-detail-value" id="modal-full-name"></p>
        </div>
        <div class="modal-detail-group">
            <p class="modal-detail-label">お問い合わせ内容</p>
            <p class="modal-detail-value" id="modal-detail"></p>
        </div>
        {{-- (全てのお問い合わせ項目をid付きで配置します) --}}
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('detailModal');
        const closeModal = document.getElementById('closeModal');
        const detailButtons = document.querySelectorAll('.button-detail');

        // モーダルを閉じる関数
        function hideModal() {
            modal.style.display = 'none';
        }

        detailButtons.forEach(button => {
            button.addEventListener('click', function() {
                // JSONデータを取り出しパース
                const contactData = JSON.parse(this.getAttribute('data-contact'));

                // モーダル内の要素にデータをセット
                document.getElementById('modal-id').textContent = contactData.id;
                document.getElementById('modal-full-name').textContent = contactData.full_name;
                // ... 他の要素にもデータをセット
                document.getElementById('modal-detail').textContent = contactData.detail;

                // モーダルを表示
                modal.style.display = 'flex';
            });
        });

        // 閉じるボタンと背景クリックで閉じる処理
        closeModal.addEventListener('click', hideModal);
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                hideModal();
            }
        });
    });
</script>
@endsection