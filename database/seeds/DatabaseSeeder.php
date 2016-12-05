<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // All Seeds
        $this->call(UserRolesSeeder::class);

        // Development Seeds
        if(env('APP_ENV') == 'local') {
            $this->call(DevUserSeeder::class);
        }

        // Testing Seeds
        if(env('APP_ENV') == 'testing') {
            $this->call(TestUserSeeder::class);
        }
    }
}
