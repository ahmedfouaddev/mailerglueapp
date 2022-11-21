<?php

namespace MailerGlueApp\API;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Save_Basic_Settings class.
 */
class Save_Basic_Settings {

	/**
	 * Construct.
	 */
	public function __construct() {

		register_rest_route( MAILERGLUEAPP_API_VERSION, '/save_basic_settings', array(
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

		$user->update_meta( $data );

		return rest_ensure_response( array_merge( array( 'success' => true ), $data ) );
	}

}

return new \MailerGlueApp\API\Save_Basic_Settings;
