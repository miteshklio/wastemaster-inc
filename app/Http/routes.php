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

/**
 * Home Page
 */

Route::get('/', 'App\HomeController@index');

/**
 * Auth
 */

// Login
Route::get('login', 'Auth\AuthController@loginPage');
Route::post('login', ['middleware' => 'csrf', 'uses' => 'Auth\AuthController@login']);

// Sign up
Route::get('sign-up', 'Auth\AuthController@signUpPage');
Route::post('sign-up', ['middleware' => 'csrf', 'uses' => 'Auth\AuthController@signUp']);

// Logout
Route::get('logout', 'Auth\AuthController@logout');

/**
 * Password Reset
 */

Route::get('password/reset/{token?}', 'Auth\PasswordController@showResetForm');
Route::post('password/email', ['middleware' => 'csrf', 'uses' => 'Auth\PasswordController@sendResetLinkEmail']);
Route::post('password/reset', ['middleware' => 'csrf', 'uses' => 'Auth\PasswordController@reset']);

/**
 * Image Cropping
 */

Route::get('cropped/width/{width}/height/{height}/{img}/{position?}', 'ImageController@crop');


/**
 * Admin
 */

Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'admin'], function()
{
    // Dashboard
    Route::get('dashboard', 'DashboardController@index');
});