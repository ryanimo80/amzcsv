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

Route::get('/', function () {
    return redirect()->action('AmazonTShirtController@index');
});


Route::resource('my_user', 'UserController');

// usage inside a laravel route
Route::get('/test', function()
{
    // $img = Image::make(Storage::get('\\avatars\\pPFsblbK07Mn0CdOom3606H0eoJZMLTfX1cCvQWA.jpeg'));
    // $watermark = Image::make(public_path().'\\files\\80 copy.png')->resize(400, 400);
    // $img->insert($watermark, 'top-left', 440, 350);

	$url = "https://rsz.tzy.li/816/918/tzy/previews/images/001/203/652/095/original/ichbingerneerzieher.jpg";
	$contents = file_get_contents($url);
	$name = substr($url, strrpos($url, '/') + 1);
	Storage::put($name, $contents);
});

Route::get('/amz/fix', 'AmazonTShirtController@fix_db_error');

Route::get('/amz/keyword/','AmazonTShirtController@keyword');
Route::post('/amz/keyword/','AmazonTShirtController@keyword');
Route::post('/amz/keyword/{id}','AmazonTShirtController@keyword');
Route::get('/amz/keyword/{id}','AmazonTShirtController@keyword');
Route::get('/amz/keyword/delete/{id}','AmazonTShirtController@keyword_delete');
Route::get('/amz/keyword/json/{id}','AmazonTShirtController@kwjson');

Route::get('/amz/profile/', 'AmazonTShirtController@profile');
Route::post('/amz/profile/', 'AmazonTShirtController@profile');
Route::get('/amz/profile/{id}', 'AmazonTShirtController@profile');
Route::post('/amz/profile/{id}', 'AmazonTShirtController@profile');
Route::get('/amz/profile/delete/{id}', 'AmazonTShirtController@profile_delete');

Route::get('/amz/export', 'AmazonTShirtController@exportCSV');
Route::post('/amz/export', 'AmazonTShirtController@exportCSV');


Route::post('/amz/uploads/', 'AmazonTShirtController@uploads');
Route::get('/amz/png/{path}/{name}', function($path, $name){
    $img = Image::make(public_path().'\\files\\'.$path.'\\'.$name)->resize(216, 180);
    return $img->response('png');
});

Route::get('/amz/clearqueue/','AmazonTShirtController@clearqueue');
Route::post('/amz/savecsvrow/', 'AmazonTShirtController@saveCSVRow');
Route::get('/amz/gen-mockup/', 'AmazonTShirtController@genMockup');

Route::get('/amz/edit/{id}', 'AmazonTShirtController@edit');
Route::post('/amz/edit/{id}', 'AmazonTShirtController@edit');

Route::resource('amz', 'AmazonTShirtController');

Route::get('/fulfillment/', 'CustomCatController@index');

Route::get('/teezily/scan/', 'TeezilyController@scan');
Route::post('/teezily/scan/', 'TeezilyController@scan');
Route::post('/teezily/ajax_scan/', 'TeezilyController@ajax_scan');