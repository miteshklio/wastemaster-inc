<?php

use Illuminate\Database\Seeder;

class UserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create roles
        DB::table('user_roles')->insert([
            ['name' => 'User'],
            ['name' => 'Admin']
        ]);
    }
}
