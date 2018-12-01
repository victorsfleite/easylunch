<?php

use App\Models\Menu;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrdersTableSeeder extends Seeder
{
    public function run()
    {
        Menu::all()->each(function ($menu) {
            factory(Order::class, rand(1, 10))->create([
                'owner_id' => User::all()->random()->id,
                'menu_id'  => $menu->id,
            ]);
        });
    }
}
