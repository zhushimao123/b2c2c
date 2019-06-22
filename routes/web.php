<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/','index\IndexController@index');
//注册
Route::get('/register','user\RegController@reg');
//手机号发送短信
Route::post('/TelCode','user\RegController@code');
//注册执行
Route::post('/RegDo','user\RegController@regdo');
//登陆
Route::get('/login','user\LoginController@login');
//登陆执行
Route::post('/LoginDO','user\LoginController@LoginDO');

//微信登陆
Route::get('/wxlogin','user\LoginController@WxLogin');
//获取code
Route::get('/code','user\LoginController@getcode');

