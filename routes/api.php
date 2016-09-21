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

Route::get('/fund', 'FundController@all')->middleware('api');

Route::post('/fund', 'FundController@store')->middleware('api');

Route::get('/fund/{name}', 'FundController@show')->middleware('api');

Route::get('/history/{name}', 'FundController@history')->middleware('api');

Route::get('/chart/{name}/{months}', 'FundController@chart')->middleware('api');
