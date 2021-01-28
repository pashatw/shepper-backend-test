<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/user', 'API\UserController@detail')->name('api.user.detail');

    // location
    Route::get('/locations', 'API\LocationController@detail')->name('api.location.detail');
    Route::post('/locations', 'API\LocationController@create')->name('api.location.create');
    Route::put('/locations/{location}', 'API\LocationController@update')->name('api.location.update');
    Route::delete('/locations/{location}', 'API\LocationController@delete')->name('api.location.delete');
});
