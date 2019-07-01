<?php

Route::resource('menu', 'Admin\MenuController', ['except' => ['create']]);

Route::middleware(['auth.admin'])->group(function () {
    Route::resource('menu', 'Admin\MenuController', ['except' => ['create']]);
});

Route::get('login', 'Admin\LoginController@showLoginForm');
Route::post('login', 'Admin\LoginController@login')->name('admin.login');
Route::get('logout', 'Admin\LoginController@logout');
