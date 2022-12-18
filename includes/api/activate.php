<?php

namespace MailerGlueApp\API;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Activate class.
 */
class Activate {

	/**
	 * Construct.
	 */
	public function __construct() {

		register_rest_route( MAILERGLUEAPP_API_VERSION, '/activate', array(
			'methods'				=> 'post',
			'callback'				=> array( $this, 'response' ),
			'permission_callback'	=> array( '\MailerGlueApp\API', 'authenticate' ),
		) );

	}

	/**
	 * Response.
	 */
	public function response( $request ) {
		$data = array();

		$rest = new \MailerGlueApp\API\Log_Request;
		$rest->insert( $request );

		$headers = $request->get_headers();

		$account_id		= isset( $headers[ 'mailerglue_account_id' ] ) ? absint( $headers[ 'mailerglue_account_id' ][0] ) : 0;
		$access_token	= isset( $headers[ 'mailerglue_access_token' ] ) ? $headers[ 'mailerglue_access_token' ][0] : '';

		$user = new \MailerGlueApp\User;
		$user->set( $account_id );

		if ( ! $user->validate_token( $access_token ) ) {
			return new \WP_Error( 'invalid_token', 'Your access token has expired. Please login again.' );
		}

		$data = json_decode( $request->get_body(), true );

		$submitted_code = isset( $data[ 'code' ] ) ? $data[ 'code' ] : '';
		$code 			= $user->get_activation_code();

		if ( ! $user->is_activated() && ( absint( trim( $submitted_code ) ) !== absint( trim( $code ) ) ) ) {
			return new \WP_Error( 'invalid_code', 'Invalid activation code.' );
		} else {

			$user->activate_account();

			$data = array(
				'success' => true,
			);

		}

		return rest_ensure_response( $data );
	}

}

return new \MailerGlueApp\API\Activate;