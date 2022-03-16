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


Route::post('/login', 'AccountController@login')->name('AccountLogin');

//Route::get('/test/{id}', 'ChallengeController@delete');

Route::group(['middleware' => 'auth.jwt'], function () {
    Route::get('logout', 'AccountController@logout');
    Route::get('me', 'AccountController@me');
    Route::put('me', 'AccountController@changeInfo');
    Route::put('change_password', 'AccountController@changePassword');
    Route::get('message', 'AccountController@getMessage');
    Route::put('change_info/{id}', 'AccountController@changeInfoUser');
    Route::get('avatar', 'AccountController@getAvatar');
    Route::post('change_avatar', 'AccountController@changeAvt');
    Route::get('exercises', 'ExerciseController@get');
    Route::get('submit_exercise/{id}', 'ExerciseController@submitted');
    Route::post('submit_exercise/{id}', 'ExerciseController@submit');
    Route::get('challenges', 'ChallengeController@get');
    Route::post('submit_challenge/{id}', 'ChallengeController@submit');
});

Route::group(['middleware' => 'admin'], function() {
    Route::get('all', 'AccountController@getAll');
    Route::post('send_message/{id}', 'AccountController@sendMessage');
});


Route::group(['middleware' => 'teacher'], function() {
    Route::get('students', 'AccountController@getStudents');
    //Route::put('change_info/{id}', 'AccountController@changeInfoStudent');
    Route::post('create_exercise', 'ExerciseController@create');
    Route::get('submit_student_exercise/{id}', 'ExerciseController@getSubmit');
    Route::delete('exercise/{id}', 'ExerciseController@delete');
    //Route::get('challenges', 'ChallengeController@get');
    Route::post('create_challenge', 'ChallengeController@create');
    Route::delete('challenge/{id}', 'ChallengeController@delete');
});
