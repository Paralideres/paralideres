<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Resource::class, function (Faker $faker) {
    $title = $faker->sentences(1, true);
    $title_slug = str_replace(' ', '-', $title);
    return [
        'user_id' => random_int(1,3),
        'category_id' => random_int(1,3),
        'title' => $title,
        'review' => $faker->sentences(5, true),
        'slug' => $title_slug,
        'attachment' => '',
        'content' => $faker->paragraph(100, true),
    ];
});
