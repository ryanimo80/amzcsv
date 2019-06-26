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


// Route::group(['namespace' => 'Auth', 'prefix' => 'auth'], function () {
//     Route::get('/login','LoginController@login');
//     Route::post('/login','LoginController@login');
//     Route::post('/register','RegisterController');
// });

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', 'Auth\LoginController@login');
// Route::post('login', function (Request $request) {
    
//     if (auth()->attempt(['username' => $request->input('username'), 'password' => $request->input('password')])) {
//         // Authentication passed...
//         $user = auth()->user();
//         $user->save();
//         return $user;
//     }
    
//     return response()->json([
//         'error' => 'Unauthenticated user',
//         'code' => 401,
//     ], 401);
// });