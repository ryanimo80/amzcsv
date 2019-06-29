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



// Route::post('login', 'AuthController@login');
Route::middleware('json.response')->group(function(){
	Route::post('register', 'AuthController@register');
	Route::post('login', 'AuthController@login');
});

// Route::middleware('json.response')->get('/logout', 'AuthController@logout')->name('logout');
Route::middleware('auth:api')->group(function () {
	Route::get('user', 'AuthController@index');
	Route::put('update', 'AuthController@updatePassword');
	Route::get('/logout', 'AuthController@logout')->name('logout');
});
