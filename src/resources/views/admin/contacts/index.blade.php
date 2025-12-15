@extends('layouts.common')

@section('title', 'Admin | FashionablyLate')
@section('styles')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
<link rel="stylesheet" href="{{ asset('css/modal.css') }}">
@endsection

@section('content')
<div class="container">
    <h2 class="page-title">Admin</h2>

    @if (session('success_message'))
    <div class="alert alert-success">
        {{ session('success_message') }}
    </div>
    @endif

    {{-- 検索フォーム (FN022) --}}
    <form method="GET" action="{{ route('admin.contacts.index') }}" class="search-form card">
        <div class="form-group-wrap">
            <div class="form-group">
                <input type="text" name="name_or_email" id="name_or_email" placeholder="名前やメールアドレスを入力してください"
                    value="{{ request('name_or_email') }}" class="input-field name-email-field">
            </div>
            <div class="form-group">
                {{-- 性別選択肢に「その他」(3)を追加 --}}
                <select name="gender" id="gender" class="input-field select-field">
                    <option value="">性別 ▼</option>
                    <option value="1" {{ request('gender') == '1' ? 'selected' : '' }}>男性</option>
                    <option value="2" {{ request('gender') == '2' ? 'selected' : '' }}>女性</option>
                    <option value="3" {{ request('gender') == '3' ? 'selected' : '' }}>その他</option>
                </select>
            </div>
            <div class="form-group">
                <select name="category" id="category" class="input-field select-field">
                    <option value="">お問い合わせの種類 ▼</option>
                    @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->content }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <input type="date" name="date" id="date" value="{{ request('date') }}"
                    class="input-field date-field" placeholder="年月日">
            </div>

            <button type="submit" class="button button-primary search-button">
                検索
            </button>
            <a href="{{ route('admin.contacts.index') }}" class="button button-secondary reset-button">
                リセット
            </a>
        </div>
    </form>

    <div class="actions-and-pagination">
        {{-- エクスポートボタン (FN024) --}}
        <a href="{{ route('admin.contacts.export', request()->query()) }}" class="button button-export">
            エクスポート
        </a>
        <div class="pagination-info">
            <span>全 {{ $contacts->total() }} 件中、{{ $contacts->firstItem() }} 〜 {{ $contacts->lastItem() }} 件</span>
            {{ $contacts->appends(request()->except('page'))->links('vendor.pagination.simple-tailwind') }}
        </div>
    </div>

    {{-- 一覧テーブル --}}
    <div class="table-container card">
        <table>
            <thead>
                <tr>
                    <th class="table-header w-1/5">お名前</th>
                    <th class="table-header w-1/12">性別</th>
                    <th class="table-header w-1/4">メールアドレス</th>
                    <th class="table-header w-1/4">お問い合わせの種類</th>
                    <th class="table-header w-1/6"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($contacts as $contact)
                <tr class="table-row">
                    <td class="table-data">{{ $contact->full_name }}</td>
                    <td class="table-data">{{ $contact->gender_text }}</td>
                    <td class="table-data email-cell">{{ $contact->email }}</td>
                    <td class="table-data category-cell">{{ $contact->category->content ?? '-' }}</td>
                    <td class="table-data detail-cell">
                        <?php
                        // FN025: JSに渡すデータをモデルのアクセサを利用して構築
                        $contactData = [
                            'id' => $contact->id,
                            'full_name' => $contact->full_name,
                            'gender' => $contact->gender_text,
                            'email' => $contact->email,
                            'tel' => $contact->tel_without_hyphen ?? '-', // FN006-4a: ハイフンなし
                            'address' => $contact->address ?? '-',
                            'building' => $contact->building ?? '-',
                            'category_content' => $contact->category->content ?? '-',
                            'detail' => $contact->detail,
                        ];
                        ?>
                        <button type="button" class="button button-detail"
                            data-contact="{{ json_encode($contactData) }}">
                            詳細
                        </button>
                    </td>
                </tr>
                @endforeach
                @if ($contacts->isEmpty())
                <tr>
                    <td colspan="5" class="table-data table-empty">お問い合わせ情報は見つかりませんでした。</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

{{-- 詳細モーダルウィンドウ (FN023, FN025) --}}
<div id="detailModal" class="modal-overlay" style="display: none;">
    <div class="modal-content-area">
        <span id="closeModal" class="close-button">&times;</span>

        <h2 class="modal-title"></h2>
        <table class="modal-detail-table">
            <tr>
                <th>氏名</th>
                <td id="modal-full-name"></td>
            </tr>
            <tr>
                <th>性別</th>
                <td id="modal-gender"></td>
            </tr>
            <tr>
                <th>メールアドレス</th>
                <td id="modal-email"></td>
            </tr>
            <tr>
                <th>電話番号</th>
                <td id="modal-tel"></td>
            </tr>
            <tr>
                <th>住所</th>
                <td id="modal-address"></td>
            </tr>
            <tr>
                <th>建物名</th>
                <td id="modal-building"></td>
            </tr>
            <tr>
                <th>お問い合わせの種類</th>
                <td id="modal-category-content"></td>
            </tr>
            <tr>
                <th>お問い合わせ内容</th>
                <td id="modal-detail" class="detail-box" colspan="2" style="white-space: pre-wrap;"></td>
            </tr>
        </table>

        {{-- 削除フォーム (FN026) --}}
        <form id="delete-form" method="POST" action="">
            @csrf
            @method('DELETE')
            <button type="submit" class="button button-danger delete-button"
                onclick="return confirm('このお問い合わせ（ID: ' + document.getElementById('modal-id').textContent + '）を削除してもよろしいですか？\nこの操作は元に戻せません。');">
                削除
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tableContainer = document.querySelector('.table-container');
        const modal = document.getElementById('detailModal');
        const deleteForm = document.getElementById('delete-form');
        const closeModal = document.getElementById('closeModal');

        if (!tableContainer || !modal) {
            console.error("エラー: HTML要素が見つかりません。");
            return;
        }

        const deleteRouteTemplate = '{{ route("admin.contacts.destroy", ["contact" => "TEMP_ID"]) }}';

        function hideModal() {
            modal.style.display = 'none';
        }

        tableContainer.addEventListener('click', function(event) {
            const button = event.target.closest('.button-detail');

            if (button) {
                event.preventDefault();

                try {
                    const contactData = JSON.parse(button.getAttribute('data-contact'));

                    // データ設定
                    document.getElementById('modal-full-name').textContent = contactData.full_name;
                    document.getElementById('modal-gender').textContent = contactData.gender;
                    document.getElementById('modal-email').textContent = contactData.email;
                    document.getElementById('modal-tel').textContent = contactData.tel;
                    document.getElementById('modal-address').textContent = contactData.address;
                    document.getElementById('modal-building').textContent = contactData.building;
                    document.getElementById('modal-category-content').textContent = contactData.category_content;
                    document.getElementById('modal-detail').textContent = contactData.detail;

                    // 削除フォームのURLを設定
                    deleteForm.action = deleteRouteTemplate.replace('TEMP_ID', contactData.id);

                    // モーダルを表示
                    modal.style.display = 'flex';
                } catch (e) {
                    console.error("JSONデータのパース中にエラーが発生しました:", e);
                }
            }
        });

        closeModal.addEventListener('click', hideModal);
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                hideModal();
            }
        });
    });
</script>
@endpush