<?php

use App\Models\Menu;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Seeder;

class MenusTableSeeder extends Seeder
{
    public function run()
    {
        collect(CarbonPeriod::create(today()->subWeek(), today())->toArray())->each(function (Carbon $date) {
            create(Menu::class, compact('date'));
        });
    }
}
