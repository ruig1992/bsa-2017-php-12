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

Auth::routes();

Route::get('/', 'HomeController@index')->name('app.index');

Route::resource('cars', 'CarController');

Route::get('cars/rent/{id}', 'CarController@rent')->name('cars.rent');
Route::post('cars/rent', 'CarController@storeRent')->name('cars.rent.store');
Route::post('cars/return', 'CarController@returnFromRent')->name('cars.rent.return');

Route::get('/auth/{provider}', 'Auth\SocialAuthController@redirectToProvider');
Route::get('/auth/{provider}/callback', 'Auth\SocialAuthController@handleProviderCallback');
