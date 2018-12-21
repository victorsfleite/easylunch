<?php

namespace Tests\Feature\Reports;

use App\Models\Menu;
use App\Models\Order;
use App\Models\User;
use Carbon\CarbonPeriod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserReportsControllerTest extends TestCase
{
    public function dateRange(): array
    {
        return [
            'start' => today()->startOfWeek()->toDateTimeString(),
            'end'   => today()->endOfWeek()->subDays(2)->toDateTimeString(),
        ];
    }

    public function getUserReports(array $dateRange = null): TestResponse
    {
        $dateRange = $dateRange ?? $this->dateRange();

        return $this->postJson(route('reports.users'), $dateRange);
    }

    public function createUserOrders(int $numberOfOrders, string $date): User
    {
        $menu = $this->menu($date);
        $user = create(User::class);

        create(Order::class, [
            'menu_id'  => $menu,
            'owner_id' => $user,
        ], $numberOfOrders);

        return $user->fresh();
    }

    /** @test */
    public function it_returns_the_total_amount_of_the_users_in_a_date_range()
    {
        $user1 = create(User::class);
        $menu1 = create(Menu::class, ['date' => today()->toDateString()]);
        create(Order::class, ['owner_id' => $user1, 'menu_id'  => $menu1, 'completed_at' => now()]);
        create(Order::class, ['owner_id' => $user1, 'menu_id'  => $menu1, 'completed_at' => now()]);
        create(Order::class, ['owner_id' => $user1, 'menu_id'  => $menu1, 'completed_at' => null]);
        $range = array_flatten($this->dateRange());
        $user1->refresh();
        $user1Orders = $user1->orders()->completed()->betweenDates($range)->get();

        $this->assertEquals(2, $user1->orders()->completed()->betweenDates($range)->count());
        $this->assertEquals(20, $user1->totalAmountInRange($range));
        $this->actingAs($this->admin())
            ->getUserReports()
            ->assertSuccessful()
            ->assertJsonFragment([
                'data' => [
                    [
                        'id'           => $user1->id,
                        'name'         => $user1->name,
                        'orders'       => $user1Orders->toArray(),
                        'total_amount' => $user1->totalAmountInRange($range),
                    ]
                ]
            ]);
    }

    /** @test */
    // public function it_is_not_accessible_by_users()
    // {
    // }

    // /** @test */
    // public function it_is_not_accessible_by_chefs()
    // {
    // }
}
