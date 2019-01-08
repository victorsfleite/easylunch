<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Tests\TestCase;
use Tests\TestResponse;

class ImpersonateUserTest extends TestCase
{
    use DatabaseTransactions;

    protected $users;

    public function setUp()
    {
        parent::setUp();

        $this->users = collect(User::ROLES)->map(function ($role) {
            return create(User::class, ['role' => $role]);
        });
    }

    private function impersonateUser(User $user) : TestResponse
    {
        $uri  = route('users.impersonate', ['user' => $user]);

        return $this->get($uri);
    }

    private function stopImpersonating() : TestResponse
    {
        $uri  = route('users.stop-impersonating');

        return $this->get($uri);
    }

    /** @test */
    public function it_should_allow_an_admin_to_impersonate_any_user()
    {
        $this->users->each(function ($user) {
            $this->actingAs($this->admin())
                ->impersonateUser($user)
                ->assertRedirect(route('home'));
            $this->assertAuthenticatedAs($user);
        });
    }

    /** @test */
    public function it_should_not_allow_a_non_admin_user_to_impersonate()
    {
        $this->users->each(function ($user) {
            $this->actingAs($this->chef())
                ->impersonateUser($user)
                ->assertRedirect(Response::HTTP_NOT_FOUND);
            $this->assertAuthenticatedAs($this->chef());
        });

        $this->users->each(function ($user) {
            $this->actingAs($this->user())
                ->impersonateUser($user)
                ->assertRedirect(Response::HTTP_NOT_FOUND);
            $this->assertAuthenticatedAs($this->user());
        });
    }

    /** @test */
    public function it_should_allow_an_impersonating_user_to_stop_impersonating()
    {
        $this->users->each(function ($user) {
            $this->actingAs($this->admin())
                ->impersonateUser($user)
                ->assertRedirect(route('home'));
            $this->assertAuthenticatedAs($user);

            $this->stopImpersonating()
                ->assertRedirect(route('users'));
            $this->assertAuthenticatedAs($this->admin());
        });
    }
}
