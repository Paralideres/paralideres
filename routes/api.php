<?php

use Illuminate\Http\Request;

/**
 * API
 */

//User Actions
Route::group(['prefix' => 'account', 'namespace' => 'Api\V1'], function() {

    // Get logged user
    Route::get('/', 'UserController@currentUser');

    // All this is handled via web
    // Authentication route
//     Route::post('login', 'AuthenticateController@authenticate');

    // Clear cookie route
//     Route::post('logout', 'AuthenticateController@logout');

    // Password reset link request routes...
    // Route::post('password/email', 'Auth\PasswordController@postEmail');

    // Password reset routes...
    // Route::post('password/reset', 'Auth\PasswordController@postReset');
});


// // Route to create a new role
// Route::post('role', 'Api\RolesController@createRole');
//
// // Route to create a new permission
// Route::post('permission', 'Api\RolesController@createPermission');
//
// // Route to assign role to user
// Route::post('assign-role', 'Api\RolesController@assignRole');
//
// // Route to attache permission to a role
// Route::post('attach-permission', 'Api\RolesController@attachPermission');
//

//User Actions
Route::group(['prefix' => 'users', 'namespace' => 'Api\V1'], function() {

    // List Users
    Route::get('/', 'UserController@index');

    // Create User
    Route::post('/', 'UserController@store');

    // User Methods
    Route::group(['prefix' => '{id}'], function() {

        // Show User
        Route::get('/', 'UserController@show');

        // Update User
        Route::put('/', 'UserController@update');

        // Delete User
        Route::delete('/', 'UserController@delete');

        // Get User Profile
        Route::get('profile', 'UserController@getProfile');

        // Update user password
//        Route::post('password/reset', 'Auth\PasswordController@postReset');

        // Update User Profile
        Route::put('profile', 'UserController@updateProfile');

        // Update User Profile Image
        Route::post('profile/image', 'UserController@updateImage');

        // Delete User Profile Image
        Route::delete('profile/image', 'UserController@deleteImage');
    });
});

// Resources
Route::group(['prefix' => 'resources', 'namespace' => 'Api\V1'], function() {

    // List Resource
    Route::get('/', 'ResourceController@index');

    // Create Resource
    Route::post('/', 'ResourceController@store');

    // Search Resource
    Route::get('/search', 'ResourceController@search');

    Route::group(['prefix' => '{id}'], function() {

        // Show Resource
        Route::get('/', 'ResourceController@show');

        // Update Resource
        Route::put('/', 'ResourceController@update');

        // Delete Resource
        Route::delete('/', 'ResourceController@delete');

        // Attach a file
        Route::post('attach', 'ResourceController@upload');

        // Like/Unlike
        Route::post('like', 'ResourceController@like');

        // Add the resource to a collection
        Route::put('addToCollection', 'ResourceController@addToCollection');

        // Resource tags
        Route::resource('tags', 'ResourceTagController');

    });
});

// Categories
Route::group(['prefix' => 'categories', 'namespace' => 'Api\V1'], function() {
    Route::get('/{slug}/resources', 'CategoryController@resources');
    Route::resource('/', 'CategoryController');
});


// Collections
Route::resource('collections', 'Api\V1\CollectionController');

// Tags
Route::resource('tags', 'Api\V1\TagController@index');
Route::post('tags/create', 'Api\V1\ExtraController@create');
Route::post('user/register', 'Api\V1\ExtraController@signup');

// Polls
Route::group(['prefix' => 'polls', 'namespace' => 'Api\V1'], function() {
    Route::post('/{id}/vote', 'PollController@vote');
    Route::get('/{id}/result', 'PollController@result');
    Route::get('/last', 'PollController@last');

    Route::resource('/', 'PollController');
});

