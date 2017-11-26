<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Poll::class, function (Faker $faker) {
    return [
        'question' => $faker->sentences(1, true),
        'active' => 1,
        'date_from' => date('Y-m-d'),
        'date_to' => date('Y-m-d'),
        'former_id' => 1,
    ];
});
