<?php

use Faker\Generator as Faker;

$factory->define(Turing\Models\Product::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(),
        'description' => $faker->paragraph(),
        'price' => rand(10, 100),
        'discounted_price' => rand(10, 100),
        'image' => $faker->word(),
        'image_2' => $faker->word(),
        'thumbnail' => $faker->word(),
        'display' => 0
    ];
});
