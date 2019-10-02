<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        factory(User::class)->create(['email' => 'chef@devsquad.com', 'role' => 'chef', 'name' => 'Chef']);
        factory(User::class)->create(['email' => 'admin@devsquad.com', 'role' => 'admin', 'name' => 'Admin']);
        factory(User::class)->create(['email' => 'user@devsquad.com', 'role' => 'user', 'name' => 'User']);
    }
}
