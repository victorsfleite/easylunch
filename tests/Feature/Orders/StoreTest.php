<?php

namespace Tests\Feature\Orders;

use App\Models\Menu;
use App\Models\Option;
use App\Models\Order;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestResponse;

class StoreTest extends TestCase
{
    use DatabaseTransactions;

    public function storeOrder(Menu $menu, Order $order): TestResponse
    {
        return $this->postJson(route('orders.store', $menu), $order->toArray());
    }

    /** @test */
    public function it_should_create_a_new_record_in_database()
    {
        $menu           = create(Menu::class);
        $order          = make(Order::class, ['menu_id' => $menu, 'owner_id' => $this->user()]);
        $order->options = null;

        $this->assertCount(0, $menu->orders);

        $this->actingAs($this->user())
            ->storeOrder($menu, $order)
            ->assertSuccessful();
        $menu->refresh();

        $this->assertCount(1, $menu->orders);
        $this->assertCount(0, $menu->orders->first()->options);
        $this->assertDatabaseHas($order->getTable(), $order->only('description', 'owner_id', 'menu_id'));
        $this->assertCount(1, $menu->orders);
    }

    /** @test */
    public function it_has_options_and_change_the_order_price()
    {
        $menu           = create(Menu::class);
        $order          = make(Order::class, ['menu_id' => $menu, 'owner_id' => $this->user()]);
        $order->options = create(Option::class, [], 3)->each(function (Option $option) {
            $option->pivot = ['price' => 2.5];
        });

        $this->assertCount(0, $menu->orders);

        $this->actingAs($this->user())
            ->storeOrder($menu, $order)
            ->assertSuccessful();
        $menu->refresh();

        $this->assertDatabaseHas($order->getTable(), $order->only('description', 'owner_id', 'menu_id'));
        $this->assertCount(1, $menu->orders);
        $this->assertCount(3, $menu->orders->first()->options);
        $this->assertEquals(17.5, $menu->orders->first()->price);
    }
}
