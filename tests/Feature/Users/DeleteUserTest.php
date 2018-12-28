<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class DeleteUserTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = $this->createUser();
    }

    private function createUser()
    {
        return create(User::class);
    }

    private function deleteUser(User $user = null) : TestResponse
    {
        $user = $user ?? $this->user;
        $url = route('users.destroy', ['user' => $user->id]);

        return $this->deleteJson($url);
    }

    /** @test */
    public function it_should_delete_a_user()
    {
        $this->actingAs($this->admin())
            ->deleteUser()
            ->assertSuccessful();
    }

    /** @test */
    public function a_non_admin_user_cannot_delete_a_user()
    {
        $this->actingAs($this->chef())
            ->deleteUser()
            ->assertRedirect(Response::HTTP_NOT_FOUND);

        $this->actingAs($this->user())
            ->deleteUser()
            ->assertRedirect(Response::HTTP_NOT_FOUND);

        $this->assertDatabaseHas('users', $this->user->only('id', 'name', 'email', 'role', 'password'));
    }

    /** @test */
    public function a_guest_cannot_delete_a_user()
    {
        $this->assertGuest()
            ->deleteUser()
            ->assertStatus(Response::HTTP_UNAUTHORIZED);

        $this->assertDatabaseHas('users', $this->user->only('id', 'name', 'email', 'role', 'password'));
    }
}
