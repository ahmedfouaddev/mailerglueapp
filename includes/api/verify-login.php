<?php

namespace MailerGlueApp\API;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Verify_Login class.
 */
class Verify_Login {

	/**
	 * Construct.
	 */
	public function __construct() {

		register_rest_route( MAILERGLUEAPP_API_VERSION, '/verify_login', array(
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

		$email		= isset( $headers[ 'mailerglue_email' ] ) ? $headers[ 'mailerglue_email' ][0] : '';
		$password	= isset( $headers[ 'mailerglue_password' ] ) ? $headers[ 'mailerglue_password' ][0] : '';

		$user = new \MailerGlueApp\User;

		// Find the user by email.
		$user->set_by_email( $email );

		// Check if user exists.
		if ( ! $user->has_id() ) {
			return new \WP_Error( 'invalid_account', 'These credentials do not match our records.' );
		}

		// Check password.
		if ( ! $user->check_credentials( $password ) ) {
			return new \WP_Error( 'incorrect_credentials', 'These credentials do not match our records.' );
		} else {

			$user->set_access_token();

			$data = array(
				'success'		=> true,
				'id'			=> $user->get_id(),
				'email'			=> $user->get_email(),
				'name'			=> $user->get_name(),
				'token'			=> $user->get_access_token(),
				'flow'			=> 'settings',
			);

			if ( ! $user->is_activated() ) {
				$code = $user->setup_activation();
				$data[ 'flow' ] = 'activate';
			}

			return rest_ensure_response( $data );

		}

		return new \WP_Error( 'something_wrong', 'Something has went wrong. Please try again later.' );

	}

}

return new \MailerGlueApp\API\Verify_Login;