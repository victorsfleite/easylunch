<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        factory(User::class)->create(['email' => 'chef@devsquad.com', 'role' => 'chef', 'name' => 'Andreia']);
        factory(User::class)->create(['email' => 'marco@devsquad.com', 'role' => 'admin', 'name' => 'Marco']);
        factory(User::class)->create(['email' => 'barreto@devsquad.com', 'role' => 'user', 'name' => 'Barreto']);
        factory(User::class)->create(['email' => 'victor@devsquad.com', 'role' => 'user', 'name' => 'Victor']);
        factory(User::class)->create(['email' => 'gabriel@devsquad.com', 'role' => 'user', 'name' => 'Gabriel']);
        factory(User::class)->create(['email' => 'wilker@devsquad.com', 'role' => 'user', 'name' => 'Wilker']);
        factory(User::class)->create(['email' => 'mathews@devsquad.com', 'role' => 'user', 'name' => 'Mathews']);
        factory(User::class)->create(['email' => 'carol@devsquad.com', 'role' => 'user', 'name' => 'Carol']);
    }
}
