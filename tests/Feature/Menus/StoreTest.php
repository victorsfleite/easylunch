<?php

namespace Tests\Feature\Menus;

use App\Models\Menu;
use App\Models\Option;
use App\Models\User;
use App\Notifications\MenuCreated;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use DatabaseTransactions;

    public function storeMenu(Menu $menu): TestResponse
    {
        return $this->postJson(route('menus.store'), $menu->toArray());
    }

    public function setUp()
    {
        parent::setUp();

        Notification::fake();
    }

    /** @test */
    public function it_can_be_done_by_chef()
    {
        $chef = create(User::class, ['role' => 'chef']);
        $menu = make(Menu::class);

        $this->actingAs($chef)
            ->storeMenu($menu)
            ->assertSuccessful();
    }

    /** @test */
    public function it_can_be_done_by_admin()
    {
        $admin = create(User::class, ['role' => 'admin']);
        $menu  = make(Menu::class);

        $this->actingAs($admin)
            ->storeMenu($menu)
            ->assertSuccessful();
    }

    /** @test */
    public function it_cannot_be_done_by_user()
    {
        $user  = create(User::class, ['role' => 'user']);
        $menu  = make(Menu::class);

        $this->actingAs($user)
            ->storeMenu($menu)
            ->assertForbidden();
    }

    /** @test */
    public function it_should_store_a_menu()
    {
        $chef = create(User::class, ['role' => 'chef']);
        $menu = make(Menu::class);

        $this->actingAs($chef)
            ->storeMenu($menu)
            ->assertSuccessful();

        $this->assertDatabaseHas($menu->getTable(), [
            'description' => $menu->description,
        ]);
    }

    /** @test */
    public function it_should_store_menu_with_options()
    {
        $chef          = create(User::class, ['role' => 'chef']);
        $menu          = make(Menu::class);
        $menu->options = create(Option::class, ['price' => 3.50], 3)->each(function (Option $option) {
            $option->pivot = ['price' => $option->price];
        });

        $menu = $this->actingAs($chef)
            ->storeMenu($menu)
            ->assertSuccessful()
            ->original;

        $this->assertDatabaseHas($menu->getTable(), [
            'description' => $menu->description,
        ]);
        $this->assertCount(3, $menu->options);
        $menu->options->each(function (Option $option) use ($menu) {
            $this->assertDatabaseHas($option->pivot->getTable(), [
                'option_id' => $option->id,
                'menu_id'   => $menu->id,
                'price'     => $option->price,
            ]);
        });
    }

    /** @test */
    public function it_should_store_options_with_custom_price_in_pivot_table()
    {
        $chef          = create(User::class, ['role' => 'chef']);
        $menu          = make(Menu::class);
        $menu->options = create(Option::class, ['price' => 3.50], 3)->each(function (Option $option) {
            $option->pivot = [
                'price' => 2.99
            ];
        });

        $menu = $this->actingAs($chef)
            ->storeMenu($menu)
            ->assertSuccessful()
            ->original;

        $this->assertDatabaseHas($menu->getTable(), [
            'description' => $menu->description,
        ]);
        $this->assertCount(3, $menu->options);
        $menu->options->each(function (Option $option) use ($menu) {
            $this->assertDatabaseHas($option->pivot->getTable(), [
                'option_id' => $option->id,
                'menu_id'   => $menu->id,
                'price'     => 2.99,
            ]);
            $this->assertDatabaseHas($option->getTable(), [
                'id'    => $option->id,
                'price' => 3.5,
            ]);
        });
    }

    /** @test */
    public function it_sends_a_notification_to_slack()
    {
        $chef = create(User::class, ['role' => 'chef']);
        $menu = make(Menu::class);

        $this->actingAs($chef)
            ->storeMenu($menu)
            ->assertSuccessful();

        Notification::assertSentTo($chef, MenuCreated::class);
    }
}
