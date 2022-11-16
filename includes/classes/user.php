<?php

namespace MailerGlueApp;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * User class.
 */
class User {

	private $id;

	private $data;

	private $access_token;

	/**
	 * Construct.
	 */
	public function __construct() {

	}

	/**
	 * Sets a user by a given email.
	 */
	public function set_by_email( $email ) {

		$user = get_user_by( 'email', $email );

		if ( $user && ! empty( $user->ID ) ) {
			$this->id	 = $user->ID;
			$this->data	 = $user->data;
		}
	}

	/**
	 * Get user's ID.
	 */
	public function get_id() {

		return $this->id ? $this->id : 0;
	}

	/**
	 * Get user's email.
	 */
	public function get_email() {

		return ! empty( $this->data->user_email ) ? $this->data->user_email : '';
	}

	/**
	 * Get user's password.
	 */
	public function get_password() {

		return ! empty( $this->data->user_pass ) ? $this->data->user_pass : '';
	}

	/**
	 * Get access token.
	 */
	public function get_access_token() {

		if ( ! empty( $this->access_token ) ) {
			return $this->access_token;
		}

		return get_user_meta( $this->get_id(), '_mailerglue_token', true );
	}

	/**
	 * Check if user has a valid ID.
	 */
	public function has_id() {

		return $this->id ? true : false;
	}

	/**
	 * Checks if the provided password is valid for a user.
	 */
	public function check_credentials( $password ) {

		return wp_check_password( $password, $this->get_password() );
	}

	/**
	 * Sets access token for a user.
	 */
	public function set_access_token() {

		$token = bin2hex( random_bytes( 20 ) );

		update_user_meta( $this->get_id(), '_mailerglue_token', $token );

		$this->access_token = $token;

		return $this->access_token;
	}

}
