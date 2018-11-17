<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::prefix('/')->namespace('Web')->group(function(){

    // show the page page
    Route::get('/', 'HomeController@index');
    Route::get('/account/activation/{token}', 'HomeController@activation');

    // resource list
    Route::get('/resources', 'ResourceController@index');
    Route::get('/resources/{slug}/download', 'ResourceController@download');

    // show resource create form
    Route::get('/resources/create', 'ResourceController@showCreate')->middleware('auth');

    // show single resource
    Route::get('/resources/{slug}', 'ResourceController@show');


});

Route::prefix('/')->namespace('Auth')->group(function(){

    // show web login form
    Route::get('login', 'LoginController@showLoginForm')->name('login');

    // show web registration form
    Route::get('register', 'LoginController@showRegistrationForm');
    Route::get('register-ok', 'LoginController@showRegistrationSuccess');

    //Password Reset Routes...
    Route::get('password-reset', 'ForgotPasswordController@showLinkRequestForm');
    Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail');
    Route::get('password/reset/{token?}', 'ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/reset', 'ResetPasswordController@reset');
});

Route::prefix('/')->namespace('Api\V1')->group(function(){

    // login from web by using api
    Route::post('login', 'AuthenticateController@authenticate');

    // logout from web by using api
    Route::post('logout', 'AuthenticateController@logout');
});

