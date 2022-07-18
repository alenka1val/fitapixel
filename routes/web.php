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


Auth::routes();

Route::get('/', 'App\Http\Controllers\HomeController@index')->name('home');
Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home');
Route::get('/judges', 'App\Http\Controllers\HomeController@indexJury')->name('info.judges');

Route::group(['middleware' => ['auth', 'adminOrPhotographer']], function () {
    Route::get('/photographies/create', 'App\Http\Controllers\PhotographyController@create')->name('photographies.create');
    Route::post('/photographies/create', 'App\Http\Controllers\PhotographyController@store')->name('photographies.store');
});

Route::get('/gallery', 'App\Http\Controllers\PhotographyController@index')->name('info.gallery');

Route::group(['middleware' => ['auth', 'adminOrJury']], function () {
    Route::get('/voteList', 'App\Http\Controllers\VotesController@voteList')->name('info.voteList');
    Route::get('/vote', 'App\Http\Controllers\VotesController@voteIndex')->name('info.vote');
    Route::post('/vote', 'App\Http\Controllers\VotesController@voteStore')->name('info.voteStore');
});

Route::get('/competition', 'App\Http\Controllers\EventController@index')->name('info.competition');

//Route::get('/gallery', function () {
//    return view('info.gallery');
//})->name('info.gallery');

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

// Route::post('/photographies/{photography}/update', 'App\Http\Controllers\PhotographyController@update')->name('photographies.update');
// Route::delete('/photographies/{photography}/delete', 'App\Http\Controllers\PhotographyController@destroy')->name('photographies.destroy');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/user', 'App\Http\Controllers\UserController@index')->name('users.profile');
    Route::get('/user/photos', 'App\Http\Controllers\UserController@photos')->name('users.photos');
    Route::get('/user/update', 'App\Http\Controllers\UserController@create')->name('users.create');
    Route::post('/user/update', 'App\Http\Controllers\UserController@store')->name('users.store');
    Route::get('/user/password/update', 'App\Http\Controllers\UserController@passwordCreate')->name('users.passwordCreate');
    Route::post('/user/password/update', 'App\Http\Controllers\UserController@passwordStore')->name('users.passwordStore');
});


Route::group(['middleware' => ['auth', 'admin']], function () {
    Route::get('/admin', 'App\Http\Controllers\HomeController@adminIndex')->name('admin.home');

    Route::get('/admin/events', 'App\Http\Controllers\EventController@adminIndex')->name('admin.eventIndex');
    Route::get('/admin/events/{id}', 'App\Http\Controllers\EventController@show')->name('admin.eventShow');
    Route::post('/admin/events/{id}', 'App\Http\Controllers\EventController@update')->name('admin.eventStore');
    Route::delete('/admin/events/{id}', 'App\Http\Controllers\EventController@destroy')->name('admin.eventDestroy');

    Route::get('/admin/contents', 'App\Http\Controllers\ContentController@adminIndex')->name('admin.contentIndex');
    Route::get('/admin/contents/{id}', 'App\Http\Controllers\ContentController@show')->name('admin.contentShow');
    Route::post('/admin/contents/{id}', 'App\Http\Controllers\ContentController@update')->name('admin.contentStore');
    Route::delete('/admin/contents/{id}', 'App\Http\Controllers\ContentController@destroy')->name('admin.contentDestroy');

    Route::get('/admin/photos', 'App\Http\Controllers\PhotographyController@adminIndex')->name('admin.photoIndex');
    Route::get('/admin/photos/{id}', 'App\Http\Controllers\PhotographyController@show')->name('admin.photoShow');
    Route::post('/admin/photos/{id}', 'App\Http\Controllers\PhotographyController@update')->name('admin.photoStore');
    Route::delete('/admin/photos/{id}', 'App\Http\Controllers\PhotographyController@destroy')->name('admin.photoDestroy');

    Route::get('/admin/sponsors', 'App\Http\Controllers\SponsorController@adminIndex')->name('admin.sponsorIndex');
    Route::get('/admin/sponsors/{id}', 'App\Http\Controllers\SponsorController@show')->name('admin.sponsorShow');
    Route::post('/admin/sponsors/{id}', 'App\Http\Controllers\SponsorController@update')->name('admin.sponsorStore');
    Route::delete('/admin/sponsors/{id}', 'App\Http\Controllers\SponsorController@destroy')->name('admin.sponsorDestroy');

    Route::get('/admin/users', 'App\Http\Controllers\UserController@adminIndex')->name('admin.userIndex');
    Route::get('/admin/users/{id}', 'App\Http\Controllers\UserController@show')->name('admin.userShow');
    Route::post('/admin/users/{id}', 'App\Http\Controllers\UserController@update')->name('admin.userStore');
    Route::delete('/admin/users/{id}', 'App\Http\Controllers\UserController@destroy')->name('admin.userDestroy');

    Route::get('/admin/groups', 'App\Http\Controllers\GroupController@adminIndex')->name('admin.groupIndex');
    Route::get('/admin/groups/{id}', 'App\Http\Controllers\GroupController@show')->name('admin.groupShow');
    Route::post('/admin/groups/{id}', 'App\Http\Controllers\GroupController@update')->name('admin.groupStore');
    Route::delete('/admin/groups/{id}', 'App\Http\Controllers\GroupController@destroy')->name('admin.groupDestroy');
});
