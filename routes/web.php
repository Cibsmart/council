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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('threads', 'ThreadsController@index')
    ->name('threads.index');

Route::get('threads/create', 'ThreadsController@create')
    ->name('threads.create');

Route::get('threads/{channel}/{thread}', 'ThreadsController@show')
    ->name('threads.show');

Route::delete('threads/{channel}/{thread}', 'ThreadsController@destroy')
    ->name('threads.delete');

Route::post('threads', 'ThreadsController@store')
    ->name('threads.store');

Route::get('threads/{channel}', 'ThreadsController@index')
    ->name('channel.index');

Route::post('threads/{channel}/{thread}/replies', 'RepliesController@store')
    ->name('replies.store');

Route::post('replies/{reply}/favourites', 'FavouritesController@store')
    ->name('favourites.store');

Route::get('profiles/{user}', 'ProfilesController@show')
    ->name('profiles.show');
