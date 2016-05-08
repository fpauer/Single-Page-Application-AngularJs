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

App::bind('App\Repositories\Interfaces\UserRepositoryInterface', 'App\Repositories\UserRepository');
App::bind('App\Repositories\Interfaces\MealsRepositoryInterface', 'App\Repositories\MealsRepository');

Route::get('/', function () {
    return view('app');
});

Route::group(array('prefix' => 'api'), function () {

    Route::group(array('prefix' => 'auth'), function () {
        Route::post('register', 'TokenAuthController@register');
        Route::post('authenticate', 'TokenAuthController@authenticate');
        Route::get('authenticate/user','TokenAuthController@getAuthenticatedUser');
    });

    Route::resource('meals', 'MealsController');
});