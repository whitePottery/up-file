<?php





// use Illuminate\Support\Facades\Route;


Route::get('/example/up-file', [\UpFile\Http\Controllers\UpFileMainController::class, 'index']);
  //   Route::get('/get-image', [\UpFile\Http\Controllers\UploadImageController::class, 'test']);
/*
Image CRUD
 */
// Route::group(['middleware' => ['auth']], function () {
// Route::post('/add-image/{page}/{userId}/{postId?}', function(){return auth()->id();});
Route::post('/add-image/{name?}/{nameModel?}/{userId?}/{postId?}', [\UpFile\Http\Controllers\UploadImageController::class, 'store'])->name('addImg');//->middleware('ajax');
// Route::post('/del-image/{id}', function(){return $_REQUEST;});
Route::get('/get-image/{name}/{userId}/{postId?}', [\UpFile\Http\Controllers\UploadImageController::class, 'getImage'])->name('getImg');

Route::post('/del-image', [\UpFile\Http\Controllers\UploadImageController::class, 'delete'])->name('delImg');

Route::post('/add-image-alt', [\UpFile\Http\Controllers\UploadImageController::class, 'imageAlt'])->name('addImgAlt');

// });