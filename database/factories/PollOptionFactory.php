<?php

use Faker\Generator as Faker;

$factory->define(App\Models\PollOption::class, function (Faker $faker) {
    return [
        'poll_id' => random_int(60,70),
        'option' => $faker->sentence(2, true),
        'index' => random_int(1,5),
    ];
});
