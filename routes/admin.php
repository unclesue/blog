<?php

Route::get('login', 'Admin\LoginController@showLoginForm');
Route::post('login', 'Admin\LoginController@login')->name('admin.login');
Route::post('logout', 'Admin\LoginController@logout');

Route::get('/', 'Admin\IndexController@index');
Route::any('/menu', 'Admin\MenuController@index', ['except' => ['create']])->name('admin.menu');

Route::get('/main', 'Admin\IndexController@main')->name('main');
Route::resource('articles', 'Admin\ArticleController');
