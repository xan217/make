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

Route::post('login', 'Api\RegisterController@login');
Route::post('register', 'Api\RegisterController@register');

Route::group(['middleware' => ['cors']], function () {
    //Rutas a las que se permitirá acceso
    Route::post('login', 'Api\RegisterController@login');
    Route::post('register', 'Api\RegisterController@register');
    Route::post('user', 'Api\RegisterController@user');
    Route::post('api/login', 'Api\RegisterController@login');
    Route::post('api/register', 'Api\RegisterController@register');
});