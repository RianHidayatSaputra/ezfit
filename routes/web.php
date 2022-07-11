<?php

use crocodicstudio\crudbooster\helpers\CB;
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



CB()->routeController('api-custom','CustomApiController');

CB()->routeController('/','FrontController');
Route::get('privacy-policy','FrontController@getPrivacy');