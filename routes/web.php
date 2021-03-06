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


Route::view('/', 'welcome');

Route::get('/users/{name}', 'UserSearchController@index')->middleware('auth');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/teams/{team}', 'TeamController@show')->middleware('auth');
Route::post('/teams', 'TeamController@store')->middleware('auth');

Route::post('/memberships/{team}', 'MembershipsController@store')->middleware('auth');
Route::delete('/memberships/{team}', 'MembershipsController@destroy')->middleware('auth');
