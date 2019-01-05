<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Option::class, function (Faker $faker) {
    return [
        'name'  => $faker->name,
        'price' => $faker->numberBetween(100, 500) / 100
    ];
});
