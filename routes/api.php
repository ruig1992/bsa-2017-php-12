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

Route::get('user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::middleware('auth')->group(function () {

    Route::get('cars', 'Api\CarController@index');
    Route::get('cars/{id}', 'Api\CarController@show');

    Route::post('cars/{id}/rent', 'Api\CarRentalController@rentCar');
    Route::post('cars/{id}/return', 'Api\CarRentalController@returnCar');

    Route::resource('admin/cars', 'Api\Admin\AdminCarController', [
        'except' => ['create', 'edit']
    ]);
});
