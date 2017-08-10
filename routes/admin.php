<?php

Route::group(['middleware' => ['jwt.auth', 'admin.auth']], function () {
    Route::resource('orders', 'OrderController', ['only' => ['show', 'index']]);
});
