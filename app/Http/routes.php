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

Route::get('users', [
    'as'   => 'users.all',
    'uses' => 'UsersController@get',
]);

Route::post('/users', [
    'as'   => 'users.create',
    'uses' => 'UsersController@post',
]);

Route::put('/users/{users}', [
    'as'   => 'users.update',
    'uses' => 'UsersController@put',
]);

Route::get('courses', [
    'as'   => 'courses.all',
    'uses' => 'CoursesController@get',
]);

Route::post('courses', [
    'as'   => 'courses.create',
    'uses' => 'CoursesController@post',
]);

Route::post('courses/{courses}/register', [
    'as'   => 'courses.registerUser',
    'uses' => 'CoursesController@registerUser',
]);

Route::delete('courses/{courses}/register', [
    'as'   => 'courses.removeUser',
    'uses' => 'CoursesController@removeUser',
]);