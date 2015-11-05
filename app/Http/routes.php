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


Route::get('/', 'HomeController@home');
Route::post('/userLogin', ['uses' => 'HomeController@userLogin']);
Route::post('/guestLogin', ['uses' => 'HomeController@guestLogin']);
Route::get('/logout', ['uses' => 'HomeController@logout']);

Route::get('/tools/flushCaches', ['uses' => 'ToolsController@flushCaches']);

Route::get('/stats/{username}', ['as' => 'stats', 'uses' => 'StatsController@home']);
Route::get('/collection/{username}', ['as' => 'collection', 'uses' => 'CollectionController@home']);

// Routes for getting previous pages
Route::get('ajaxPlayByMonthPrevious/{username}/{page}', ['as' => 'ajaxPlayByMonthPrevious', 'uses' => 'StatsController@ajaxPlayByMonthPrevious']);
Route::get('ajaxMostPlayedPrevious/{username}/{page}', ['as' => 'ajaxMostPlayedPrevious', 'uses' => 'StatsController@ajaxMostPlayedPrevious']);
Route::get('ajaxAcquisitionPrevious/{username}/{page}', ['as' => 'ajaxAcquisitionPrevious', 'uses' => 'StatsController@ajaxAcquisitionPrevious']);

// Routes for getting URL in ajax
Route::get('ajaxPlayByMonthGetUrl/{username}/{page}/{label}', ['as' => 'ajaxPlayByMonthGetUrl', 'uses' => 'StatsController@ajaxPlayByMonthGetUrl']);
Route::get('ajaxMostPlayedGetUrl/{username}/{page}/{label}', ['as' => 'ajaxMostPlayedGetUrl', 'uses' => 'StatsController@ajaxMostPlayedGetUrl']);
Route::get('ajaxAcquisitionByMonthGetUrl/{username}/{page}/{label}', ['as' => 'ajaxAcquisitionByMonthGetUrl', 'uses' => 'StatsController@ajaxAcquisitionByMonthGetUrl']);