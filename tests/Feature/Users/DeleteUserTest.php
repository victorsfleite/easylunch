<?php

namespace Tests\Feature\Users;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeleteUserTest extends TestCase
{
    use DatabaseTransactions;

    private function deleteUser(User $user) : TestResponse
    {
        return $this->deleteJson(route('users.destroy', ['user' => $user->id]));
    }

    /** @test */
    public function it_should_delete_a_user()
    {
        $this->actingAs($this->admin())
            ->deleteUser(create(User::class))
            ->assertSuccessful();
    }

    /** @test */
    public function a_non_admin_user_cannot_delete_a_user()
    {
        $user = create(User::class);

        $this->actingAs($this->chef())
            ->deleteUser($user)
            ->assertForbidden();

        $this->actingAs($this->user())
            ->deleteUser($user)
            ->assertForbidden();

        $this->assertDatabaseHas('users', $user->only('id', 'name', 'email', 'role', 'password'));
    }

    /** @test */
    public function a_guest_cannot_delete_a_user()
    {
        $user = create(User::class);
        $this->assertGuest()
            ->deleteUser($user)
            ->assertStatus(Response::HTTP_UNAUTHORIZED);

        $this->assertDatabaseHas('users', $user->only('id', 'name', 'email', 'role', 'password'));
    }
}
