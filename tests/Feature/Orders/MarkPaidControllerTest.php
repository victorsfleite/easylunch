<?php

namespace Tests\Feature\Orders;

use App\Models\Order;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestResponse;

class MarkPaidControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function markOrder(Order $order, bool $marked): TestResponse
    {
        return $this->putJson(route('orders.mark-paid', $order), [
            'paid' => $marked,
        ]);
    }

    /** @test */
    public function it_should_mark_a_order_as_paid_if_paid_param_is_true()
    {
        $order = create(Order::class);

        $this->assertNull($order->paid_at);

        $this->actingAs($this->admin())
            ->markOrder($order, true)
            ->assertSuccessful();

        $this->assertDatabaseHas($order->getTable(), [
            'id'      => $order->id,
            'paid_at' => now()->toDateTimeString(),
        ]);
    }

    /** @test */
    public function it_should_mark_a_order_as_not_paid_if_paid_param_is_false()
    {
        $order = create(Order::class, ['paid_at' => now()]);

        $this->assertNotNull($order->paid_at);

        $this->actingAs($this->admin())
            ->markOrder($order, false)
            ->assertSuccessful();

        $this->assertDatabaseHas($order->getTable(), [
            'id'      => $order->id,
            'paid_at' => null,
        ]);
    }

    /** @test */
    public function is_can_be_done_only_by_admin()
    {
        $order = create(Order::class);

        $this->actingAs($this->user())
            ->markOrder($order, true)
            ->assertForbidden();
    }
}
