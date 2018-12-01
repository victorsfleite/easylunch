<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        factory(User::class)->create(['email' => 'team@devsquad.com', 'role' => 'admin']);
        factory(User::class)->create(['email' => 'chef@devsquad.com', 'role' => 'chef']);
        factory(User::class)->create(['email' => 'user@devsquad.com', 'role' => 'user']);
    }
}
