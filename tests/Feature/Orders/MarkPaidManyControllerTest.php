<?php

namespace Tests\Feature\Orders;

use App\Models\Order;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestResponse;
use Illuminate\Support\Carbon;

class MarkPaidManyControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function markManyOrders($ids, $paid): TestResponse
    {
        return $this->putJson(route('orders.mark-paid-many'), compact('ids', 'paid'));
    }

    /** @test */
    public function it_should_toggle_status_for_all_orders_given_in_the_id()
    {
        $ids = create(Order::class, ['paid_at' => null], 3)->map->id->values()->toArray();
        Carbon::setTestNow(now());

        $this->actingAs($this->admin())
            ->markManyOrders($ids, true)
            ->assertSuccessful();

        foreach ($ids as $id) {
            $this->assertDatabaseHas('orders', [
                'id'      => $id,
                'paid_at' => now(),
            ]);
        }
    }

    /** @test */
    public function it_should_toggle_paid_at_to_null_if_paid_field_is_false()
    {
        $ids = create(Order::class, ['paid_at' => now()], 3)->map->id->values()->toArray();

        $this->actingAs($this->admin())
            ->markManyOrders($ids, false)
            ->assertSuccessful();

        foreach ($ids as $id) {
            $this->assertDatabaseHas('orders', [
                'id'      => $id,
                'paid_at' => null,
            ]);
        }
    }

    /** @test */
    public function it_should_return_404_if_there_is_no_order_found()
    {
        $ids    = create(Order::class, [], 3)->map->id->values()->toArray();
        $nonIds = collect([1, 2, 3, 4, 5, 6])->diff($ids)->toArray();

        $this->actingAs($this->admin())
            ->markManyOrders($nonIds, true)
            ->assertNotFound();
    }

    /** @test */
    public function it_can_be_done_only_by_the_admin()
    {
        $ids = create(Order::class, [], 3)->map->id->values()->toArray();

        $this->actingAs($this->user())
            ->markManyOrders($ids, true)
            ->assertForbidden();
    }

    /** @test */
    public function field_ids_is_required()
    {
        $this->actingAs($this->admin())
            ->markManyOrders(null, true)
            ->assertJsonHasFragmentError('ids', 'O campo ids é obrigatório.');
    }

    /** @test */
    public function field_ids_should_be_an_array()
    {
        $this->actingAs($this->admin())
            ->markManyOrders('string', true)
            ->assertJsonHasFragmentError('ids', 'O campo ids deve ser uma matriz.');
    }

    /** @test */
    public function field_paid_is_required()
    {
        $this->actingAs($this->admin())
            ->markManyOrders([], null)
            ->assertJsonHasFragmentError('paid', 'O campo pago é obrigatório.');
    }

    /** @test */
    public function field_paid_should_be_a_boolean()
    {
        $this->actingAs($this->admin())
            ->markManyOrders([], 'string')
            ->assertJsonHasFragmentError('paid', 'O campo pago deve ser verdadeiro ou falso.');
    }
}
