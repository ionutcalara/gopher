<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;
use Themsaid\Forge\Forge;

class DeployForgeSite implements ShouldQueue {
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected $visible = [];

	private $sites;

	/**
	 *
	 * @var Forge
	 */
	protected $forge = null;

	/**
	 *
	 */
	protected $serverId = null;

	/**
	 * @var array
	 */

	/**
	 * Create a new job instance.
	 *
	 * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
	 * @return void
	 */
	public function __construct() {

	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
	 */
	public function handle() {
		$this->beforeHandle();
		$site = array_pop( $this->sites );
		$this->forge->deploySite( $this->serverId, $site['id'], true );
		echo 'Deployed ' . $site['name'] . PHP_EOL;
		$this->saveSites();
		if ( count( $this->sites ) > 0 ) {
			$this->dispatch( new DeployForgeSite() );
		}

	}

	/**
	 * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
	 */
	public function beforeHandle() {
		$this->sites = json_decode( Storage::disk( 'local' )->get( 'forge-deploy-sites.json' ), true );
		$this->forge = new Forge( config( 'forge.token' ) );
		$this->forge->setTimeout( 300 );
		$this->serverId = config( 'forge.server_id' );
	}


	/**
	 *
	 */
	private function saveSites() {
		Storage::disk( 'local' )->put( 'forge-deploy-sites.json', json_encode( $this->sites ) );
	}
}
