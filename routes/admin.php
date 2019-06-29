<?php

Route::get('login', 'Admin\LoginController@showLoginForm');
Route::post('login', 'Admin\LoginController@login')->name('admin.login');
Route::post('logout', 'Admin\LoginController@logout');

Route::get('/', 'Admin\IndexController@index');
Route::get('/menu', 'Admin\MenuController@index')->name('admin.menu');
Route::post('/menu', 'Admin\MenuController@save')->name('admin.menu');

Route::get('/main', 'Admin\IndexController@main')->name('main');
Route::resource('articles', 'Admin\ArticleController');
