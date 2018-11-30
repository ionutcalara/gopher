<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Podio;
use PodioApp;
use PodioDateItemField;
use PodioItem;
use PodioItemFieldCollection;
use Themsaid\Forge\Forge;

class ForgeDeployAllSites extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'forge:deploy {repository}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Deploy all forge sites';


	/**
	 *
	 * @var Forge
	 */
	public $forge = null;

	/**
	 *
	 */
	public $serverId = null;

	/**
	 * @var array
	 */
	public $sites = [];

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		$this->forge = new Forge( config( 'forge.token' ) );
		$this->forge->setTimeout( 300 );
		$this->serverId = config( 'forge.server_id' );
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function handle() {
		$this->process();
	}

	/**
	 * Create an item in the application sent as param
	 * @throws \Exception
	 */
	private function process() {
		$this->getAllSites()->deploy();
	}

	/**
	 *
	 */
	private function deploy() {
		foreach ( $this->sites as $site ) {
			echo 'Deploying ' . $site->name;
			$this->forge->deploySite( $this->serverId, $site->id, true );
		}
	}

	/**
	 * @return $this
	 */
	private function getAllSites() {
		$sites = $this->forge->sites( $this->serverId );

		if ( ! $sites ) {
			return $this;
		}
		foreach ( $sites as $k => $site ) {
			if ( $site->repository === $this->argument( 'repository' ) ) {
				$this->sites[] = $site;
			}
		}

		return $this;
	}


}
