<?php

use App\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class)->create([
            'id' => 1,
            'api_token' => 'one',
            'country_code' => 'GB',
        ]);

        factory(User::class)->create([
            'id' => 2,
            'api_token' => 'two',
            'country_code' => 'FR',
        ]);

        factory(User::class)->create([
            'id' => 3,
            'api_token' => 'three',
            'country_code' => 'DE',
        ]);
    }
}
