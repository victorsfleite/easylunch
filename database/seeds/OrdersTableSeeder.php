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
            $users->take(rand(3, 5))->each(function ($user) use ($menu) {
                create(Order::class, [
                    'menu_id'  => $menu->id,
                    'owner_id' => $user->id,
                ]);
            });
        });
    }
}
