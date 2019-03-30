<?php

use Faker\Generator as Faker;

$factory->define(Turing\Models\Department::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(),
        'description' => $faker->paragraph()
    ];
});
