<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 7/1/2019
 * Time: 10:02 AM
 */
use Illuminate\Support\Facades\Route;
Route::group(['prefix' => 'booking'],function (){
    Route::get('/','BookingController@index')->name('report.admin.booking');
    Route::get('/email_preview/{id}','BookingController@email_preview')->name('report.booking.email_preview');
    Route::post('/bulkEdit','BookingController@bulkEdit')->name('report.admin.booking.bulkEdit');
});
Route::get('/enquiry','EnquiryController@index')->name('report.admin.enquiry.index');

Route::post('/enquiry/bulkEdit','EnquiryController@bulkEdit')->name('report.admin.enquiry.bulkEdit');


Route::get('/statistic','StatisticController@index')->name('report.admin.statistic.index');
Route::get('/reloadChart','StatisticController@reloadChart')->name('report.admin.statistic.reloadChart');