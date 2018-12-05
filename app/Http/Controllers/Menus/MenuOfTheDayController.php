<?php

namespace App\Http\Controllers\Menus;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuOfTheDayController extends Controller
{
    public function __invoke(Request $request)
    {
        $menu = Menu::whereDate('date', today())->first();

        return view('menus.show', compact('menu'));
    }
}
