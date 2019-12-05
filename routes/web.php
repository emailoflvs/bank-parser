<?php

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

/*
* Аутентификация и регистрация пользователей Larovel 6
* */
Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', "Controller@index");
//    Route::get('/show', "Controller@showTable");
});


Route::get('/home', 'HomeController@index')->name('home');
