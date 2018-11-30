<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;

class GopherController extends Controller {

	CONST RESERVED_PARAMS = [ 'command', 'api_token' ];

	/**
	 * @param Request $request
	 *
	 * @return array|\Illuminate\Http\JsonResponse|string
	 */
	public function errand( Request $request ) {
		$validator = Validator::make( $request->all(), [ 'command' => 'required|in:' . config( 'commands.allowed' ) ] );
		if ( $validator->fails() ) {
			return response()->json( $validator->errors(), 422 );
		}


		$command = $request->input( 'command' );
		$params  = $this->getParams( $request );

		// call the command if this is direct
		if ( config( 'commands.direct' ) == 'direct' ) {
			$exitCode = Artisan::call( $command, $params );

			return Artisan::output();
		}

		// if the config is not set to direct, then we queue the command
		$exitCode = Artisan::queue( $command, $params );

		return [ 'success' => 'Command has been added to the queue' ];

	}

	/**
	 * @param Request $request
	 *
	 * @return array
	 */
	private function getParams( Request $request ) {
		$params = $request->all();
		foreach ( self::RESERVED_PARAMS as $param ) {
			unset( $params[ $param ] );
		}

		return $params;
	}


}