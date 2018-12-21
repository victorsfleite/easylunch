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
    /** @var \App\Models\User */
    protected $chef;
    /** @var \App\Models\User */
    protected $user;
    /** @var \App\Models\Menu */
    protected $menu;

    public function admin($adminId = null): User
    {
        if ($this->admin) {
            return $this->admin;
        }

        return $this->admin = $adminId ? User::find($adminId) : create(User::class, ['role' => User::ROLE_ADMIN]);
    }

    public function chef($chefId = null) : User
    {
        if ($this->chef) {
            return $this->chef;
        }

        return $this->chef = $chefId ? User::find($chefId) : create(User::class, ['role' => User::ROLE_CHEF]);
    }

    public function user($userId = null) : User
    {
        if ($this->user) {
            return $this->user;
        }

        return $this->user = $userId ? User::find($userId) : create(User::class, ['role' => User::ROLE_USER]);
    }

    public function menu($date = null): Menu
    {
        $date = $date ?? now()->toDateString();

        if ($this->menu && $this->menu->date->toDateString() == $date) {
            return $this->menu;
        }

        return $this->menu = Menu::whereDate('date', $date)->first() ?? create(Menu::class, ['date' => $date]);
    }

    protected function createTestResponse($response) : TestResponse
    {
        return TestResponse::fromBaseResponse($response);
    }
}
