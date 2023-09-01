<?php


// use Illuminate\Support\Facades\Route;


  Route::get('/example/up-file', [\UpFile\Http\Controllers\PageUpFileController::class, 'index']);
/*
Image CRUD
 */
Route::post('/add-image/{page}/{postId?}', 'UploadImageController@store')->name('addImg');
// Route::post('/del-image/{id}', function(){return $_REQUEST;});
Route::get('/get-image/{page}/{postId?}', 'UploadImageController@getImage')->name('getImg');

Route::post('/del-image', 'UploadImageController@delete')->name('delImg');

Route::post('/add-image-alt', 'UploadImageController@imageAlt')->name('addImgAlt');
