<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiTest extends TestCase {

	use RefreshDatabase;

	public function setUp() {
		parent::setUp();
		Artisan::call( 'db:seed', [ '--class' => 'TestUsersTableSeeder' ] );
	}

	/**
	 * A basic test example.
	 *
	 * @return void
	 */
	public function testApi() {
		$response = $this->get( '/api/v1/errand?api_token=' . env( 'API_TOKEN' ) . '&command=inspire' );

		$response->assertStatus( 200 );
	}
}
