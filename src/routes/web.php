<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AdminContactController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- ユーザー向けお問い合わせフォーム ---
Route::get('/', [ContactController::class, 'index'])->name('contact.index');
Route::match(['get', 'post'], '/confirm', [ContactController::class, 'confirmOrSend'])->name('contact.confirm');
Route::get('/thanks', [ContactController::class, 'thanks'])->name('contact.thanks');


// --- 管理者向け認証エリア ---
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // /admin/ にアクセスした際のデフォルトリダイレクト
    Route::redirect('/', '/admin/contacts')->name('index');

    // お問い合わせ管理機能
    Route::prefix('contacts')->name('contacts.')->group(function () {

        // 一覧表示と検索 (GET /admin/contacts)
        Route::get('/', [AdminContactController::class, 'index'])->name('index');

        // 検索リセット (GET /admin/contacts/reset)
        Route::get('/reset', [AdminContactController::class, 'reset'])->name('reset');

        // エクスポート機能 (GET /admin/contacts/export)
        Route::get('/export', [AdminContactController::class, 'export'])->name('export');

        // 詳細データの取得 (GET /admin/contacts/{contact})
        Route::get('/{contact}', [AdminContactController::class, 'show'])->name('show');

        // 削除機能 (DELETE /admin/contacts/{contact})
        Route::delete('/{contact}', [AdminContactController::class, 'destroy'])->name('destroy');
    });

    // ログアウト機能（fortifyを使用）
});
