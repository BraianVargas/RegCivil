<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'HomeController@index');
Auth::routes(['register' => false]);

Route::auth();
Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', 'HomeController@index')->name('home');

    // Users, Permissions and Roles
    Route::get('users/settings', 'UserController@settings')->name('users.settings');
    Route::post('users/change', 'UserController@change')->name('users.change');
    Route::resource('permissions', 'PermissionController');
    Route::resource('users', 'UserController');
    Route::resource('roles', 'RoleController');

    // Delegations
    Route::get('delegations/{id}/dates', 'DelegationController@getDates');
    Route::get('delegations/{id}/{date}/turns', 'DelegationController@getTurns');
    Route::get('delegations/{id}/{fromdate}/{todate}/turns', 'DelegationController@getTurnsAssigned');
    Route::get('delegations/{id}', 'DelegationController@show');

    // Turns
    Route::post('turns/amount', 'TurnController@getAmountTurns');
    Route::post('turns/reserve', 'TurnController@reserve');
    Route::get('turns/search', 'TurnController@search');
    Route::post('turns/cancel', 'TurnController@cancel');
    Route::get('turns', 'TurnController@generate');
    Route::post('turns', 'TurnController@create');

    // People
    Route::get('persons/{type}/{number}', 'PersonController@find');

    // Records
    Route::get('records/search', 'RecordController@search')->name('records.search');
    Route::get('records/register', 'RecordController@register')->name('records.register');
});