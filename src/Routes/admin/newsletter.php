<?php

// Newsletter Files
Route::group(['as' => 'newsletter.'], function () {
    Route::post('newsletter/file/store/{newsletter?}', 'FileController@store')->name('file.store');
    Route::post('newsletter/file/delete/{file}', 'FileController@delete')->name('file.delete');
    Route::get('newsletter/file/download/{file}', 'FileController@download')->name('file.download');
    Route::get('newsletter/file/show/{file}', 'FileController@show')->name('file.show');
});

Route::resource('newsletter', NewsletterController::class);