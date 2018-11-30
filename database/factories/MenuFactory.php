<?php

use App\Models\Menu;
use Faker\Generator as Faker;

$factory->define(Menu::class, function (Faker $faker) {
    return [
        'date'        => $faker->dateTimeThisMonth,
        'description' => $faker->realText,
    ];
});
