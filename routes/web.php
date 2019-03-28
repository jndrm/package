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

// ruby gems
// Route::get('gems', 'GemController@index');
// Route::get('gems/{name}', 'GemController@show');
// Route::get('gems/gems/{name}', 'GemController@show');
// Route::get('gems/quick/{name}/{version}', 'GemController@quick');
// Route::get('gems/versions', 'GemController@version');
Route::get('gems/{any}', 'GemController@handle')->where('any', '.*');

// java gradle
Route::get('gradles2/{package}', 'GradleController@show')->where('package', '.*');
Route::get('gradles2/{package}-metadata.xml', 'GradleController@metadata')->where('package', '.*');

