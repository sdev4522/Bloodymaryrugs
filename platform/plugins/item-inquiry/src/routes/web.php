<?php

use Botble\Base\Facades\BaseHelper;
use Botble\ItemInquiry\Http\Controllers\InquiryController;
use Illuminate\Support\Facades\Route;

$locale = BaseHelper::getAdminPrefix();
Route::group(['prefix' => $locale, 'middleware' => ['web', 'auth', 'verified']], function () {
    Route::resource('item-inquiries', InquiryController::class)->names([
        'index'   => 'item-inquiry.index',
        'create'  => 'item-inquiry.create',
        'store'   => 'item-inquiry.store',
        'view'    => 'item-inquiry.view',
        'update'  => 'item-inquiry.update',
        'destroy' => 'item-inquiry.destroy',
        'edit'    => 'item-inquiry.edit',

    ]);
    Route::get('detail/{id}', [InquiryController::class, 'detail'])->name('item-inquiry.detail');
});
