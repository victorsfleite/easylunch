<?php

use App\Models\Menu;
use App\Models\Order;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'description' => $faker->realText,
        'owner_id'    => function () {
            return create(User::class)->id;
        },
        'menu_id' => function () {
            return create(Menu::class)->id;
        },
        'completed_at' => array_random([null, $faker->dateTimeBetween('-1 year')]),
    ];
});
