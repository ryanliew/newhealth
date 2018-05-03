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
    return redirect(route('register'));
});



Route::group(['middleware' => 'locale'], function() {
	Auth::routes();
	Route::get('/register/success', 'HomeController@thankyou');
});

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/locale', 'LocaleController@switch')->name('locale');
Route::get('/social/{service}/login', 'Auth\SocialAuthController@login');