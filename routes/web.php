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

Route::get('/', function () {
    return view('home');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/photographies', 'App\Http\Controllers\PhotographyController@index')->name('photographies.index');
Route::post('/photographies/create', 'App\Http\Controllers\PhotographyController@store')->name('photographies.store');
Route::get('/photographies/create', 'App\Http\Controllers\PhotographyController@create')->name('photographies.create');
// Route::post('/photographies/{photography}/update', 'App\Http\Controllers\PhotographyController@update')->name('photographies.update');
// Route::delete('/photographies/{photography}/delete', 'App\Http\Controllers\PhotographyController@destroy')->name('photographies.destroy');
