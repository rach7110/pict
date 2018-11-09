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
Route::get('/recognition/create/full', 'ImageRecognitionController@createFullAnalysis')->name('recognition.full');
Route::get('/recognition/create/concept', 'ImageRecognitionController@createConceptAnalysis')->name('recognition.concept');
Route::post('/recognition', 'ImageRecognitionController@analyze')->name('recognition.analyze');
// Route::post('/recognition', ['as' => 'recognition', 'uses' => 'ImageRecognitionController@store']);
