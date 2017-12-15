<?php

// Gets a paginated list of newsletters
Route::get('/', 'NewsletterController@all');

// Gets a list of years that have newsletters
Route::get('/years', 'NewsletterController@years');

// Gets a list of newsletters for a particular year
Route::get('/year/{year}', 'NewsletterController@byYear');

// Gets a newsletter's PDF
Route::get('/{newsletter}/file/{file}', 'FileController@download');

// Gets a newsletter's image
Route::get('/{newsletter}/image/{file}', 'FileController@show');
