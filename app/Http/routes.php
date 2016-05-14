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

/**
 * Routing all request to the REST API
 */
Route::group(['prefix' => 'api'], function () {

    //routing the authentication methods
    Route::group(array('prefix' => 'auth'), function () {
        Route::post('register', 'TokenAuthController@register');
        Route::post('authenticate', 'TokenAuthController@authenticate');
        Route::get('authenticate/user','TokenAuthController@getAuthenticatedUser');
    });

    //Middleware/Filter to check user permissions
    Route::group(['middleware' => 'acl'], function () {
        
        //routing method to change the expected calories
        Route::group(array('prefix' => 'user/{user_id}'), function () {

            Route::put('/calories', 'TokenAuthController@updateCalories');
            Route::get('/users', 'TokenAuthController@listUsers');

            //routing all methos for Meals
            Route::get('/meals/{date_from}/{time_from}/{date_to}/{time_to}/', 'MealsController@indexByDates');//list all by user
            Route::get('/meals', 'MealsController@index');//list all by user
            Route::post('/meals', 'MealsController@store');//add a new by user
            Route::get('/meals/{meal_id}', 'MealsController@show');//get info from one
            Route::put('/meals/{meal_id}', 'MealsController@update');//update one
            Route::delete('/meals/{meal_id}', 'MealsController@destroy');//delete one
        });
        
        //routing all methos for Users
        Route::get('/user', 'UserController@index');//list all
        Route::post('/user', 'UserController@store');//add a new
        Route::get('/user/{user_id}', 'UserController@show');//get info from one
        Route::put('/user/{user_id}', 'UserController@update');//update one
        Route::delete('/user/{user_id}', 'UserController@destroy');//delete one
    });

});