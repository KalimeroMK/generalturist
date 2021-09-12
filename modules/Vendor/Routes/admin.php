<?php

    use Illuminate\Support\Facades\Route;

    Route::group(['prefix' => 'payout'], function () {
        Route::get('/', 'PayoutController@index')->name('vendor.admin.payout.index');
        Route::post('/bulkEdit', 'PayoutController@bulkEdit')->name('vendor.admin.payout.bulkEdit');
    });
