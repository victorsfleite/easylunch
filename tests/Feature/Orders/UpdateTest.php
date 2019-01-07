<?php

namespace Tests\Feature\Orders;

use App\Models\Menu;
use App\Models\Option;
use App\Models\Order;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestResponse;

class UpdateTest extends TestCase
{
    use DatabaseTransactions;

    public function updateOrder(Menu $menu, Order $order): TestResponse
    {
        return $this->putJson(route('orders.update', compact('menu', 'order')), $order->toArray());
    }

    /** @test */
    public function it_should_update_an_existing_record_in_database()
    {
        $menu               = create(Menu::class);
        $order              = create(Order::class, ['menu_id' => $menu, 'owner_id' => $this->user(), 'completed_at' => null]);
        $order->description = 'changed';

        $this->actingAs($this->user())
            ->updateOrder($menu, $order)
            ->assertSuccessful();

        $this->assertDatabaseHas($order->getTable(), $order->only('id', 'description', 'owner_id', 'menu_id'));
    }

    /** @test */
    public function it_has_options_and_change_the_order_price()
    {
        $menu           = create(Menu::class);
        $order          = create(Order::class, ['menu_id' => $menu, 'owner_id' => $this->user(), 'completed_at' => null]);
        $order->options = create(Option::class, [], 3)->each(function (Option $option) {
            $option->pivot = ['price' => 2.5];
        });

        $this->assertCount(1, $menu->orders);

        $this->actingAs($this->user())
            ->updateOrder($menu, $order)
            ->assertSuccessful();
        $menu->refresh();

        $this->assertDatabaseHas($order->getTable(), $order->only('description', 'owner_id', 'menu_id'));
        $this->assertCount(1, $menu->orders);
        $this->assertCount(3, $menu->orders->first()->options);
        $this->assertEquals(17.5, $menu->orders->first()->price);
    }

    /** @test */
    public function it_cant_update_a_completed_order()
    {
        $menu               = create(Menu::class);
        $order              = create(Order::class, ['completed_at' => now(), 'owner_id' => $this->user(), 'menu_id' => $menu]);
        $order->description = 'Updated';

        $this->actingAs($this->user())
            ->updateOrder($menu, $order)
            ->assertBadRequest();
    }
}
