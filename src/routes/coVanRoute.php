<?php

use Illuminate\Support\Facades\Route; 

Route::group(['prefix' => 'covan', 'namespace' => 'App\Http\Controllers\CoVan', 'middleware' => ['isCoVan']], function () {
    
    // Trang chủ Cố vấn (Hiển thị danh sách lớp đang phụ trách cố vấn)
    Route::get('/', 'CoVanHomeController@index')->name('covan.home');

});


