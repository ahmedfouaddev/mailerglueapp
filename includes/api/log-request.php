<?php

namespace MailerGlueApp\API;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * API class.
 */
class Log_Request {

	/**
	 * Construct.
	 */
	public function __construct() {

	}

	/**
	 * Insert a new API request in the log.
	 */
	public function insert( $request ) {
		global $wpdb;

		$headers = $request->get_headers();

		$account_id 	= ! empty( $headers[ 'mailerglue_account_id' ] ) ? $headers[ 'mailerglue_account_id' ][ 0 ] : 0;
		$access_token 	= ! empty( $headers[ 'mailerglue_access_token' ] ) ? $headers[ 'mailerglue_access_token' ][ 0 ] : '';
		$user_agent 	= ! empty( $headers[ 'user_agent' ] ) ? $headers[ 'user_agent' ][ 0 ] : '';

		$metadata = array(
			'account_id'	=> $account_id,
			'access_token'	=> $access_token,
			'user_agent' 	=> $user_agent,
			'endpoint'		=> $request->get_route(),
			'request_time'	=> current_time( 'mysql', 1 ),
		);

	}

}