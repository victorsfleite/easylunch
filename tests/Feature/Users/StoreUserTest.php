<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use Tests\TestCase;

class StoreUserTest extends TestCase
{
    use DatabaseTransactions;

    private function storeUser(User $user = null, $password = '123456', $password_confirmation = null) : TestResponse
    {
        if (!$user) {
            $user = make(User::class);
        }

        return $this->postJson(route('users.store'), array_merge($user->toArray(), [
            'password'              => $password,
            'password_confirmation' => $password_confirmation ?? $password,
        ]));
    }

    /** @test */
    public function it_should_store_a_user()
    {
        $response = $this->actingAs($this->admin())
            ->storeUser()
            ->assertStatus(Response::HTTP_CREATED);

        $created = $response->original;
        $this->assertDatabaseHas('users', $created->only('id', 'name', 'email', 'role', 'password'));
    }

    /** @test */
    public function it_should_not_allow_a_guest_to_store_a_user()
    {
        $newUser = make(User::class);

        $this->storeUser($newUser)
            ->assertStatus(Response::HTTP_UNAUTHORIZED);

        $this->assertDatabaseMissing('users', $newUser->only('email'));
    }

    /** @test */
    public function it_should_not_allow_a_non_admin_to_store_a_user()
    {
        $newUser = make(User::class);

        $this->actingAs($this->chef())
            ->storeUser($newUser)
            ->assertForbidden();

        $this->actingAs($this->user())
            ->storeUser($newUser)
            ->assertForbidden();

        $this->assertDatabaseMissing('users', $newUser->only('email'));
    }

    /** @test */
    public function it_should_not_allow_a_non_admin_to_open_the_create_user_page()
    {
        $this->actingAs($this->chef())
            ->getJson(route('users.create'))
            ->assertForbidden();

        $this->actingAs($this->user())
            ->getJson(route('users.create'))
            ->assertForbidden();

        $this->actingAs($this->admin())
            ->getJson(route('users.create'))
            ->assertSuccessful();
    }

    /** @test */
    public function it_should_validate_name()
    {
        $this->actingAs($this->admin())->storeUser(make(User::class, ['name' => '']))
            ->assertJsonHasFragmentError('name', __('validation.required', ['attribute' => 'nome']));
        $this->actingAs($this->admin())->storeUser(make(User::class, ['name' => str_random(256)]))
            ->assertJsonHasFragmentError('name', 'O campo nome não pode ser superior a 255 caracteres.');
    }

    /** @test */
    public function it_should_validate_email()
    {
        $this->actingAs($this->admin())->storeUser(make(User::class, ['email' => '']))
            ->assertJsonHasFragmentError('email', __('validation.required', ['attribute' => 'email']));
        $this->actingAs($this->admin())->storeUser(make(User::class, ['email' => str_random()]))
            ->assertJsonHasFragmentError('email', __('validation.email', ['attribute' => 'email']));
        $this->actingAs($this->admin())->storeUser(make(User::class, ['email' => str_random(256)]))
            ->assertJsonHasFragmentError('email', 'O campo email deve ser um endereço de e-mail válido.');

        $user = create(User::class);
        $this->actingAs($this->admin())->storeUser(make(User::class, ['email' => $user->email]))
            ->assertJsonHasFragmentError('email', __('validation.unique', ['attribute' => 'email']));
    }

    /** @test */
    public function it_should_validate_role()
    {
        $this->actingAs($this->admin())->storeUser(make(User::class, ['role' => '']))
            ->assertJsonHasFragmentError('role', __('validation.required', ['attribute' => 'perfil']));
        $this->actingAs($this->admin())->storeUser(make(User::class, ['role' => str_random()]))
            ->assertJsonHasFragmentError('role', __('validation.in', ['attribute' => 'perfil']));
    }

    /** @test */
    public function it_should_validate_password()
    {
        $this->actingAs($this->admin())->storeUser(make(User::class), '')
            ->assertJsonHasFragmentError('password', __('validation.required', ['attribute' => 'senha']));
        $this->actingAs($this->admin())->storeUser(make(User::class), str_random(5))
            ->assertJsonHasFragmentError('password', 'O campo senha deve ter pelo menos 6 caracteres.');
        $this->actingAs($this->admin())->storeUser(make(User::class), str_random(5), '')
            ->assertJsonHasFragmentError('password', 'A confirmação de senha não confere.');
    }
}
