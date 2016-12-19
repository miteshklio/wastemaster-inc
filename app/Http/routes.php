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
Route::post('login', ['middleware' => 'csrf', 'uses' => 'Auth\AuthController@login']);

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

    // Users
    Route::match(['get', 'post'], 'users', 'UserController@index');
    Route::get('user', 'UserController@newUser');
    Route::post('user', 'UserController@create');
    Route::get('user/{id}', 'UserController@get');
    Route::post('user/{id}', 'UserController@update');
    Route::get('user/{id}/delete', 'UserController@delete');

    // Haulers
    Route::match(['get', 'post'], 'haulers', 'HaulerController@index')->name('haulers::home');
    Route::get('hauler', 'HaulerController@newHauler')->name('haulers::new');
    Route::post('hauler', 'HaulerController@create')->name('haulers::create');
    Route::get('hauler/{id}', 'HaulerController@show')->name('haulers::show');
    Route::post('hauler/{id}', 'HaulerController@update')->name('haulers::update');
    Route::get('hauler/{id}/delete', 'HaulerController@delete')->name('haulers::delete');
    Route::get('hauler/{id}/archive', 'HaulerController@archive')->name('haulers::archive');
    Route::get('hauler/{id}/unarchive', 'HaulerController@unarchive')->name('haulers::unarchive');

    // Leads
    Route::match(['get', 'post'], 'leads', 'LeadsController@index')->name('leads::home');
    Route::get('lead', 'LeadsController@newLead')->name('leads::new');
    Route::post('lead', 'LeadsController@create')->name('leads::create');
    Route::get('lead/{id}', 'LeadsController@show')->name('leads::show');
    Route::post('lead/{id}', 'LeadsController@update')->name('leads::update');
    Route::get('lead/{id}/delete', 'LeadsController@delete')->name('leads::delete');
    Route::get('lead/{id}/archive', 'LeadsController@archive')->name('leads::archive');
    Route::get('lead/{id}/unarchive', 'LeadsController@unarchive')->name('leads::unarchive');
});

/**
 * AJAX
 */
Route::group(['prefix' => 'ajax', 'namespace' => 'Ajax'], function()
{
    Route::get('cities/autocomplete', 'CityController@autocomplete');
});
