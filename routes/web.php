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

Route::view('scan', 'scan');


//Search
Route::get('threads/search', 'SearchController@show')
    ->name('search.show');

//Threads
Route::get('threads', 'ThreadsController@index')
    ->name('threads.index');

Route::get('threads/create', 'ThreadsController@create')
    ->name('threads.create');

Route::get('threads/{channel}/{thread}', 'ThreadsController@show')
    ->name('threads.show');

Route::patch('threads/{channel}/{thread}', 'ThreadsController@update')
    ->name('threads.update');

Route::delete('threads/{channel}/{thread}', 'ThreadsController@destroy')
    ->name('threads.delete');

Route::post('threads', 'ThreadsController@store')
    ->name('threads.store')->middleware('must-be-confirmed');

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

Route::post('replies/{reply}/best', 'BestRepliesController@store')
    ->name('best-replies.store');


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

Route::get('register/confirm', 'Auth\RegisterConfirmationController@index')
    ->name('confirmation.index');


//Locked Threads
Route::post('locked-threads/{thread}', 'LockedThreadsController@store')
    ->name('locked-threads.store')->middleware('admin');
Route::delete('locked-threads/{thread}', 'LockedThreadsController@destroy')
    ->name('locked-threads.destroy')->middleware('admin');


//Api
Route::get('api/users', 'Api\UsersController@index');
Route::post('api/users/{user}/avatar', 'Api\UserAvatarController@store')
    ->middleware('auth')
    ->name('avatar.store');