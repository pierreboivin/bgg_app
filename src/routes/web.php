<?php

use Illuminate\Support\Facades\Route;

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
Route::get('/login', ['uses' => 'App\Http\Controllers\LoginController@login']);
Route::post('/userLogin', ['uses' => 'App\Http\Controllers\LoginController@userLogin']);
Route::post('/guestLogin', ['uses' => 'App\Http\Controllers\LoginController@guestLogin']);
Route::get('/logout', ['uses' => 'App\Http\Controllers\LoginController@logout']);

Route::get('/status', ['uses' => 'App\Http\Controllers\StatusController@dashboard']);

// Administration
Route::group(['middleware' => ['app.auth', 'app.isAdmin']], function()
{
    Route::get('/admin/', ['uses' => 'App\Http\Controllers\AdminController@home']);
    Route::get('/admin/tools/flushCaches', ['uses' => 'App\Http\Controllers\ToolsController@flushCaches']);
    Route::get('/admin/tools/flushPersistentCaches', ['uses' => 'App\Http\Controllers\ToolsController@flushPersistentCaches']);
    Route::get('/admin/logs', ['uses' => '\Rap2hpoutre\LaravelLogViewer\App\Http\Controllers\LogViewerController@index']);
    Route::resource('/admin/users', 'App\Http\Controllers\UserController');
});

// Connected or guest user
Route::group(['middleware' => ['app.auth']], function()
{
    Route::get('/home', ['as' => 'default_home', 'uses' => 'App\Http\Controllers\HomeController@home']);
    Route::get('/home/{username}', ['as' => 'home', 'uses' => 'App\Http\Controllers\HomeController@home']);
    Route::get('/check_loading/{username}', ['as' => 'check_loading', 'uses' => 'App\Http\Controllers\HomeController@check_loading']);
    Route::get('/load/{username}', ['as' => 'load', 'uses' => 'App\Http\Controllers\HomeController@load']);

    Route::get('/stats/{username}', ['as' => 'stats', 'uses' => 'App\Http\Controllers\StatsController@home']);
    Route::get('/collection/{username}/{filter?}/{sorting?}/{typeDisplay?}', ['as' => 'collection', 'uses' => 'App\Http\Controllers\CollectionController@home']);
    Route::get('/resume/{username}', ['as' => 'resume', 'uses' => 'App\Http\Controllers\SummaryController@home']);
    Route::get('/fiche/{username}/{gameid}', ['as' => 'collectiongame', 'uses' => 'App\Http\Controllers\CollectionController@game']);
    Route::get('/rapports/{username}', ['as' => 'rapports', 'uses' => 'App\Http\Controllers\RapportsController@home']);
    Route::match(['get', 'post'], '/rapports/mensuel/{username}', ['as' => 'rapports_mensuel', 'uses' => 'App\Http\Controllers\RapportsController@mensuel']);
    Route::match(['get', 'post'], '/rapports/annuel/{username}', ['as' => 'rapports_annuel', 'uses' => 'App\Http\Controllers\RapportsController@annuel']);
    Route::get('/rapports/vendre/{username}', ['as' => 'rapports_vendre', 'uses' => 'App\Http\Controllers\RapportsController@vendre']);
    Route::get('/rapports/tobuy/{username}', ['as' => 'rapports_tobuy', 'uses' => 'App\Http\Controllers\RapportsController@tobuy']);
    Route::get('/rapports/home_compare_user/{username}', ['as' => 'rapports_compare', 'uses' => 'App\Http\Controllers\RapportsController@home_compare_user']);
    Route::get('/compare/loadCompare/{username}', ['as' => 'compareLoadCompare', 'uses' => 'App\Http\Controllers\RapportsController@loadCompare']);
    Route::get('/compare/check_loading/{username}', ['as' => 'compare_check_loading', 'uses' => 'App\Http\Controllers\RapportsController@check_loading']);
    Route::get('/rapport/compare/{username}', ['as' => 'rapportCompareLoadCompare', 'uses' => 'App\Http\Controllers\RapportsController@compare']);

    // Routes for getting previous pages
    Route::get('ajaxPlayByMonth/{username}/{page}', ['as' => 'ajaxPlayByMonth', 'uses' => 'App\Http\Controllers\StatsController@ajaxPlayByMonth']);
    Route::get('ajaxPlayByYear/{username}/{page}', ['as' => 'ajaxPlayByYear', 'uses' => 'App\Http\Controllers\StatsController@ajaxPlayByYear']);
    Route::get('ajaxMostPlayedPrevious/{username}/{page}', ['as' => 'ajaxMostPlayedPrevious', 'uses' => 'App\Http\Controllers\StatsController@ajaxMostPlayedPrevious']);
    Route::get('ajaxMostTypePrevious/{username}/{page}', ['as' => 'ajaxMostTypePrevious', 'uses' => 'App\Http\Controllers\StatsController@ajaxMostTypePrevious']);
    Route::get('ajaxAcquisitionPrevious/{username}/{page}', ['as' => 'ajaxAcquisitionPrevious', 'uses' => 'App\Http\Controllers\StatsController@ajaxAcquisitionPrevious']);
    Route::get('ajaxTableTimeSince/{type}/{username}/{page}', ['as' => 'ajaxTableTimeSince', 'uses' => 'App\Http\Controllers\StatsController@ajaxTableTimeSince']);
    Route::get('ajaxTableRentable/{type}/{username}/{page}', ['as' => 'ajaxTableRentable', 'uses' => 'App\Http\Controllers\StatsController@ajaxTableRentable']);
    Route::get('ajaxTableLastPlay/{username}/{page}', ['as' => 'ajaxTableLastPlay', 'uses' => 'App\Http\Controllers\SummaryController@ajaxTableLastPlay']);
    Route::get('ajaxTableLastAcquisition/{username}/{page}', ['as' => 'ajaxTableLastAcquisition', 'uses' => 'App\Http\Controllers\SummaryController@ajaxTableLastAcquisition']);

    // Routes for getting URL in ajax
    Route::get('ajaxPlayByMonthGetUrl/{username}/{page}/{label}', ['as' => 'ajaxPlayByMonthGetUrl', 'uses' => 'App\Http\Controllers\StatsController@ajaxPlayByMonthGetUrl']);
    Route::get('ajaxPlayByYearGetUrl/{username}/{page}/{label}', ['as' => 'ajaxPlayByYearGetUrl', 'uses' => 'App\Http\Controllers\StatsController@ajaxPlayByYearGetUrl']);
    Route::get('ajaxMostPlayedGetUrl/{username}/{page}/{label}', ['as' => 'ajaxMostPlayedGetUrl', 'uses' => 'App\Http\Controllers\StatsController@ajaxMostPlayedGetUrl']);
    Route::get('ajaxAcquisitionByMonthGetUrl/{username}/{page}/{label}', ['as' => 'ajaxAcquisitionByMonthGetUrl', 'uses' => 'App\Http\Controllers\StatsController@ajaxAcquisitionByMonthGetUrl']);

});

// Connected any user
Route::group(['middleware' => ['app.auth']], function()
{
    Route::get('/modules', ['uses' => 'App\Http\Controllers\ModulesController@home']);

    Route::group(['namespace' => 'Modules'], function() {
        Route::resource('/modules/lists/admin', 'App\Http\Controllers\Modules\AdminListsController');
    });
});

// Modules public
Route::group(['namespace' => 'Modules', 'middleware' => ['app.public']], function() {
    Route::get('/lists', ['uses' => '\App\Http\Controllers\Modules\ViewListsController@index']);
    Route::get('/lists/{slug}/{filter?}/{sorting?}/{typeDisplay?}', ['as' => 'modules.lists.view.show', 'uses' => '\App\Http\Controllers\Modules\ViewListsController@show']);
});


// Gestion d'erreur critique
Route::get('/error', function()
{
    return 'Une erreur s\'est produite. Contactez l\'administrateur du site <a href="mailto:pierreboivin85@gmail.com">ici</a>.';
});
