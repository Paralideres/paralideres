<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

//$factory->define(App\User::class, function (Faker $faker) {
//    static $password;
//
//    return [
//        'name' => $faker->name,
//        'email' => $faker->unique()->safeEmail,
//        'password' => $password ?: $password = bcrypt('secret'),
//        'remember_token' => str_random(10),
//    ];
//});


$factory->define(App\Models\User::class, function (Faker $faker) {
    return [
        'username' => $faker->userName,
        'email' => $faker->safeEmail,
        'password' => bcrypt(111111),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Models\UserProfile::class, function (Faker $faker) {
    return [
        'fullname' => $faker->name,
        'country_id' => 1,
        'city' => $faker->city,
        'birthdate' => $faker->dateTimeThisCentury($max = '-15 years') ,
        'description' => $faker->text($maxNbChars = 200),
        'image' => $faker->md5,
        'social_facebook' => $faker->userName,
        'social_twitter' => $faker->userName,
        'social_youtube' => $faker->userName,
        'social_instagram' => $faker->userName,
        'social_snapchat' => $faker->userName,
    ];
});
