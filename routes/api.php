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

Route::group(['namespace' => 'Api'], function () {
    Route::post('login', [ 'uses' => 'UserController@login']);

    Route::group(['middleware' => 'jwt.auth'], function () {
        Route::post('logout', 'UserController@logout');

        /* Order routes for customer */
        Route::resource('orders', 'OrderController', ['only' => ['show', 'index', 'store']]);
    	Route::post('orders/submit-proof', 'OrderController@submitProof');
    	Route::post('shippings/status', 'ShippingController@checkStatus');
    });
});
