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
    return view('welcome');
});

// Image recognition
Route::get('/recognition/create', ['as' => 'recognition', 'uses' => 'ImageRecognitionController@create']);
Route::post('/recognition', ['as' => 'recognition', 'uses' => 'ImageRecognitionController@analyze']);
// Route::post('/recognition', ['as' => 'recognition', 'uses' => 'ImageRecognitionController@store']);
