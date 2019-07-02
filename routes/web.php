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
use App\Http\Controllers\Auth\LoginController;
use League\Flysystem\Filesystem;
use Spatie\Dropbox\Client;
use Spatie\FlysystemDropbox\DropboxAdapter;

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

	// $url = "https://rsz.tzy.li/816/918/tzy/previews/images/001/203/652/095/original/ichbingerneerzieher.jpg";
	// $contents = file_get_contents($url);
	// $name = substr($url, strrpos($url, '/') + 1);
	// Storage::put($name, $contents);

    // $authorizationToken = env('DROPBOX_TOKEN');
    // $client = new Client($authorizationToken);
    // $adapter = new DropboxAdapter($client);
    // $filesystem = new Filesystem($adapter);
    // $result = $client->upload('/Artwork/test.png', Storage::get('84 copy.png'));
    // $result = $client->createSharedLinkWithSettings($result['path_display']);
    // print_r($result);
    // $client->download('test.png');

    echo '<img width=100 src="https://www.dropbox.com/s/uhykkvr5cv9l7m8/test.png?dl=1"/>';
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
Route::get('/amz/listing', 'CSVController@listingCSV')->name('listing');
Route::post('/amz/listing', 'CSVController@listingCSV');
Route::get('/amz/clearqueue/','CSVController@clearqueue');
Route::post('/amz/savecsvrow/', 'CSVController@saveCSVRow');
Route::get('/amz/edit/{id}', 'CSVController@edit');
Route::post('/amz/edit/{id}', 'CSVController@edit');

/**
 * Brand manager
 */
Route::get('/amz/brandmanager', 'BrandManagerController@index')->name('brand_manager_index');
Route::post('/amz/brandmanager', 'BrandManagerController@store');
Route::get('/amz/brandmanager/{id}', 'BrandManagerController@edit');
Route::post('/amz/brandmanager/{id}/update', 'BrandManagerController@update');

/**
 * Upload PNG
 */
Route::get('/amz/fix', 'AmazonTShirtController@fix_db_error');
Route::post('/amz/uploads/', 'AmazonTShirtController@uploads');
Route::get('/amz/png/{path}/{name}', function($path, $name){
    $img = Image::make(public_path().'\\files\\'.$path.'\\'.$name)->resize(216, 180);
    return $img->response('png');
});
Route::get('/amz/gen-mockup/', 'AmazonTShirtController@genMockup');
Route::resource('amz', 'AmazonTShirtController');

/**
 * Customcat
 */
Route::get('/customcat/', 'CustomCatController@index');
Route::post('/customcat/', 'CustomCatController@index');
Route::get('/customcat/{id}', 'CustomCatController@index');
Route::post('/customcat/{id}', 'CustomCatController@index');

/**
 * Teezily scanner
 */
Route::get('/teezily/scan/', 'TeezilyController@scan');
Route::post('/teezily/scan/', 'TeezilyController@scan');
Route::post('/teezily/ajax_scan/', 'TeezilyController@ajax_scan');

/**
 * Authen
 */
Route::get('/home', 'Auth\LoginController@login')->name('login');
Route::post('/home', 'Auth\LoginController@login')->name('login');
Route::get('/logoutx', 'Auth\LoginController@logout');
