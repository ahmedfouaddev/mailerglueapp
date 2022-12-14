<?php

namespace MailerGlueApp\API;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Save_List class.
 */
class Save_List {

	/**
	 * Construct.
	 */
	public function __construct() {

		register_rest_route( MAILERGLUEAPP_API_VERSION, '/save_list', array(
			'methods'				=> 'post',
			'callback'				=> array( $this, 'response' ),
			'permission_callback'	=> array( '\MailerGlueApp\API', 'authenticate' ),
		) );

	}

	/**
	 * Response.
	 */
	public function response( $request ) {

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

		$list = new \MailerGlueApp\Lists;
		$list->update( $data, $account_id );

		return rest_ensure_response( array_merge( array( 'success' => true ), $data ) );
	}

}

return new \MailerGlueApp\API\Save_List;