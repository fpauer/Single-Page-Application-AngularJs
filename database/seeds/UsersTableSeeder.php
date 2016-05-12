<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('@dmin1'),
            'role' => \App\Repositories\UserRepository::ROLE_ADMIN,
            'calories_expected' => \App\Repositories\UserRepository::DEFAULT_EXPECTED_CALORIES_PERSON
        ]);
    }
}
