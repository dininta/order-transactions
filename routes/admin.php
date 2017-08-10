<?php

Route::group(['prefix' => 'admin', 'middleware' => ['jwt.auth', 'admin.auth']], function () {
    Route::resource('orders', 'OrderController', ['only' => ['show', 'index']]);
    Route::post('orders/change-status', 'OrderController@changeStatus');
});
