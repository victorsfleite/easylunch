<?php

use App\Models\Menu;
use Faker\Generator as Faker;

$factory->define(Menu::class, function (Faker $faker) {
    return [
        'date'        => $faker->dateTimeBetween(today()->subWeek(), today()),
        'description' => $faker->realText,
    ];
});
