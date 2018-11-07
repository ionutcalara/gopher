<?php

use Illuminate\Database\Seeder;

class TestUsersTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 * @return void
	 */
	public function run()
	{
		DB::table('users')->insert([
			'name' => 'Errand',
			'email' => 'test@test.com',
			'password' => bcrypt(str_random(15)),
			'api_token' => '55j88a169z6A6ocd7sUII3zTckUHMXgo7K2aHbNtRkC3fnNeKKEnROQVfhwe',
		]);
	}
}
