<?php

use Faker\Generator as Faker;

$factory->define(Turing\Models\Category::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(),
        'description' => $faker->paragraph()
    ];
});
