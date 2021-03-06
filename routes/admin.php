<?php
Route::middleware(['auth.admin'])->group(function () {
    Route::resource('menus', 'Admin\MenuController', ['except' => ['create']]);

    Route::resource('articles', 'Admin\ArticleController');
    Route::resource('users', 'Admin\UserController');
});

Route::get('login', 'Admin\LoginController@showLoginForm');
Route::post('login', 'Admin\LoginController@login')->name('admin.login');
Route::get('logout', 'Admin\LoginController@logout');
