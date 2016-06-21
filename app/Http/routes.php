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

Route::group(['middleware' => 'guest'], function () {
	Route::get('login', [
		'as' => 'login-page',
		'uses' => 'UserController@login',
	]);

	Route::post('login', [
		'as' => 'login',
		'uses' => 'UserController@doLogin',
	]);

	Route::get('register', [
		'as' => 'register-page',
		'uses' => 'UserController@register',
	]);

	Route::post('register', [
		'as' => 'register',
		'uses' => 'UserController@doRegister',
	]);
});

Route::group(['middleware' => 'auth'], function () {
	Route::get('/', [
		'as' => 'dashboard',
		'uses' => 'Controller@dashboard',
	]);

	Route::get('tasks', [
		'as' => 'tasks',
		'uses' => 'TaskController@index',
	]);

	Route::get('logout', [
		'as' => 'logout',
		'uses' => 'UserController@logout'
	]);

	Route::group(['prefix' => 'api'], function () {
		Route::get('tasks', [
			'as' => 'api.tasks',
			'uses' => 'Api\TaskController@index',
		]);

		Route::post('tasks', [
			'as' => 'api.tasks.save',
			'uses' => 'Api\TaskController@store',
		]);
		
		Route::put('tasks/{id}', [
			'as' => 'api.tasks.update',
			'uses' => 'Api\TaskController@update',
		]);
		
		Route::patch('tasks/{id}', [
			'as' => 'api.tasks.mark.as.completed',
			'uses' => 'Api\TaskController@markAsCompleted',
		]);
		
		Route::patch('tasks/uncompleted/{id}', [
			'as' => 'api.tasks.mark.as.uncompleted',
			'uses' => 'Api\TaskController@markAsUncompleted',
		]);

		Route::delete('tasks/{id}', [
			'as' => 'api.tasks.destroy',
			'uses' => 'Api\TaskController@destroy',
		]);
	});
});