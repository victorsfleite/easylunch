<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        factory(User::class)->create(['email' => 'marco@devsquad.com']);
        factory(User::class)->create(['email' => 'gabriel@devsquad.com']);
        factory(User::class)->create(['email' => 'victor@devsquad.com']);
    }
}
