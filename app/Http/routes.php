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

Route::get('/', function()
{
    return redirect('/home');
});

Route::get('/home', ['as' => 'home', 'uses' => 'HomeController@home', 'middleware' => 'app.auth']);
Route::get('/home/{username}', ['as' => 'home', 'uses' => 'HomeController@home', 'middleware' => 'app.auth']);
Route::get('/check_loading/{username}', ['as' => 'check_loading', 'uses' => 'HomeController@check_loading', 'middleware' => 'app.auth']);
Route::get('/load/{username}', ['as' => 'load', 'uses' => 'HomeController@load', 'middleware' => 'app.auth']);
Route::get('/login', ['uses' => 'LoginController@login']);
Route::post('/userLogin', ['uses' => 'LoginController@userLogin']);
Route::post('/guestLogin', ['uses' => 'LoginController@guestLogin']);
Route::get('/logout', ['uses' => 'LoginController@logout']);

Route::get('/tools/flushCaches', ['uses' => 'ToolsController@flushCaches']);

Route::get('/stats/{username}', ['as' => 'stats', 'uses' => 'StatsController@home', 'middleware' => 'app.auth']);
Route::get('/collection/{username}', ['as' => 'collection', 'uses' => 'CollectionController@home', 'middleware' => 'app.auth']);
Route::get('/fiche/{username}/{gameid}', ['as' => 'collection', 'uses' => 'CollectionController@game', 'middleware' => 'app.auth']);
Route::get('/rapports/{username}', ['as' => 'rapports', 'uses' => 'RapportsController@home', 'middleware' => 'app.auth']);
Route::match(['get', 'post'], '/rapports/mensuel/{username}', ['as' => 'rapports', 'uses' => 'RapportsController@mensuel', 'middleware' => 'app.auth']);
Route::match(['get', 'post'], '/rapports/annuel/{username}', ['as' => 'rapports', 'uses' => 'RapportsController@annuel', 'middleware' => 'app.auth']);
Route::get('/rapports/vendre/{username}', ['as' => 'rapports', 'uses' => 'RapportsController@vendre', 'middleware' => 'app.auth']);
Route::get('/rapports/tobuy/{username}', ['as' => 'rapports', 'uses' => 'RapportsController@tobuy', 'middleware' => 'app.auth']);
Route::get('/rapports/home_compare_user/{username}', ['as' => 'rapports', 'uses' => 'RapportsController@home_compare_user', 'middleware' => 'app.auth']);
Route::get('/compare/loadCompare/{username}', ['as' => 'loadCompare', 'uses' => 'RapportsController@loadCompare', 'middleware' => 'app.auth']);
Route::get('/compare/check_loading/{username}', ['as' => 'check_loading', 'uses' => 'RapportsController@check_loading', 'middleware' => 'app.auth']);
Route::get('/rapport/compare/{username}', ['as' => 'loadCompare', 'uses' => 'RapportsController@compare', 'middleware' => 'app.auth']);

// Routes for getting previous pages
Route::get('ajaxPlayByMonth/{username}/{page}', ['as' => 'ajaxPlayByMonth', 'uses' => 'StatsController@ajaxPlayByMonth', 'middleware' => 'app.auth']);
Route::get('ajaxPlayByYear/{username}/{page}', ['as' => 'ajaxPlayByYear', 'uses' => 'StatsController@ajaxPlayByYear', 'middleware' => 'app.auth']);
Route::get('ajaxMostPlayedPrevious/{username}/{page}', ['as' => 'ajaxMostPlayedPrevious', 'uses' => 'StatsController@ajaxMostPlayedPrevious', 'middleware' => 'app.auth']);
Route::get('ajaxMostTypePrevious/{username}/{page}', ['as' => 'ajaxMostTypePrevious', 'uses' => 'StatsController@ajaxMostTypePrevious', 'middleware' => 'app.auth']);
Route::get('ajaxAcquisitionPrevious/{username}/{page}', ['as' => 'ajaxAcquisitionPrevious', 'uses' => 'StatsController@ajaxAcquisitionPrevious', 'middleware' => 'app.auth']);
Route::get('ajaxTableTimeSince/{type}/{username}/{page}', ['as' => 'ajaxTableTimeSince', 'uses' => 'StatsController@ajaxTableTimeSince', 'middleware' => 'app.auth']);
Route::get('ajaxTableRentable/{type}/{username}/{page}', ['as' => 'ajaxTableRentable', 'uses' => 'StatsController@ajaxTableRentable', 'middleware' => 'app.auth']);

// Routes for getting URL in ajax
Route::get('ajaxPlayByMonthGetUrl/{username}/{page}/{label}', ['as' => 'ajaxPlayByMonthGetUrl', 'uses' => 'StatsController@ajaxPlayByMonthGetUrl', 'middleware' => 'app.auth']);
Route::get('ajaxPlayByYearGetUrl/{username}/{page}/{label}', ['as' => 'ajaxPlayByYearGetUrl', 'uses' => 'StatsController@ajaxPlayByYearGetUrl', 'middleware' => 'app.auth']);
Route::get('ajaxMostPlayedGetUrl/{username}/{page}/{label}', ['as' => 'ajaxMostPlayedGetUrl', 'uses' => 'StatsController@ajaxMostPlayedGetUrl', 'middleware' => 'app.auth']);
Route::get('ajaxAcquisitionByMonthGetUrl/{username}/{page}/{label}', ['as' => 'ajaxAcquisitionByMonthGetUrl', 'uses' => 'StatsController@ajaxAcquisitionByMonthGetUrl', 'middleware' => 'app.auth']);

// Gestion d'erreur critique
Route::get('/error', function()
{
    return 'Une erreur s\'est produite. Contactez l\'administrateur du site <a href="mailto:pierreboivin85@gmail.com">ici</a>.';
});

// Module pour voir les logs
Route::get('logs', ['uses' => '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index', 'middleware' => 'app.auth']);