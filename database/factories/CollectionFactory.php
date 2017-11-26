<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Collection::class, function (Faker $faker) {
    $title = $faker->words(2, true);
    $title_slug = str_replace(' ', '-', $title);
    return [
        'user_id' => random_int(1,3),
        'category_id' => random_int(1,3),
        'label' => $title,
        'slug' => $title_slug,
        'description' => $faker->paragraph(3, true),
    ];
});
