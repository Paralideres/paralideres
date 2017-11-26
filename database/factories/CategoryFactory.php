<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Category::class, function (Faker $faker) {
    $name = $faker->words(2, true);
    $name_slug = str_replace(' ', '-', $name);
    return [
        'label' => $name,
        'slug' => $name_slug,
        'description' => $faker->sentences(3, true),
    ];
});
