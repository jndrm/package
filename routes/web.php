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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// php composer
Route::get('packages.json', 'ComposerController@index');

// javascript packages
Route::get('/packages/{name}/{version?}', 'PackageController@show');
Route::post('/packages/-/npm/v1/security/audits/quick', 'PackageController@auditsQuick');
Route::get('/packages/{name}/-/{tarball}', 'PackageController@tarball');
