<?php

use App\Notifications\RegisterSuccess;
use App\User;

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
	Route::get('/register/success', 'HomeController@thankyou')->name('register.success');
	Route::post('/register/success', 'ProfileController@update')->name('register.success');
	Route::get('/register/complete', 'ProfileController@show')->name('register.complete');
});


Route::group(['prefix' => 'internal'], function() {
	Route::get('referrer', 'HomeController@getReferrer');
});

Route::get('/phpinfo', function() {
	phpinfo();
});
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/locale', 'LocaleController@switch')->name('locale');
Route::get('/social/{service}/login', 'Auth\SocialAuthController@login');
Route::get('/register/cancel', 'HomeController@cancel')->name('register.cancel');

Route::group(['prefix' => 'exports'], function() {
	Route::get('/users', 'ExportController@users');
	Route::get('/transactions', 'ExportController@transactions');
	Route::get('/purchases', 'ExportController@purchases');
	Route::get('/payouts', 'ExportController@payouts');
});

Route::get('testpdf', function() {
	return view('pdf.transactions');
});

Route::get('/testmail', function() {
	App::setLocale('en');
	// foreach( User::all() as $user )
	// {
	// 	if(!is_null($user->identification))
	// 	{
	// 		$user->notify(new RegisterSuccess($user, 'en'));
	// 	}
	// }
	auth()->user()->notify(new RegisterSuccess(auth()->user(), 'zh'));
});
Route::get('/preview', function(){
	return view('presentation');
});