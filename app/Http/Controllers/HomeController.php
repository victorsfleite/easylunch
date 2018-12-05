<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Menus\MenuOfTheDayController;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return redirect()->route('menus.today');
    }
}
