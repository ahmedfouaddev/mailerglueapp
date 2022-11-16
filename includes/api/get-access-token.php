<?php

namespace MailerGlueApp\API;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * getAccessToken class.
 */
class GetAccessToken {

	/**
	 * Construct.
	 */
	public function __construct() {

		register_rest_route( MAILERGLUEAPP_API_VERSION, '/get_access_token', array(
			'methods'				=> 'get',
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

		$email		= $request->get_param( 'email' );
		$password	= $request->get_param( 'password' );

		$data = array();

		$user = new \MailerGlueApp\User;

		$user->set_by_email( $email );

		if ( ! $user->has_id() ) {
			return new \WP_Error( 'invalid_account', 'This account does not exist.' );
		}

		if ( ! $user->check_credentials( $password ) ) {
			return new \WP_Error( 'incorrect_credentials', 'Your credentials are incorrect.' );
		} else {

			$user->set_access_token();

			$data = array(
				'id'			=> $user->get_id(),
				'email'			=> $user->get_email(),
				'acess_token'	=> $user->get_access_token(),
			);
		}

		return rest_ensure_response( $data );
	}

}

return new \MailerGlueApp\API\GetAccessToken;
