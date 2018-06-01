<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/purchases', 'PurchaseController@store');
Route::get('/packages', 'PackageController@index');



Route::group(['middleware' => 'auth:api'], function() {
	
	Route::group(['prefix' => 'admin'], function() {
		Route::get('/users', 'Admin\UserController@index');
		Route::get('/tree', 'Admin\UserController@getTree');
		Route::get('/packages', 'Admin\PackageController@index');
		Route::get('/purchases', 'Admin\PurchaseController@index');
		Route::get('/transactions', 'Admin\TransactionController@index');
		Route::get('/transactions/standard', 'Admin\TransactionController@index_standard');

		Route::group(['prefix' => '/packages'], function() {
			Route::post('/', 'Admin\PackageController@store');
			Route::post('/{package}', 'Admin\PackageController@update');
		});
	});

	Route::group(['prefix' => 'user/{user}'], function() {
		Route::get('/company/contacts', 'UserController@showCompanyContacts');
		Route::get('/purchases', 'PurchaseController@index');
		Route::get('/transactions', 'TransactionController@index');
		Route::get('/tree', 'UserController@getTree');
		Route::get('/', 'UserController@show');
	});

	Route::group(['prefix' => 'purchase'], function() {
		Route::post('/pay/{purchase}', 'PaymentController@pay');
		Route::post('/verify/{purchase}', 'PurchaseController@verify');
	});

	Route::group(['prefix' => 'payment'], function() {
		Route::post('/verify/{payment}', 'PaymentController@verify');
	});

});

Route::middleware('auth:api')->get('/profile', 'ProfileController@index');
