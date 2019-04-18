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

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('home');
    }
    return view('welcome');
});

Auth::routes(['register' => false]);



Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', 'HomeController@index')->name('home');

    Route::get('config', 'ConfigController@index');
    Route::post('config', 'ConfigController@store');

    Route::resource('targets', 'TargetsController');
});
