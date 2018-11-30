<?php

use App\Models\Menu;
use App\Models\Order;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'description' => $faker->realText,
        'owner_id'    => function () {
            return factory(User::class)->create()->id;
        },
        'menu_id' => function () {
            return factory(Menu::class)->create()->id;
        },
    ];
});
