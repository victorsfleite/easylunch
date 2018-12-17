<?php

use App\Models\Menu;
use App\Models\Order;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    $completedAt = array_random([null, $faker->dateTimeBetween('-1 year')]);
    $menu = create(Menu::class);

    return [
        'description' => $faker->realText,
        'owner_id'    => function () {
            return create(User::class)->id;
        },
        'menu_id'      => $menu->id,
        'completed_at' => $completedAt,
        'created_at'   => $completedAt ?? $menu->date,
    ];
});
