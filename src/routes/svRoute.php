<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'sinh-vien', 'namespace' => 'App\Http\Controllers\SinhVien', 'middleware' => ['isSinhVien']], function () {
    
    // Trang chủ Sinh viên (Thông tin cá nhân & Chương trình đào tạo)
    Route::get('/', 'SVHomeController@index');

    // Tương tác với AI
    Route::post('/gemini/generate', 'SVGeminiController@generate');
    
});
