<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Notifications\ThreadWasUpdated;
use App\User;
use Illuminate\Notifications\DatabaseNotification;
use Ramsey\Uuid\Uuid;

$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'confirmed' => false
    ];
});

$factory->define(App\Thread::class, function ($faker){
    return [
        'user_id' => function (){
            return factory(App\User::class)->create()->id;
        },
        'channel_id' => function(){
            return factory(App\Channel::class)->create()->id;
        },
        'title' => $faker->sentence,
        'body' => $faker->paragraph,
        'visits' => 0
    ];
});

$factory->define(App\Channel::class, function ($faker){
    $name = $faker->word;
   return [
       'name' => $name,
       'slug' => $name
   ];
});

$factory->define(App\Reply::class, function ($faker){
    return [
        'user_id' => function (){
            return factory(App\User::class)->create()->id;
        },
        'thread_id' => function (){
            return factory(App\Thread::class)->create()->id;
        },
        'body' => $faker->paragraph
    ];
});

$factory->define(DatabaseNotification::class, function ($faker){
    return [
        'id' => Uuid::uuid4()->toString(),
        'type' => ThreadWasUpdated::class,
        'notifiable_id' => function(){
            return auth()->id() ? : factory(User::class)->create()->id;
        },
        'notifiable_type' => User::class,
        'data' => ['foo' => 'bar']
    ];
});
