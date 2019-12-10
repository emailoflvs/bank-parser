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
    Route::get('/', "Controller@index")->name('index');
    Route::get('xml_daily', "Controller@xml_daily")->name('xml_daily');
    Route::get('xml_dynamic', "Controller@xml_dynamic")->name('xml_dynamic');
    Route::get('show_table', "Controller@show_table")->name('show_table');
});


//Route::get('/home', 'HomeController@index')->name('home');
