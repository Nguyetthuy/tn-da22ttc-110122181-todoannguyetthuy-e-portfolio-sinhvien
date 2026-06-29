<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['prefix' => '/','namespace'=>'App\Http\Controllers'], function () {
    Route::get('/', 'loginController@index');
    Route::get('/dang-nhap', 'loginController@index');
    Route::post('/dang-nhap', 'loginController@login_submit');
    Route::get('/dang-xuat', 'loginController@logout');
    Route::get('/language/{lang}','LanguageController@index'); 
});

Route::redirect('/co-van', '/covan');

