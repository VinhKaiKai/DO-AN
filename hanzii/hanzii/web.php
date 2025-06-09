Route::get('/dang-ky-quan-tam', 'App\Http\Controllers\Admin\DangKyQuanTamController@index')->name('dang-ky-quan-tam.index');
Route::put('/dang-ky-quan-tam/{id}', 'App\Http\Controllers\Admin\DangKyQuanTamController@update')->name('dang-ky-quan-tam.update');
Route::get('/thanh-toan', 'App\Http\Controllers\Admin\ThanhToanController@index')->name('admin.thanh-toan.index');
Route::get('/luong', 'App\Http\Controllers\Admin\LuongController@index')->name('admin.luong.index'); 