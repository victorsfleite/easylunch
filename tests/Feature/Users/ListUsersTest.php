<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class ListUsersTest extends TestCase
{
    use DatabaseTransactions;

    public function listUsers() : TestResponse
    {
        return $this->getJson(route('users.index'));
    }

    /** @test */
    public function it_should_list_all_users()
    {
        $users = create(User::class, [], 3);

        $data = $this->actingAs($this->admin())
            ->listUsers()
            ->assertSuccessful()
            ->assertJsonCount(4, 'data')->decodeResponseJson('data');

        $allUsersIds = array_merge($users->pluck('id')->toArray(), [$this->admin()->id]);
        $this->assertTrue(collect($data)->every(function ($user) use ($allUsersIds) {
            return collect($allUsersIds)->contains($user['id']);
        }));
    }

    /** @test */
    public function a_non_admin_user_cannot_list_users()
    {
        $this->actingAs($this->chef())
            ->listUsers()
            ->assertRedirect(Response::HTTP_NOT_FOUND);

        $this->actingAs($this->user())
            ->listUsers()
            ->assertRedirect(Response::HTTP_NOT_FOUND);

        $this->actingAs($this->admin())
            ->listUsers()
            ->assertSuccessful()
            ->assertJsonCount(3, 'data');
    }

    /** @test */
    public function a_guest_cannot_list_users()
    {
        $this->assertGuest()
            ->listUsers()
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function it_should_filter_by_name()
    {
        create(User::class, [], 3);
        $user = create(User::class, ['name' => 'Some Name']);
        $uri = route('users.index', ['search' => 'name']);

        $data = $this->actingAs($this->admin())
            ->getJson($uri)
            ->assertSuccessful()
            ->assertJsonCount(1, 'data')->decodeResponseJson('data');

        $this->assertEquals(collect($data)->first()['name'], $user->name);
    }

    /** @test */
    public function it_should_filter_by_email()
    {
        create(User::class, [], 3);
        $user = create(User::class, ['email' => 'some.email@test.com']);
        $uri = route('users.index', ['search' => 'some.email']);

        $data = $this->actingAs($this->admin())
            ->getJson($uri)
            ->assertSuccessful()
            ->assertJsonCount(1, 'data')->decodeResponseJson('data');

        $this->assertEquals(collect($data)->first()['email'], $user->email);
    }
}
