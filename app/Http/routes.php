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

// Not connected
Route::get('/login', ['uses' => 'LoginController@login']);
Route::post('/userLogin', ['uses' => 'LoginController@userLogin']);
Route::post('/guestLogin', ['uses' => 'LoginController@guestLogin']);
Route::get('/logout', ['uses' => 'LoginController@logout']);

// Administration
Route::group(['middleware' => ['app.auth', 'app.isAdmin']], function()
{
    Route::get('/admin/', ['uses' => 'AdminController@home']);
    Route::get('/admin/tools/flushCaches', ['uses' => 'ToolsController@flushCaches']);
    Route::get('/admin/tools/flushPersistentCaches', ['uses' => 'ToolsController@flushPersistentCaches']);
    Route::get('/admin/logs', ['uses' => '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index']);
    Route::resource('/admin/users', 'UserController');
});

// Connected or guest user
Route::group(['middleware' => ['app.auth']], function()
{
    Route::get('/home', ['as' => 'home', 'uses' => 'HomeController@home']);
    Route::get('/home/{username}', ['as' => 'home', 'uses' => 'HomeController@home']);
    Route::get('/check_loading/{username}', ['as' => 'check_loading', 'uses' => 'HomeController@check_loading']);
    Route::get('/load/{username}', ['as' => 'load', 'uses' => 'HomeController@load']);

    Route::get('/stats/{username}', ['as' => 'stats', 'uses' => 'StatsController@home']);
    Route::get('/collection/{username}', ['as' => 'collection', 'uses' => 'CollectionController@home']);
    Route::get('/resume/{username}', ['as' => 'resume', 'uses' => 'SummaryController@home']);
    Route::get('/fiche/{username}/{gameid}', ['as' => 'collection', 'uses' => 'CollectionController@game']);
    Route::get('/rapports/{username}', ['as' => 'rapports', 'uses' => 'RapportsController@home']);
    Route::match(['get', 'post'], '/rapports/mensuel/{username}', ['as' => 'rapports', 'uses' => 'RapportsController@mensuel']);
    Route::match(['get', 'post'], '/rapports/annuel/{username}', ['as' => 'rapports', 'uses' => 'RapportsController@annuel']);
    Route::get('/rapports/vendre/{username}', ['as' => 'rapports', 'uses' => 'RapportsController@vendre']);
    Route::get('/rapports/tobuy/{username}', ['as' => 'rapports', 'uses' => 'RapportsController@tobuy']);
    Route::get('/rapports/home_compare_user/{username}', ['as' => 'rapports', 'uses' => 'RapportsController@home_compare_user']);
    Route::get('/compare/loadCompare/{username}', ['as' => 'loadCompare', 'uses' => 'RapportsController@loadCompare']);
    Route::get('/compare/check_loading/{username}', ['as' => 'check_loading', 'uses' => 'RapportsController@check_loading']);
    Route::get('/rapport/compare/{username}', ['as' => 'loadCompare', 'uses' => 'RapportsController@compare']);

    // Routes for getting previous pages
    Route::get('ajaxPlayByMonth/{username}/{page}', ['as' => 'ajaxPlayByMonth', 'uses' => 'StatsController@ajaxPlayByMonth']);
    Route::get('ajaxPlayByYear/{username}/{page}', ['as' => 'ajaxPlayByYear', 'uses' => 'StatsController@ajaxPlayByYear']);
    Route::get('ajaxMostPlayedPrevious/{username}/{page}', ['as' => 'ajaxMostPlayedPrevious', 'uses' => 'StatsController@ajaxMostPlayedPrevious']);
    Route::get('ajaxMostTypePrevious/{username}/{page}', ['as' => 'ajaxMostTypePrevious', 'uses' => 'StatsController@ajaxMostTypePrevious']);
    Route::get('ajaxAcquisitionPrevious/{username}/{page}', ['as' => 'ajaxAcquisitionPrevious', 'uses' => 'StatsController@ajaxAcquisitionPrevious']);
    Route::get('ajaxTableTimeSince/{type}/{username}/{page}', ['as' => 'ajaxTableTimeSince', 'uses' => 'StatsController@ajaxTableTimeSince']);
    Route::get('ajaxTableRentable/{type}/{username}/{page}', ['as' => 'ajaxTableRentable', 'uses' => 'StatsController@ajaxTableRentable']);
    Route::get('ajaxTableLastPlay/{username}/{page}', ['as' => 'ajaxTableLastPlay', 'uses' => 'SummaryController@ajaxTableLastPlay']);
    Route::get('ajaxTableLastAcquisition/{username}/{page}', ['as' => 'ajaxTableLastAcquisition', 'uses' => 'SummaryController@ajaxTableLastAcquisition']);

    // Routes for getting URL in ajax
    Route::get('ajaxPlayByMonthGetUrl/{username}/{page}/{label}', ['as' => 'ajaxPlayByMonthGetUrl', 'uses' => 'StatsController@ajaxPlayByMonthGetUrl']);
    Route::get('ajaxPlayByYearGetUrl/{username}/{page}/{label}', ['as' => 'ajaxPlayByYearGetUrl', 'uses' => 'StatsController@ajaxPlayByYearGetUrl']);
    Route::get('ajaxMostPlayedGetUrl/{username}/{page}/{label}', ['as' => 'ajaxMostPlayedGetUrl', 'uses' => 'StatsController@ajaxMostPlayedGetUrl']);
    Route::get('ajaxAcquisitionByMonthGetUrl/{username}/{page}/{label}', ['as' => 'ajaxAcquisitionByMonthGetUrl', 'uses' => 'StatsController@ajaxAcquisitionByMonthGetUrl']);

});

// Connected
Route::group(['middleware' => ['app.auth']], function()
{
    Route::get('/modules', ['uses' => 'ModulesListsController@home']);

    Route::group(['namespace' => 'Modules'], function() {
        Route::resource('/modules/lists', 'ListsController');
    });
});

// Gestion d'erreur critique
Route::get('/error', function()
{
    return 'Une erreur s\'est produite. Contactez l\'administrateur du site <a href="mailto:pierreboivin85@gmail.com">ici</a>.';
});
