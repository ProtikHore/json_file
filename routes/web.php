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

Route::get('/', 'FileController@index')->name('file.index');
Route::get('get/image', 'FileController@getImage')->name('file.getImage');
Route::post('image/upload', 'FileController@upload')->name('file.upload');
Route::get('image/file/remove/{id}', 'FileController@remove')->name('file.remove');
