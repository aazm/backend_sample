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

Route::get('products', 'ProductController@search');
Route::get('products/{id}', 'ProductController@show');


Route::post('registration', 'Auth\RegisterController@register');

Route::group(['prefix' => 'auth', 'middleware' => 'api'], function () {

    Route::post('login', 'Auth\LoginController@login');
    Route::put('refresh', 'Auth\LoginController@refresh');
    Route::post('logout', 'Auth\LoginController@logout');

});

Route::group(['prefix' => 'customer', 'middleware' => 'jwt.auth'], function(){
    Route::put('/', 'CustomerController@update');
    Route::get('/', 'CustomerController@show');
});

Route::group(['prefix' => 'cart', 'middleware' => 'jwt.auth'], function(){
    Route::put('/', 'ShoppingCartController@addProduct');
});

