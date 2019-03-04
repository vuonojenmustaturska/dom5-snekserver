<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::auth();

Route::get('/home', 'HomeController@index');

Route::get('games/{game}/status', 'GamesController@server_status')->where('game', '[0-9]+');
Route::get('games/{game}/stats', 'GamesController@stats')->where('game', '[0-9]+');
Route::get('lobbies/{lobby}', 'LobbiesController@show')->where('lobby', '[0-9]+');

Route::post('lobbies/{lobby}/signup', 'LobbiesController@signup')->where('lobby', '[0-9]+');
Route::delete('lobbies/{lobby}/remove/{signup}', 'LobbiesController@remove_signup')->where('lobby', '[0-9]+')->where('signup', '[0-9]+');
Route::get('assets/mods/{asset}', 'ModsController@getModAsset')->where('asset', '[\s\w.\/\-]+');

Route::group(['middleware' => 'admin'], function() {
	Route::resource('admin/users', 'UsersController');
	Route::get('admin/maps/{map}/regeneratethumbnail', 'MapsController@regeneratethumbnail')->where('map', '[0-9]+');
	Route::get('admin/mods/{mod}/parsenations', 'ModsController@parsenations')->where('map', '[0-9]+');
	Route::get('admin/mods/parsecorenations', 'ModsController@parsecorenations');
});

Route::group(['middleware' => 'auth'], function () {

	Route::get('json/lobbies/nationmods', 'LobbiesController@json_nationmods');
	Route::get('json/lobbies/nations/{nation}', 'LobbiesController@json_nations')->where('nation', '[0-9]*');
	Route::get('json/lobbies/{lobby}/nations', 'LobbiesController@json_lobbynations')->where('lobby', '[0-9]*');
	Route::get('json/lobbies/{lobby}/corenations', 'LobbiesController@json_corenations')->where('lobby', '[0-9]*');
	Route::post('games/{game}/server-createnew', 'GamesController@server_createnew')->where('game', '[0-9]+');
	Route::post('games/{game}/server-start', 'GamesController@server_start')->where('game', '[0-9]+');
	Route::post('games/{game}/server-restart', 'GamesController@server_restart')->where('game', '[0-9]+');


	Route::get('maps/upload', 'MapsController@upload');
	Route::post('maps/upload', 'MapsController@handleupload');
	Route::get('mods/upload', 'ModsController@upload');
	Route::post('mods/upload', 'ModsController@handleupload');

	Route::resource('games', 'GamesController');

	Route::resource('maps', 'MapsController');
	Route::resource('mods', 'ModsController');
	Route::get('lobbies/createfromgame/{game}', 'LobbiesController@createfromgame')->where('game', '[0-9]+');
	Route::resource('lobbies', 'LobbiesController');

});

/* Route::group(['middleware' => 'auth'], function () {
	Route::resource('games', 'GamesController', ['only' => ['index', 'show']]);
	Route::resource('mods', 'ModsController', ['only' => ['index', 'show']]);
	Route::resource('maps', 'MapsController', ['only' => ['index', 'show']]);
}); */


