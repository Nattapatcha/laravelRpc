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
    return redirect(url('/login'));
});
// Route::get('/register', function () { 
//     return view('auth.register'); 
// });


//Route::auth();
Route::get('login', 'Auth\AuthController@showLoginForm');
Route::post('login', 'Auth\AuthController@login');
Route::get('logout', 'Auth\AuthController@logout');

Route::get('/register', 'Auth\AuthController@getRegister');
Route::post('/register', 'Auth\AuthController@postRegister');


Route::get('files/images/{file}', ['as' => 'files.images', 'uses' => 'FilesController@images']);
Route::get('files/download/{file}', ['as' => 'files.download', 'uses' => 'FilesController@download']);

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', 'PesticidesController@index');

    Route::get('pesticides', ['as' => 'pesticides.index', 'uses' => 'PesticidesController@index']);
    Route::get('pesticides/add', ['as' => 'pesticides.add', 'uses' => 'PesticidesController@add']);
    Route::post('pesticides', ['as' => 'pesticides.store', 'uses' => 'PesticidesController@store']);
    Route::get('pesticides/edit/{id}', ['as' => 'pesticides.edit', 'uses' => 'PesticidesController@edit']);
    Route::post('pesticides/update/{id}', ['as' => 'pesticides.update', 'uses' => 'PesticidesController@update']);
    Route::get('pesticides/delete/{id}', ['as' => 'pesticides.delete', 'uses' => 'PesticidesController@destroy']);

    Route::get('disease_insects', ['as' => 'disease_insects.index', 'uses' => 'DiseaseInsectsController@index']);
    Route::get('disease_insects/add', ['as' => 'disease_insects.add', 'uses' => 'DiseaseInsectsController@add']);
    Route::post('disease_insects', ['as' => 'disease_insects.store', 'uses' => 'DiseaseInsectsController@store']);
    Route::get('disease_insects/edit/{id}', ['as' => 'disease_insects.edit', 'uses' => 'DiseaseInsectsController@edit']);
    Route::post('disease_insects/update/{id}', ['as' => 'disease_insects.update', 'uses' => 'DiseaseInsectsController@update']);
    Route::get('disease_insects/delete/{id}', ['as' => 'disease_insects.delete', 'uses' => 'DiseaseInsectsController@destroy']);

    Route::get('issues', ['as' => 'issues.index', 'uses' => 'IssuesController@index']);
    Route::get('issues/{id}', ['as' => 'issues.show', 'uses' => 'IssuesController@show']);
    Route::get('issues/delete/{id}', ['as' => 'issues.delete', 'uses' => 'IssuesController@destroy']);
});

Route::group(['prefix' => 'api'], function () {
    Route::get('pesticides', ['as' => 'pesticides.index', 'uses' => 'PesticidesController@index']);
    Route::get('disease_insects', ['as' => 'disease_insects.index', 'uses' => 'DiseaseInsectsController@index']);
    Route::post('issues', ['as' => 'api.issues.add', 'uses' => 'IssuesController@storeMultiple']);
});
