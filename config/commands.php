<?php

return [
	/*
    |--------------------------------------------------------------------------
    | Allowed commands
    |--------------------------------------------------------------------------
    |
    | This value sets all artisan commands that can be called via the api.
	| There is no way to only block some and allow the rest for better security.
    |
    */

	'allowed' => env( 'ALLOWED_COMMANDS', 'podio,inspire' ),

	/*
   |--------------------------------------------------------------------------
   | Command run mode
   |--------------------------------------------------------------------------
   |
   | Here you can set if the comamnds should be put into a queue, or run directly
   | Be aware that you cannot access the output if they are placed into a queue.
   |
   */
	'direct'=> env( 'RUN_COMMAND_ON', 'direct' ),
];