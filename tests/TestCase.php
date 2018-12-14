<?php

namespace Tests;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseTransactions;

    /** @var \App\Models\User */
    protected $admin;
    /** @var \App\Models\Menu */
    protected $menu;

    public function admin($userId = null): User
    {
        if ($this->admin) {
            return $this->admin;
        }

        return $this->admin = $userId ? User::find($userId) : create(User::class);
    }

    public function menu($date = null): Menu
    {
        $date = $date ?? now()->toDateString();

        if ($this->menu && $this->menu->date->toDateString() == $date) {
            return $this->menu;
        }

        return $this->menu = Menu::whereDate('date', $date)->first() ?? create(Menu::class, ['date' => $date]);
    }
}
