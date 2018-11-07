<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Podio;
use PodioApp;
use PodioDateItemField;
use PodioItem;
use PodioItemFieldCollection;

class CreatePodioItem extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'podio:create {application} {params}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create an item on podio';


	/**
	 * The application id
	 *
	 * @var null
	 */
	public $app_id = null;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		require_once __DIR__ . '/../../vendorNotCompatibleWithComposer/podio-php-4.3.0/PodioAPI.php';
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
		Podio::setup( env( 'PODIO_CLIENT_ID' ), env( 'PODIO_CLIENT_SECRET' ) );
		$application  = $this->argument( 'application' );
		$this->app_id = env( 'PODIO_' . $application . '_ID' );
		Podio::authenticate_with_app( $this->app_id, env( 'PODIO_' . $application . '_TOKEN' ) );
		$item = $this->getPodioObject( $this->argument( 'params' ) );
		$item->save();

	}

	/**
	 * Params can be added as follows in the url:
	 * params=text::headline::This is the headline$:contact::attendees::3704440$$4184599
	 *
	 * @param $params
	 *
	 * @return PodioItem
	 */
	private function getPodioObject( $params ) {
		$elements = explode( '$:', $params );
		$fields   = array();
		foreach ( $elements as $element ) {
			$data        = explode( '::', $element );
			$type        = $data[0];
			$external_id = $data[1];
			$values      = explode( '$$', $data[2] );
			$fields[]    = $this->getField( $type, $external_id, $values );
		}
		$fields = new PodioItemFieldCollection( $fields );

		// Create the item object with fields
		// Be sure to add an app or podio-php won't know where to create the item
		$item = new PodioItem( array(
			'app'    => new PodioApp( (int) $this->app_id ),
			'fields' => $fields
		) );

		return $item;

	}

	private function getField( $type, $id, $values ) {
		$data = array(
			'__api_values' => true,
			'values'       => $values,
			'external_id'  => $id
		);
		switch ( $type ) {
			case 'text';
				unset( $data['values'] );
				$data['values'] = array(
					array( 'value' => trim( $values[0], "\"" ) )
				);

				return new \PodioTextItemField( $data );
			case 'contact':
				$data['values'] = array();
				foreach ( $values as $value ) {
					$data['values'][] = array( 'value' => array( 'profile_id' => $value ) );
				}

				return new \PodioContactItemField( $data );
			case 'date':
				$start          = explode( " ", trim( $values[0], "\"" ) );
				$end            = explode( " ", trim( $values[1], "\"" ) );
				$data['values'] = array(
					array(
						"start_date_utc" => $start[0],
						"end_date_utc"   => $end[0],
						"start_time_utc" => $start[1],
						"end_time_utc"   => $end[1],
					)
				);

				return new PodioDateItemField( $data );
			case 'app':
				$data['values'] = array();
				foreach ( $values as $value ) {
					$data['values'][] = array( 'value' => array( 'item_id' => $value ) );
				}

				return new \PodioAppItemField( $data );
		}
	}


}
