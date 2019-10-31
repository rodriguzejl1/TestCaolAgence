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


Route::get('/', 'SinglePageController@index');

Route::resource('/getConsultores', 'CaolController');
Route::post('/getRelatorio', 'CaolController@getRelatorio');
Route::post('/getPizza', 'CaolController@getPizza');
Route::post('/getGrafico', 'CaolController@getGrafico');
