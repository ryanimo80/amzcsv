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

/**
 * Keyword Controller
 */
Route::get('/amz/keyword/','KeywordController@show');
Route::post('/amz/keyword/','KeywordController@show');
Route::post('/amz/keyword/{id}','KeywordController@show');
Route::get('/amz/keyword/{id}','KeywordController@show');
Route::get('/amz/keyword/delete/{id}','KeywordController@keyword_delete');
Route::get('/amz/keyword/json/{id}','KeywordController@kwjson');

/**
 * Profile Mockup Controller
 */
Route::get('/amz/profile/', 'ProfileMockupController@show');
Route::post('/amz/profile/', 'ProfileMockupController@show');
Route::get('/amz/profile/{id}', 'ProfileMockupController@show');
Route::post('/amz/profile/{id}', 'ProfileMockupController@show');
Route::get('/amz/profile/delete/{id}', 'ProfileMockupController@profile_delete');

/**
 * CSV Controller
 */
Route::get('/amz/export', 'CSVController@exportCSV');
Route::post('/amz/export', 'CSVController@exportCSV');
Route::get('/amz/clearqueue/','CSVController@clearqueue');
Route::post('/amz/savecsvrow/', 'CSVController@saveCSVRow');
Route::get('/amz/edit/{id}', 'CSVController@edit');
Route::post('/amz/edit/{id}', 'CSVController@edit');


Route::get('/amz/fix', 'AmazonTShirtController@fix_db_error');
Route::post('/amz/uploads/', 'AmazonTShirtController@uploads');
Route::get('/amz/png/{path}/{name}', function($path, $name){
    $img = Image::make(public_path().'\\files\\'.$path.'\\'.$name)->resize(216, 180);
    return $img->response('png');
});
Route::get('/amz/gen-mockup/', 'AmazonTShirtController@genMockup');
Route::resource('amz', 'AmazonTShirtController');

// Route::get('/fulfillment/', 'CustomCatController@index');

Route::get('/teezily/scan/', 'TeezilyController@scan');
Route::post('/teezily/scan/', 'TeezilyController@scan');
Route::post('/teezily/ajax_scan/', 'TeezilyController@ajax_scan');