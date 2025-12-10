<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AdminContactController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/


Route::get('/', [ContactController::class, 'index'])->name('contact.index');
Route::match(['get', 'post'], '/confirm', [ContactController::class, 'confirmOrSend'])->name('contact.confirm');
Route::get('/thanks', [ContactController::class, 'thanks'])->name('contact.thanks');


Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::redirect('/', '/admin/contacts')->name('admin.index');
    Route::get('/contacts/reset', [AdminContactController::class, 'reset'])->name('admin.contacts.reset');
    Route::get('/contacts', [AdminContactController::class, 'index'])->name('admin.contacts.index');
    Route::get('/export', [AdminContactController::class, 'export'])->name('admin.export');
    Route::get('/contacts/{contact}', [AdminContactController::class, 'show'])->name('admin.contacts.show');
    Route::delete('/contacts/{contact}', [AdminContactController::class, 'destroy'])->name('admin.contacts.destroy');
});
