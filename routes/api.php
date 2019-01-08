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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});




Route::group(['prefix' => 'auth'], function($router){

	Route::post('login', 'AuthController@login');
	Route::post('logout', 'AuthController@logout');
	Route::post('refresh', 'AuthController@refresh');
	Route::post('me', 'AuthController@me');

	Route::post('register', 'AuthController@register');

});

Route::group(['middleware' => 'auth:api'], function($router){

	Route::get('customers', 'CustomersController@all');
	Route::get('customers/{id}', 'CustomersController@get');
	Route::post('customers/new', 'CustomersController@new');
	Route::post('customers/edit', 'CustomersController@edit');

});

Route::group(['middleware' => 'auth:api'], function($router){

	Route::get('manufacturer', 'ManufacturerController@all');
	Route::get('manufacturer/{id}', 'ManufacturerController@get');
	Route::post('manufacturer/new', 'ManufacturerController@new');
	Route::post('manufacturer/edit', 'ManufacturerController@edit');

});

Route::group(['middleware' => 'auth:api'], function($router){

	Route::get('model', 'ModelController@all');
	Route::get('model/{id}', 'ModelController@get');
	Route::post('model/new', 'ModelController@new');
	Route::post('model/edit', 'ModelController@edit');
	Route::post('model/remove', 'ModelController@remove');

});

Route::group(['middleware' => 'auth:api'], function($router){

	Route::get('inventories', 'ModelController@inventories');
	Route::post('uploadpics', 'ModelController@uploadpics');

});

Route::get('test', function(){

	#FILE.
	$path = config('filesystems.UPLOAD_PATH');
	$file_name = '1_15468940610419864561.jpg';
	$file_path=$path.$file_name;

	$pic_url = '';
	if( strlen($file_name) && \Storage::exists($file_path) ){
		$pic_url = \Storage::url($file_path);
	}

	echo '<img src="'.$pic_url.'">';

});
