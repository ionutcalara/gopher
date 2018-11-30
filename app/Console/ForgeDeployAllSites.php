<?php

namespace App\Console\Commands;

use App\Jobs\DeployForgeSite;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Storage;
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
	 * @var Filesystem
	 */
	protected $files;

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
	protected $sites = [];

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct( ) {
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
	 * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
	 */
	private function deploy() {
		$this->saveSites();
		dispatch(new DeployForgeSite());
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

	/**
	 *
	 */
	private function saveSites() {
		Storage::disk('local')->put(  'forge-deploy-sites.json' , json_encode( $this->sites ) );
	}


}
