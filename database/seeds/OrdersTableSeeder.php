<?php

use App\Models\Menu;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrdersTableSeeder extends Seeder
{
    public function run()
    {
        $users = User::all('id');

        Menu::all()->each(function ($menu) use ($users) {
            factory(Order::class, rand(1, 10))->make([
                'menu_id'  => $menu->id,
                'owner_id' => null,
            ])->each(function ($order) use ($users) {
                $order->fill(['owner_id' => $users->random()->id]);
                $order->save();
            });
        });
    }
}
