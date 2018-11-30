<?php

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenusTableSeeder extends Seeder
{
    public function run()
    {
        factory(Menu::class, 30)->create();
    }
}
