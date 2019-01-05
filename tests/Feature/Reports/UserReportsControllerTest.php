<?php

namespace Tests\Feature\Reports;

use App\Models\Menu;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class UserReportsControllerTest extends TestCase
{
    use DatabaseTransactions;

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
    public function it_returns_the_total_amount_per_user_in_a_date_range()
    {
        $range = array_flatten($this->dateRange());
        $now   = array_random(CarbonPeriod::createFromArray($range)->toArray());
        Carbon::setTestNow($now);

        $user1  = create(User::class);
        $menu1  = create(Menu::class, ['date' => now()->toDateString()]);
        create(Order::class, ['owner_id' => $user1, 'menu_id'  => $menu1, 'completed_at' => now()]);
        create(Order::class, ['owner_id' => $user1, 'menu_id'  => $menu1, 'completed_at' => now()]);
        create(Order::class, ['owner_id' => $user1, 'menu_id'  => $menu1, 'completed_at' => null]);
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
}
