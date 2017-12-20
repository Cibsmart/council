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


//Threads
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


//Replies
Route::get('threads/{channel}/{thread}/replies', 'RepliesController@index')
    ->name('replies.index');

Route::post('threads/{channel}/{thread}/replies', 'RepliesController@store')
    ->name('replies.store');

Route::delete('replies/{reply}', 'RepliesController@destroy')
    ->name('replies.delete');

Route::patch('replies/{reply}', 'RepliesController@update')
    ->name('replies.update');

Route::post('threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionsController@store')
    ->name('subscriptions.store');

Route::delete('threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionsController@destroy')
    ->name('subscriptions.destroy');


//Favourites
Route::post('replies/{reply}/favourites', 'FavouritesController@store')
    ->name('favourites.store');

Route::delete('replies/{reply}/favourites', 'FavouritesController@destroy')
    ->name('favourites.delete');


//Profiles
Route::get('profiles/{user}', 'ProfilesController@show')
    ->name('profiles.show');

Route::get('profiles/{user}/notifications', 'UserNotificationsController@index')
    ->name('notifications.index');

Route::delete('profiles/{user}/notifications/{notification}', 'UserNotificationsController@destroy')
    ->name('notifications.destroy');
