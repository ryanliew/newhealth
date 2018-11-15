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
Route::post('/purchases/account', 'PurchaseController@accountStore');
Route::post('/account/sell', 'AccountController@sellAccount');
Route::get('/packages', 'PackageController@index');
Route::get('/packages/account', 'PackageController@getAccountPackages');
Route::get('/items', 'RedemptionController@items');
Route::get('/countries', 'CountryController@index');
Route::get('/dashboard/{user}', 'HomeController@dashboard');

Route::group(['middleware' => 'auth:api'], function() {
	
	Route::group(['prefix' => 'admin'], function() {
		Route::get('/users/pending', 'Admin\UserController@indexPending');
		Route::get("/users/parents", 'Admin\UserController@getParents');
		Route::post("/users/{user}/setting", "Admin\UserController@settings");
		Route::get('/users', 'Admin\UserController@index');
		Route::get('/tree', 'Admin\AccountController@getTree');
		Route::get('/packages', 'Admin\PackageController@index');
		Route::get('/purchases/pending', 'Admin\PurchaseController@indexPending');
		Route::get('/purchases', 'Admin\PurchaseController@index');
		Route::get('/redemptions', 'Admin\RedemptionController@index');
		Route::get('/redemptions/pending', 'Admin\RedemptionController@indexPending');
		Route::get('/transactions', 'Admin\TransactionController@index');
		Route::get('/transactions/standard', 'Admin\TransactionController@index_standard');
		Route::post('/transaction/paid', 'Admin\TransactionController@pay');
		Route::post('/post/{post}/notify/admin', 'PostController@notifyAdmin');
		Route::post('/post/{post}/update', 'PostController@update');
		Route::post('/post/{post}/notify', 'PostController@notify');
		Route::post('/posts', 'PostController@store');

		Route::group(['prefix' => '/packages'], function() {
			Route::post('/', 'Admin\PackageController@store');
			Route::post('/{package}', 'Admin\PackageController@update');
		});
	});

	Route::group(['prefix' => 'internal'], function() {
		Route::get('/user/referrer', 'HomeController@getUserByReferralCode');
		Route::get('referrer', 'HomeController@getReferrer');
		Route::get('ancestor', 'HomeController@getAncestor');
		Route::get('/auth/accounts/{userid?}', 'AccountController@getAuthAccounts');
		Route::get('/ewallet/{user}', 'RedemptionController@getEwalletAmount');
	});

	Route::group(['prefix' => 'user/{user}'], function() {
		Route::get('/company/contacts', 'UserController@showCompanyContacts');
		Route::get('/purchases', 'PurchaseController@index');
		Route::get('/redemptions', 'RedemptionController@index');
		Route::get('/transactions', 'TransactionController@index');
		Route::get('/tree', 'AccountController@getTree');
		Route::post('/update', 'UserController@update');
		Route::post('/kyc/reject', 'UserController@reject_documents');
		Route::post('/kyc/verify', 'UserController@verify_documents');
		Route::post('/kyc/remind', 'UserController@remind_documents');
		Route::post('/legal', 'UserController@update_legal');
		Route::post('/kyc', 'UserController@update_documents');
		Route::post('/lock', "UserController@update_lock");
		Route::post('/approve', "UserController@approve");
		Route::post('/reject', "UserController@reject");
		Route::post('/delete', "UserController@delete");
		Route::get("/address", "Admin\UserController@getAddress");
		Route::get("/commission", "Admin\UserController@getCommission");
		Route::get("/unpaid/commission", "Admin\UserController@getUnpaidCommission");
		Route::get('/', 'UserController@show');
	});

	Route::group(['prefix' => 'purchase'], function() {
		Route::post('/{purchase}/update', 'PurchaseController@update');
		Route::post('/account/{purchase}/update', 'PurchaseController@accountUpdate');
		Route::post('/pay/{purchase}', 'PaymentController@pay');
		// Route::post('/verify/{purchase}', 'PurchaseController@verify');
		Route::post('/delete/{purchase}', 'PurchaseController@delete');
	});

	Route::group(['prefix' => 'payment'], function() {
		Route::post('/verify/{payment}', 'PaymentController@verify');
		Route::post('/reject/{payment}', 'PaymentController@reject');
		Route::post('/update/{payment}', 'PaymentController@revise');
	});

	Route::group(['prefix' => 'redemption'], function() {
		Route::post('/store', 'RedemptionController@store');
		Route::post('/cancel/{redemption}', 'RedemptionController@cancel');
		Route::post('/approve/{redemption}', 'RedemptionController@approve');
		Route::post('/reject/{redemption}', 'RedemptionController@reject');
	});

	Route::group(['prefix' => 'posts'], function() {
		Route::get('/', 'PostController@index');
	});

	Route::post("/apply/advisor", "UserController@apply_advisor");

});

Route::middleware('auth:api')->get('/profile', 'ProfileController@index');
