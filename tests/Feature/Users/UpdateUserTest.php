<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class UpdateUserTest extends TestCase
{
    use DatabaseTransactions;

    protected $updatingUser;

    private function createUser()
    {
        if (!$this->updatingUser) {
            return $this->updatingUser = create(User::class);
        }

        return $this->updatingUser;
    }

    private function fakeUser($attributes = [])
    {
        return make(User::class, [
            'name'     => $attribute['name'] ?? 'Jetete',
            'email'    => $attribute['email'] ?? 'jetete@email.com',
            'role'     => $attribute['role'] ?? 'user',
            'password' => $attribute['password'] ?? '111111',
        ]);
    }

    private function updateUser(User $user = null, $password = null, $password_confirmation = null) : TestResponse
    {
        if (!$user) {
            $user = $this->fakeUser();
        }

        return $this->putJson(
            route('users.update', ['user' => $this->createUser()->id]),
            array_merge($user->toArray(), [
                'password' => $password,
                'password_confirmation' => $password_confirmation ?? $password,
            ])
        );
    }

    /** @test */
    public function it_should_update_a_user()
    {
        $response = $this->actingAs($this->admin())
            ->updateUser()
            ->assertSuccessful();

        $updated = $response->original;
        $this->assertDatabaseHas('users', $updated->only('id', 'name', 'email', 'role', 'password'));
    }

    /** @test */
    public function it_should_not_allow_a_guest_to_update_a_user()
    {
        $this->updateUser()
            ->assertStatus(Response::HTTP_UNAUTHORIZED);

        $this->assertDatabaseMissing('users', $this->fakeUser()->only('id', 'name', 'email', 'role', 'password'));
    }

    /** @test */
    public function it_should_not_allow_a_non_admin_to_update_a_user()
    {
        $this->actingAs($this->chef())
            ->updateUser()
            ->assertRedirect(Response::HTTP_NOT_FOUND);

        $this->actingAs($this->user())
            ->updateUser()
            ->assertRedirect(Response::HTTP_NOT_FOUND);

        $this->assertDatabaseMissing('users', $this->fakeUser()->only('id', 'name', 'email', 'role', 'password'));
    }

    /** @test */
    public function it_should_not_allow_a_non_admin_to_open_the_create_user_page()
    {
        $this->actingAs($this->chef())
            ->getJson(route('users.create'))
            ->assertRedirect(Response::HTTP_NOT_FOUND);

        $this->actingAs($this->user())
            ->getJson(route('users.create'))
            ->assertRedirect(Response::HTTP_NOT_FOUND);

        $this->actingAs($this->admin())
            ->getJson(route('users.create'))
            ->assertSuccessful();
    }

    /** @test */
    public function it_should_validate_name()
    {
        $this->actingAs($this->admin())->updateUser(make(User::class, ['name' => '']))
            ->assertJsonHasFragmentError('name', __('validation.required', ['attribute' => 'nome']));
        $this->actingAs($this->admin())->updateUser(make(User::class, ['name' => str_random(256)]))
            ->assertJsonHasFragmentError('name', 'O campo nome não pode ser superior a 255 caracteres.');
    }

    /** @test */
    public function it_should_validate_email()
    {
        $this->actingAs($this->admin())->updateUser(make(User::class, ['email' => '']))
            ->assertJsonHasFragmentError('email', __('validation.required', ['attribute' => 'email']));
        $this->actingAs($this->admin())->updateUser(make(User::class, ['email' => str_random()]))
            ->assertJsonHasFragmentError('email', __('validation.email', ['attribute' => 'email']));
        $this->actingAs($this->admin())->updateUser(make(User::class, ['email' => str_random(256)]))
            ->assertJsonHasFragmentError('email', 'O campo email deve ser um endereço de e-mail válido.');

        // Test unique e-mail on users and ignore unique if e-mail field is not being updating
        $user = create(User::class);
        $this->actingAs($this->admin())->updateUser(make(User::class, ['email' => $user->email]))
            ->assertJsonHasFragmentError('email', __('validation.unique', ['attribute' => 'email']));
        $this->actingAs($this->admin())->updateUser($this->updatingUser)
            ->assertSuccessful();
    }

    /** @test */
    public function it_should_validate_role()
    {
        $this->actingAs($this->admin())->updateUser(make(User::class, ['role' => '']))
            ->assertJsonHasFragmentError('role', __('validation.required', ['attribute' => 'perfil']));
        $this->actingAs($this->admin())->updateUser(make(User::class, ['role' => str_random()]))
            ->assertJsonHasFragmentError('role', __('validation.in', ['attribute' => 'perfil']));
    }

    /** @test */
    public function it_should_validate_password()
    {
        $this->actingAs($this->admin())->updateUser(make(User::class), str_random(5))
            ->assertJsonHasFragmentError('password', 'O campo senha deve ter pelo menos 6 caracteres.');
        $this->actingAs($this->admin())->updateUser(make(User::class), str_random(5), '')
            ->assertJsonHasFragmentError('password', 'A confirmação de senha não confere.');

        // Test password it not required when updating user
        $this->actingAs($this->admin())->updateUser(make(User::class), null)
            ->assertSuccessful();
    }
}
