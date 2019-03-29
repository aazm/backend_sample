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

Route::get('products', 'ProductController@search');
Route::get('products/{id}', 'ProductController@show');


Route::post('registration', 'Auth\RegisterController@register');

Route::group(['prefix' => 'auth', 'middleware' => 'api'], function () {

    Route::post('login', 'Auth\LoginController@login');
    Route::put('refresh', 'Auth\LoginController@refresh');
    Route::post('logout', 'Auth\LoginController@logout');

});

Route::group(['prefix' => 'customer', 'middleware' => 'auth'], function(){
    Route::post('profile', 'CustomerController@update');
    Route::get('profile', 'CustomerController@show');
});