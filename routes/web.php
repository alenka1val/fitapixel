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

Route::get('/', 'App\Http\Controllers\HomeController@index')->name('home');
Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home');
Route::get('/judges', 'App\Http\Controllers\HomeController@indexJury')->name('info.judges');
Route::get('/competition', 'App\Http\Controllers\EventController@index')->name('info.competition');

Route::get('/gallery', function () {
    return view('info.gallery');
})->name('info.gallery');

Route::get('/ldap', 'App\Http\Controllers\LDAPController@index')->name('ldap');

Route::get('/rules', function () {
    return view('photography.rules');
})->name('rules');
Route::get('/faq', function () {
    return view('photography.faq');
})->name('faq');
Route::get('/gdpr', function () {
    return view('photography.gdpr');
})->name('gdpr');
Route::get('/author', function () {
    return view('photography.author');
})->name('author');
Route::get('/links', function () {
    return view('photography.links');
})->name('links');
Route::get('/rss', function () {
    return view('photography.rss');
})->name('rss');

Auth::routes();

Route::get('/photographies', 'App\Http\Controllers\PhotographyController@index')->name('photographies.index');
Route::get('/results', 'App\Http\Controllers\PhotographyController@results')->name('results');

Route::group(['middleware' => ['auth', 'adminOrPhotographer']], function () {
    Route::get('/photographies/create', 'App\Http\Controllers\PhotographyController@create')->name('photographies.create');
    Route::post('/photographies/create', 'App\Http\Controllers\PhotographyController@store')->name('photographies.store');
});

Route::group(['middleware' => ['auth', 'adminOrJury']], function () {});

// Route::post('/photographies/{photography}/update', 'App\Http\Controllers\PhotographyController@update')->name('photographies.update');
// Route::delete('/photographies/{photography}/delete', 'App\Http\Controllers\PhotographyController@destroy')->name('photographies.destroy');
