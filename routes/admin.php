<?php

Route::get('login', 'Admin\LoginController@showLoginForm');
Route::post('login', 'Admin\LoginController@login')->name('admin.login');
Route::post('logout', 'Admin\LoginController@logout');

Route::get('/', 'Admin\IndexController@index');
Route::get('/menu', 'Admin\MenuController@index')->name('menu');
Route::post('/menu/save', 'Admin\MenuController@save')->name('menu.save');
Route::get('/main', 'Admin\IndexController@main')->name('main');
