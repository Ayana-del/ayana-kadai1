@extends('layouts.common')

@section('title', 'Admin | FashionablyLate')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">

@endsection

@section('content')
<div class="container">
    <h2 class="page-title">Admin</h2>

    @if (session('success_message'))
    <div class="alert alert-success">
        {{ session('success_message') }}
    </div>
    @endif

    <form method="GET" action="{{ route('admin.contacts.index') }}" class="search-form card">
        <div class="form-group-wrap">
            <div class="form-group">
                <input type="text" name="name_or_email" id="name_or_email" placeholder="名前やメールアドレスを入力してください"
                    value="{{ request('name_or_email') }}" class="input-field name-email-field">
            </div>
            <div class="form-group">
                <select name="gender" id="gender" class="input-field select-field">
                    <option value="">性別 ▼</option>
                    <option value="1" {{ request('gender') == '1' ? 'selected' : '' }}>男性</option>
                    <option value="2" {{ request('gender') == '2' ? 'selected' : '' }}>女性</option>
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
        <button class="button button-export">
            エクスポート
        </button>
        <div class="pagination-info">
            <span>全 {{ $contacts->total() }} 件中、{{ $contacts->firstItem() }} 〜 {{ $contacts->lastItem() }} 件</span>
            {{ $contacts->appends(request()->except('page'))->links('vendor.pagination.simple-tailwind') }}
        </div>
    </div>

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
                    <td class="table-data">{{ $contact->last_name . ' ' . $contact->first_name }}</td>
                    <td class="table-data">
                        @if(isset($contact->gender))
                        @if($contact->gender == 1) 男性 @elseif($contact->gender == 2) 女性 @else - @endif
                        @else - @endif
                    </td>
                    <td class="table-data email-cell">{{ $contact->email }}</td>
                    <td class="table-data category-cell">{{ $contact->category->content ?? '-' }}</td>
                    <td class="table-data detail-cell">
                        <button type="button" class="button button-detail"
                            data-contact='@json([
                            ' id'=> $contact->id,
                            'full_name' => $contact->last_name . ' ' . $contact->first_name,
                            'gender' => ($contact->gender == 1) ? '男性' : (($contact->gender == 2) ? '女性' : '不明'),
                            'email' => $contact->email,
                            'tel' => $contact->tel,
                            'address' => $contact->address,
                            'building' => $contact->building ?? '-',
                            'category_content' => $contact->category->content ?? 'カテゴリなし',
                            'detail' => $contact->detail,
                            'created_at' => $contact->created_at->format('Y/m/d H:i')
                            ])'>
                            詳細
                        </button>
                    </td>
                </tr>
                @endforeach

                @if ($contacts->isEmpty())
                <tr>
                    <td colspan="5" class="table-data table-empty">
                        お問い合わせ情報は見つかりませんでした。
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('detailModal');
        const closeModal = document.getElementById('closeModal');
        const detailButtons = document.querySelectorAll('.button-detail');
        const deleteForm = document.getElementById('delete-form');

        const deleteRouteTemplate = '{{ route("admin.contacts.destroy", ["contact" => "TEMP_ID"]) }}';

        function hideModal() {
            modal.style.display = 'none';
        }

        detailButtons.forEach(button => {
            button.addEventListener('click', function() {

                const contactData = JSON.parse(this.getAttribute('data-contact'));

                document.getElementById('modal-id').textContent = contactData.id;
                document.getElementById('modal-full-name').textContent = contactData.full_name;

                document.getElementById('modal-gender').textContent = contactData.gender;
                document.getElementById('modal-email').textContent = contactData.email;
                document.getElementById('modal-tel').textContent = contactData.tel;
                document.getElementById('modal-address').textContent = contactData.address;
                document.getElementById('modal-building').textContent = contactData.building;
                document.getElementById('modal-category-content').textContent = contactData.category_content;

                document.getElementById('modal-detail').textContent = contactData.detail;
                deleteForm.action = deleteRouteTemplate.replace('TEMP_ID', contactData.id);

                modal.style.display = 'flex';
            });
        });

        closeModal.addEventListener('click', hideModal);
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                hideModal();
            }
        });
    });
</script>
@endsection