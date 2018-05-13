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

Route::group(['middleware' => 'auth:api'], function() {

	Route::group(['prefix' => 'user/{user}'], function() {
		Route::get('/company/contacts', 'UserController@showCompanyContacts');
		Route::get('/', 'UserController@show');
	});	

});

Route::middleware('auth:api')->get('/profile', 'ProfileController@index');
